<div class='page-header'>
  <h1>Imagemap Scrape Request <small>Put your imagemap in the queue to be added to ImageGPS</small></h1>
</div>
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
        <label for='UserPermanent' class='col-lg-2 control-label'>Remember me</label>
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
</div>
