<?php
class ImagesController extends AppController {
  public $helpers = ['Html', 'Form'];
  public $components = ['Session'];

  public function index() {
    $this->set('images', array_map(function ($i) {
      return $i['Image'];
    }, 
    $this->Image->find('all')
    ));
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