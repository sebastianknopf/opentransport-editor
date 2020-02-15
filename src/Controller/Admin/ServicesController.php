<?php


namespace App\Controller\Admin;


use App\Controller\AdminController;
use App\Model\Entity\Service;
use Cake\Console\ShellDispatcher;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;

class ServicesController extends AdminController
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

        $this->loadSessionFilter('Services');
    }

    /**
     * Before filter method.
     *
     * @param Event $event
     * @return \Cake\Http\Response|null
     */
    public function beforeFilter(Event $event)
    {
        $this->set('title', __('Services'));

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
        $services = $this->Services->find('search', [
            'search' => $this->request->getQueryParams()
        ])->contain(['ServiceExceptions']);

        $services = $this->Authorization->applyScope($services);
        $services = $this->paginate($services);

        $this->setRedirect();
        $this->set('services', $services);
    }

    /**
     * View method
     *
     * @param string|null $id Service id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $service = $this->Services->get($id, [
            'contain' => ['ServiceExceptions', 'Clients']
        ]);

        $this->Authorization->authorize($service);

        $this->getRedirect();
        $this->set('service', $service);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $service = $this->Services->newEntity();

        $this->Authorization->authorize($service);

        if ($this->request->is('post')) {
            $service = $this->Services->patchEntity($service, $this->request->getData());
            $service->client_id = $this->Authentication->getIdentity()->client_id;

            if ($this->Services->save($service, ['associated' => ['ServiceExceptions']])) {
                $this->Flash->success(__('The service has been saved.'));
                $this->queueMonitoringTask();

                return $this->redirect($this->isRedirect() ? $this->getRedirect() : ['action' => 'index']);
            }

            $this->Flash->error(__('The service could not be saved. Please, try again.'));
        }

        $this->set('service', $service);
    }

    /**
     * Edit method
     *
     * @param string|null $id Service id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $service = $this->Services->get($id, [
            'contain' => ['ServiceExceptions']
        ]);

        $this->Authorization->authorize($service);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $service = $this->Services->patchEntity($service, $this->request->getData());
            if ($this->Services->save($service)) {
                $this->Flash->success(__('The service has been saved.'));
                $this->queueMonitoringTask();

                return $this->redirect($this->isRedirect() ? $this->getRedirect() : ['action' => 'index']);
            }

            $this->Flash->error(__('The service could not be saved. Please, try again.'));
        }

        $this->set('service', $service);
    }

    /**
     * Delete method
     *
     * @param string|null $id Service id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);

        $service = $this->Services->get($id);

        $this->Authorization->authorize($service);

        if ($this->Services->delete($service)) {
            $this->Flash->success(__('The service has been deleted.'));
            $this->queueMonitoringTask();
        } else {
            $this->Flash->error(__('The service could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * Queues the data monitor task.
     */
    private function queueMonitoringTask()
    {
        $this->loadModel('Queue.QueuedJobs');

        if (!$this->QueuedJobs->isQueued('ServicesMonitor', 'DataMonitor')) {
            $this->QueuedJobs->createJob('DataMonitor', ['Services'], ['reference' => 'ServicesMonitor']);
        }
    }

}