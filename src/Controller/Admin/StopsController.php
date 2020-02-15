<?php
namespace App\Controller\Admin;

use App\Controller\AdminController;
use Cake\Console\ShellDispatcher;
use Cake\Event\Event;

/**
 * Stops Controller
 *
 * @property \App\Model\Table\StopsTable $Stops
 *
 * @method \App\Model\Entity\Stop[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class StopsController extends AdminController
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

        $this->loadSessionFilter('Stops');
    }

    /**
     * Before filter method.
     *
     * @param Event $event
     * @return \Cake\Http\Response|null
     */
    public function beforeFilter(Event $event)
    {
        $this->set('title', __('Stations'));

        $this->Security->setConfig([
            'unlockedActions' => ['add', 'edit']
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
        $stations = $this->paginate($this->Stops->find('search', ['search' => $this->request->getQueryParams()])->where(['Stops.parent_station' => '']));

        $this->setRedirect();

        $this->set(compact('stations'));
    }

    /**
     * View method
     *
     * @param string|null $id Stop id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $station = $this->Stops->get($id);
        $stops = $this->Stops->find()->where(['Stops.parent_station' => $station->stop_id])->toArray();

        if ($station->parent_station == '') {
            $this->setRedirect();
        }

        $this->Authorization->authorize($station);

        $this->set(compact('station'));
        $this->set(compact('stops'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add($parent_station = null)
    {
        $station = $this->Stops->newEntity();

        $this->Authorization->authorize($station);

        if ($this->request->is('post')) {
            $station = $this->Stops->patchEntity($station, $this->request->getData());
            if ($this->Stops->save($station)) {
                $this->Flash->success(__('The stop has been saved.'));

                if ($this->request->getData('create_stops') == '1') {
                    for ($i = 1; $i <= 2; $i++) {
                        $stopDirection = $this->Stops->newEntity($this->request->getData());
                        $stopDirection->stop_code .= $i;
                        $stopDirection->parent_station = $station->stop_id;
                        $stopDirection->location_type = 0;

                        $this->Stops->save($stopDirection);
                    }
                }

                $this->queueMonitoringTask();

                return $this->redirect($this->isRedirect() ? $this->getRedirect() : ['action' => 'index']);
            }
            $this->Flash->error(__('The stop could not be saved. Please, try again.'));
        }

        $this->queueMonitoringTask();

        $this->set(compact('parent_station'));
        $this->set(compact('station'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Stop id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $station = $this->Stops->get($id);
        $stops = $this->Stops->find()->where(['Stops.parent_station' => $station->stop_id]);

        if ($station->parent_station == '') {
            $this->setRedirect();
        }

        $this->Authorization->authorize($station);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $station = $this->Stops->patchEntity($station, $this->request->getData());
            if ($this->Stops->save($station)) {
                $this->Flash->success(__('The stop has been saved.'));
                $this->queueMonitoringTask();

                return $this->redirect($this->isRedirect() ? $this->getRedirect() : ['action' => 'index']);
            }
            $this->Flash->error(__('The stop could not be saved. Please, try again.'));
        }

        $this->set(compact('station'));
        $this->set(compact('stops'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Stop id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $station = $this->Stops->get($id);

        $this->Authorization->authorize($station);

        try {
            if ($this->Stops->delete($station)) {
                $this->Flash->success(__('The stop has been deleted.'));
                $this->queueMonitoringTask();
            } else {
                $this->Flash->error(__('The stop could not be deleted. Please, try again.'));
            }
        } catch (\PDOException $e) {
            $this->Flash->error(__('Unable to delete the stop! The stop is still referenced by some other objects.'));
        }

        return $this->redirect($this->isRedirect() ? $this->getRedirect() : ['action' => 'index']);
    }

    /**
     * Queues the data monitor task.
     */
    private function queueMonitoringTask()
    {
        $this->loadModel('Queue.QueuedJobs');

        if (!$this->QueuedJobs->isQueued('StopsMonitor', 'DataMonitor')) {
            $this->QueuedJobs->createJob('DataMonitor', ['Stops'], ['reference' => 'StopsMonitor']);
        }
    }
}
