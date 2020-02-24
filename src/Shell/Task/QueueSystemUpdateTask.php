<?php

namespace App\Shell\Task;

use App\Utility\GitHubApiClient;
use Cake\Core\Configure;
use Cake\Database\Expression\QueryExpression;
use Cake\Log\Log;
use Cake\ORM\Query;
use Cake\ORM\TableRegistry;
use Migrations\Migrations;
use Queue\Shell\Task\QueueTask;
use Queue\Shell\Task\QueueTaskInterface;
use ZipArchive;

class QueueSystemUpdateTask extends QueueTask implements QueueTaskInterface
{
    /**
     * @var int
     */
    public $timeout = 3600;

    /**
     * @var int
     */
    public $retries = 1;

    /**
     * @var int Job ID of current instance
     */
    public $jobId = 0;

    /**
     * @var mixed Reference to the jobs table
     */
    public $tableRef = null;

    /**
     * @var array The list of tags in remote repository
     */
    private $tagList = [];

    /**
     * @var array The list of files assumed to be changed since the latest update
     */
    private $fileList = [];

    /**
     * @var string Filename of the backup file to rollback changes in case of error
     */
    private $backupFilename = null;

    /**
     * @var bool Flag indicating whether database migrations were changed
     */
    private $isMigrationsUpdated = false;

    /**
     * @var bool Flag indicating whether database seeds were changed
     */
    private $isSeedsUpdated = false;

    /**
     * @var bool Flag indicating whether composer dependencies were updated
     */
    private $isComposerUpdated = false;

    /**
     * Main execution of the task.
     *
     * @param array $data The array passed to QueuedJobsTable::createJob()
     * @param int $jobId The id of the QueuedJob entity
     */
    public function run(array $data, $jobId)
    {
        $this->jobId = $jobId;
        $this->tableRef = TableRegistry::getTableLocator()->get('Queue.QueuedJobs');
        $this->tagList = GitHubApiClient::getTags();

        // proceed only if there's an update required
        if ($this->isUpdateRequired()) {
            $currentJobs = $this->tableRef->find()->where(function (QueryExpression $exp, Query $q) {
                return $exp->isNull('QueuedJobs.completed');
            })->where([
                'QueuedJobs.job_type !=' => 'SystemUpdate',
                'QueuedJobs.failed >' => 1
            ])->toArray();

            // wait for concurrent jobs to terminate
            if (count($currentJobs) > 0) {
                $this->tableRef->updateAll(['status' => 'waiting for concurrent jobs to terminate'], ['id' => $jobId]);

                while (count($currentJobs) > 0) {
                    sleep(15);

                    $currentJobs = $this->tableRef->find()->where(function (QueryExpression $exp, Query $q) {
                        return $exp->isNull('QueuedJobs.completed');
                    })->where([
                        'QueuedJobs.job_type !=' => 'SystemUpdate',
                        'QueuedJobs.failed >' => 1
                    ])->toArray();
                }
            }

            // fetch all files to change from GitHub repo
            $this->tableRef->updateAll(['status' => 'fetching latest changes'], ['id' => $jobId]);
            $latestChanges = GitHubApiClient::getLatestChanges(Configure::read('App.version'));

            foreach ($latestChanges['files'] as $fileInfo) {
                if (is_file(ROOT . DS . $fileInfo['filename'])) {
                    array_push($this->fileList, [
                       'status' => $fileInfo['status'],
                       'filename' => $fileInfo['filename'],
                       'raw_url' => $fileInfo['raw_url']
                    ]);
                }
            }

            chdir(ROOT);

            // create a backup of each file which is tended to be changed
            $this->tableRef->updateAll(['status' => 'staging current version backup'], ['id' => $jobId]);
            $this->createBackup();

            // update application files
            try {
                $this->tableRef->updateAll(['status' => 'update files to new version'], ['id' => $jobId]);
                $this->updateFiles();
            } catch (\Exception $e) {
                $this->tableRef->updateAll(['status' => 'error: ' . $e->getMessage()], ['id' => $jobId]);
            }

            // run migrations if needed
            if ($this->isMigrationsUpdated) {
                try {
                    $this->tableRef->updateAll(['status' => 'running database migrations'], ['id' => $jobId]);
                    $migrations = new Migrations();
                    $migrations->migrate();
                } catch (\Exception $e) {
                    $this->tableRef->updateAll(['status' => 'error: ' . $e->getMessage()], ['id' => $jobId]);
                }
            }

            // run seeding if needed
            if ($this->isSeedsUpdated) {
                try {
                    $this->tableRef->updateAll(['status' => 'running database seeds'], ['id' => $jobId]);
                    $migrations = new Migrations();
                    $migrations->seed();
                } catch (\Exception $e) {
                    $this->tableRef->updateAll(['status' => 'error: ' . $e->getMessage()], ['id' => $jobId]);
                }
            }

            // TODO: how to update composer dependencies on server ... ?

            $this->tableRef->updateAll(['status' => 'terminating update task'], ['id' => $jobId]);

            unset($this->tableRef, $this->tagList, $this->fileList, $this->isMigrationsUpdated, $this->isSeedsUpdated, $this->isComposerUpdated, $this->backupFilename);
            sleep(5);
        }
    }

