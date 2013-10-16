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

  public function cleanName($name) {
    // sanitizes and standardizes a tag's name.
    // lowercases, replaces spaces with underscores, removes all non-alphanumerics.
    $name = strtolower($name);
    $name = preg_replace("/\ +/", '_', $name);
    $name = preg_replace("/[^a-zA-Z0-9\_]+/", '', $name);
    return $name;
  }

  public function parseQuery($tags) {
    // takes a space-separated list of tags, e.g. "gif reaction -nws"
    // returns an array of two arrays, allowed and denied tags
    $results = [
      'allow' => [],
      'deny' => []
    ];
    foreach (explode(" ", $tags) as $part) {
      if (substr($part, 0, 1) != "-") {
        $results['allow'][] = $this->cleanName($part);
      } else {
        $results['deny'][] = $this->cleanName(substr($part, 1));
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
    $tagName = $this->cleanName($tag);
    if (substr($tag, 0, 1) == "-") {
      $type = "deny";
      $tagName = $this->cleanName(substr($tag, 1));
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
      $queryParts[] = '+"'.$this->cleanName($allowed).'"';
    }
    foreach ($tags['deny'] as $denied) {
      $queryParts[] = '-"'.$this->cleanName($denied).'"';
    }
    return implode(" ", $queryParts);
  }

  public function incrementImages($tag) {
    if (!$tag) {
      return True;
    }

    return $this->updateAll(
      [$this->alias.'.image_count' => $this->alias.'.image_count+1'],
      [$this->alias.'.id' => $tag]
    );
  }

  public function decrementImages($tag) {
    if (!$tag) {
      return True;
    }

    return $this->updateAll(
      [$this->alias.'.image_count' => $this->alias.'.image_count-1'],
      [$this->alias.'.id' => $tag]
    );
  }

  public function getImages($tag) {
    // returns a list of all images tagged with this tag.
    $imageClass = ClassRegistry::init('Image');
    $tagSearch = $this->parseQuery($tag);
    return $imageClass->find('all', [
                              'conditions' => [
                                "MATCH(tags) AGAINST('".$this->sqlQuery($tagSearch)."' IN BOOLEAN MODE)"
                              ]
                             ]);
  }
}
?>