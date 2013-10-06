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
    <div class='row'>
      <div class='col-md-6'>
        <?php echo $this->Form->create('User', [
                                  'class' => 'form-horizontal',
                                  'role' => 'form'
                                 ]); ?>
          <?php echo $this->Form->input('id', [
                                        'type' => 'hidden',
                                        'div' => False,
                                        'label' => False
                                        ]); ?>
          <div class='form-group'>
            <label for='UserName' class='col-lg-2 control-label'>Username</label>
            <?php echo $this->Form->input('name', [
                                          'type' => '',
                                          'class' => 'form-control',
                                          'disabled' => 'disabled',
                                          'placeholder' => 'e.g. shaldengeki',
                                          'div' => [
                                            'class' => 'col-lg-10'
                                          ],
                                          'label' => False
                                          ]); ?>
          </div>
          <div class='form-group'>
            <div class='col-lg-offset-2 col-lg-10'>
              <?php echo $this->Form->button('Save Changes', [
                                              'type' => 'submit',
                                              'class' => 'btn btn-default'
                                             ]); ?>
            </div>
          </div>
        <?php echo $this->Form->end(); ?>
      </div>
    </div>
  </div>
  <div class='tab-pane fade in' id='eti'>
    <div class='row'>
      <div class='col-md-6'>
        <?php echo $this->Form->create('User', [
                                  'class' => 'form-horizontal',
                                  'role' => 'form',
                                  'action' => 'scrape_image_map'
                                 ]); ?>
          <div class='form-group'>
            <label for='UserPassword' class='col-lg-2 control-label'>Password</label>
            <?php echo $this->Form->input('password', [
                                          'type' => 'password',
                                          'class' => 'form-control',
                                          'placeholder' => 'ETI password',
                                          'div' => [
                                            'class' => 'col-lg-10'
                                          ],
                                          'label' => False
                                          ]); ?>
          </div>
          <div class='form-group'>
            <div class='col-lg-offset-2 col-lg-10'>
              <?php echo $this->Form->button('Update Imagemap', [
                                              'type' => 'submit',
                                              'class' => 'btn btn-default'
                                             ]); ?>
            </div>
          </div>
        <?php echo $this->Form->end(); ?>
      </div>
    </div>
  </div>
</div>