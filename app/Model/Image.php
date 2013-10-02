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
    'type' => [
      'alphaNumeric' => [
        'rule' => 'alphaNumeric',
        'required' => True,
        'allowEmpty' => False,
        'message' => "Only alphanumeric characters allowed"
      ],
      'between' => [
        'rule' => ['between', 1, 3],
        'message' => "Must be between 1 and 3 characters long"
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
    'added_on' => [
      'datetime' => [
        'rule' => 'datetime',
        'required' => True,
        'allowEmpty' => False,
        'message' => "Only valid datetimes allowed"
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
      'className' => 'User'
    ]
  ];
}
?>