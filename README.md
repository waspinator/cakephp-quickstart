# cakephp-quickstart

This is an example cakephp app with user accounts and a REST api.

## Steps to reproduce

### install app and plugins

- `composer create-project --prefer-dist cakephp/app app`
- `composer require cakephp/authentication`
- `composer require cakephp/authorization`
- `composer require friendsofcake/crud`
- `composer require muffin/footprint`

### prepare database

- modify `app/config/app.php` Datasources setting `'host' => 'mysql'`
- `bin/cake bake migration CreateUsers`
- write the database structure for users in `/app/config/migrations/<date>_CreateUsers.php`
- `bin/cake migrations migrate`

### configure users

- `bin/cake bake all users`
- delete all functions in `/app/src/Controller/UsersController.php`
- hash passwords by adding `_setPassword()` to `/app/src/Model/Entity/User.php`

```php

    use Authentication\PasswordHasher\DefaultPasswordHasher;

    protected function _setPassword($value)
    {
        if (strlen($value)) {
            $hasher = new DefaultPasswordHasher();

            return $hasher->hash($value);
        }
    }
```

- generate api tokens by adding `beforeSave()` to `/app/src/Model/Table/UsersTable.php`

```php
    use Cake\Event\Event;
    use Cake\Utility\Security;
    use Authentication\PasswordHasher\DefaultPasswordHasher;

    public function beforeSave(Event $event)
    {
        $entity = $event->getData('entity');

        if ($entity->isNew()) {
            $hasher = new DefaultPasswordHasher();
            $entity->api_key_plain = Security::hash(Security::randomBytes(32), 'sha256', false);
            $entity->api_key = $hasher->hash($entity->api_key_plain);
        }
        return true;
    }
}
```


### configure plugins

#### friendsofcake/crud

[documentation](https://crud.readthedocs.io/en/latest/contents.html)

- `bin/cake plugin load Crud`
- add `use \Crud\Controller\ControllerTrait;` to `class AppController` in `/app/src/Controller/AppController.php`
- add the following to the `initialize()` function in `/app/src/Controller/AppController.php` below `$this->loadComponent('RequestHandler')`

```php
    $this->loadComponent('Crud.Crud', [
        'actions' => [
            'Crud.Index',
            'Crud.Add',
            'Crud.Edit',
            'Crud.View',
            'Crud.Delete'
        ],
        'listeners' => [
            'Crud.Api',
            'Crud.ApiPagination'
        ]
    ]);
```

- add `Router::extensions(['json', 'xml']);` to `/app/config/routes.php`

#### cakephp/authentication

- `bin/cake plugin load Authentication`

- modify `/app/src/Application.php` with

```php
    use Authentication\AuthenticationService;
    use Authentication\AuthenticationServiceProviderInterface;
    use Authentication\Middleware\AuthenticationMiddleware;
    use Cake\Routing\Router;
    use Psr\Http\Message\ResponseInterface;
    use Psr\Http\Message\ServerRequestInterface;

    class Application extends BaseApplication implements AuthenticationServiceProviderInterface
    {
        public function getAuthenticationService(ServerRequestInterface $request, ResponseInterface $response)
        {
            $service = new AuthenticationService();

            $fields = [
                'username' => 'email',
                'password' => 'password'
            ];

            $service->loadIdentifier('Authentication.Password', compact('fields'));

            $service->loadAuthenticator('Authentication.Session');
            $service->loadAuthenticator('Authentication.Form', [
                'fields' => $fields,
                'loginUrl' => '/users/login'
            ]);

            return $service;
        }

        public function middleware($middlewareQueue)
        {
            // Various other middlewares for error handling, routing etc. added here.

            // Add the authentication middleware
            $authentication = new AuthenticationMiddleware($this, [
                'unauthenticatedRedirect' => Router::url('/users/login')
            ]);

            // Add the middleware to the middleware queue
            $middlewareQueue->add($authentication);

            return $middlewareQueue;
        }
    }
```

- add the `AuthenticationComponent` to `AppController::initialize()` function in `/app/src/Controller/AppController.php`

```php
    $this->loadComponent('Authentication.Authentication', [
        'logoutRedirect' => '/users/login'
    ]);
```

- add functions to `UsersController` in `/app/src/Controller/UsersController.php`

```php
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
```

#### cakephp/authorization




## Steps to run
