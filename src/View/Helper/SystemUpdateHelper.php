<?php

namespace App\View\Helper;

use App\Utility\GitHubApiClient;
use Cake\Core\Configure;
use Cake\Database\Expression\QueryExpression;
use Cake\ORM\Query;
use Cake\ORM\TableRegistry;
use Cake\View\Helper;

/**
 * Class SystemUpdateHelper
 * Helper class for accessing update information from GitHub repository.
 *
 * @package App\View\Helper
 */
class SystemUpdateHelper extends Helper
{

    /**
     * Returns, whether there's an update available for the current version.
     *
     * @return bool Whether there's an update available
     */
    public function isUpdateAvailable()
    {
        $tagsList = GitHubApiClient::getTags();

        if ($tagsList != null) {
            if (isset($tagsList[0])) {
                $latestTag = $tagsList[0]['name'];

                return version_compare(Configure::read('App.version'), $latestTag) < 0;
            }

            return false;
        }

        return false;
    }

    /**
     * Returns the latest available app version. If the repository request fails, the current
     * app version is returned.
     *
     * @return string The latest available app version
     */
    public function getLatestVersion()
    {
        $tagsList = GitHubApiClient::getTags();

        if ($tagsList != null) {
            if (isset($tagsList[0])) {
                return $tagsList[0]['name'];
            }

            return Configure::read('App.version');
        }

        return Configure::read('App.version');
    }

    /**
     * Compares the specified version with the latest repository version
     * and lists all changes.
     *
     * @param $currentVersion The current version to compare
     * @return array The changes message list
     */
    public function getLatestChanges($currentVersion)
    {
        $latestChanges = GitHubApiClient::getLatestChanges($currentVersion);

        $changesList = [];
        if (isset($latestChanges['commits'])) {
            foreach ($latestChanges['commits'] as $commitInfo) {
                if (isset($commitInfo['commit']) && !empty($commitInfo['commit']['message'])) {
                    array_push($changesList, [
                        'message' => $commitInfo['commit']['message'],
                        'author' => $commitInfo['commit']['author']['name']
                    ]);
                }
            }
        }

        return $changesList;
    }

    /**
     * Returns the ID of the current update job or null, if there's
     * no update job started.
     *
     * @return int|null The ID of the current update job
     */
    public function getCurrentUpdateJobId()
    {
        $jobsTable = TableRegistry::getTableLocator()->get('Queue.QueuedJobs');

        $currentUpdateJob = $jobsTable->find()->select(['id'])->where(function (QueryExpression $exp, Query $q) {
            return $exp->isNull('QueuedJobs.completed');
        })->where(['QueuedJobs.job_type' => 'SystemUpdate'])->toArray();

        if ($currentUpdateJob != null) {
            return $currentUpdateJob[0]['id'];
        } else {
            return null;
        }
    }
}