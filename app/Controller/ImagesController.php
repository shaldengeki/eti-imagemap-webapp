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
    $this->setTagListing($pageResults, $tagQuery);
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

    $tagListing = [];
    foreach ($this->Image->tagArray($image['Image']['tags']) as $tag) {
      $tag = $this->Tag->findByName($tag)['Tag'];
      $tagListing[$tag['id']] = [
        'id' => $tag['id'],
        'name' => $tag['name'],
        'count' => $tag['image_count'],
        'addLink' => $tag['name'],
        'removeLink' => '-'.$tag['name']
      ];
    }

    $this->set('image', $image);
    $this->set('tagListing', $tagListing);
  }

  public function add() {
    if ($this->request->is('post')) {
      $this->Image->create();

      // set default parameters.
      $this->request->data['Image']['user_id'] = $this->Auth->user('id');
      $this->request->data['Image']['hits'] = 0;

      if ($this->Image->save($this->request->data)) {
        // create any non-extant tags.
        $this->Image->createTags($this->request->data['Image']['tags'], $this->Auth->user('id'));

        // update tag image_counts, if necessary.
        if (isset($this->request->data['Image']['tags'])) {
          $this->Image->updateTagImageCounts("", $this->request->data['Image']['tags']);
        }

        $this->Session->setFlash(__('This image has been saved.'));
        return $this->redirect([
                                'action' => 'view',
                                $this->Image->id
                               ]);
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
      $this->request->data['Image']['user_id'] = isset($image['Image']['user_id']) ? $image['Image']['user_id'] : $this->Auth->user('id');
      if ($this->Image->save($this->request->data)) {

        // create any non-extant tags.
        $this->Image->createTags($this->request->data['Image']['tags'], $this->Auth->user('id'));

        // update tag image_counts, if necessary.
        if (isset($this->request->data['Image']['tags'])) {
          $this->Image->updateTagImageCounts($image['Image']['tags'], $this->request->data['Image']['tags']);
        }

        $this->Session->setFlash(__('This image has been updated.'));
        return $this->redirect(['action' => 'view', $id]);
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

    $image = $this->Image->findById($id);
    if (!$image) {
      throw new NotFoundException(__('Invalid image'));
    }    

    if ($this->Image->delete($id)) {
      // update tag image_counts.
      $this->Image->updateTagImageCounts($image['Image']['tags'], "");

      $this->Session->setFlash(__('The image with id: %s has been deleted.', h($id)));
      return $this->redirect(['action' => 'index']);
    }
  }
}
?>