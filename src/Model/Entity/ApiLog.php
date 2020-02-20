<?php
namespace App\Model\Entity;

use Cake\Http\Response;
use Cake\Network\Request;
use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;

/**
 * Log Entity
 *
 * @property int $id
 * @property string $method
 * @property string $endpoint
 * @property string $request_data
 * @property int $response_code
 * @property string $response_data
 * @property string $exception
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 */
class ApiLog extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'method' => true,
        'endpoint' => true,
        'query_params' => true,
        'request_data' => true,
        'response_code' => true,
        'response_data' => true,
        'exception' => true,
        'created' => true,
        'modified' => true
    ];

    /**
     * Adds an REST API log entry from everywhere in code.
     *
     * @param Request $request The request to log.
     * @param Response $response The response to log.
     * @param string $exception The exception fired during processing.
     */
    public static function add(Request $request, Response $response, $exception = '')
    {
        try {
            $table = TableRegistry::getTableLocator()->get('ApiLogs');

            $logData = [
                'method' => $request->getMethod(),
                'endpoint' => $request->getRequestTarget(),
                'query_params' => json_encode($request->getQueryParams()),
                'request_data' => $request->getBody()->getContents(),
                'response_code' => $response->getStatusCode(),
                'response_data' => $response->body(),
                'exception' => $exception
            ];

            $self = $table->newEntity($logData);
            $table->save($self);
        } catch(\Exception $exception) {
            // throw $exception;
        }
    }
}
