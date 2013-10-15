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
            <label for='UserPermanent' class='col-lg-2 control-label'>Sync imagemap</label>
            <?php echo $this->Form->input('permanent', [
                                          'class' => 'form-control',
                                          'type' => 'checkbox',
                                          'div' => [
                                            'class' => 'col-lg-1'
                                          ],
                                          'label' => False
                                          ]); ?>
          </div>
          <div class='form-group'>
            <label for='UserPrivate' class='col-lg-2 control-label'>Privatize images</label>
            <?php echo $this->Form->input('private', [
                                          'class' => 'form-control',
                                          'type' => 'checkbox',
                                          'div' => [
                                            'class' => 'col-lg-1'
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
      <div class='col-md-6'>
        <h2>Warning!</h2>
        <p>If you select the "sync imagemap" option, ImageGPS will sign in to ETI as you periodically via the mobile website and sync your imagemap. This necessitates <strong>saving your ETI password</strong>, so <strong>only do this if you trust us to not steal your password!</strong> Otherwise, we strongly recommend you manually-sync your imagemap; your password is permanently cleared after each manual sync. You can also freely use the mobile website without fear of being kicked off by ImageGPS this way.</p>
        <p>The "privatize images" option causes all of the images synced over to be set to private status, which means other users can't see them until you make them public. This is a good option if you're syncing over your imagemap for the first time, or if you know you've got some sensitive information on your imagemap that you don't want others to see.</p>
      </div>
    </div>
  </div>
</div>