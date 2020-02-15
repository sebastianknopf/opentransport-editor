<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;

/**
 * Route Entity
 *
 * @property int $id
 * @property int $client_id
 * @property string $agency_id
 * @property string $route_id
 * @property string $route_short_name
 * @property string $route_long_name
 * @property string|null $route_desc
 * @property string $route_type
 * @property string|null $route_url
 * @property string|null $route_color
 * @property string|null $route_text_color
 *
 * @property \App\Model\Entity\Client $client
 * @property \App\Model\Entity\Agency $agency
 * @property \App\Model\Entity\Trip[] $trips
 */
class Route extends Entity
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
        'route_id' => true,
        'route_short_name' => true,
        'route_long_name' => true,
        'route_desc' => true,
        'route_type' => true,
        'route_url' => true,
        'route_color' => true,
        'route_text_color' => true,
        'flags' => true,
        'modified' => true,
        'created' => true,
        'client' => true,
        'agency' => true,
        'trips' => true
    ];

    protected $_hidden = [
        'client_id',
        'agency_id',
        'flags',
        'created',
        'modified'
    ];

    /**
     * Returns a single instance Client object for permission checks.
     *
     * @return Client Single instance object for permission checks.
     */
    public static function getInstance($clientId = 0)
    {
        if (self::$_singleInstance == null) {
            self::$_singleInstance = new Route();
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
        return $this->_properties['route_id'] . ' - ' . $this->_properties['route_short_name'];
    }

    /**
     * Returns an array with all available route variations.
     *
     * @return array All available variations.
     */
    protected function _getRouteVariations() {
        $variations = [];

        // need to load the trips by a table here due to a maybe previously filter set
        $trips = TableRegistry::getTableLocator()->get('Trips');
        foreach($trips->find('all')->where(['Trips.route_id' => $this->route_id]) as $trip) {
            if(!array_key_exists($trip->route_variation_id, $variations)) {
                $variations[$trip->route_variation_id] = $trip->route_variation_name;
            }
        }

        return $variations;
    }

    /**
     * Virtual field for all service IDs used in this route.
     *
     * @return array All services used in a route.
     */
    protected function _getRouteServices() {
        $services = [];

        // need to load the trips by a table here due to a maybe previously filter set
        $trips = TableRegistry::getTableLocator()->get('Trips');
        foreach($trips->find('all')->where(['Trips.route_id' => $this->route_id])->contain(['Services']) as $trip) {
            if(!array_key_exists($trip->service_id, $services)) {
                $services[$trip->service_id] = $trip->service != null ? $trip->service->service_name : $trip->service_id;
            }
        }

        return $services;
    }

    /**
     * Returns the textual representation of a route type.
     *
     * @param $type The input route type id.
     * @return mixed Textual representation for $type.
     */
    /*public function getRouteTypeString($type) {
        return $this->getRouteTypes()[$type];
    }*/
}
