<?php
class TagsController extends AppController {
  public $helpers = ['Html', 'Form'];
  public $components = ['Session', 'Paginator'];

  public $paginate = [
    'Tag' => [
      'limit' => 50,
      'order' => [
        'Tag.id' => 'desc'
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

  public function isAuthorized($user) {
    // Only admins can change tags.

    return parent::isAuthorized($user);
  }  

  public function index() {
    $this->set('tags', $this->Tag->find('all', [
                'fields' => ['Tag.id', 'Tag.name', 'Tag.image_count'],
                'recursive' => -1
               ]));
  }

  public function view($id = Null) {
    if (!$id) {
      throw new NotFoundException(__('Invalid tag'));
    }

    $tag = $this->Tag->findById($id);
    if (!$tag) {
      throw new NotFoundException(__('Invalid tag'));
    }
    $this->set('tag', $tag);

    // pull the images belonging to this tag.
    $tagSearch = $this->Tag->parseQuery($tag['Tag']['name']);
    $this->paginate['Image']['conditions'][] = "MATCH(tags) AGAINST('".$this->Tag->sqlQuery($tagSearch)."' IN BOOLEAN MODE)";
    $this->Paginator->settings = $this->paginate;

    // filter out all images that the user cannot view.
    // TODO: work this into the paginate() call so we always pull a full page.
    $this->Image = ClassRegistry::init('Image');
    $allowedImages = array_filter(
      array_map(
        function($i) {
          return $i['Image'];
        }, $this->Paginator->paginate('Image')
      ), 
      function ($i) {
        return $this->Image->canView($i['id'], $this->Auth->user('id'));
      }
    );
    $this->set('images', $allowedImages);
  }

  public function add() {
    if ($this->request->is('post')) {
      $this->Tag->create();

      // set default parameters.
      $this->request->data['Tag']['user_id'] = $this->Auth->user('id');
      $this->request->data['Tag']['image_count'] = 0;

      if ($this->Tag->save($this->request->data)) {
        $this->Session->setFlash(__('Your tag has been saved.'));
        return $this->redirect(['action' => 'index']);
      }
      $this->Session->setFlash(__('Unable to add your tag.'));
    }
  }

  public function edit($id = Null) {
    if (!$id) {
      throw new NotFoundException(__('Invalid tag'));
    }

    $tag = $this->Tag->findById($id);
    if (!$tag) {
      throw new NotFoundException(__('Invalid tag'));
    }

    if ($this->request->is('post') || $this->request->is('put')) {
      $this->Tag->id = $id;
      if ($this->Tag->save($this->request->data)) {
        $this->Session->setFlash(__('Your tag has been updated.'));
        return $this->redirect(['action' => 'index']);
      }
      $this->Session->setFlash(__('Unable to update your tag.'));
    }

    if (!$this->request->data) {
      $this->request->data = $tag;
    }
  }


  public function delete($id) {
    if ($this->request->is('get')) {
      throw new MethodNotAllowedException();
    }

    if ($this->Tag->delete($id)) {
      $this->Session->setFlash(__('The tag with id: %s has been deleted.', h($id)));
      return $this->redirect(['action' => 'index']);
    }
  }
}
?>