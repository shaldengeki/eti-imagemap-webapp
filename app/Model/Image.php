<?php
class Image extends AppModel {
  public $validate = [
    'server' => [
      'naturalNumber' => [
        'rule' => 'naturalNumber',
        'required' => True,
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
        'required' => True,
        'allowEmpty' => False,
        'message' => "Only alphanumeric characters allowed"
      ],
      'between' => [
        'rule' => ['between', 32, 32],
        'message' => "Must be exactly 32 characters long"
      ]
    ],
    'filename' => [
      'between' => [
        'rule' => ['between', 1, 255],
        'required' => True,
        'allowEmpty' => False,
        'message' => "Must be between 1 and 255 characters long"
      ]      
    ],
    'type' => [
      'alphaNumeric' => [
        'rule' => 'alphaNumeric',
        'required' => True,
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
        'allowEmpty' => False,
        'message' => "Only natural numbers allowed"
      ]
    ],
    'private' => [
      'boolean' => [
        'rule' => 'boolean',
        'required' => True,
        'allowEmpty' => False,
        'message' => "Only valid boolean allowed"
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
}
?>