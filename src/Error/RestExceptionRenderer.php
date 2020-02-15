<?php

namespace App\Error;

use App\Controller\Api\OpenTransportApiController;
use App\Model\Entity\ApiLog;
use Cake\Core\Configure;
use Cake\Error\ExceptionRenderer;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Response;
use Cake\Utility\Xml;

class RestExceptionRenderer extends ExceptionRenderer
{
    const CONTENT_TYPE_JSON = 'json';
    const CONTENT_TYPE_XML = 'xml';

    /**
     * Renders any exception in the desired content format.
     *
     * @return Response|\Psr\Http\Message\MessageInterface The modified exception message in desired format.
     */
    public function render()
    {
        $contentType = $this->controller->request->getParam('_ext');

        $request = $this->controller->request;
        $response = $this->controller->response;
        $exception = $this->error;
        $body = $response->getBody();

        $statusCode = 500;
        if(in_array($exception->getCode(), [500, 501, 502, 503, 504, 505, 400, 401, 402, 403, 404])) {
            $statusCode = $exception->getCode();
        }

        if($contentType == self::CONTENT_TYPE_JSON) {
            $responseBody = [
                'status' => $statusCode,
                'message' => $exception->getMessage()
            ];

            $body->write(json_encode($responseBody));
        } else if($contentType == self::CONTENT_TYPE_XML) {
            $responseBody = [
                'status' => $statusCode,
                'message' => $exception->getMessage()
            ];

            $body->write(Xml::fromArray(['response' => $responseBody], 'tags')->asXML());
        } else { // default exception printing with 'no' content type
            $contentType = 'text';
            $responseBody = $exception->getMessage();

            $body->write($responseBody);
        }

        // build response and log it optionally
        $response = $response->withBody($body)->withType($contentType)->withStatus($statusCode);
        if(Configure::read('RestAPI.logRequests') && (Configure::read('RestAPI.logCodes') == '*' || in_array($exception->getCode(), Configure::read('RestAPI.logCodes')))) {
            ApiLog::add($request, $response, $exception->getMessage());
        }

        // return response with CORS enabled
        return $response->cors($request)
            ->allowOrigin(Configure::read('RestAPI.CORS.allowOrigins'))
            ->allowMethods(Configure::read('RestAPI.CORS.allowMethods'))
            ->allowHeaders(Configure::read('RestAPI.CORS.allowHeaders'))
            ->maxAge(Configure::read('RestAPI.CORS.maxAge'))
            ->allowCredentials(['true'])
            ->build();
    }
}