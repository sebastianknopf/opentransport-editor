<?php

namespace App\Model\Entity;

use Tools\Model\Entity\Entity;

/**
 * Message Entity
 *
 * @property int $id
 * @property string $serial
 * @property string $level
 * @property string|null $entity_source
 * @property int|null $entity_id
 * @property string $short_message
 * @property string $long_message
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Entity $entity
 */
class Message extends Entity
{
    const STATUS_ACTIVE = 0;
    const STATUS_IGNORE = 1;

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
        'serial' => true,
        'level' => true,
        'entity_source' => true,
        'entity_id' => true,
        'short_message' => true,
        'long_message' => true,
        'created' => true,
        'modified' => true,
        'entity' => true
    ];

    /**
     * Static method for bitmask provider.
     *
     * @param null $value The flag value to be used
     * @return mixed The flags or value of specific flag
     */
    public static function flags($value = null)
    {
        $options = [
            self::STATUS_ACTIVE => __('Active'),
            self::STATUS_IGNORE => __('Ignored')
        ];

        return parent::enum($value, $options);
    }
}
