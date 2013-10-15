<?php
class Image extends AppModel {
  public $validate = [
    'server' => [
      'naturalNumber' => [
        'rule' => 'naturalNumber',
        'required' => 'create',
        'allowEmpty' => False,
        'message' => "Only natural numbers allowed"
      ],
      'range' => [
        'rule' => ['range', 0, 10],
        'message' => "Must be between 1 and 9 inclusive"
      ]
    ],
    'hash' => [
      'alphaNumeric' => [
        'rule' => 'alphaNumeric',
        'required' => 'create',
        'allowEmpty' => False,
        'message' => "Only alphanumeric characters allowed"
      ],
      'between' => [
        'rule' => ['between', 32, 32],
        'message' => "Must be exactly 32 characters long"
      ],
      'unique' => [
        'rule' => 'isUnique'
      ]
    ],
    'filename' => [
      'between' => [
        'rule' => ['between', 1, 255],
        'required' => 'create',
        'allowEmpty' => False,
        'message' => "Must be between 1 and 255 characters long"
      ]      
    ],
    'type' => [
      'alphaNumeric' => [
        'rule' => 'alphaNumeric',
        'required' => 'create',
        'allowEmpty' => False,
        'message' => "Only alphanumeric characters allowed"
      ],
      'between' => [
        'rule' => ['between', 1, 4],
        'message' => "Must be between 1 and 4 characters long"
      ]
    ],
    'user_id' => [
      'naturalNumber' => [
        'rule' => 'naturalNumber',
        'required' => True,
        'allowEmpty' => 'create',
        'message' => "Only natural numbers allowed"
      ]
    ],
    'private' => [
      'boolean' => [
        'rule' => 'boolean',
        'required' => 'create',
        'allowEmpty' => False,
        'message' => "Only valid boolean allowed"
      ]
    ],
    'tags' => [
       // no validations.
    ],
    'tag_count' => [
      'naturalNumber' => [
        'rule' => ['naturalNumber', True],
        'allowEmpty' => True,
        'message' => "Must be a natural number or zero"
      ]
    ]
  ];
  public $belongsTo = [
    'User' => [
      'className' => 'User',
      'counterCache' => True
    ]
  ];

  public function __construct($id = False, $table = Null, $ds = Null) {
    parent::__construct($id, $table, $ds);
    $this->virtualFields['eti_url'] = sprintf('CONCAT("http://i", %s.server, ".endoftheinter.net/i/n/", %s.hash, "/", %s.filename, ".", %s.type)', $this->alias, $this->alias, $this->alias, $this->alias);
    $this->virtualFields['eti_thumb_url'] = sprintf('CONCAT("http://i", %s.server, ".endoftheinter.net/i/t/", %s.hash, "/", %s.filename, ".jpg")', $this->alias, $this->alias, $this->alias);
    $this->virtualFields['eti_image_tag'] = sprintf('CONCAT(\'<img src="\', \'http://i\', %s.server, \'\.endoftheinter\.net/i/n/\', %s.hash, \'/\', %s.filename, \'\.\', %s.type, \'" />\')', $this->alias, $this->alias, $this->alias, $this->alias, $this->alias);
  }

  public function beforeSave($options=[]) {
    // set tag_count to the length of the tag array.
    parent::beforeSave($options);

    if (isset($this->data['Image']['tags'])) {
      $tagArray = $this->tagArray($this->data['Image']['tags']);
      $this->data['Image']['tag_count'] = count($tagArray);
      $this->data['Image']['tags'] = implode(" ", $tagArray);
    }
    return True;
  }

  public function createTags($tags, $user) {
    // creates any tags in the tag-string $tags under the user $user that don't already exist.
    $tagArray = $this->tagArray($tags);
    $success = True;
    $tagClass = ClassRegistry::init('Tag');

    foreach ($tagArray as $tag) {
      $findTag = $tagClass->find('first', [
                              'conditions' => [
                                'Tag.name' => $tag
                              ]
                             ]);
      if (!$findTag) {
        $tagClass->create();
        $save = $tagClass->save([
                              'Tag' => [
                                'name' => $tag,
                                'user_id' => $user,
                                'image_count' => 0
                              ]
                            ]);
        $success = $save && $success;
      }
    }
    return $success;
  }

  public function updateTags($beforeTags, $afterTags) {
    // updates the image_counts of this image's tags during a save.
    // $beforeTags and $afterTags should be an image's 'tags' attribute, i.e. space-separated strings of tag names.
    $tagClass = ClassRegistry::init('Tag');

    $beforeTags = $this->tagArray($beforeTags);
    $afterTags = $this->tagArray($afterTags);

    $removedTags = array_map(function ($t) use ($tagClass) {
        $tag = $tagClass->findByName($t);
        return $tag['Tag']['id'];
      }, 
      array_diff($beforeTags, $afterTags)
    );

    $addedTags = array_map(function ($t) use ($tagClass) {
        $tag = $tagClass->findByName($t);
        return $tag['Tag']['id'];
      }, 
      array_diff($afterTags, $beforeTags)
    );

    return $tagClass->decrementImages($removedTags) && $tagClass->incrementImages($addedTags);
  }

  public function incrementHits($id) {
    $this->updateAll(
      [$this->alias.'.hits' => $this->alias.'.hits+1'],
      [$this->alias.'.id' => $id]
    );
  }

  public function isOwnedBy($image, $user) {
    return (bool) ($this->field('id', ['id' => $image, 'user_id' => $user]) === $image);
  }

  public function isPrivate($image) {
    return (bool) $this->field('private', ['id' => $image]);
  }

  public function isPublic($image) {
    return (bool) !$this->isPrivate($image);
  }

  public function canView($image, $user) {
    // returns a boolean reflecting whether or not a given $user ID can view an $image.
    return (bool) ($this->isPublic($image) || $this->isOwnedBy($image, $user));
  }

  public function tagArray($tags) {
    $tags = trim($tags);
    if (!$tags) {
      return [];
    }
    $tagClass = ClassRegistry::init('Tag');
    $tags = explode(" ", $tags);
    foreach ($tags as $key=>$tag) {
      $tags[$key] = $tagClass->cleanName($tag);
    }
    return $tags;
  }

  public function isTaggedWith($image, $tag) {
    $tagArray = $this->tagArray($this->field('tags', ['id' => $image]));
    return in_array($tag, $tagArray);
  }
}
?>