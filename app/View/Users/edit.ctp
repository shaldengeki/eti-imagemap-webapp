<!-- File: /app/View/Users/edit.ctp -->

<div class='page-header'>
  <h1>Account Settings</h1>
</div>
<ul class="nav nav-tabs" id="user-settings-tabs">
  <li class="active"><a href="#general" data-toggle="tab">General</a></li>
  <li><a href="#eti" data-toggle="tab">ETI</a></li>
</ul>
<div class='tab-content'>
  <div class='tab-pane fade in active' id='general'>
    <?php
      echo $this->Form->create('User');
      echo $this->Form->input('id', ['type' => 'hidden']);
      echo $this->Form->input('name');
      echo $this->Form->input('role', [
          'options' => ['admin' => 'Admin', 'user' => 'User']
      ]);
      echo $this->Form->end('Save Changes');
    ?>
  </div>
  <div class='tab-pane fade in' id='eti'>
    <?php echo $this->Form->create('User', [
                                   'action' => 'scrape_image_map'
    ]); ?>
        <fieldset>
            <legend><?php echo __('Please enter your ETI password.'); ?></legend>
            <?php echo $this->Form->input('password');
        ?>
        </fieldset>
    <?php echo $this->Form->end(__('Update Imagemap')); ?>
  </div>
</div>