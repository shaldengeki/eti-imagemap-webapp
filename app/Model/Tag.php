<?php
class Tag extends AppModel {
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

  public function parseQuery($tags) {
    // takes a space-separated list of tags, e.g. "gif reaction -nws"
    // returns an array of two arrays, allowed and denied tags
    $results = [
      'allow' => [],
      'deny' => []
    ];
    foreach (explode(" ", $tags) as $part) {
      if (substr($part, 0, 1) != "-") {
        $results['allow'][] = preg_replace('/[^A-Za-z0-9\_]+/', '_', $part);
      } else {
        $results['deny'][] = preg_replace('/[^A-Za-z0-9\_]+/', '_', substr($part, 1));
      }
    }
    return $results;
  }

  public function stringQuery($tags) {
    // takes an array of two arrays, allowed and denied tags
    // returns the single-string query represented by this array

    foreach ($tags['deny'] as $key=>$deniedTag) {
      $tags['deny'][$key] = "-".$deniedTag;
    }
    return trim(implode(" ", $tags['allow'])." ".implode(" ", $tags['deny']));
  }

  public function appendToQuery($tag, $query) {
    // takes a tag expression i.e. "nws" or "-gundam", and a string tag query
    // returns the string query represented by tag AND query
    $priorQuery = $this->parseQuery($query);
    $type = "allow";
    $tagName = $tag;
    if (substr($tag, 0, 1) == "-") {
      $type = "deny";
      $tagName = substr($tag, 1);
    }
    if (!in_array($tagName, $priorQuery[$type])) {
      $priorQuery[$type][] = $tagName;
    }
    return $this->stringQuery($priorQuery);
  }

  public function sqlQuery($tags) {
    // takes an array of allowed and denied tags
    // returns a string formatted for a MATCH() AGAINST() query.

    $queryParts = [];
    foreach ($tags['allow'] as $allowed) {
      $queryParts[] = '+"'.$allowed.'"';
    }
    foreach ($tags['deny'] as $denied) {
      $queryParts[] = '-"'.$denied.'"';
    }
    return implode(" ", $queryParts);
  }
}
?>