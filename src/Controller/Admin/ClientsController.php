<?php
namespace App\Controller\Admin;

use App\Controller\AdminController;
use App\Model\Entity\Agency;
use App\Model\Entity\Client;
use App\Model\Entity\Route;
use Cake\Event\Event;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;

/**
 * Clients Controller
 *
 * @property \App\Model\Table\ClientsTable $Clients
 *
 * @method \App\Model\Entity\Client[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ClientsController extends AdminController
{
    /**
     * Before filter method.
     *
     * @param Event $event
     * @return \Cake\Http\Response|null
     */
    public function beforeFilter(Event $event)
    {
        $this->set('title', __('Clients'));

        return parent::beforeFilter($event);
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $clients = $this->paginate($this->Clients);

        $this->setRedirect();
        $this->set(compact('clients'));
    }

    /**
     * View method
     *
     * @param string|null $id Client id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $client = $this->Clients->get($id, [
            'contain' => ['Users']
        ]);

        $this->Authorization->authorize($client);

        $this->setRedirect();
        $this->set('client', $client);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $client = $this->Clients->newEntity();
        
        $this->Authorization->authorize($client);
        
        if ($this->request->is('post')) {
            $client = $this->Clients->patchEntity($client, $this->request->getData());
            if ($this->Clients->save($client)) {
                $this->Flash->success(__('The client has been saved.'));

                return $this->redirect($this->isRedirect() ? $this->getRedirect() : ['action' => 'index']);
            }
            $this->Flash->error(__('The client could not be saved. Please, try again.'));
        }
        $this->set(compact('client'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Client id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $client = $this->Clients->get($id, [
            'contain' => []
        ]);
        
        $this->Authorization->authorize($client);
        
        if ($this->request->is(['patch', 'post', 'put'])) {
            $client = $this->Clients->patchEntity($client, $this->request->getData());
            if ($this->Clients->save($client)) {
                $this->Flash->success(__('The client has been saved.'));

                return $this->redirect($this->isRedirect() ? $this->getRedirect() : ['action' => 'index']);
            }
            $this->Flash->error(__('The client could not be saved. Please, try again.'));
        }

        $this->set(compact('client'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Client id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $client = $this->Clients->get($id);
        
        $this->Authorization->authorize($client);
        
        if ($this->Clients->delete($client)) {
            $this->Flash->success(__('The client has been deleted.'));
        } else {
            $this->Flash->error(__('The client could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function transfer($entityClass = null, $entityId = null, $redirectEntity = null) {
        $this->Authorization->authorize(Client::getInstance(), 'transfer');

        if ($entityClass == null || $entityId == null && $this->request->is(['patch', 'post', 'put'])) {
            $entityClass = $this->request->getData('entity_class');
            $entityId = $this->request->getData('entity_id');
        }

        if ($this->request->is(['patch', 'post', 'put']) && $entityClass != null && $entityId != null) {
            $client_id = h($this->request->getData('client_id'));
            $saveFlag = true;

            $table = TableRegistry::getTableLocator()->get($entityClass);
            $entity = $table->get($entityId, [
                'contain' => $this->getContainsArray($entityClass)
            ]);

            $entity->client_id = $client_id;
            if (!$table->save($entity)) {
                $saveFlag = false;
            }

            // transfer also all client-related associations of this object
            if ($this->request->getData('override_associations') == '1') {
                $this->loadModel('Routes');
                $this->loadModel('Trips');

                if ($entity instanceof Agency) { // if Agency => transfer also routes and trips
                    foreach ($entity->routes as $route) {
                        foreach ($route->trips as $trip) {
                            $trip->client_id = $this->request->getData('client_id');
                            if (!$this->Trips->save($trip)) {
                                $saveFlag = false;
                            }
                        }

                        $route->client_id = $this->request->getData('client_id');
                        if (!$this->Routes->save($route)) {
                            $saveFlag = false;
                        }
                    }
                } else if ($entity instanceof Route) { // if Route => transfer also trips
                    foreach ($entity->trips as $trip) {
                        $trip->client_id = $this->request->getData('client_id');
                        if (!$this->Trips->save($trip)) {
                            $saveFlag = false;
                        }
                    }
                }
            }

            // could all associations be saved correctly???
            if ($saveFlag) {
                $this->Flash->success(__('The clientship has been transferred.'));

                if ($redirectEntity != null) {
                    return $this->redirect(['controller' => $entityClass, 'action' => 'view', $entityId]);
                }
            } else {
                $this->Flash->error(__('The clientship could not be transferred. Please, try again.'));
            }
        }

        $clients = $this->Clients->find('list');

        $this->set('entityClass', $entityClass);
        $this->set('entityId', $entityId);
        $this->set('clients', $clients);
    }

    /**
     * Returns an array with associations for an entity class.
     *
     * @param string $entityClass The entity class
     * @return array The array with associations
     */
    private function getContainsArray($entityClass) {
        if ($entityClass == 'Agencies') {
            return [
                'Routes' => [
                    'Trips'
                ]
            ];
        } else if($entityClass == 'Routes') {
            return [
                'Trips'
            ];
        }

        return [];
    }
}
