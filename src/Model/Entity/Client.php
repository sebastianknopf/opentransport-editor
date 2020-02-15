<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Client Entity
 *
 * @property int $id
 * @property string $shortname
 * @property string $longname
 */
class Client extends Entity
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
        'shortname' => true,
        'longname' => true
    ];

    /**
     * Returns a single instance Client object for permission checks.
     *
     * @return Client Single instance object for permission checks.
     */
    public static function getInstance() {
        if(self::$_singleInstance == null) {
            self::$_singleInstance = new Client();
        }

        return self::$_singleInstance;
    }
}
