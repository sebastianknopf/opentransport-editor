<?php

namespace App\Controller;

use App\Utility\LocaleList;
use Cake\Controller\Controller;
use Cake\Core\Configure;
use Cake\I18n\I18n;

class AppController extends BaseController
{
    /**
     * Initialize method.
     */
    public function initialize()
    {
        parent::initialize();

        // load other configuration
        $this->loadComponent('RequestHandler', [
            'enableBeforeRedirect' => false,
        ]);

        // redirect to the install controller, when installation is not already done
        if (Configure::read('Install.done') != 'true') {
            $this->redirect(['_name' => 'install']);
        }
    }
}