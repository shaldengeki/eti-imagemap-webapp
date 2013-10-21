<?php

class TopbarOption {
  public $name;
  public $link;
  public $suboptions;
  public $parent;

  public function __construct($name, array $options=Null) {
    $this->name = $name;
    if ($options) {
      if (isset($options['link'])) {
        $this->link = $options['link'];
      }
      if (isset($options['suboptions'])) {
        $this->suboptions = $options['suboptions'];
      }
      if (isset($options['parent'])) {
        $this->parent = $options['parent'];
      }
    }
  }
}

class TopbarHelper extends AppHelper {
  public $name = 'Topbar';
  public $helpers = ['Html'];
  public $options = [
    'id' => 'topbar',
    'class' => 'nav nav-tabs',
    'tag' => 'ul'
  ];
  public $controller = Null;

  public $blocks = [];

  public function setController(&$controller) { 
    $this->controller = $controller; 
  }

  /** 
   * Override the default options. 
   * 
   * @param $new_options Array with any of the following indexes: id, class, tag
   */ 
  public function options($new_options) { 
    $this->options = array_merge($this->options, $new_options); 
  } 

  public function getTopBar($url) {
    $view = ClassRegistry::getObject('view');

    $output = '<'.$this->options['tag'].' id="'.$this->options['id'].'" class="'.$this->options['class'].'">';

    foreach ($this->blocks as $block) {
      
    }

    $output .= '</'.$this->options['tag'].'>';
    return $output;
  }
}

?>