<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\ORM\Query;

/**
 * Stop Entity
 *
 * @property int $id
 * @property string $stop_id
 * @property string|null $stop_code
 * @property string $stop_name
 * @property string $stop_desc
 * @property float $stop_lat
 * @property float $stop_lon
 * @property string $location_type
 * @property string $parent_station
 * @property string $wheelchair_boarding
 *
 * @property \App\Model\Entity\StopTime[] $stop_times
 */
class Stop extends Entity
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
        'stop_id' => true,
        'stop_code' => true,
        'stop_name' => true,
        'stop_desc' => true,
        'stop_lat' => true,
        'stop_lon' => true,
        'location_type' => true,
        'parent_station' => true,
        'platform_code' => true,
        'wheelchair_boarding' => true,
        'stop_times' => true
    ];

    protected $_hidden = [
        'flags',
        'created',
        'modified'
    ];

    /**
     * Returns a single instance Client object for permission checks.
     *
     * @return Client Single instance object for permission checks.
     */
    public static function getInstance() {
        if(self::$_singleInstance == null) {
            self::$_singleInstance = new Stop();
        }

        return self::$_singleInstance;
    }
}
