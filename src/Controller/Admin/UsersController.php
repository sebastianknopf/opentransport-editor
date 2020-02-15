<?php
namespace App\Controller\Admin;

use App\Controller\AdminController;
use Authentication\Identifier\IdentifierInterface;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Mailer\Email;
use Cake\Utility\Security;
use Firebase\JWT\JWT;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 *
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersController extends AdminController
{
    public function beforeFilter(Event $event)
    {
        $this->Authentication->allowUnauthenticated(['login', 'pwforgot']);

        $this->set('title', __('Users'));

        parent::beforeFilter($event);
    }
    
    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Groups']
        ];

        $users = $this->paginate($this->Users);

        $this->setRedirect();
        $this->set('users', $users);
    }

    /**
     * View method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => ['Groups', 'Clients']
        ]);
        
        $this->Authorization->authorize($user);

        $this->setRedirect();
        $this->set('user', $user);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $user = $this->Users->newEntity();
        
        $this->Authorization->authorize($user);
        
        if ($this->request->is('post')) {
            $user = $this->Users->patchEntity($user, $this->request->getData());

            // if there's no extra activation required, activate user by default
            if ($this->request->getData('send_registration_mail') != 1) {
                $user->activated = 1;
            }

            if ($this->Users->save($user)) {
                // send registration email if this is enabled
                if ($this->request->getData('send_registration_mail') == 1) {
                    $payload = [IdentifierInterface::CREDENTIAL_JWT_SUBJECT => ['id' => $user->id]];
                    $token = JWT::encode($payload, Security::getSalt());

                    $email = new Email();
                    $email->setTemplate('users_registration');
                    $email->setViewVars([
                        'appName' => Configure::read('App.name'),
                        'token' => $token,
                        'userId' => $user->id,
                        'username' => h($this->request->getData('username')),
                        'password' => h($this->request->getData('password'))
                    ]);

                    $email->setTo($user->email);
                    $email->setSubject(__('{0} | User Registration', Configure::read('App.name')));
                    $email->send();
                }

                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect($this->isRedirect() ? $this->getRedirect() : ['action' => 'index']);
            }

            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        
        $clients = $this->Users->Clients->find('list', ['limit' => 200]);
        $groups = $this->Users->Groups->find('list', ['limit' => 200]);

        $this->set(compact('user', 'groups', 'clients'));
    }

    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $user = $this->Users->get($id);
        
        $this->Authorization->authorize($user);
        
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect($this->isRedirect() ? $this->getRedirect() : ['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        
        $clients = $this->Users->Clients->find('list', ['limit' => 200]);
        $groups = $this->Users->Groups->find('list', ['limit' => 200]);

        $this->set('passwordChangeable', $this->Authentication->getIdentity()->can('changePassword', $user));
        $this->set(compact('user', 'groups', 'clients'));
    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $user = $this->Users->get($id);
        
        $this->Authorization->authorize($user);
        
        if ($this->Users->delete($user)) {
            $this->Flash->success(__('The user has been deleted.'));
        } else {
            $this->Flash->error(__('The user could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * Activate method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects to login.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function activate($id = null)
    {
        // automatically pickup current user id
        if ($id == null) {
            $identity = $this->Authentication->getIdentity();
            $id = $identity->id;
        }

        $user = $this->Users->get($id);
        $user->activated = 1;

        if ($this->Users->save($user)) {
            $this->Flash->success(__('The user account has been activated.'));
        } else {
            $this->Flash->error(__('The user account could not be activated.'));
        }

        return $this->redirect(['_name' => 'login']);
    }

    /**
     * Password change method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function pwchange($id = null)
    {
        // automatically pickup current user id
        if ($id == null) {
            $identity = $this->Authentication->getIdentity();
            $id = $identity->id;
        }

        $user = $this->Users->get($id);

        $this->Authorization->authorize($user, 'changePassword');

        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The password has been changed.'));
            } else {
                $this->Flash->error(__('The password could not be changed. Please, try again.'));
            }
        }

        $this->set('user', $user);
    }

    /**
     * Password forgot action.
     */
    public function pwforgot()
    {
        if ($this->Authentication->getIdentity() != null) {
            $this->redirect(['_name' => 'index']);
        }

        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->findByUsernameOrEmail($this->getRequest()->getData('username'), $this->getRequest()->getData('username'))->first();

            if ($user != null) {
                $payload = [IdentifierInterface::CREDENTIAL_JWT_SUBJECT => ['id' => $user->id]];
                $token = JWT::encode($payload, Security::getSalt());

                $email = new Email();
                $email->setTemplate('users_pwreset');
                $email->setViewVars([
                    'appName' => Configure::read('App.name'),
                    'token' => $token
                ]);

                $email->setTo($user->email);
                $email->setSubject(__('{0} | Password Reset', Configure::read('App.name')));
                $email->send();

                $this->Flash->success(__('A mail with next instructions has been sent! If you didn\'t receive any mail, you can try it again or contact our support.'));
            } else {
                $this->Flash->error(__('Invalid username or E-Mail!'));
            }
        }

        $this->viewBuilder()->setLayout('login');
        $this->set('title', __('Password Forgot'));
    }

    /**
     * Password reset action.
     *
     * @return \Cake\Http\Response|null
     */
    public function pwreset()
    {
        $result = $this->Authentication->getResult();
        if ($result->isValid()) {
            $user = $this->Authentication->getIdentity();
            $this->set('user', $user);

            $this->Authorization->authorize($user, 'changePassword');

            if ($this->request->is(['patch', 'post', 'put'])) {
                $user = $this->Users->patchEntity($user, $this->request->getData());
                if ($this->Users->save($user)) {
                    $this->Flash->success(__('The password has been changed.'));

                    return $this->redirect(['_name' => 'login']);
                } else {
                    $this->Flash->error(__('The password could not be reset!'));
                }
            }
        } else {
            $this->Flash->error(__('Invalid username or password!'));
        }

        $this->viewBuilder()->setLayout('login');
        $this->set('title', __('Password Reset'));
    }

    /**
     * System settings method.
     *
     * @param string|null $id User ID of user to edit the settings.
     */
    public function settings($id = null)
    {
        // automatically pickup current user id
        if ($id == null) {
            $identity = $this->Authentication->getIdentity();
            $id = $identity->id;
        }

        $user = $this->Users->get($id, [
            'contain' => ['UserSettings']
        ]);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The settings have been saved.'));

                return $this->redirect(['controller' => 'Users', 'action' => 'settings']);
            } else {
                $this->Flash->error(__('The settings could not be saved. Please, try again.'));
            }
        }

        $this->set('user', $user);
    }

    /**
     * Login action for backend users.
     */
    public function login()
    {
        $identity = $this->Authentication->getIdentity();
        if ($identity != null && $identity->activated == '1') {
            $this->redirect(['_name' => 'index']);
        }
        
        if ($this->request->is('post')) {
            $result = $this->Authentication->getResult();

            if ($result->isValid() && $identity->activated == '1') {
                $this->redirect(['_name' => 'index']);
            } else {
                // logout in every case! otherwise the identity is kept in session storage
                // and is not loaded again from database! this causes errors according to the
                // user activation process
                $this->Authentication->logout();

                $this->Flash->error(__('Invalid username or password!'));
            }
        }

        $this->viewBuilder()->setLayout('login');
    }
    
    /**
     * Logout action for backend users.
     */
    public function logout()
    {
        $this->Authentication->logout();
        //$this->getRequest()->getSession()->delete('ACL');

        return $this->redirect(['_name' => 'login']);
    }
}