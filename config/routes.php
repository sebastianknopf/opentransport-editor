<?php
/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different URLs to chosen controllers and their actions (functions).
 *
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

use App\Middleware\RestApiMiddleware;
use Cake\Http\Middleware\CsrfProtectionMiddleware;
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\Routing\Route\DashedRoute;

/**
 * The default class to use for all routes
 *
 * The following route classes are supplied with CakePHP and are appropriate
 * to set as the default:
 *
 * - Route
 * - InflectedRoute
 * - DashedRoute
 *
 * If no call is made to `Router::defaultRouteClass()`, the class used is
 * `Route` (`Cake\Routing\Route\Route`)
 *
 * Note that `Route` does not do any inflections on URLs which will result in
 * inconsistently cased URLs when used with `:plugin`, `:controller` and
 * `:action` markers.
 *
 * Cache: Routes are cached to improve performance, check the RoutingMiddleware
 * constructor in your `src/Application.php` file to change this behavior.
 *
 */
Router::defaultRouteClass(DashedRoute::class);

// frontend routes
Router::scope('/', function (RouteBuilder $routes) {
    // default frontend controller
    $routes->connect('/', ['controller' => 'frontend']);
    $routes->connect('/install', ['controller' => 'install'], ['_name' => 'install']);

    $routes->fallbacks(DashedRoute::class);
});

// rest api routes
Router::prefix('api', function (RouteBuilder $routes) {
    // register rest api middleware
    $routes->registerMiddleware('rest', new RestApiMiddleware());
    $routes->applyMiddleware('rest');

    // mapping to rest controller
    $routes->connect('/', ['controller' => 'OpenTransportApi', 'action' => 'index'], ['_name' => 'api']);
    $routes->connect('/:action', ['controller' => 'OpenTransportApi'])->setExtensions(['json', 'xml']);
    $routes->connect('/:action/:param', ['controller' => 'OpenTransportApi'])->setPass(['param'])->setExtensions(['json', 'xml']);

    $routes->fallbacks(DashedRoute::class);
});

// admin routes
Router::prefix('admin', function (RouteBuilder $routes) {
    // basic login / logout actions and dashboard
    $routes->connect('/', ['controller' => 'System', 'action' => 'index'], ['_name' => 'index']);
    $routes->connect('/login', ['controller' => 'Users', 'action' => 'login'], ['_name' => 'login']);
    $routes->connect('/logout', ['controller' => 'Users', 'action' => 'logout'], ['_name' => 'logout']);

    // internal ajax route
    $routes->connect('/ajax/:action', ['controller' => 'Ajax'])->setExtensions(['json']);

    $routes->fallbacks(DashedRoute::class);
});
