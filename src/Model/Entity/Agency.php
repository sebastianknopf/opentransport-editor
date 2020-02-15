<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Agency Entity
 *
 * @property int $id
 * @property int $client_id
 * @property string $agency_id
 * @property string $agency_name
 * @property string $agency_url
 * @property string $agency_timezone
 * @property string $agency_lang
 * @property string|null $agency_phone
 * @property string|null $agency_fare_url
 *
 * @property \App\Model\Entity\Client $client
 * @property \App\Model\Entity\Route[] $routes
 */
class Agency extends Entity
{
    /**
     * @var Client Single instance for permission checks.
     */
    protected static $_singleInstance;

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
        'client_id' => true,
        'agency_id' => true,
        'agency_name' => true,
        'agency_url' => true,
        'agency_timezone' => true,
        'agency_lang' => true,
        'agency_phone' => true,
        'agency_fare_url' => true,
        'flags' => true,
        'modified' => true,
        'created' => true,
        'client' => true,
        'routes' => true
    ];

    protected $_hidden = [
        'client_id',
        'flags',
        'created',
        'modified'
    ];

    /**
     * Returns a single instance Client object for permission checks.
     *
     * @return Client Single instance object for permission checks.
     */
    public static function getInstance($clientId = 0) {
        if (self::$_singleInstance == null) {
            self::$_singleInstance = new Agency();
        }

        self::$_singleInstance->client_id = $clientId;

        return self::$_singleInstance;
    }

    /**
     * Accessor for virtual field used in displayFields.
     *
     * @return string Displayable string from agency object.
     */
    protected function _getLabel() {
        return $this->_properties['agency_id'] . ' - ' . $this->_properties['agency_name'];
    }
}
