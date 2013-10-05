<?php
class ScrapeRequest extends AppModel {
  public $primaryKey = 'user_id';
  public $validate = [
    'user_id' => [
      'naturalNumber' => [
        'rule' => 'naturalNumber',
        'required' => True,
        'allowEmpty' => False,
        'message' => "Only natural numbers allowed"
      ]
    ],
    'date' => [
      'datetime' => [
        'rule' => 'datetime',
        'required' => True,
        'allowEmpty' => False,
        'message' => "Only valid datetimes allowed"
      ]
    ],
    'progress' => [
      'number' => [
        'rule' => ['range', -1, 101],
        'allowEmpty' => False,
        'message' => "Please enter a number between 0 and 100"
      ]
    ],
    'password' => [
      'between' => [
        'rule' => ['between', 1, 30],
        'message' => "Must be between 1 and 30 characters long"
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