<?php

namespace App\Controller;

use App\Utility\LocaleList;
use Cake\Controller\Controller;
use Cake\Core\Configure;
use Cake\I18n\I18n;

/**
 * BaseController for all controllers within OpenTransport application.
 *
 * @package App\Controller
 */
class BaseController extends Controller
{
    /**
     * Initialize method.
     */
    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('Flash');
        $this->loadComponent('Security');

        // set controller preferred locale
        // this will be overridden by user-settings if there's a language specified
        if (Configure::check('App.preferredLocale') && Configure::read('App.preferredLocale') !== false) {
            I18n::setLocale(Configure::read('App.preferredLocale'));
        } else {
            I18n::setLocale(LocaleList::getPreferredLocale());
        }
    }
}