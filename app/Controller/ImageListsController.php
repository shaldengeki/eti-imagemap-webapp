<?php
class ImageListsController extends AppController {
  public $helpers = ['Html', 'Form'];
  public $components = ['Session', 'Paginator'];
  public $uses = ['ImageList', 'Tag', 'Image'];

  public $paginate = [
    'ImageList' => [
      'limit' => 50,
      'order' => [
        'ImageList.updated' => 'desc'
      ],
      'conditions' => []
    ],
    'Image' => [
      'limit' => 50,
      'joins' => [
        [
          'table' => 'image_lists_images',
          'alias' => 'ImageListsImages',
          'type' => 'INNER',
          'conditions' => [
            'ImageListsImages.image_id = Image.id'
          ]
        ]
      ],
      'order' => [
        'Image.created' => 'desc'
      ],
      'conditions' => []
    ]
  ];

  public function beforeFilter() {
    parent::beforeFilter();

    // Anyone can view public image lists, but only the owning user can view private image lists.
    if ($this->action === 'view') {
      $listID = $this->request->params['pass'][0];
      if ($this->ImageList->isPublic($listID)) {
        $this->Auth->allow('view');
      }
    }
  }

  public function isAuthorized($user) {
    // All registered users can add image lists.
    if ($this->action === "add") {
      return True;
    }

    // A user can edit and delete his own image lists.
    if (in_array($this->action, ['edit', 'delete'])) {
      $listID = $this->request->params['pass'][0];
      if ($this->ImageList->isOwnedBy($listID, $user['id'])) {
        return True;
      }
    }

    return parent::isAuthorized($user);
  }

  public function index() {
    // only list image lists that the user can view.

    $this->paginate['ImageList']['fields'] = ['ImageList.id', 'ImageList.name', 'ImageList.description', 'ImageList.updated', 'image_count', 'private'];
    $this->Paginator->settings = $this->paginate;

    $imageLists = array_filter($this->Paginator->paginate('ImageList')
                               , function($i) {
                                return $this->ImageList->canView($i['ImageList']['id'], $this->Auth->user('id'));
                              });
    $this->set('imageLists', $imageLists);
  }

  public function popular() {
    // only list image lists that the user can view.

    $this->paginate['ImageList']['fields'] = ['ImageList.id', 'ImageList.name', 'ImageList.description', 'ImageList.updated', 'image_count', 'private'];
    $this->paginate['ImageList']['order'] = [
      'ImageList.hits' => 'desc'
    ];
    $this->Paginator->settings = $this->paginate;

    $imageLists = array_filter($this->Paginator->paginate('ImageList')
                               , function($i) {
                                return $this->ImageList->canView($i['ImageList']['id'], $this->Auth->user('id'));
                              });
    $this->set('imageLists', $imageLists);
    $this->render('index');
  }

  public function view($id = Null) {
    if (!$id) {
      throw new NotFoundException(__('Invalid image list'));
    }

    $imageList = $this->ImageList->findById($id);
    if (!$imageList) {
      throw new NotFoundException(__('Invalid image list'));
    }

    $this->ImageList->incrementHits($id);
    $imageList['ImageList']['hits'] += 1;

    $this->paginate['Image']['conditions'] = [
      'ImageListsImages.image_list_id' => $imageList['ImageList']['id'],
    ];

    $this->Paginator->settings = $this->paginate;
    $images = array_map(function($i) {
      return $i['Image'];
    }, $this->Paginator->paginate('Image'));
    $this->set('images', $images);


    $this->set('title_for_layout', $imageList['ImageList']['name']);
    $this->set('imageList', $imageList);
  }

  public function add() {
    if ($this->request->is('post')) {
      $this->ImageList->create();

      // set default parameters.
      $this->request->data['ImageList']['user_id'] = $this->Auth->user('id');
      $this->request->data['ImageList']['hits'] = 0;

      if ($this->ImageList->save($this->request->data)) {
        $this->Session->setFlash(__('This list has been saved.'));
        return $this->redirect([
                                'action' => 'view',
                                $this->ImageList->id
                               ]);
      }
      $this->Session->setFlash(__('Unable to create your list.'));
    }
    $this->set('title_for_layout', 'Adding List');
  }

  public function edit($id = Null) {
    if (!$id) {
      throw new NotFoundException(__('Invalid image list'));
    }

    $imageList = $this->ImageList->findById($id);
    if (!$imageList) {
      throw new NotFoundException(__('Invalid image list'));
    }

    if ($this->request->is('post') || $this->request->is('put')) {
      $this->ImageList->id = $id;
      $this->request->data['ImageList']['user_id'] = isset($image['ImageList']['user_id']) ? $image['ImageList']['user_id'] : $this->Auth->user('id');
      if ($this->ImageList->save($this->request->data)) {

        $this->Session->setFlash(__('This list has been updated.'));
        return $this->redirect(['action' => 'view', $id]);
      }
      $this->Session->setFlash(__('Unable to update your image list.'));
    }

    if (!$this->request->data) {
      $this->request->data = $imageList;
      $this->set('title_for_layout', "Editing ".$imageList['ImageList']['name']);
    }
  }

  public function delete($id) {
    if ($this->request->is('get')) {
      throw new MethodNotAllowedException();
    }

    $imageList = $this->ImageList->findById($id);
    if (!$imageList) {
      throw new NotFoundException(__('Invalid image list'));
    }    

    if ($this->ImageList->delete($id)) {
      $this->Session->setFlash(__('The image list with id: %s has been deleted.', h($id)));
      return $this->redirect(['action' => 'index']);
    }
  }
}
?>