    /**
     * Returns whether an update is required or not.
     *
     * @return bool Whether an update is required
     */
    private function isUpdateRequired()
    {
        if (isset($this->tagList[0])) {
            return version_compare($this->tagList[0]['name'], Configure::read('App.version')) >= 0;
        }

        return false;
    }

    /**
     * Creates a backup zip file for each file which is assumed
     * to be changed.
     */
    private function createBackup()
    {
        $zipArchive = new \ZipArchive();

        $this->backupFilename = 'backup/backup-' . date('YmdHis') . '-' . Configure::read('App.version') . '.zip';
        if ($zipArchive->open($this->backupFilename, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
            foreach ($this->fileList as $fileInfo) {
                if (file_exists(ROOT . DS . $fileInfo['filename']) && is_file(ROOT . DS . $fileInfo['filename'])) {
                    $zipArchive->addFromString($fileInfo['filename'], file_get_contents(ROOT . DS . $fileInfo['filename']));
                }
            }

            $zipArchive->close();
        } else {
            throw new \RuntimeException('unable to create backup file');
        }
    }

    /**
     * Updates all files assumed to be changed. If there're Migrations or Seeds changed,
     * the method will set a flag to process those elements. If composer.json is changed,
     * it will also set a flag, but this cannot be processed currently.
     */
    private function updateFiles()
    {
        for ($i = 0; $i < count($this->fileList); $i++) {
            $fileInfo = $this->fileList[$i];
            $fullFileName = ROOT . DS . $fileInfo['filename'];

            $this->tableRef->updateAll(['status' => 'update files to new version - processing (' . ($i + 1) . ' of ' . count($this->fileList) . ')'], ['id' => $this->jobId]);

            if ($fileInfo['status'] == GitHubApiClient::STATUS_ADDED || $fileInfo['status'] == GitHubApiClient::STATUS_MODIFIED) {
                if (preg_match("/config\/Migrations/", $fileInfo['filename'])) {
                    $this->isMigrationsUpdated = true;
                }

                if (preg_match("/config\/Seeds/", $fileInfo['filename'])) {
                    $this->isSeedsUpdated = true;
                }

                if (preg_match("/composer.json/", $fileInfo['filename'])) {
                    $this->isComposerUpdated = true;
                }

                $fileContent = GitHubApiClient::getFileContent($fileInfo['raw_url']);
                if (file_put_contents($fullFileName, $fileContent) === false) {
                    Log::debug('unable to write file ' . $fullFileName);
                }
            } else {
                if (file_exists($fullFileName) && unlink($fullFileName) === false) {
                    Log::debug('unable to delete file ' . $fullFileName);
                }
            }

            // sleep one second to avoid rate limiting by GitHub API
            sleep(1);
        }
    }
}