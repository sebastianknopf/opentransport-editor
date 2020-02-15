<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ServiceException Entity
 *
 * @property int $service_id
 * @property string $date
 * @property string $exception_type
 *
 * @package App\Model\Entity
 */
class ServiceException extends Entity
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
        'service_id' => true,
        'date' => true,
        'exception_type' => true
    ];

    protected $_hidden = [
        'id',
        'service_id',
        'flags',
        'created',
        'modified'
    ];
}