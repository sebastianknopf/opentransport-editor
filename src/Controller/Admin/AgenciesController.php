<?php

namespace App\Controller\Admin;

use App\Controller\AdminController;
use App\Controller\AppController;
use App\Utility\LocaleList;
use Cake\Console\ShellDispatcher;
use Cake\Event\Event;

/**
 * Agencies Controller
 *
 * @property \App\Model\Table\AgenciesTable $Agencies
 *
 * @method \App\Model\Entity\Agency[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class AgenciesController extends AdminController
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

        $this->loadSessionFilter('Agencies');
    }

    /**
     * Before filter method.
     *
     * @param Event $event
     * @return \Cake\Http\Response|null
     */
    public function beforeFilter(Event $event)
    {
        $this->set('title', __('Agencies'));

        return parent::beforeFilter($event);
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $agencies = $this->Agencies->find('search', ['search' => $this->request->getQueryParams()]);

        $agencies = $this->Authorization->applyScope($agencies);
        $agencies = $this->paginate($agencies);

        $this->setRedirect();
        $this->set('agencies', $agencies);
    }

    /**
     * View method
     *
     * @param string|null $id Agency id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $agency = $this->Agencies->get($id, [
            'contain' => ['Clients', 'Routes']
        ]);

        $this->Authorization->authorize($agency);

        $this->setRedirect();
        $this->set('agency', $agency);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $agency = $this->Agencies->newEntity();

        $this->Authorization->authorize($agency);

        if ($this->request->is('post')) {
            $agency = $this->Agencies->patchEntity($agency, $this->request->getData());
            $agency->client_id = $this->Authentication->getIdentity()->client_id;

            if ($this->Agencies->save($agency)) {
                $this->Flash->success(__('The agency has been saved.'));
                $this->queueMonitoringTask();

                return $this->redirect($this->isRedirect() ? $this->getRedirect() : ['action' => 'view', $agency->agency_id]);
            }
            $this->Flash->error(__('The agency could not be saved. Please, try again.'));
        }

        $this->queueMonitoringTask();

        $this->set('agency', $agency);
    }

    /**
     * Edit method
     *
     * @param string|null $id Agency id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $agency = $this->Agencies->get($id);

        $this->Authorization->authorize($agency);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $agency = $this->Agencies->patchEntity($agency, $this->request->getData());
            if ($this->Agencies->save($agency)) {
                $this->Flash->success(__('The agency has been saved.'));
                $this->queueMonitoringTask();

                return $this->redirect($this->isRedirect() ? $this->getRedirect() : ['action' => 'view', $agency->agency_id]);
            }
            $this->Flash->error(__('The agency could not be saved. Please, try again.'));
        }

        $this->queueMonitoringTask();

        $this->set('agency', $agency);
    }

    /**
     * Delete method
     *
     * @param string|null $id Agency id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $agency = $this->Agencies->get($id);

        $this->Authorization->authorize($agency);

        if ($this->Agencies->delete($agency)) {
            $this->Flash->success(__('The agency has been deleted.'));
            $this->queueMonitoringTask();
        } else {
            $this->Flash->error(__('The agency could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * Queues the data monitor task.
     */
    private function queueMonitoringTask()
    {
        $this->loadModel('Queue.QueuedJobs');

        if (!$this->QueuedJobs->isQueued('AgenciesMonitor', 'DataMonitor')) {
            $this->QueuedJobs->createJob('DataMonitor', ['Stops'], ['reference' => 'AgenciesMonitor']);
        }
    }
}
