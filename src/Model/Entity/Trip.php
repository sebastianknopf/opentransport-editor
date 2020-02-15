<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\ORM\Locator\TableLocator;
use Cake\ORM\TableRegistry;

/**
 * Trip Entity
 *
 * @property int $id
 * @property int $client_id
 * @property string $route_id
 * @property string $service_id
 * @property string $trip_id
 * @property string $trip_headsign
 * @property string $trip_short_name
 * @property string $direction_id
 * @property string|null $block_id
 * @property string|null $shape_id
 * @property string $wheelchair_accessible
 *
 * @property \App\Model\Entity\Client $client
 * @property \App\Model\Entity\Route $route
 * @property \App\Model\Entity\Calendar $calendar
 * @property \App\Model\Entity\Shape $shape
 * @property \App\Model\Entity\StopTime[] $stop_times
 */
class Trip extends Entity
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
        'route_id' => true,
        'route_variation_id' => true,
        'service_id' => true,
        'trip_id' => true,
        'trip_headsign' => true,
        'trip_short_name' => true,
        'direction_id' => true,
        'block_id' => true,
        'shape_id' => true,
        'wheelchair_accessible' => true,
        'bikes_allowed' => true,
        'client' => true,
        'route' => true,
        'service' => true,
        'shape' => true,
        'stop_times' => true,
        'start_time' => true,
        'end_time' => true,
        'frequencies' => true
    ];

    protected $_hidden = [
        'client_id',
        'route_id',
        'route_variation_id',
        'service_id',
        'shape_id',
        'flags',
        'created',
        'modified',
        '_matchingData'
    ];

    /**
     * Returns a single instance Client object for permission checks.
     *
     * @return Client Single instance object for permission checks.
     */
    public static function getInstance($clientId = 0) {
        if (self::$_singleInstance == null) {
            self::$_singleInstance = new Trip();
        }

        self::$_singleInstance->client_id = $clientId;

        return self::$_singleInstance;
    }

    /**
     * Virtual field for human-readable route variation name dynamically calculated of stored stop times.
     *
     * @return string|null Human-readable route variation name.
     */
    protected function _getRouteVariationName() {
        if(!isset($this->stop_times) || $this->stop_times == null) {
            $table = TableRegistry::getTableLocator()->get($this->getSource());
            $table->loadInto($this, ['StopTimes' => ['Stops']]);
        }

        $stop_succession = [];
        foreach($this->stop_times as $stop_time) {
            if($stop_time->stop != null) {
                array_push($stop_succession, $stop_time->stop->stop_code);
            } else {
                array_push($stop_succession, $stop_time->stop_id);
            }
        }

        if(count($stop_succession) > 0) {
            return $stop_succession[0] . '_' . $stop_succession[count($stop_succession) - 1];
        } else {
            return null;
        }
    }

    /**
     * Returns an array with all directions.
     *
     * @return array All directions.
     */
    public function getDirections() {
        return [
            '0' => __('Outbound'),
            '1' => __('Inbound')
        ];
    }

    /**
     * Returns a textual representation of direction id.
     *
     * @param $id Input direction code.
     * @return mixed The textual representation of direction.
     */
    public function getDirectionString($id) {
        return $this->getDirections()[$id];
    }
}
