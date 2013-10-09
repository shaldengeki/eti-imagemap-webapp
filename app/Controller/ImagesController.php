<?php
class ImagesController extends AppController {
  public $helpers = ['Html', 'Form'];
  public $components = ['Session', 'Paginator'];
  public $uses = ['Image', 'Tag'];

  public $paginate = [
    'Image' => [
      'limit' => 50,
      'order' => [
        'Image.created' => 'desc'
      ],
      'conditions' => []
    ]
  ];

  public function beforeFilter() {
    parent::beforeFilter();

    // Anyone can view public images, but only the owning user can view private images.
    if ($this->action === 'view') {
      $imageID = $this->request->params['pass'][0];
      if ($this->Image->isPublic($imageID)) {
        $this->Auth->allow('view');
      }
    }
  }

  public function isAuthorized($user) {
    // All registered users can add images.
    if ($this->action === "add") {
      return True;
    }

    // A user can edit and delete his own images.
    if (in_array($this->action, ['edit', 'delete'])) {
      $imageID = $this->request->params['pass'][0];
      if ($this->Image->isOwnedBy($imageID, $user['id'])) {
        return True;
      }
    }

    return parent::isAuthorized($user);
  }  


  public function index() {
    // only list images that the user can view.

    // parse tag queries first.
    $tagQuery = "";
    if (isset($this->request->query['tags'])) {
      $tagQuery = $this->request->query['tags'];
      $tagSearch = $this->Tag->parseQuery($tagQuery);
      $this->paginate['Image']['conditions'][] = "MATCH(tags) AGAINST('".$this->Tag->sqlQuery($tagSearch)."' IN BOOLEAN MODE)";
    }

    $this->paginate['Image']['fields'] = ['Image.id', 'Image.eti_thumb_url', 'Image.eti_image_tag', 'Image.tags'];
    $this->Paginator->settings = $this->paginate;

    $pageResults = array_filter($this->Paginator->paginate('Image')
                               , function($i) {
                                return $this->Image->canView($i['Image']['id'], $this->Auth->user('id'));
                              });
    $images = array_map(function ($i) {
      return $i['Image'];
    }, $pageResults);
    $this->set('images', $images);

    // count up the number of images tagged with each tag on this page.
    $tagListing = [];
    foreach ($pageResults as $result) {
      if ($result['Image']['tags']) {
        $imageTags = explode(" ", $result['Image']['tags']);
        foreach ($imageTags as $tag) {
          $tag = $this->Tag->findByName($tag)['Tag'];
          if (!isset($tagListing[$tag['id']])) {
            $tag['count'] = 1;
            $tag['addLink'] = $this->Tag->appendToQuery($tag['name'], $tagQuery);
            $tag['removeLink'] = $this->Tag->appendToQuery('-'.$tag['name'], $tagQuery);
            $tagListing[$tag['id']] = $tag;
          } else {
            $tagListing[$tag['id']]['count']++;
          }
        }
      }
    }
    $this->set('tagListing', $tagListing);

  }

  public function view($id = Null) {
    if (!$id) {
      throw new NotFoundException(__('Invalid image'));
    }

    $image = $this->Image->findById($id);
    if (!$image) {
      throw new NotFoundException(__('Invalid image'));
    }

    $this->Image->incrementHits($id);
    $image['Image']['hits'] += 1;

    $this->set('image', $image);
  }

  public function add() {
    if ($this->request->is('post')) {
      $this->Image->create();

      // set default parameters.
      $this->request->data['Image']['user_id'] = $this->Auth->user('id');
      $this->request->data['Image']['hits'] = 0;

      if ($this->Image->save($this->request->data)) {
        $this->Session->setFlash(__('Your image has been saved.'));
        return $this->redirect(['action' => 'index']);
      }
      $this->Session->setFlash(__('Unable to add your image.'));
    }
  }

  public function edit($id = Null) {
    if (!$id) {
      throw new NotFoundException(__('Invalid image'));
    }

    $image = $this->Image->findById($id);
    if (!$image) {
      throw new NotFoundException(__('Invalid image'));
    }

    if ($this->request->is('post') || $this->request->is('put')) {
      $this->Image->id = $id;
      if ($this->Image->save($this->request->data)) {
        $this->Session->setFlash(__('Your image has been updated.'));
        return $this->redirect(['action' => 'index']);
      }
      $this->Session->setFlash(__('Unable to update your image.'));
    }

    if (!$this->request->data) {
      $this->request->data = $image;
    }
  }

  public function delete($id) {
    if ($this->request->is('get')) {
      throw new MethodNotAllowedException();
    }

    if ($this->Post->delete($id)) {
      $this->Session->setFlash(__('The post with id: %s has been deleted.', h($id)));
      return $this->redirect(['action' => 'index']);
    }
  }
}
?>