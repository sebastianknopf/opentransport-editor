<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use App\Utility\LocaleList;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\NotFoundException;
use Cake\I18n\I18n;
use Cake\Routing\Router;
use Cake\View\Exception\MissingTemplateException;

/**
 * Static content controller
 *
 * This controller will render views from Template/Frontend/
 *
 * @link https://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class FrontendController extends AppController
{
    /**
     * beforeFilter method.
     *
     * @param Event $event The event fired with beforeFilter
     * @return \Cake\Http\Response|void|null The HTTP response object
     * @throws \Exception The exception fired on error
     */
    public function beforeFilter(Event $event)
    {
        if (!Configure::read('Frontend.allowUnauthenticated')) {
            $this->loadComponent('Authentication.Authentication');
        }

        // set frontend preferred locale
        if (Configure::check('Frontend.preferredLocale') && Configure::read('Frontend.preferredLocale') !== false) {
            I18n::setLocale(Configure::read('Frontend.preferredLocale'));
        } else {
            I18n::setLocale(LocaleList::getPreferredLocale());
        }
    }

    /**
     * Index method.
     */
    public function index()
    {
        $this->viewBuilder()->setLayout('frontend');
    }
}
