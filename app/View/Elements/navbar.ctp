<div class="navbar navbar-inverse navbar-fixed-top">
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a href="/" class="navbar-brand">ImageGPS</a>
    </div>
    <div class="collapse navbar-collapse">
      <ul class="nav navbar-nav">
        <li><?php echo $this->Html->link('Images', [
                                         'controller' => 'images',
                                         'action' => 'index'
        ]); ?></li>
        <li><?php echo $this->Html->link('Tags', [
                                         'controller' => 'tags',
                                         'action' => 'index'
        ]); ?></li>
        <li><?php echo $this->Html->link('Users', [
                                         'controller' => 'users',
                                         'action' => 'index'
        ]); ?></li>
      </ul>
      <ul class='nav navbar-nav navbar-right'>
<?php
  if (isset($authUser)) {
?>
        <li id='navbar-user' class='dropdown'>
          <a href='#' class='dropdown-toggle' data-toggle='dropdown'><i class='glyphicon glyphicon-user glyphicon-white'></i><?php echo h($authUser['name']); ?><b class='caret'></b></a>
          <ul class='dropdown-menu'>
            <li><?php echo $this->Html->link('Profile', 
                   [
                     'controller' => 'users',
                     'action' => 'view',
                     $authUser['id']
                  ]); ?></li>
            <li><?php echo $this->Html->link('Settings', 
                   [
                     'controller' => 'users',
                     'action' => 'edit',
                     $authUser['id']
                  ]); ?></li>
            <li><?php echo $this->Html->link('Log Out', 
                   [
                     'controller' => 'users',
                     'action' => 'logout'
                  ]); ?></li>
          </ul>
         </li>
<?php
  } else {
?>
        <li>
          <?php echo $this->element('login_inline'); ?>
        </li>
<?php
  }
?>
      </ul>
    </div>
  </div>
</div>