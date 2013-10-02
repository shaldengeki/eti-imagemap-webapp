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
        'required' => True,
        'allowEmpty' => False,
        'message' => "A valid IP address is required"
      ]
    ],
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
}
?>