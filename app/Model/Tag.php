<?php
class Tag extends AppModel {
  public $validate = [
    'name' => [
      'between' => [
        'rule' => ['between', 1, 64],
        'allowEmpty' => False,
        'message' => "Must be between 1 and 64 characters long"
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
    'image_count' => [
      'naturalNumber' => [
        'rule' => ['naturalNumber', True],
        'allowEmpty' => True,
        'message' => "Must be a natural number or zero"
      ]
    ]    
  ];
  public $belongsTo = [
    'User' => [
      'className' => 'User'
    ]
  ];
  public $hasAndBelongsToMany = [
    'Image' => [
      'className' => 'Image',
      'order' => 'ImagesTag.image_id DESC',
      'counterCache' => True
    ]
  ];

  public function parseQuery($query) {
    $results = [
      'allow' => [],
      'deny' => []
    ];
    foreach (explode(" ", $query) as $part) {
      if (substr($part, 0, 1) != "-") {
        $results['allow'][] = $part;        
      } else {
        $results['deny'][] = substr($part, 1);
      }
    }
    return $results;
  }
}
?>