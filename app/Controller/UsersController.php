<?php
class UsersController extends AppController {
  public $helpers = ['Html', 'Form'];
  public $components = ['Session'];

  public function beforeFilter() {
    parent::beforeFilter();
    $this->Auth->allow('add');
  }

  public function isAuthorized($user) {
    // All registered users can logout and queue imagemap scrapings.
    if (in_array($this->action, ['logout', 'scrape_image_map']) && isset($user['id']) && $user['id'] > 0) {
      return True;
    }

    // All unregistered users can register and login.
    if (in_array($this->action, ['add', 'login']) && !isset($user['id'])) {
      return True;
    }

    // A user can edit and delete himself.
    if (in_array($this->action, ['edit', 'delete'])) {
      $userID = $this->request->params['pass'][0];
      if ($userID === $user['id']) {
        return True;
      }
    }

    return parent::isAuthorized($user);
  }  

  public function index() {
    $this->set('users', $this->User->find('all'));
  }

  public function view($id = Null) {
    if (!$id) {
      throw new NotFoundException(__('Invalid user'));
    }

    $user = $this->User->findById($id);
    if (!$user) {
      throw new NotFoundException(__('Invalid user'));
    }
    $this->set('user', $user);
    if ($user['User']['id'] === $this->Auth->user('id') || $this->Auth->user('role') === 'admin') {
      $this->set('images', $user['Images']);
    } else {
      $this->set('images', $user['PublicImages']);
    }
  }

  public function add() {
    if ($this->request->is('post')) {
      $this->User->create();
      // set parameters.
      $this->request->data['User']['last_ip'] = $_SERVER['REMOTE_ADDR'];

      if ($this->User->save($this->request->data)) {
        $this->Session->setFlash(__('Your user info has been saved.'));
        return $this->redirect(['action' => 'index']);
      }
      $this->Session->setFlash(__('Unable to add your user.'));
    }
  }

  public function edit($id = Null) {
    if (!$id) {
      throw new NotFoundException(__('Invalid user'));
    }

    $user = $this->User->findById($id);
    if (!$user) {
      throw new NotFoundException(__('Invalid user'));
    }

    if ($this->request->is('post') || $this->request->is('put')) {
      $this->User->id = $id;
      if ($this->User->save($this->request->data)) {
        $this->Session->setFlash(__('Your user has been updated.'));
        return $this->redirect(['action' => 'index']);
      }
      $this->Session->setFlash(__('Unable to update your user.'));
    }

    if (!$this->request->data) {
      $this->request->data = $user;
    }
  }

  public function delete($id) {
    if ($this->request->is('get')) {
      throw new MethodNotAllowedException();
    }

    if ($this->User->delete($id)) {
      $this->Session->setFlash(__('The user with id: %s has been deleted.', h($id)));
      return $this->redirect(['action' => 'index']);
    }
  }

  public function login() {
    if ($this->request->is('post')) {
      if ($this->Auth->login()) {
        // set the user's last-ip.
        $this->User->id = $this->Auth->user('id');
        $this->User->saveField('last_ip', $_SERVER['REMOTE_ADDR']);

        $this->Session->setFlash(__("You're now logged in."));
        return $this->redirect($this->Auth->redirect());
      }
      $this->Session->setFlash(__("You're not logged in on ETI under that username; please log in on ETI and try again."));
    }
  }

  public function logout() {
    // clear the user's last-ip.
    $this->User->id = $this->Auth->user('id');
    $this->User->saveField('last_ip', Null);

    return $this->redirect($this->Auth->logout());
  }

  public function scrape_image_map() {
    // scrape this user's imagemap.
    if ($this->request->is('post')) {
      // require username and password.
      if (isset($this->request['data']) && isset($this->request['data']['User']) && isset($this->request['data']['User']['password'])) {
        $username = $this->Auth->user('name');
        $password = $this->request['data']['User']['password'];

        // log into mobile ETI as the user.
        try {
          $eti = new \ETI\Connection($username, $password, "mobile");
        } catch (Exception $e) {
          // failed to log into ETI.
        }
      }

    }
  }
}
?>