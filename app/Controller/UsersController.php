<?php
class UsersController extends AppController {
  public $helpers = ['Html', 'Form'];
  public $components = ['Session', 'Paginator'];
  public $uses = ['User', 'Image', 'Tag'];

  public $paginate = [
    'User' => [
      'limit' => 50,
      'order' => [
        'User.name' => 'asc'
      ]
    ],
    'Image' => [
      'limit' => 50,
      'order' => [
        'Image.created' => 'desc'
      ],
      'recursive' => -1
    ]
  ];

  public function beforeFilter() {
    parent::beforeFilter();

    // all users can view a user's image listing.
    $this->Auth->allow('images');

    // unregistered users can register and login.
    if (!isset($user['id'])) {
      $this->Auth->allow('add', 'login');
    }
  }

  public function isAuthorized($user) {
    // All registered users can logout and queue imagemap scrapings.
    if (in_array($this->action, ['logout', 'scrape_image_map'])) {
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
    $this->set('users', $this->User->find('all', [
                'fields' => ['User.id', 'User.name', 'User.image_count'],
                'recursive' => -1
               ]));
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

    $this->paginate['Image']['conditions'] = [
      'Image.user_id' => $user['User']['id'],
    ];
    // if the signed-in user is neither the given user nor an admin, filter out all private images.
    if ($this->User->canViewPrivateImages($this->Auth->user('id'), $user['User']['id'])) {
      $images = $this->Paginator->paginate('Image', [
                  'Image.user_id' => $user['User']['id'],
                  'Image.private' => False
                 ]);
    } else {
      $images = $this->Paginator->paginate('Image', [
                                    'Image.user_id' => $user['User']['id'],
                                    ]);
    }

    // only return images, not joined things.
    $this->set('images', array_map(function ($i) {
      return $i['Image'];
    }, $images));
  }

  public function add() {
    if ($this->request->is('post')) {
      if ($this->Auth->login()) {
        // if the user doesn't already exist, they'll have an ID set to null.
        if ($this->Auth->user('id') === Null) {
          // create this user.
          $this->User->create();
          // set parameters.
          $this->request->data['User']['role'] = 'user';
          $this->request->data['User']['last_ip'] = $_SERVER['REMOTE_ADDR'];

          if ($this->User->save($this->request->data)) {
            $this->Session->setFlash(__("You've been registered and logged in!"));
            return $this->redirect($this->Auth->redirectUrl());
          }
        } else {
          // otherwise, the user already exists. log them in.
          $this->Session->setFlash(__("You've already registered; we've logged you in."));
          return $this->redirect($this->Auth->redirectUrl());
        }
      } else {
        // IP and ETI authentication failed.
        $this->Session->setFlash(__('Unable to register you.'));
      }
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

      // disallow users from changing their usernames.
      if (isset($this->request->data['User']['name'])) {
        unset($this->request->data['User']['name']);
      }

      // ensure that this user is not elevating a userlevel beyond what he is allowed to.
      if ($this->Auth->user('role') !== 'admin' && $this->request->data['User']['role'] !== $user['User']['role']) {
        $this->Session->setFlash(__("You can't elevate / decrement this user's userlevel to this point."));
      } elseif ($this->User->save($this->request->data)) {
        $this->Session->setFlash(__('Your account settings have been updated.'));
        return $this->redirect(['action' => 'index']);
      } else {
        // an error occurred while saving the user's data. validation errors should show up in the form so just flash.
        $this->Session->setFlash(__('Unable to update your account settings.'));
      }
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

    $authedUser = $this->User->findById($this->Auth->user('id'));
    $this->ScrapeRequest = ClassRegistry::init('ScrapeRequest');
    $scrapeRequest = $authedUser['ScrapeRequest'];

    if ($this->request->is('post')) {
      // require password.
      if (isset($this->request->data) && isset($this->request->data['User']) && isset($this->request->data['User']['password'])) {
        $username = $this->Auth->user('name');
        $password = $this->request['data']['User']['password'];
        $permanent = $this->request['data']['User']['permanent'];
        $private = $this->request['data']['User']['private'];

        // queue a scrape request for this user.
        $scrapeRequestParams = [
                               'date' => date("Y-m-d H:i:s"),
                               'password' => $password,
                               'progress' => 0,
                               'permanent' => $permanent,
                               'private' => $private
                               ];

        if ($scrapeRequest['user_id']) {
          // user already has a scraperequest row. update with latest time.
          $scrapeRequestParams['user_id'] = $this->ScrapeRequest->id = $scrapeRequest['user_id'];
        } else {
          // user doesn't have a scraperequest row. create one.
          $this->ScrapeRequest->create();
          $scrapeRequestParams['user_id'] = $this->Auth->user('id');
        }

        if ($this->ScrapeRequest->save(['ScrapeRequest' => $scrapeRequestParams])) {
          $this->Session->setFlash(__("Your imagemap has been queued for updating."));
          return $this->redirect($this->referer());
        }
      }
      $this->Session->setFlash(__("Could not queue your imagemap for scraping. Please try again!"));
    }

    if (!$this->request->data) {
      $this->request->data = $scrapeRequest;
    }
  }

  public function images($id = Null) {
    if (!$id) {
      throw new NotFoundException(__('Invalid user'));
    }

    $user = $this->User->findById($id);
    if (!$user) {
      throw new NotFoundException(__('Invalid user'));
    }
    $this->set('user', $user);

    $this->paginate['Image']['fields'] = ['Image.id', 'Image.eti_thumb_url', 'Image.eti_image_tag', 'Image.tags'];
    $this->Paginator->settings = $this->paginate;
    // if the signed-in user is neither the given user nor an admin, filter out all private images.
    if ($this->User->canViewPrivateImages($this->Auth->user('id'), $user['User']['id'])) {
      $images = $this->Paginator->paginate('Image', [
                  'Image.user_id' => $user['User']['id'],
                  'Image.private' => False
                 ]);
    } else {
      $images = $this->Paginator->paginate('Image', [
                                    'Image.user_id' => $user['User']['id'],
                                    ]);
    }
    // only return images, not joined things.
    $this->set('images', array_map(function ($i) {
      return $i['Image'];
    }, $images));

    // count up the number of images tagged with each tag on this page.
    $this->setTagListing($images, '');
  }
}
?>