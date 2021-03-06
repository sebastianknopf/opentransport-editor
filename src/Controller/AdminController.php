<?php

namespace App\Controller;

use Authentication\Identifier\IdentifierInterface;
use Authentication\IdentityInterface;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\I18n\Date;
use Cake\I18n\FrozenDate;
use Cake\I18n\FrozenTime;
use Cake\I18n\I18n;
use Cake\I18n\Time;
use Cake\Routing\Router;
use Cake\Utility\Security;
use Firebase\JWT\JWT;

/**
 * Admin Application Controller
 *
 */
class AdminController extends AppController
{
    /**
     * @var IdentityInterface|null The current authenticated identity
     */
    private $Identity = null;

    /**
     * Initialization hook method.
     *
     * @return void
     * @throws \Exception
     */
    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('Authentication.Authentication');
        $this->loadComponent('Authorization.Authorization');
        $this->loadComponent('Acl.Acl');

        /*
         * Load user dependent settings from database and write to Configure.
         */
        $this->Identity = $this->Authentication->getIdentity();
        if ($this->Identity != null) {
            $this->loadModel('UserSettings');
            $userSettings = $this->UserSettings->findByUserId($this->Identity->id);

            foreach ($userSettings as $userSetting) {
                Configure::write($userSetting->name, $userSetting->value);
            }

            // set date and time format
            Date::setToStringFormat(Configure::read('Date.toStringFormat', 'yyyy-MM-dd'));
            FrozenDate::setToStringFormat(Configure::read('Date.toStringFormat', 'yyyy-MM-dd'));
            Time::setToStringFormat(Configure::read('Time.toStringFormat', 'yyyy-MM-dd HH:mm:ss'));
            FrozenTime::setToStringFormat(Configure::read('Time.toStringFormat', 'yyyy-MM-dd HH:mm:ss'));

            // set maximum results for pagination
            $this->loadComponent('Paginator', [
               'limit' => Configure::read('Paginator.resultsLength')
            ]);

            // set controller default locale
            I18n::setLocale(Configure::read('App.defaultLocale'));
        }

        // trigger deferred queue worker task by calling the corresponding method in ajax controller
        // be aware, that this request emulates a new session! A authentication token is always needed
        // to identify the user calling the worker task!
        // Note that the curl request is killed after 800ms to ensure short loading times when the worker
        // script does not run immediately.
        register_shutdown_function(function () {
            $ch = curl_init();

            $accessToken = JWT::encode([IdentifierInterface::CREDENTIAL_JWT_SUBJECT => ['id' => $this->Identity != null ? $this->Identity->id : 0]], Security::getSalt());
            $startQueueWorkerUrl = Router::url(['controller' => 'Ajax', 'action' => 'startQueueWorker', '?' => ['token' => $accessToken]], true);

            curl_setopt($ch, CURLOPT_URL, $startQueueWorkerUrl);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_TIMEOUT_MS, 800);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'User-Agent: ' . Configure::read('App.name'),
            ]);

            if (!empty(session_id())) {
                session_write_close();
            }

            curl_exec($ch);
            curl_close($ch);
        });
    }

    /**
     * beforeFilter method used to set general security settings.
     *
     * @param Event $event The event when beforeFilter fired
     * @return \Cake\Http\Response|void|null The server response object
     */
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);

        // need to unlock delete action because there's no CSRF-Token
        // generated in modal of type delete-confirm.
        $this->Security->setConfig([
            'unlockedActions' => ['delete']
        ]);
    }

    /**
     * Before render method.
     * Set global view vars for Admin Views here.
     *
     * @param Event $event Event object
     * @return \Cake\Http\Response|null Response object
     */
    public function beforeRender(Event $event)
    {
        $this->viewBuilder()->setTheme('AdminLTE');
        $this->viewBuilder()->setClassName('AdminLTE.AdminLTE');

        // set identity and acl object to all views by default
        $this->set('_IDENTITY', $this->Authentication->getIdentity());
        $this->set('_ACL', $this->Acl);
        $this->set('_REDIRECT', $this->isRedirect() ? $this->getRequest()->getSession()->read('Redirect') : null);

        return parent::beforeRender($event);
    }

    /**
     * Sets the redirect url params. If parameter is null, the current url will be
     * set as redirect.
     *
     * @param mixed|null $redirect The redirect url params
     */
    protected function setRedirect($redirect = null)
    {
        if ($redirect == null) {
            $redirect = Router::url(null, true);
        }

        if ($redirect == false) {
            $this->getRequest()->getSession()->delete('Redirect');
        }

        $this->getRequest()->getSession()->write('Redirect', $redirect);
    }

    /**
     * Returns whether there's a redirect value or not.
     *
     * @return bool Whether there's a redirect value or not
     */
    protected function isRedirect()
    {
        $redirect = $this->getRequest()->getSession()->read('Redirect');

        return $this->getRequest()->getSession()->check('Redirect') && $redirect != Router::url(null, true);
    }

    /**
     * Returns the redirect url params.
     *
     * @return mixed The redirect url params
     */
    protected function getRedirect()
    {
        return $this->getRequest()->getSession()->consume('Redirect');
    }

    /**
     * Returns the current session filter array by name.
     *
     * @param string $filterName Filter name to be selected
     * @return array|string|null Current filter array
     */
    protected function getSessionFilter($filterName)
    {
        if ($this->getRequest()->getSession()->check('Filter.' . $filterName)) {
            return $this->getRequest()->getSession()->read('Filter.' . $filterName);
        } else {
            return null;
        }
    }

    /**
     * Writes the current session filter into an array by name.
     *
     * @param string $filterName Filter name to be written
     * @param array $filterValue Current filter array
     */
    protected function setSessionFilter($filterName, $filterValue)
    {
        $this->getRequest()->getSession()->write('Filter.' . $filterName, $filterValue);
    }

    /**
     * Loads the current session filter by name.
     *
     * @param string $filterName Filter name to be load
     */
    protected function loadSessionFilter($filterName)
    {
        // set session filter automatically when in index method and receiving post request
        if ($this->getRequest()->getParam('action') == 'index' && $this->getRequest()->is('post')) {
            $this->setSessionFilter($filterName, array_filter($this->getRequest()->getData()));
        }

        // delete session filter when requested to delete via get parameter
        if ($this->getRequest()->getQuery('deleteSessionFilter')) {
            $this->setSessionFilter($filterName, null);
            $this->redirect(['?' => []]);
        }

        if ($this->getSessionFilter($filterName) != null) {
            $currentQueryKeys = array_keys(array_filter($this->getRequest()->getQueryParams()));
            $currentFilterKeys = array_keys($this->getSessionFilter($filterName));

            if ($this->getRequest()->getParam('action') == 'index' && count(array_intersect($currentFilterKeys, $currentQueryKeys)) != count($currentFilterKeys)) {
                $this->redirect(['?' => array_merge($this->getSessionFilter($filterName), array_filter($this->getRequest()->getQueryParams()))]);
            }
        }
    }
}
