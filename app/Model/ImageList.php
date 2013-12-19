<?php
class ImageList extends AppModel {
  public $validate = [
    'name' => [
      'between' => [
        'rule' => ['between', 1, 64],
        'allowEmpty' => False,
        'message' => "Must be between 1 and 64 characters long"
      ],
      'unique' => [
        'rule' => 'isUnique',
        'required' => 'create'
      ]
    ],
    'description' => [
      'between' => [
        'rule' => ['between', 0, 140],
        'allowEmpty' => True,
        'message' => "Must be between 0 and 140 characters long"
      ],
      'unique' => [
        'rule' => 'isUnique',
        'required' => 'create'
      ]
    ],
    'user_id' => [
      'naturalNumber' => [
        'rule' => 'naturalNumber',
        'required' => 'create',
        'allowEmpty' => 'create',
        'message' => "Only natural numbers allowed"
      ]
    ],
    'image_count' => [
      'naturalNumber' => [
        'rule' => ['naturalNumber', True],
        'allowEmpty' => True,
        'message' => "Must be a natural number or zero"
      ]
    ],
    'follow_count' => [
      'naturalNumber' => [
        'rule' => ['naturalNumber', True],
        'allowEmpty' => True,
        'message' => "Must be a natural number or zero"
      ]
    ],
    'private' => [
      'boolean' => [
        'rule' => 'boolean',
        'required' => 'create',
        'allowEmpty' => False,
        'message' => "Only valid boolean allowed"
      ]
    ]
  ];
  public $belongsTo = [
    'User' => [
      'className' => 'User'
    ]
  ];
  public $hasAndBelongsToMany = [
    'Images' => [
      'className' => 'Image'
    ]
  ];

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