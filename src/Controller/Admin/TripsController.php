<?php
namespace App\Controller\Admin;

use App\Controller\AdminController;
use App\Controller\AppController;
use Cake\Console\ShellDispatcher;
use Cake\Event\Event;
use App\Model\Entity\Trip;
use Cake\I18n\Time;
use Cake\Routing\Router;

/**
 * Trips Controller
 *
 * @property \App\Model\Table\TripsTable $Trips
 *
 * @method \App\Model\Entity\Trip[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class TripsController extends AdminController
{
    /**
     * Controller initialize method
     *
     * @throws \Exception
     */
    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('Search.Prg', [
            'actions' => ['index']
        ]);

        $this->loadSessionFilter('Trips');
    }

    /**
     * Before filter method.
     *
     * @param Event $event
     * @return \Cake\Http\Response|null
     */
    public function beforeFilter(Event $event)
    {
        $this->set('title', __('Trips'));

        $this->Security->setConfig([
            'unlockedActions' => ['add', 'edit', 'copy']
        ]);

        return parent::beforeFilter($event);
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $trips = $this->Trips->find('search', [
            'search' => $this->request->getQueryParams()
        ])->contain([
            'Routes',
            'Services',
            'StopTimes' => [
                'Stops'
            ]
        ]);

        $trips = $this->Authorization->applyScope($trips);
        $trips = $this->paginate($trips);

        $services = $this->Authorization->applyScope($this->Trips->Services->find('list'), 'index');
        $routes = $this->Authorization->applyScope($this->Trips->Routes->find('list'), 'index');

        $this->setRedirect();
        $this->set('trips', $trips);
        $this->set('services', $services);
        $this->set('routes', $routes);
    }

    /**
     * View method
     *
     * @param string|null $id Trip id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $trip = $this->Trips->get($id, [
            'contain' => [
                'Clients',
                'Routes',
                'Services',
                'Shapes',
                'StopTimes' => [
                    'Stops'
                ],
                'Frequencies'
            ]
        ]);

        $this->setRedirect();
        $this->set('trip', $trip);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add($route_id = null)
    {
        $trip = $this->Trips->newEntity();

        $this->Authorization->authorize($trip);

        if ($this->request->is('post')) {
            $trip = $this->Trips->patchEntity($trip, $this->request->getData());
            $trip->client_id = $this->Authentication->getIdentity()->client_id;

            if ($this->Trips->save($trip)) {
                $this->Flash->success(__('The trip has been saved.'));
                $this->queueMonitoringTask();

                return $this->redirect($this->isRedirect() ? $this->getRedirect() : ['action' => 'view', $trip->trip_id]);
            }

            $this->Flash->error(__('The trip could not be saved. Please, try again.'));
        }

        $shapes = $this->Trips->Shapes->find('list', ['limit' => 200])->all();
        $routes = $this->Authorization->applyScope($this->Trips->Routes->find('list', ['limit' => 200]), 'index');
        $services = $this->Authorization->applyScope($this->Trips->Services->find('list', ['limit' => 200]), 'index');

        $this->queueMonitoringTask();

        $this->set('shapes', $shapes);
        $this->set('routes', $routes);
        $this->set('services', $services);
        $this->set('route_id', $route_id);
        $this->set('trip', $trip);
    }

    /**
     * Edit method
     *
     * @param string|null $id Trip id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $trip = $this->Trips->get($id, [
            'contain' => [
                'StopTimes' => ['Stops'],
                'Shapes',
                'Frequencies'
            ]
        ]);

        $this->Authorization->authorize($trip);

        if ($this->request->is(['patch', 'post', 'put'])) {

            // delete old stop times object to avoid overriding the stop times without re-ordering
            // TODO: is this really the only one?!
            unset($trip->stop_times);

            // normal patch of data, as in add method
            $trip = $this->Trips->patchEntity($trip, $this->request->getData());
            if ($this->Trips->save($trip)) {
                $this->Flash->success(__('The trip has been saved.'));
                $this->queueMonitoringTask();

                return $this->redirect($this->isRedirect() ? $this->getRedirect() : ['action' => 'view', $trip->trip_id]);
            }

            $this->Flash->error(__('The trip could not be saved. Please, try again.'));
        }

        $shapes = $this->Trips->Shapes->find('list', ['limit' => 200])->all();
        $routes = $this->Authorization->applyScope($this->Trips->Routes->find('list', ['limit' => 200]), 'index');
        $services = $this->Authorization->applyScope($this->Trips->Services->find('list', ['limit' => 200]), 'index');

        $this->queueMonitoringTask();

        $this->set('shapes', $shapes);
        $this->set('routes', $routes);
        $this->set('services', $services);
        $this->set('trip', $trip);
    }

    /**
     * Copy method
     *
     * @param string|null $id Trip id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function copy($id = null)
    {
        $this->request->allowMethod(['post']);

        $trip = $this->Trips->get($id);
        $this->Authorization->authorize($trip, 'edit');

        // load input parameters
        $service_id = h($this->request->getData('service_id'));
        $trip_short_name = h($this->request->getData('trip_short_name'));
        $trip_headsign = h($this->request->getData('trip_headsign'));

        $stop_times_reference = h($this->request->getData('stop_times_reference'));
        $stop_times_reverse = h($this->request->getData('stop_times_reverse')) == '1' ? true : false;

        $trip = $this->Trips->duplicateEntity($id);
        if ($trip != null) {
            // set trip service, short name and headsign
            if (!empty($service_id)) {
                $trip->service_id = $service_id;
            }

            if (!empty($trip_short_name)) {
                $trip->trip_short_name = $trip_short_name;
            }

            if (!empty($trip_headsign)) {
                $trip->trip_headsign = $trip_headsign;
            }

            // reverse stop times if needed
            if ($stop_times_reverse) {
                $trip->stop_times = array_reverse($trip->stop_times);

                $trip->stop_times[0]->pickup_type = 0;
                $trip->stop_times[0]->drop_off_type = 1;

                $trip->stop_times[count($trip->stop_times) - 1]->pickup_type = 1;
                $trip->stop_times[count($trip->stop_times) - 1]->drop_off_type = 0;

                if ($trip->direction_id == '0') {
                    $trip->direction_id = '1';
                } else {
                    $trip->direction_id = '0';
                }
            }

            if (count($trip->stop_times) > 0) {
                // adapt stop times of each stop
                $reftime = $trip->stop_times[0]->arrival_time;
                if (!empty($stop_times_reference)) {
                    $stop_times_reference = Time::createFromFormat('Y-m-d H:i:s', $reftime->format('Y-m-d ') . $stop_times_reference);
                    if ($stop_times_reference != null) {
                        $reftime = $stop_times_reference;
                    }
                }

                $st = $trip->stop_times;    // shortcut
                $ad_difference = null;      // difference arrival and departure
                $st_difference = null;      // difference between two stop times

                for ($s = 0; $s < count($st); $s++) {
                    $ad_difference = $st[$s]->arrival_time->diff($st[$s]->departure_time, true);
                    $st_difference = ($s < count($st) - 1) ? $st[$s]->departure_time->diff($st[$s + 1]->arrival_time, true) : $st[$s]->departure_time->diff($st[$s]->departure_time);

                    $st[$s]->arrival_time = clone $reftime;
                    $reftime = $reftime->add($ad_difference);

                    $st[$s]->departure_time = clone $reftime;

                    $reftime = $reftime->add($st_difference);
                }
            }

            if ($this->Trips->save($trip)) {
                $this->Flash->success(__('The trip has been copied.'));
                $this->queueMonitoringTask();
            } else {
                $this->Flash->error(__('The trip could not be copied. Please, try again.'));
            }
        } else {
            $this->Flash->error(__('The trip could not be copied. Please, try again.'));
        }

        $this->queueMonitoringTask();

        return $this->redirect($this->isRedirect() ? $this->getRedirect() : ['action' => 'index']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Trip id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $trip = $this->Trips->get($id);

        $this->Authorization->authorize($trip);

        if ($this->Trips->delete($trip)) {
            $this->Flash->success(__('The trip has been deleted.'));
            $this->queueMonitoringTask();
        } else {
            $this->Flash->error(__('The trip could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * Queues the data monitor task.
     */
    private function queueMonitoringTask()
    {
        $this->loadModel('Queue.QueuedJobs');

        if (!$this->QueuedJobs->isQueued('TripsMonitor', 'DataMonitor')) {
            $this->QueuedJobs->createJob('DataMonitor', ['Trips', 'Routes', 'Agencies', 'Services', 'Shapes', 'Stops'], ['reference' => 'TripsMonitor']);
        }
    }
}
