<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Frequency Entity
 *
 * @property int $id
 * @property int $trip_id
 * @property \Cake\I18n\FrozenTime $start_time
 * @property \Cake\I18n\FrozenTime $end_time
 * @property int $headway_secs
 * @property string $exact_times
 * @property int $flags
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Trip $trip
 */
class Frequency extends Entity
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
        'trip_id' => true,
        'start_time' => true,
        'end_time' => true,
        'headway_secs' => true,
        'headway_min' => true,
        'exact_times' => true,
        'flags' => true,
        'created' => true,
        'modified' => true,
        'trip' => true
    ];

    /**
     * Virtual field to populate the headway in minutes.
     *
     * @return int The headway in minutes.
     */
    protected function _getHeadwayMin() {
        return $this->headway_secs / 60;
    }

    /**
     * Virtual field to populate the headway in minutes.
     *
     * @param $value The headway in minutes.
     */
    protected function _setHeadwayMin($value) {
        $this->headway_secs = $value * 60;
    }
}
