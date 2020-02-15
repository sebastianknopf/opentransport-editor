<?php

namespace App\Middleware;

use App\Error\RestExceptionRenderer;
use App\Event\RestApiRequestHandler;
use Cake\Error\Middleware\ErrorHandlerMiddleware;
use http\Exception\RuntimeException;

class RestApiMiddleware extends ErrorHandlerMiddleware
{
    /**
     * Only set a custom exception renderer here.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request The current request.
     * @param \Psr\Http\Message\ResponseInterface $response The current response.
     * @param callable $next The next middleware callable.
     * @return \Psr\Http\Message\ResponseInterface The response of the next applied middleware.
     */
    public function __invoke($request, $response, $next)
    {
        $this->exceptionRenderer = RestExceptionRenderer::class;
        return parent::__invoke($request, $response, $next);
    }
}