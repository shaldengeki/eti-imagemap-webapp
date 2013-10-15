<?php
class TagsController extends AppController {
  public $helpers = ['Html', 'Form'];
  public $components = ['Session', 'Paginator', 'RequestHandler'];
  public $uses = ['Tag', 'Image'];

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
    $this->paginate['Image']['fields'] = ['Image.id', 'Image.eti_thumb_url', 'Image.eti_image_tag', 'Image.tags'];
    $this->Paginator->settings = $this->paginate;

    // filter out all images that the user cannot view.
    // TODO: work this into the paginate() call so we always pull a full page.
    $pageResults = array_filter($this->Paginator->paginate('Image'),
                                function($i) {
                                  return $this->Image->canView($i['Image']['id'], $this->Auth->user('id'));
                                });
    $this->set('images', array_map(function($i) {
      return $i['Image'];
    }, $pageResults));

    // count up the number of images tagged with each tag on this page.
    $tagListing = [];
    foreach ($pageResults as $result) {
      if ($result['Image']['tags']) {
        foreach ($this->Image->tagArray($result['Image']['tags']) as $thisTag) {
          $thisTag = $this->Tag->findByName($thisTag)['Tag'];
          if (!isset($tagListing[$thisTag['id']])) {
            $thisTag['count'] = 1;
            $thisTag['addLink'] = $this->Tag->appendToQuery($thisTag['name'], $tag['Tag']['name']);
            $thisTag['removeLink'] = $this->Tag->appendToQuery('-'.$thisTag['name'], $tag['Tag']['name']);
            $tagListing[$thisTag['id']] = $thisTag;
          } else {
            $tagListing[$thisTag['id']]['count']++;
          }
        }
      }
    }
    $this->set('tagListing', $tagListing);
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

  public function power_tag() {
    $this->layout = 'empty';
    if ($this->request->is('post') || $this->request->is('put')) {
      if (!isset($this->request->data['tags']) || !isset($this->request->data['ids'])) {
        $this->set('result', -1);
      } elseif (!$this->Auth->user('id')) {
        $this->set('result', -2);
      } else {
        // proceed to power-tag images.
        $this->Image = ClassRegistry::init('Image');
        $imageIDs = explode(",", $this->request->data['ids']);
        $userID = $this->Auth->user('id');
        $tags = explode(" ", $this->request->data['tags']);

        foreach ($imageIDs as $image) {
          if ($this->Auth->user('role') === 'admin' || $this->Image->isOwnedBy($image, $userID)) {
            $currImage = $this->Image->findById($image);
            if (!$currImage) {
              continue;
            }

            // only add tags that we need.
            $currImageTags = $this->Image->tagArray($currImage['Image']['tags']);
            $newTags = [];
            foreach ($tags as $tag) {
              $tag = $this->Tag->cleanName($tag);
              if (!in_array($tag, $currImageTags)) {
                $currImageTags[] = $tag;
                $newTags[] = $tag;
              }
            }
            if ($newTags) {
              // tag this image with all the tags.
              $finalTags = implode(" ", $currImageTags);
              // create any tags that need to be created.
              $this->Image->createTags(implode(" ", $newTags), $userID);

              // update the image's tags' image_counts.
              $this->Image->updateTags($currImage['Image']['tags'], $finalTags);

              $this->Image->create();
              $this->Image->id = $currImage['Image']['id'];
              $currImage['Image']['tags'] = $finalTags;
              $result = $this->Image->save($currImage);
            }
          }
        }
        $this->set('result', 1);
      }
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