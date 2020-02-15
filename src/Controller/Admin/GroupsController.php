<?php

namespace App\Controller\Admin;

use App\Controller\AdminController;
use Cake\Event\Event;

/**
 * Groups Controller
 *
 * @property \App\Model\Table\GroupsTable $Groups
 *
 * @method \App\Model\Entity\Group[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class GroupsController extends AdminController
{
    /**
     * Initialize method to load needed components.
     *
     * @throws \Exception
     */
    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('Permissions');
    }

    /**
     * Before filter method.
     *
     * @param Event $event
     * @return \Cake\Http\Response|null
     */
    public function beforeFilter(Event $event)
    {
        $this->set('title', __('Groups'));

        return parent::beforeFilter($event);
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $groups = $this->paginate($this->Groups);

        $this->setRedirect();
        $this->set(compact('groups'));
    }

    /**
     * View method
     *
     * @param string|null $id Group id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $group = $this->Groups->get($id, [
            'contain' => ['Users']
        ]);
        
        $this->Authorization->authorize($group);

        $this->setRedirect();
        $this->set('group', $group);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $group = $this->Groups->newEntity();
        
        $this->Authorization->authorize($group);
        
        if ($this->request->is('post')) {
            $group = $this->Groups->patchEntity($group, $this->request->getData());
            if ($this->Groups->save($group)) {
                // if saving was successful, process the acl table
                $this->Permissions->processPermissions(['model' => 'Groups', 'foreign_key' => $group->id], $this->request->getData('Aco'));
                $this->Flash->success(__('The group has been saved.'));

                return $this->redirect($this->isRedirect() ? $this->getRedirect() : ['action' => 'index']);
            }
            $this->Flash->error(__('The group could not be saved. Please, try again.'));
        }

        $this->set(compact('group'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Group id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $group = $this->Groups->get($id, [
            'contain' => []
        ]);
        
        $this->Authorization->authorize($group);
        
        if ($this->request->is(['patch', 'post', 'put'])) {
            $group = $this->Groups->patchEntity($group, $this->request->getData());
            if ($this->Groups->save($group)) {
                // if saving was successful, process the acl table
                $this->Permissions->processPermissions(['model' => 'Groups', 'foreign_key' => $group->id], $this->request->getData('Aco'));
                $this->Flash->success(__('The group has been saved.'));

                return $this->redirect($this->isRedirect() ? $this->getRedirect() : ['action' => 'index']);
            }
            $this->Flash->error(__('The group could not be saved. Please, try again.'));
        }

        $this->set('group', $group);
    }

    /**
     * Delete method
     *
     * @param string|null $id Group id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $group = $this->Groups->get($id);
        
        $this->Authorization->authorize($group);
        
        if ($this->Groups->delete($group)) {
            $this->Flash->success(__('The group has been deleted.'));
        } else {
            $this->Flash->error(__('The group could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
