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

  public function beforeFilter() {
    parent::beforeFilter();
  }

  public function isAuthorized($user) {
    // TODO: allow user to power-tag if he owns all the images provided.

    // All users are allowed to search for tags.
    if ($this->action == "autocomplete") {
      return True;
    }

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
    $this->setTagListing($pageResults, $tag['Tag']['name']);
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
    $this->layout = 'ajax';
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

        $results = [];
        foreach ($imageIDs as $image) {
          if ($this->Auth->user('role') === 'admin' || $this->Image->isOwnedBy($image, $userID)) {
            $currImage = $this->Image->findById($image);
            if (!$currImage) {
              continue;
            }

            // only add tags that we need.
            $this->Image->addTags($image, $tags);
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
              $this->Image->updateTagImageCounts($currImage['Image']['tags'], $finalTags);

              $this->Image->create();
              $this->Image->id = $currImage['Image']['id'];
              $currImage['Image']['tags'] = $finalTags;
              $results[$image] = intval($this->Image->save($currImage));
            }
          }
        }
        $this->set('results', $results);
      }
    }
  }

  public function autocomplete() {
    $this->layout = 'ajax';
    if (!isset($this->request->query['query'])) {
      $this->set('tags', []);
    } else {
      $query = trim($this->request->query['query']);


      $this->paginate['Tag']['limit'] = 20;
      $this->paginate['Tag']['conditions']['Tag.name LIKE'] = $query.'%';

      $this->paginate['Tag']['order'] = ["Tag.name" =>  "asc"];
      $this->Paginator->settings = $this->paginate;

      $this->set('tags', $this->Paginator->paginate('Tag'));
    }
  }
  public function delete($id) {
    if ($this->request->is('get')) {
      throw new MethodNotAllowedException();
    }

    $tag = $this->Tag->findById($id);
    if (!$tag) {
      throw new NotFoundException(__('Invalid tag'));
    }

    if ($this->Tag->delete($id)) {
      $this->Image = ClassRegistry::init('Image');

      // remove this tag from all images.
      $images = $this->Tag->getImages($tag['Tag']['name']);
      foreach ($images as $image) {
        $this->Image->removeTag($image['Image']['id'], $tag['Tag']['name']);
      }
      $this->Session->setFlash(__('The tag with id: %s has been deleted.', h($id)));
      return $this->redirect(['action' => 'index']);
    }
  }
}
?>