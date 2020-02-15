<?php
namespace App\Model\Entity;

use Cake\Auth\DefaultPasswordHasher;
use Cake\ORM\Entity;
use Cake\ORM\Query;

/**
 * User Entity
 *
 * @property int $id
 * @property string $username
 * @property string $email
 * @property string $password
 * @property int $group_id
 * @property int $client_id
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\Group $group
 * @property \App\Model\Entity\Client $client
 * @property UserSetting[] $user_settings
 */
class User extends Entity implements \Authorization\IdentityInterface, \Authentication\IdentityInterface
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
        'username' => true,
        'email' => true,
        'password' => true,
        'activated' => true,
        'group_id' => true,
        'client_id' => true,
        'created' => true,
        'modified' => true,
        'group' => true,
        'client' => true,
        'user_settings' => true
    ];

    /**
     * Fields that are excluded from JSON versions of the entity.
     *
     * @var array
     */
    protected $_hidden = [
        'password'
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
    
    /**
     * Internal setter method for password. Hashes the password before storing it
     * by the DefaultPasswordHasher.
     * 
     * @param string $password
     * @return string
     */
    protected function _setPassword($password) {
        if(strlen($password)) {
            $hasher = new DefaultPasswordHasher();
            return $hasher->hash($password);
        }
    }

    /**
     * Setting lookup method.
     *
     * @param $settingName The setting name to be returned.
     * @return UserSetting|null Returns the corresponding UserSetting or null if not found.
     */
    public function getUserSetting($settingName) {
        if(isset($this->user_settings)) {
            foreach($this->user_settings as $setting) {
                if($setting->name == $settingName) {
                    return $setting;
                }
            }
        }

        return null;
    }
    
    public function bindNode($node) {
        return ['model' => 'Groups', 'foreign_key' => $this->group_id];
    }
    
    public function setAuthorization($service)
    {
        $this->authorization = $service;
        return $this;
    }

    public function applyScope($action, $resource) {
        return $this->authorization->applyScope($this, $action, $resource);
    }

    public function can($action, $resource) {
        return $this->authorization->can($this, $action, $resource);
    }

    public function getOriginalData() {
        return $this;
    }

    public function getIdentifier() {
        return $this->id;
    }

}
