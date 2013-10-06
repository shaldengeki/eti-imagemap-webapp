<?php
class User extends AppModel {
  public $validate = [
    'id' => [
      'naturalNumber' => [
        'rule' => 'naturalNumber',
        'required' => True,
        'allowEmpty' => False,
        'message' => "Must be a natural number"
      ]
    ],
    'name' => [
      'between' => [
        'rule' => ['between', 1, 64],
        'required' => True,
        'allowEmpty' => False,
        'message' => "Must be between 1 and 64 characters long"
      ]
    ],
    'last_ip' => [
      'ip' => [
        'rule' => ['ip', 'IPv4'],
        'allowEmpty' => True,
        'message' => "A valid IP address is required"
      ]
    ],
    'role' => [
      'valid' => [
        'rule' => ['inList', ['admin', 'user']],
        'message' => 'Please enter a valid role',
        'required' => True,
        'allowEmpty' => False
      ]
    ],
    'image_count' => [
      'naturalNumber' => [
        'rule' => ['naturalNumber', True],
        'allowEmpty' => True,
        'message' => "Must be a natural number or zero"
      ]
    ]
  ];
  public $hasMany = [
    'Images' => [
      'className' => 'Image'
    ],
    'PublicImages' => [
      'className' => 'Image',
      'conditions' => ['PublicImages.private' => False]
    ]
  ];
  public $hasOne = [
    'ScrapeRequest' => [
      'className' => 'ScrapeRequest',
      'conditions' => ['ScrapeRequest.password IS NOT NULL']
    ]
  ];
}
?>