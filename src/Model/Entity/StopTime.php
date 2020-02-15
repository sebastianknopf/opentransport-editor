<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * StopTime Entity
 *
 * @property int $id
 * @property string $trip_id
 * @property string $stop_id
 * @property string $arrival_time
 * @property string $departure_time
 * @property integer $stop_sequence
 * @property integer $pickup_type
 * @property integer $drop_off_type
 *
 * @property \App\Model\Entity\Stop $stop
 * @property \App\Model\Entity\Trip $trip
 */
class StopTime extends Entity
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
        'id' => true,
        'trip_id' => true,
        'stop_id' => true,
        'arrival_time' => true,
        'departure_time' => true,
        'stop_sequence' => true,
        'pickup_type' => true,
        'drop_off_type' => true,
        'flags' => true,
        'modified' => true,
        'created' => true,
        'stop' => true,
        'trip' => true
    ];

    protected $_hidden = [
        'id',
        'trip_id',
        'stop_id',
        'stop_sequence',
        'flags',
        'created',
        'modified'
    ];
}
