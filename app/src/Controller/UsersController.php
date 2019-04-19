<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 *
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersController extends AppController
{
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Authentication->allowUnauthenticated(['add', 'login']);
    }

    public function login()
    {
        $result = $this->Authentication->getResult();

        // regardless of POST or GET, redirect if user is logged in
        if ($result->isValid()) {
            $redirect = $this->request->getQuery('redirect', ['controller' => 'Pages', 'action' => 'display', 'home']);
            return $this->redirect($redirect);
        }

        // display error if user submitted and authentication failed
        if ($this->request->is(['post']) && !$result->isValid()) {
            $this->Flash->error('Invalid username or password');
        }
    }

    public function logout()
    {
        return $this->redirect($this->Authentication->logout());
    }

}
