<?php

namespace App\Shell\Task;

use App\Model\Entity\Message;
use App\Model\Entity\Message\Level;
use Queue\Model\QueueException;
use Queue\Shell\Task\QueueTask;
use Queue\Shell\Task\QueueTaskInterface;

class QueueDataMonitorTask extends QueueTask implements QueueTaskInterface
{
    /**
     * @var int
     */
    public $timeout = 600;

    /**
     * @var int
     */
    public $retries = 1;

    /**
     * List of all entity types which can be monitored by this task.
     *
     * @var array List of all entity types to allow monitoring.
     */
    protected $_monitorEntities = [
        'Stops',
        'Shapes',
        'Services',
        'Agencies',
        'Routes',
        'Trips'
    ];

    /**
     * Buffer for all created messages to determine messages which must be deleted-
     *
     * @var array Messages buffer
     */
    protected $_currentMessages = [];

    /**
     * Main execution of the task.
     *
     * @param array $data The array passed to QueuedJobsTable::createJob()
     * @param int $jobId The id of the QueuedJob entity
     * @return void
     */
    public function run(array $data, $jobId)
    {
        if (count($data) < 1) {
            throw new QueueException('no entity types passed for monitoring');
        }

        $this->loadModel('Messages');

        foreach ($data as $entityType) {
            $performMethodCall = 'perform' . $entityType . 'Monitoring';
            if (method_exists($this, $performMethodCall) && in_array($entityType, $this->_monitorEntities)) {
                $this->$performMethodCall();

                if (count($this->_currentMessages) > 0) {
                    // delete only those messages which are not meant to be kept
                    $this->Messages->deleteAll([
                        'Messages.serial NOT IN' => $this->_currentMessages,
                        'Messages.entity_source' => $entityType
                    ]);

                    $this->_currentMessages = [];
                } else {
                    // delete all messages, there's no message meant to be left
                    $this->Messages->deleteAll([
                        'Messages.entity_source' => $entityType
                    ]);
                }
            }
        }
    }

    /**
     * Performs the monitoring on entities of type Stop.
     */
    private function performStopsMonitoring()
    {
        $this->loadModel('Stops');

        $stops = $this->Stops->find()->contain([
            'StopTimes'
        ]);

        foreach ($stops as $stop) {
            if ($stop->location_type == 0 && $stop->platform_code == '') {
                $this->createMessage(Level::WARNING, 'Stops', $stop->stop_id, __('stop #{0} does not specify a platform code', $stop->stop_id));
            }

            if (count($stop->stop_times) < 1) {
                $this->createMessage(Level::WARNING, 'Stops', $stop->stop_id, __('stop #{0} is never used in any trip', $stop->stop_id));
            }
        }
    }

    /**
     * Performs the monitoring on entities of type Shape.
     */
    private function performShapesMonitoring()
    {
        $this->loadModel('Shapes');

        $shapes = $this->Shapes->find()->contain([
            'Trips'
        ]);

        foreach ($shapes as $shape) {
            if (count($shape->trips) < 1) {
                $this->createMessage(Level::WARNING, 'Shapes', $shape->shape_id, __('shape #{0} is never used in any trip', $shape->shape_id));
            }
        }
    }

    /**
     * Performs the monitoring on entities of type Service.
     */
    private function performServicesMonitoring()
    {
        $this->loadModel('Services');

        $services = $this->Services->find()->contain([
            'ServiceExceptions',
            'Trips'
        ]);

        foreach ($services as $service) {
            if (count($service->trips) < 1) {
                $this->createMessage(Level::WARNING, 'Services', $service->service_id, __('service #{0} is never used in any trip', $service->service_id));
            }

            $serviceExceptionArray = [];
            foreach ($service->service_exceptions as $exception) {
                if (array_key_exists($exception->date->format('Ymd'), $serviceExceptionArray)) {
                    if ($exception->exception_type != $serviceExceptionArray[$exception->date->format('Ymd')]) {
                        $this->createMessage(Level::ERROR, 'Services', $service->service_id, __('service exception #{0} causes a conflict with another service exception', $exception->id));
                    }
                }

                $serviceExceptionArray[$exception->date->format('Ymd')] = $exception->exception_type;
            }
        }
    }

    /**
     * Performs the monitoring on entities of type Agency.
     */
    private function performAgenciesMonitoring()
    {
        $this->loadModel('Agencies');

        $agencies = $this->Agencies->find()->contain([
            'Routes'
        ]);

        foreach ($agencies as $agency) {
            if (count($agency->routes) < 1) {
                $this->createMessage(Level::WARNING, 'Agencies', $agency->agency_id, __('agency #{0} has no routes assigned', $agency->agency_id));
            }
        }
    }

