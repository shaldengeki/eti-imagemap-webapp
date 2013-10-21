<?php
  /* 
    TODO: refactor this into TopbarHelper

    topbar tabs in $tabs.
    keys are the tab's displayed text,
    value is an array of options:
      'link' => an array passed to HtmlHelper->link to generate a link.
      'inactive' => an array passed to HtmlHelper->tag to generate a tab when the tab is inactive.
      'active' =>  an array passed to HtmlHelper->tag to generate a tab wen the tab is active.
      'submenu' => an array of submenu text => link arrays that fall under this menu.

  */

  $tabs = [];
  $currentMenu = Null;

  // user auth menu.
  if (isset($authUser)) {
    $tabs[$authUser['name']] = [
      'link' => [
        'controller' => 'users',
        'action' => 'view',
        $authUser['id']
      ],
      'submenu' => [
        'Settings' => [
          'controller' => 'users',
          'action' => 'edit',
          $authUser['id']
        ]
      ]
    ];
  } else {
    $tabs['Sign In'] = [
      'link' => [
        'controller' => 'users',
        'action' => 'login'
      ],
      'active' => ['class' => 'login active'],
      'inactive' => ['class' => 'login'],
      'submenu' => [
        'Sign Up' => [
          'controller' => 'users',
          'action' => 'add'
        ]
      ]
    ];
  }

  // images menu.
  $tabs['Images'] = [
    'link' => [
      'controller' => 'images',
      'action' => 'index'
    ],
    'submenu' => [
      'Listing' => [
        'controller' => 'images',
        'action' => 'index'
      ],
      'Add' => [
        'controller' => 'images',
        'action' => 'add'
      ]
    ]
  ];

  // tags menu.
  $tabs['Tags'] = [
    'link' => [
      'controller' => 'tags',
      'action' => 'index'
    ],
    'submenu' => [
      'Listing' => [
        'controller' => 'tags',
        'action' => 'index'
      ]
    ]
  ];
?>
<div id="topbar" class="row">
  <div class="col-md-12">
    <ul class="main nav nav-tabs">
<?php
  foreach ($tabs as $text => $tab) {
    if ($this->params['controller'] == $tab['link']['controller']) {
      $currentMenu = $text;
      if (isset($tab['active'])) {
?>
      <?php echo $this->Html->tag('li', Null, $tab['active']); ?>
<?php        
      } else {
?>
      <li class="active">
<?php
      }
    } else {
      if (isset($tab['inactive'])) {
?>
      <?php echo $this->Html->tag('li', Null, $tab['inactive']); ?>
<?php
      } else {
?>
      <li>
<?php
      }
    }
?>
        <?php echo $this->Html->link($text, $tab['link']); ?></li>
<?php
  }
?>
    </ul>
<?php
  if ($currentMenu !== Null && isset($tabs[$currentMenu]['submenu'])) {
    $submenu = $tabs[$currentMenu]['submenu'];
?>
    <ul class="sub">
<?php
    foreach ($submenu as $text => $url) {
?>
      <li><?php echo $this->Html->link($text, $url); ?></li>
<?php
    }
?>
    </ul>
<?php
  }
?>
  </div>
</div>