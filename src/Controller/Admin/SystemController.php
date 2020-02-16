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