    /**
     * Performs the monitoring on entities of type Route.
     */
    private function performRoutesMonitoring()
    {
        $this->loadModel('Routes');

        $routes = $this->Routes->find()->contain([
            'Trips'
        ]);

        foreach ($routes as $route) {
            if (count($route->trips) < 1) {
                $this->createMessage(Level::WARNING, 'Routes', $route->route_id, __('route #{0} has no trips assigned', $route->route_id));
            }

            if (preg_match('/^#?([0-9a-f]{2})([0-9a-f]{2})([0-9a-f]{2})$/i', $route->route_color) && preg_match('/^#?([0-9a-f]{2})([0-9a-f]{2})([0-9a-f]{2})$/i', $route->route_text_color)) {
                if (($this->isColorLight($route->route_color) && $this->isColorLight($route->route_text_color)) || (!$this->isColorLight($route->route_color) && !$this->isColorLight($route->route_text_color))) {
                    $this->createMessage(Level::WARNING, 'Routes', $route->route_id, __('route #{0} uses a mismatching color scheme', $route->route_id));
                }
            }
        }
    }

    /**
     * Performs the monitoring on entities of type Trip.
     */
    private function performTripsMonitoring()
    {
        $this->loadModel('Trips');

        $trips = $this->Trips->find()->contain([
            'StopTimes',
            'Frequencies'
        ]);

        foreach ($trips as $trip) {
            if ($trip->trip_short_name == '') {
                $this->createMessage(Level::WARNING, 'Trips', $trip->trip_id, __('trip #{0} does not specify a short name', $trip->trip_id));
            }

            if ($trip->shape_id == '') {
                $this->createMessage(Level::WARNING, 'Trips', $trip->trip_id, __('trip #{0} does not specify a shape', $trip->trip_id));
            }

            $lastStopTime = null;

            $startStopTime = $trip->stop_times[0];
            $endStopTime = $trip->stop_times[count($trip->stop_times) - 1];
            foreach ($trip->stop_times as $stopTime) {
                if ($lastStopTime != null) {
                    if ($lastStopTime->departure_time > $stopTime->arrival_time) {
                        $this->createMessage(Level::ERROR, 'Trips', $trip->trip_id, __('two or more stop times in trip #{0} are overlapping each other', $trip->trip_id));
                    }
                }

                if ($stopTime->arrival_time > $stopTime->departure_time) {
                    $this->createMessage(Level::ERROR, 'Trips', $trip->trip_id, __('two or more stop times in trip #{0} are inconsistent', $trip->trip_id));
                }

                if ($stopTime->arrival_time < $startStopTime->arrival_time || $stopTime->departure_time > $endStopTime->departure_time) {
                    $this->createMessage(Level::ERROR, 'Trips', $trip->trip_id, __('two or more stop times in trip #{0} are inconsistent', $trip->trip_id));
                }

                $lastStopTime = $stopTime;
            }

            $lastFrequency = null;
            foreach ($trip->frequencies as $frequency) {
                if ($lastFrequency != null) {
                    if ($lastFrequency->end_time > $frequency->start_time) {
                        $this->createMessage(Level::ERROR, 'Trips', $trip->trip_id, __('two or more frequencies in trip #{0} are overlapping each other', $trip->trip_id));
                    }
                }

                if ($frequency->start_time > $frequency->end_time) {
                    $this->createMessage(Level::ERROR, 'Trips', $trip->trip_id, __('two or more frequencies in trip #{0} are inconsistent', $trip->trip_id));
                }

                $lastFrequency = $frequency;
            }
        }
    }

    /**
     * Creates and saves a system message from passed arguments.
     *
     * @param $messageLevel The level of the message
     * @param $entityType The entity type the message is related to
     * @param $entityId The primary key of the entity the message is related to
     * @param $shortText The short informational text
     * @param null $longText The long message text
     */
    private function createMessage($messageLevel, $entityType, $entityId, $shortText, $longText = null)
    {
        if ($longText == null) {
            $longText = $shortText;
        }

        $messageData = implode(';', [$messageLevel, $entityType, $entityId, $shortText, $longText]);
        $messageHash = md5($messageData);

        $message = $this->Messages->find()->where(['Messages.serial' => $messageHash])->first();
        if ($message != null) {
            //$message->flags = Message::STATUS_ACTIVE;
        } else {
            $message = $this->Messages->newEntity();
            $message->level = $messageLevel;
            $message->serial = $messageHash;
            $message->entity_source = $entityType;
            $message->entity_id = $entityId;
            $message->short_message = $shortText;
            $message->long_message = $longText;
            $message->flags = Message::STATUS_ACTIVE;
        }

        if (!$this->Messages->save($message)) {
            throw new QueueException('could not create message');
        }

        array_push($this->_currentMessages, $messageHash);
    }

    /**
     * Checks whether a color can be estimated to be light or dark.
     *
     * @param $hex The color as hexadecimal value
     * @return bool Color light or not?
     */
    private function isColorLight($hex)
    {
        $hex       = str_replace('#', '', $hex);
        $r         = (hexdec(substr($hex, 0, 2)) / 255);
        $g         = (hexdec(substr($hex, 2, 2)) / 255);
        $b         = (hexdec(substr($hex, 4, 2)) / 255);
        $lightness = round((((max($r, $g, $b) + min($r, $g, $b)) / 2) * 100));

        return ($lightness >= 50 ? true : false);
    }
}