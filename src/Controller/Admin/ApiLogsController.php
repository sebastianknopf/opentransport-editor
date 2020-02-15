<?php

namespace App\Controller\Admin;

use App\Controller\AdminController;
use Cake\I18n\Date;
use Cake\Utility\Hash;

/**
 * ApiLogs Controller
 *
 * @property \App\Model\Table\ApiLogsTable $Log
 *
 * @method \App\Model\Entity\ApiLog[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ApiLogsController extends AdminController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $currentDate = new Date();
        $this->ApiLogs->deleteAll([
            'ApiLogs.created <' => $currentDate->subDays(8)->format('Y-m-d H:i:s')
        ]);

        $restLogs = $this->paginate($this->ApiLogs->find('all')->where(['ApiLogs.response_code <>' => 200])->order(['ApiLogs.created DESC']));

        $successCountData = $this->ApiLogs->find('countSuccessRequests', ['groupByDate' => true])->enableHydration(false);
        $errorCountData = $this->ApiLogs->find('countErrorRequests', ['groupByDate' => true])->enableHydration(false);

        $successCountData = Hash::combine($successCountData->toArray(), '{n}.date', '{n}.count');
        $errorCountData = Hash::combine($errorCountData->toArray(), '{n}.date', '{n}.count');

        $this->setRedirect();
        $this->set(compact('restLogs', 'successCountData', 'errorCountData'));
    }

    /**
     * View method
     *
     * @param string|null $id Rest Log id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $log = $this->ApiLogs->get($id);

        $this->Authorization->authorize($log);

        $this->set('log', $log);
    }

    /**
     * Delete method
     *
     * @param string|null $id Rest Log id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);

        $restLog = $this->ApiLogs->get($id);

        $this->Authorization->authorize($restLog);

        if ($this->ApiLogs->delete($restLog)) {
            $this->Flash->success(__('The log entry has been deleted.'));
        } else {
            $this->Flash->error(__('The log entry could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
