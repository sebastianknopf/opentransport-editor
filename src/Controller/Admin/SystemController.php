<?php
namespace App\Controller\Admin;

use App\Controller\AdminController;
use App\Model\Entity\Message;
use Cake\Event\Event;
use Cake\Utility\Hash;

/**
 * System Controller
 *
 *
 * @method \App\Model\Entity\System[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class SystemController extends AdminController
{
    public function beforeFilter(Event $event)
    {
        $this->set('title', __('System'));

        if ($this->request->getParam('action') == 'startQueueWorker') {
            $this->getEventManager()->off($this->Csrf);
        }

        return parent::beforeFilter($event);
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        // api log count
        $this->loadModel('ApiLogs');
        $apiLogsCount = $this->ApiLogs->find()->select([
            'date' => 'CAST(ApiLogs.created AS DATE)',
            'count' => 'COUNT(*)'
        ])->group([
            'CAST(date AS DATE)'
        ])->order([
            'date'
        ])->enableHydration(false);

        $apiLogsCount = Hash::combine($apiLogsCount->toArray(), '{n}.date', '{n}.count');

        // messages
        $this->loadModel('Messages');
        $messages = $this->Messages->find()->where([
            'Messages.flags <>' => Message\Status::IGNORED,
            'Messages.level' => Message\Level::ERROR
        ])->order([
            'Messages.modified DESC'
        ])->toArray();

        $this->set('apiLogsCount', $apiLogsCount);
        $this->set('messages', $messages);
    }

    /**
     * Sets status flag of a message to be ignored.
     *
     * @param $id The message id to be ignored
     * @return mixed The redirect url
     */
    public function msgignore($id)
    {
        $this->request->allowMethod(['post', 'delete']);

        $this->loadModel('Messages');
        $message = $this->Messages->get($id);

        if ($message) {
            $message->flags = Message::STATUS_IGNORE;

            if (!$this->Messages->save($message)) {
                $this->Flash->error(__('The message could not be ignored!'));
            }
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * Update method.
     */
    public function update()
    {
        if ($this->request->is(['post', 'put', 'patch'])) {
            $this->loadModel('Queue.QueuedJobs');

            if (!$this->QueuedJobs->isQueued('SystemUpdate', 'SystemUpdate')) {
                $this->QueuedJobs->createJob('SystemUpdate', [], ['reference' => 'SystemUpdate']);
                $this->Flash->success(__('The system update has been started!'));
            } else {
                $this->Flash->error(__('The system update is running yet!'));
            }

            return $this->redirect(['controller' => 'System', 'action' => 'update']);
        }
    }

    /**
     * Manual method.
     */
    public function manual()
    {
        // do simply nothing and load template here ... it's a static one!
    }

    /**
     * Privacy method.
     */
    public function privacy()
    {
        // do simply nothing as well as the last two methods ...
    }
}
