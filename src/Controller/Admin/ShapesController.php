<?php
namespace App\Controller\Admin;

use App\Controller\AdminController;
use App\Model\Entity\Shape;
use Cake\Console\ShellDispatcher;
use Cake\Event\Event;

/**
 * Shapes Controller
 *
 * @property \App\Model\Table\ShapesTable $Shapes
 *
 * @method \App\Model\Entity\Shape[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ShapesController extends AdminController
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

        $this->loadSessionFilter('Shapes');

        // disable security component for add and edit actions
        $this->Security->setConfig('unlockedActions', [
            'add',
            'edit'
        ]);
    }

    /**
     * Before filter method.
     *
     * @param Event $event
     * @return \Cake\Http\Response|null
     */
    public function beforeFilter(Event $event)
    {
        $this->set('title', __('Shapes'));

        return parent::beforeFilter($event);
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $shapes = $this->paginate($this->Shapes->find('search', ['search' => $this->request->getQueryParams()]));

        $this->setRedirect();
        $this->set(compact('shapes'));
    }

    /**
     * View method
     *
     * @param string|null $id Shape id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $shape = $this->Shapes->get($id);

        $this->Authorization->authorize($shape);

        $this->setRedirect();
        $this->set('shape', $shape);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $shape = $this->Shapes->newEntity();

        $this->Authorization->authorize($shape);

        if ($this->request->is('post')) {
            $shape_name = $this->request->getData('shape_name');

            $shape_polyline = $this->request->getData('shape_polyline');
            if (!is_null($shape_polyline)) {
                $shape_polyline = $shape->encode($this->request->getData('shape_polyline'));
            }

            $shape = $this->Shapes->patchEntity($shape, compact('shape_name', 'shape_polyline'));
            if ($this->Shapes->save($shape)) {
                $this->Flash->success(__('The shape has been saved.'));
                $this->queueMonitoringTask();

                return $this->redirect($this->isRedirect() ? $this->getRedirect() : ['action' => 'index']);
            }
            $this->Flash->error(__('The shape could not be saved. Please, try again.'));
        }

        $this->set('shape', $shape);
    }

    /**
     * Edit method
     *
     * @param string|null $id Shape id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $shape = $this->Shapes->get($id);

        $this->Authorization->authorize($shape);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $shape_name = $this->request->getData('shape_name');

            $shape_polyline = $this->request->getData('shape_polyline');
            if (!is_null($shape_polyline)) {
                $shape_polyline = $shape->encode($this->request->getData('shape_polyline'));
            }

            $shape = $this->Shapes->patchEntity($shape, compact('shape_name', 'shape_polyline'));
            if ($this->Shapes->save($shape)) {
                $this->Flash->success(__('The shape has been saved.'));
                $this->queueMonitoringTask();

                return $this->redirect($this->isRedirect() ? $this->getRedirect() : ['action' => 'index']);
            }
            $this->Flash->error(__('The shape could not be saved. Please, try again.'));
        }

        $this->set('shape', $shape);
    }

    /**
     * Copy method
     *
     * @param string|null $id Shape id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function copy($id = null)
    {
        $this->request->allowMethod(['post']);
        $shape = $this->Shapes->get($id);

        $this->Authorization->authorize($shape, 'edit');

        if ($this->Shapes->duplicate($shape->shape_id)) {
            $this->Flash->success(__('The shape has been copied.'));
            $this->queueMonitoringTask();
        } else {
            $this->Flash->error(__('The shape could not be copied. Please, try again.'));
        }

        return $this->redirect($this->isRedirect() ? $this->getRedirect() : ['action' => 'index']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Shape id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $shape = $this->Shapes->get($id);

        $this->Authorization->authorize($shape);

        if ($this->Shapes->delete($shape)) {
            $this->Flash->success(__('The shape has been deleted.'));
            $this->queueMonitoringTask();
        } else {
            $this->Flash->error(__('The shape could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * Queues the data monitor task.
     */
    private function queueMonitoringTask()
    {
        $this->loadModel('Queue.QueuedJobs');

        if (!$this->QueuedJobs->isQueued('ShapesMonitor', 'DataMonitor')) {
            $this->QueuedJobs->createJob('DataMonitor', ['Shapes'], ['reference' => 'ShapesMonitor']);
        }
    }
}
