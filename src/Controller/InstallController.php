<?php

namespace App\Controller;

use Cake\Core\Configure;
use Cake\Datasource\ConnectionManager;
use Cake\Event\Event;
use Cake\Log\Log;
use Cake\Utility\Security;
use Migrations\Migrations;

class InstallController extends BaseController
{
    /**
     * Before filter method
     *
     * @param Event $event The event where beforeFilter was called
     * @return \Cake\Http\Response|null The http response
     */
    public function beforeFilter(Event $event)
    {
        $this->Security->setConfig('unlockedActions', [
            'index',
            'database',
            'email',
            'user'
        ]);

        if (Configure::read('Install.done') == 'true') {
            $this->redirect(['_name' => 'login']);
        }

        $currentInstallStep = Configure::read('Install.currentStep');

        if ($this->getRequest()->getParam('action') != $currentInstallStep) {
            $this->redirect(['action' => $currentInstallStep]);
        }

        return parent::beforeFilter($event);
    }

    /**
     * Before render method
     *
     * @param Event $event The event where beforeFilter was called
     * @return \Cake\Http\Response|null The http response
     */
    public function beforeRender(Event $event)
    {
        $this->viewBuilder()->setTheme('AdminLTE');
        $this->viewBuilder()->setClassName('AdminLTE.AdminLTE');
        $this->viewBuilder()->setLayout('blank');

        return parent::beforeRender($event);
    }

    /**
     * Index method
     */
    public function index()
    {
        if ($this->getRequest()->is(['post'])) {
            Log::info('Installation started at ' . date('Y-m-d H:i:s'), ['scope' => 'install']);
            Log::info('Checking requirements ...', ['scope' => 'install']);

            Log::info('PHP_VERSION: ' . (version_compare(PHP_VERSION, '5.6') ? 'OK' : 'ERROR'), ['scope' => 'install']);
            Log::info('CAKE_PHP_CORE: ' . (version_compare(Configure::version(), '3.8') ? 'OK' : 'ERROR'), ['scope' => 'install']);
            Log::info('extension mbstring: ' . (extension_loaded('mbstring') ? 'OK' : 'ERROR'), ['scope' => 'install']);
            Log::info('extension intl: ' . (extension_loaded('intl') ? 'OK' : 'ERROR'), ['scope' => 'install']);
            Log::info('extension curl: ' . (extension_loaded('curl') ? 'OK' : 'WARNING'), ['scope' => 'install']);
            Log::info('extension sqlite3: ' . (extension_loaded('sqlite3') ? 'OK' : 'WARNING'), ['scope' => 'install']);
            Log::info('extension zip: ' . (extension_loaded('zip') ? 'OK' : 'WARNING'), ['scope' => 'install']);
            Log::info('extension simplexml: ' . (extension_loaded('simplexml') ? 'OK' : 'WARNING'), ['scope' => 'install']);
            Log::info('set_time_limit callable: ' . (strpos(ini_get('disable_functions'), 'set_time_limit') === false ? 'YES' : 'NO'), ['scope' => 'install']);

            $this->writeEnvConfigFile(['SECURITY_SALT' => Security::randomString()]);
            $this->writePhpConfigFile('install', ['currentStep' => 'database']);
            $this->redirect(['action' => 'database']);
        }
    }

    /**
     * Database method
     */
    public function database()
    {
        if ($this->getRequest()->is(['post'])) {
            Log::info('Creating database structure ...', ['scope' => 'install']);

            $dbHost = $this->getRequest()->getData('database.host');
            $dbPort = $this->getRequest()->getData('database.port');
            $dbUsername = $this->getRequest()->getData('database.username');
            $dbPassword = $this->getRequest()->getData('database.password');
            $dbDatabaseName = $this->getRequest()->getData('database.dbname');

            // verify database credentials
            try {
                $tmpConfig = [
                    'className' => \Cake\Database\Connection::class,
                    'driver' => \Cake\Database\Driver\Mysql::class,
                    'persistent' => false,
                    'timezone' => 'UTC',
                    'port' => $dbPort,
                    'host' => $dbHost,
                    'username' => $dbUsername,
                    'password' => $dbPassword,
                    'database' => $dbDatabaseName
                ];

                ConnectionManager::drop('default');
                ConnectionManager::setConfig('default', $tmpConfig);

                $connection = ConnectionManager::get('default');
                $connection->connect();
            } catch (\Exception $exception) {
                Log::error('Database connection with given credentials failed!', ['scope' => 'install']);
                $this->Flash->error(__('Unable to connect to the database server!'));

                return;
            }

            // update default database configuration
            Log::info('Writing database configuration variables', ['scope' => 'install']);
            $this->writeEnvConfigFile([
                'DATABASE_HOST' => $dbHost,
                'DATABASE_PORT' => $dbPort,
                'DATABASE_USERNAME' => $dbUsername,
                'DATABASE_PASSWORD' => $dbPassword,
                'DATABASE_DBNAME' => $dbDatabaseName
            ]);

            $migrations = new Migrations();
            try {
                Log::info('Running migrations to create database tables', ['scope' => 'install']);
                $migrations->migrate();
            } catch (\Exception $e) {
                Log::error('Error while running migrations!' . PHP_EOL . $e->getMessage(), ['scope' => 'install']);
            }

            try {
                Log::info('Seeding database with required data', ['scope' => 'install']);
                $migrations->seed();
            } catch (\Exception $e) {
                Log::error('Error while seeding database!' . PHP_EOL . $e->getMessage(), ['scope' => 'install']);
            }

            $this->writePhpConfigFile('install', ['currentStep' => 'email']);
            $this->redirect(['action' => 'email']);
        }
    }

