<!-- File: /app/View/Images/add.ctp -->
<div class='page-header'>
  <h1>Add Image</h1>
</div>
<div class='row'>
  <div class='col-md-6'>
    <?php echo $this->Form->create('Image', [
                              'class' => 'form-horizontal',
                              'role' => 'form'
                             ]); ?>
      <div class='form-group'>
        <label for='ImageServer' class='col-lg-2 control-label'>Server #</label>
        <?php echo $this->Form->input('server', [
                                      'type' => 'number',
                                      'min' => 1,
                                      'max' => 9,
                                      'class' => 'form-control',
                                      'placeholder' => 'e.g. for i4, 4',
                                      'div' => [
                                        'class' => 'col-lg-10'
                                      ],
                                      'label' => False
                                      ]); ?>
      </div>
      <div class='form-group'>
        <label for='ImageHash' class='col-lg-2 control-label'>Hash</label>
        <?php echo $this->Form->input('hash', [
                                      'class' => 'form-control',
                                      'placeholder' => 'MD5 hash',
                                      'div' => [
                                        'class' => 'col-lg-10'
                                      ],
                                      'label' => False
                                      ]); ?>
      </div>
      <div class='form-group'>
        <label for='ImageFilename' class='col-lg-2 control-label'>Filename</label>
        <?php echo $this->Form->input('filename', [
                                      'class' => 'form-control',
                                      'placeholder' => 'do not include extension',
                                      'div' => [
                                        'class' => 'col-lg-10'
                                      ],
                                      'label' => False
                                      ]); ?>
      </div>
      <div class='form-group'>
        <label for='ImageType' class='col-lg-2 control-label'>Type</label>
        <?php echo $this->Form->input('type', [
                                      'class' => 'form-control',
                                      'placeholder' => 'png/gif/jpg etc',
                                      'div' => [
                                        'class' => 'col-lg-10'
                                      ],
                                      'label' => False
                                      ]); ?>
      </div>
      <div class='form-group'>
        <label for='ImageTags' class='col-lg-2 control-label'>Tags</label>
        <?php echo $this->Form->input('tags', [
                                      'type' => 'textarea',
                                      'class' => 'form-control',
                                      'placeholder' => 'space-separated list',
                                      'div' => [
                                        'class' => 'col-lg-10'
                                      ],
                                      'label' => False
                                      ]); ?>
      </div>
      <div class='form-group'>
        <label for='ImagePrivate' class='col-lg-2 control-label'>Private</label>
        <?php echo $this->Form->input('private', [
                                      'class' => 'form-control',
                                      'type' => 'checkbox',
                                      'div' => [
                                        'class' => 'float-left col-lg-1'
                                      ],
                                      'label' => False
                                      ]); ?>
      </div>
      <div class='form-group'>
        <div class='col-lg-offset-2 col-lg-10'>
          <?php echo $this->Form->button('Add Image', [
                                          'type' => 'submit',
                                          'class' => 'btn btn-default'
                                         ]); ?>
        </div>
      </div>
    <?php echo $this->Form->end(); ?>
  </div>
  <div class='col-md-6 center-horizontal' id='image-preview'>
    <!-- TODO: js to preview image when information on left is provided / changed. -->
  </div>
</div>