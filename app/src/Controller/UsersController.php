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
        $this->Authentication->allowUnauthenticated(['login']);
        $this->Authorization->skipAuthorization(['login']);
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

    public function add()
    {
        $user = $this->Users->newEntity();
        $this->Authorization->authorize('create', $user);
        return $this->Crud->execute();
    }

    public function view($id)
    {
        $this->Crud->on('afterFind', function (Event $event) {
            $entity = $event->getSubject()->entity;
            $this->Authorization->authorize('view', $entity);
        });

        return $this->Crud->execute();
    }

    public function edit($id)
    {
        $this->Crud->on('afterFind', function (Event $event) {
            $entity = $event->getSubject()->entity;
            $this->Authorization->authorize('update', $entity);
        });

        return $this->Crud->execute();
    }

    public function delete($id)
    {
        $this->Crud->on('afterFind', function (Event $event) {
            $entity = $event->getSubject()->entity;
            $this->Authorization->authorize('delete', $entity);
        });

        return $this->Crud->execute();
    }
}