    /**
     * E-Mail method
     */
    public function email()
    {
        // flag to skip the request is set
        if ($this->getRequest()->is(['get']) && $this->getRequest()->getQuery('skipAction')) {
            Log::warning('Skipping email configuration!', ['scope' => 'install']);

            $this->writePhpConfigFile('install', ['currentStep' => 'user']);
            $this->redirect(['action' => 'user']);
        }

        // normal processing of posted data
        if ($this->getRequest()->is(['post'])) {
            Log::info('Updating email configuration ...', ['scope' => 'install']);

            $mailHost = $this->getRequest()->getData('email.host');
            $mailPort = $this->getRequest()->getData('email.port');
            $mailUsername = $this->getRequest()->getData('email.username');
            $mailPassword = $this->getRequest()->getData('email.password');
            $mailSenderAddr = $this->getRequest()->getData('email.from');

            // update default email configuration
            Log::info('Writing email configuration variables', ['scope' => 'install']);
            $this->writeEnvConfigFile([
                'SMTP_HOST' => $mailHost,
                'SMTP_PORT' => $mailPort,
                'SMTP_USERNAME' => $mailUsername,
                'SMTP_PASSWORD' => $mailPassword,
                'EMAIL_SENDER' => $mailSenderAddr
            ]);

            $this->writePhpConfigFile('install', ['currentStep' => 'user']);
            $this->redirect(['action' => 'user']);
        }
    }

    /**
     * User method
     */
    public function user()
    {
        $this->loadModel('Users');
        $user = $this->Users->newEntity();

        if ($this->getRequest()->is(['post'])) {
            Log::info('Creating first user as admin user', ['scope' => 'install']);

            $user = $this->Users->patchEntity($user, $this->getRequest()->getData());
            $user->superuser = 1;
            $user->activated = 1;
            $user->client_id = 1;
            $user->group_id = 1;

            if ($this->Users->save($user)) {
                // process webmaster contact address if available
                $contactEmailAddress = $this->getRequest()->getData('contact');
                if (!empty($contactEmailAddress)) {
                    $this->writeEnvConfigFile([
                        'APP_ADMIN_EMAIL' => $contactEmailAddress
                    ]);
                }

                $this->writePhpConfigFile('install', ['currentStep' => 'conclusion']);
                $this->redirect(['action' => 'conclusion']);
            } else {
                Log::error('Error while creating admin user', ['scope' => 'install']);
                $this->Flash->error(__('Unable to save admin user!'));
            }
        }

        $this->set('user', $user);
    }

    /**
     * Conclusion method
     */
    public function conclusion()
    {
        Log::info('Installation ended up successfully', ['scope' => 'install']);

        if ($this->getRequest()->is(['post'])) {
            $this->writePhpConfigFile('install', ['done' => 'true']);
            $this->redirect(['_name' => 'login']);
        }
    }

    /**
     * Writes data to a config file to be read by the PhpConfig engine.
     *
     * @param string $fileName Name of the config file
     * @param array $data The data to write
     * @return bool|int Whether the file has been written
     */
    private function writePhpConfigFile($fileName, $data = [])
    {
        if (!strpos($fileName, '.php')) {
            $fileName .= '.php';
        }

        $content = file_get_contents(CONFIG . $fileName);

        foreach ($data as $field => $value) {
            $content = preg_replace(
                str_replace('__FIELD__', $field, "/(.*\'__FIELD__\'\s\=\>\s\').*(\'.*)/"),
                '${1}' . addslashes($value) . '${2}',
                $content
            );
        }

        if (function_exists('opcache_reset')) {
            opcache_reset();
        }

        return file_put_contents(CONFIG . $fileName, $content);
    }

    /**
     * Writes data to an .env file to be read by the configuration files. If there exists
     * no .env file, the .env.default file will be used as template. Disable this behaviour
     * by setting $checkDefault parameter to false.
     *
     * @param array $data The variables to override in the ENV file
     * @param bool $checkDefault Whether to lookup a default file
     * @return bool|int Whether the file has been written
     */
    private function writeEnvConfigFile($data = [], $checkDefault = true)
    {
        $envFileName = CONFIG . '.env';
        if (!file_exists($envFileName) && $checkDefault) {
            $envFileName .= '.default';
        }

        $envFileContent = file_get_contents($envFileName);

        foreach ($data as $field => $value) {
            $envFileContent = preg_replace(
                str_replace('__FIELD__', $field, '/(#?)(.*__FIELD__\=\").*(\".*)/'),
                '${2}' . addslashes($value) . '${3}',
                $envFileContent
            );
        }

        return file_put_contents(CONFIG . '.env', $envFileContent);
    }
}