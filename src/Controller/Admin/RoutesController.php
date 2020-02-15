<?php

namespace App\Controller\Admin;

use App\Controller\AdminController;
use Cake\Console\ShellDispatcher;
use Cake\Event\Event;

/**
 * Routes Controller
 *
 * @property \App\Model\Table\RoutesTable $Routes
 *
 * @method \App\Model\Entity\Route[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class RoutesController extends AdminController
{
    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('Search.Prg', [
            'actions' => ['view']
        ]);

        $this->loadSessionFilter('Routes');
    }

    /**
     * Before filter method.
     *
     * @param Event $event
     * @return \Cake\Http\Response|null
     */
    public function beforeFilter(Event $event)
    {
        $this->set('title', __('Routes'));

        return parent::beforeFilter($event);
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $routes = $this->Routes->find('search', [
            'search' => $this->request->getQueryParams()
        ])->contain(['Agencies']);

        $routes = $this->Authorization->applyScope($routes);
        $routes = $this->paginate($routes);

        $this->setRedirect();
        $this->set('routes', $routes);
    }

    /**
     * View method
     *
     * @param string|null $id Route id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $route = $this->Routes->get($id, [
            'contain' => [
                'Clients',
                'Agencies'
            ]
        ]);

        // load trips filtered
        $this->loadModel('Trips');
        $route->trips = $this->Trips->find('search', [
            'search' => $this->request->getQueryParams()
        ])->contain([
            'Services'
        ])->where(
            ['Trips.route_id' => $id]
        )->order([
            'Trips.start_time'
        ]);

        $this->Authorization->authorize($route);

        $this->setRedirect();
        $this->set('route', $route);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add($agency_id = null)
    {
        $route = $this->Routes->newEntity();

        $this->Authorization->authorize($route);

        if ($this->request->is('post')) {
            $route = $this->Routes->patchEntity($route, $this->request->getData());
            $route->client_id = $this->Authentication->getIdentity()->client_id;

            if ($this->Routes->save($route)) {
                $this->Flash->success(__('The route has been saved.'));
                $this->queueMonitoringTask();

                return $this->redirect($this->isRedirect() ? $this->getRedirect() : ['action' => 'view', $route->route_id]);
            }

            $this->Flash->error(__('The route could not be saved. Please, try again.'));
        }

        $agencies = $this->Authorization->applyScope($this->Routes->Agencies->find('list', ['limit' => 200]), 'index');

        $this->set('agencies', $agencies);
        $this->set('agency_id', $agency_id);
        $this->set('route', $route);
    }

    /**
     * Edit method
     *
     * @param string|null $id Route id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $route = $this->Routes->get($id);

        $this->Authorization->authorize($route);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $route = $this->Routes->patchEntity($route, $this->request->getData());
            if ($this->Routes->save($route)) {
                $this->Flash->success(__('The route has been saved.'));
                $this->queueMonitoringTask();

                return $this->redirect($this->isRedirect() ? $this->getRedirect() : ['action' => 'view', $route->route_id]);
            }

            $this->Flash->error(__('The route could not be saved. Please, try again.'));
        }

        $agencies = $this->Authorization->applyScope($this->Routes->Agencies->find('list', ['limit' => 200]), 'index');

        $this->set('agencies', $agencies);
        $this->set('route', $route);
    }

    /**
     * Delete method
     *
     * @param string|null $id Route id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $route = $this->Routes->get($id);

        $this->Authorization->authorize($route);

        if ($this->Routes->delete($route)) {
            $this->Flash->success(__('The route has been deleted.'));
            $this->queueMonitoringTask();
        } else {
            $this->Flash->error(__('The route could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * Queues the data monitor task.
     */
    private function queueMonitoringTask()
    {
        $this->loadModel('Queue.QueuedJobs');

        if (!$this->QueuedJobs->isQueued('RoutesMonitor', 'DataMonitor')) {
            $this->QueuedJobs->createJob('DataMonitor', ['Agencies', 'Routes'], ['reference' => 'RoutesMonitor']);
        }
    }
}
