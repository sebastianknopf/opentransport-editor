<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     3.0.0
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\View;

use BootstrapUI\View\UIViewTrait;
use Cake\View\View;

/**
 * Application View
 *
 * Your application’s default view class
 *
 * @link https://book.cakephp.org/3.0/en/views.html#the-app-view
 */
class AppView extends View
{
    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading helpers.
     *
     * e.g. `$this->loadHelper('Html');`
     *
     * @return void
     */
    public function initialize()
    {
        // make controls appearing BootstrapUI
        //$this->initializeUI();

        // initialize breadcrumbs helper for default app view
        $this->loadHelper('Breadcrumbs');
        $this->Breadcrumbs->setTemplates([
            'wrapper' => '<ol{{attrs}}>{{content}}</ol>',
            'item' => '<li{{attrs}}><a href="{{url}}"{{innerAttrs}}>{{title}}</a></li>',
            'itemWithoutLink' => '<li{{attrs}}><span{{innerAttrs}}>{{title}}</span></li>',
            'separator' => ''
        ]);

        $this->loadHelper('Form', ['className' => 'AdminLTE.Form']);
    }
}
