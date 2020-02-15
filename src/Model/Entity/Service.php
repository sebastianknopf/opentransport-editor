<?php
namespace App\Model\Entity;

use Cake\Chronos\Date;
use Cake\ORM\Entity;

/**
 * Service Entity
 *
 * @property int $service_id
 * @property int $client_id
 * @property string $service_name
 * @property string $start_date
 * @property string $end_date
 * @property string $monday
 * @property string $tuesday
 * @property string $wednesday
 * @property string $thursday
 * @property string $friday
 * @property string $saturday
 * @property string $sunday
 *
 * @property Client $client
 * @property ServiceException[] $service_exceptions
 *
 * @package App\Model\Entity
 */
class Service extends Entity
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
        'service_id' => true,
        'client_id' => true,
        'service_name' => true,
        'start_date' => true,
        'end_date' => true,
        'monday' => true,
        'tuesday' => true,
        'wednesday' => true,
        'thursday' => true,
        'friday' => true,
        'saturday' => true,
        'sunday' => true,
        'client' => true,
        'service_exceptions' => true
    ];

    protected $_hidden = [
        'client_id',
        'service_name',
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
            self::$_singleInstance = new Service();
        }

        self::$_singleInstance->client_id = $clientId;

        return self::$_singleInstance;
    }

    /**
     * Provides a virtual field with all service days.
     *
     * @return string The human readable string with all service days.
     */
    protected function _getServiceDays() {
        $serviceDays = '';
        if(!$this->monday && !$this->tuesday && !$this->wednesday && !$this->thursday && !$this->friday && !$this->saturday && !$this->sunday) {
            $serviceDays .= '[' . __('Never') . ']';
        } else if($this->monday && $this->tuesday && $this->wednesday && $this->thursday && $this->friday && $this->saturday && $this->sunday) {
            $serviceDays .= '[' . __('Everyday') . ']';
        } else {
            $dayList = [];

            $this->monday ? array_push($dayList, __('Mon')) : null;
            $this->tuesday ? array_push($dayList, __('Tue')) : null;
            $this->wednesday ? array_push($dayList, __('Wed')) : null;
            $this->thursday ? array_push($dayList, __('Thu')) : null;
            $this->friday ? array_push($dayList, __('Fri')) : null;
            $this->saturday ? array_push($dayList, __('Sat')) : null;
            $this->sunday ? array_push($dayList, __('Sun')) : null;

            $serviceDays .= ' [' . implode(', ', $dayList) . ']';
        }

        return $serviceDays;
    }
    /**
     * Accessor for virtual field used in displayFields.
     *
     * @return string Displayable string from service object.
     */
    protected function _getLabel() {
        return $this->_properties['service_id'] . ' - ' . $this->_properties['service_name'];
    }

    /**
     * Provides a virtual field with service description.
     *
     * @return string The human readable string of this service with all service days and exceptions
     */
    protected function _getServiceString() {
        $serviceString = $this->start_date . ' - ' . $this->end_date;
        $serviceString .= ' ' . $this->service_days;

        if(isset($this->service_exceptions)) {
            $additionals = array();
            foreach($this->service_exceptions as $exception) {
                if($exception->exception_type == 1) {
                    array_push($additionals, $exception->date);
                }
            }

            $exceptionals = array();
            foreach($this->service_exceptions as $exception) {
                if($exception->exception_type == 2) {
                    array_push($exceptionals, $exception->date);
                }
            }

            if(count($additionals)) {
                $serviceString .= ' ' . __('additionally on') . ' ' . implode(', ', $additionals);
            }

            if(count($exceptionals)) {
                $serviceString .= ' ' . __('not on') . ' ' . implode(', ', $exceptionals);
            }
        }

        return $serviceString;
    }
}