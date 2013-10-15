<?php
class ScrapeRequest extends AppModel {

  // Error codes that progress is set to in case of imagemap scrape failure.
  public static $ERRORS = [
    -1 => "Invalid password"
  ];

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
      'numeric' => [
        'rule' => 'numeric',
        'allowEmpty' => False,
        'message' => "Please enter a number"
      ]
    ],
    'password' => [
      'between' => [
        'rule' => ['between', 1, 30],
        'message' => "Must be between 1 and 30 characters long"
      ]
    ],
    'permanent' => [
      'boolean' => [
        'rule' => 'boolean',
        'required' => True,
        'allowEmpty' => False,
        'message' => "Only valid boolean allowed"
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
  public $virtualFields = [
    'position' => 'SELECT COUNT(*) FROM scrape_requests AS SR2 WHERE SR2.password IS NOT NULL AND SR2.date <= ScrapeRequest.date'
  ];
}
?>