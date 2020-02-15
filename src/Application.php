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
 * @since     3.3.0
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App;

use App\Error\RestExceptionRenderer;
use App\Middleware\RestApiMiddleware;
use Authentication\AuthenticationService;
use Authentication\Middleware\AuthenticationMiddleware;
use Authorization\AuthorizationService;
use Authorization\IdentityInterface;
use Authorization\Middleware\AuthorizationMiddleware;
use Authorization\Policy\OrmResolver;
use Cake\Core\Configure;
use Cake\Core\Exception\MissingPluginException;
use Cake\Error\Middleware\ErrorHandlerMiddleware;
use Cake\Http\BaseApplication;
use Cake\Routing\Middleware\AssetMiddleware;
use Cake\Routing\Middleware\RoutingMiddleware;
use Cake\Routing\Router;
use DebugKit\Plugin;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Application setup class.
 *
 * This defines the bootstrapping logic and middleware layers you
 * want to use in your application.
 */
class Application extends BaseApplication implements \Authentication\AuthenticationServiceProviderInterface, \Authorization\AuthorizationServiceProviderInterface
{
    /**
     * {@inheritDoc}
     */
    public function bootstrap()
    {
        // Call parent to load bootstrap from files.
        parent::bootstrap();

        if (PHP_SAPI === 'cli') {
            try {
                $this->addPlugin('Bake');
            } catch (MissingPluginException $e) {
                // Do not halt if the plugin is missing
            }
        }

        $this->addPlugin('Migrations');

        $this->addPlugin('Authentication');
        $this->addPlugin('Authorization');
        $this->addPlugin('Acl');

        $this->addPlugin('Duplicatable');
        $this->addPlugin('Search');
        $this->addPlugin('Queue');
        $this->addPlugin('Tools');

        // load theme
        $this->addPlugin('AdminLTE');
        $this->addPlugin('Markdown');

        /*
         * Only try to load DebugKit in development mode
         * Debug Kit should not be installed on a production system
         */
        if (Configure::read('debug')) {
            $this->addPlugin(Plugin::class);
        }
    }

    /**
     * Setup the middleware queue your application will use.
     *
     * @param \Cake\Http\MiddlewareQueue $middlewareQueue The middleware queue to setup.
     * @return \Cake\Http\MiddlewareQueue The updated middleware queue.
     */
    public function middleware($middlewareQueue)
    {
        $middlewareQueue
            // Catch any exceptions in the lower layers,
            // and make an error page/response
            ->add(new ErrorHandlerMiddleware(null, Configure::read('Error')))

            // Handle plugin/theme assets like CakePHP normally does.
            ->add(new AssetMiddleware([
                'cacheTime' => Configure::read('Asset.cacheTime')
            ]))

            // Add routing middleware.
            // Routes collection cache enabled by default, to disable route caching
            // pass null as cacheConfig, example: `new RoutingMiddleware($this)`
            // you might want to disable this cache in case your routing is extremely simple
            ->add(new RoutingMiddleware($this, '_cake_routes_'));

        $authentication = new AuthenticationMiddleware($this);

        $middlewareQueue->add($authentication);

        $middlewareQueue->add(new AuthorizationMiddleware($this, [
            'identityDecorator' => function ($auth, $user) {
                return $user->setAuthorization($auth);
            },
            'requireAuthorizationCheck' => Configure::read('Authorization.requireAuthorizationCheck')
        ]));

        return $middlewareQueue;
    }

    /**
     * Provides the authentication service.
     * 
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return AuthenticationService
     */
    public function getAuthenticationService(ServerRequestInterface $request, ResponseInterface $response) {
        $service = new AuthenticationService();
        $service->setConfig([
            'unauthenticatedRedirect' => Configure::read('Authentication.unauthenticatedUrl'),
            'queryParam' => Configure::read('Authentication.queryParam')
        ]);

        $identifierFields = [
            'username' => ['username', 'email'],
            'password' => 'password'
        ];
        
        $authenticatorFields = [
            'username' => 'username',
            'password' => 'password'
        ];
        
        // Load identifiers
        $service->loadIdentifier('Authentication.JwtSubject');
        $service->loadIdentifier('Authentication.Password', compact('identifierFields'));

        // Load the authenticators, want session first
        $service->loadAuthenticator('Authentication.Jwt', [
            'returnPayload' => false // pass through identification to login the user
        ]);
        $service->loadAuthenticator('Authentication.Session');
        $service->loadAuthenticator('Authentication.Form', compact('authenticatorFields'));

        return $service;
    }

    /**
     * Provides the authorization service.
     * 
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     */
    public function getAuthorizationService(ServerRequestInterface $request, ResponseInterface $response) {
        $resolver = new OrmResolver();
        return new AuthorizationService($resolver);
    }

}
