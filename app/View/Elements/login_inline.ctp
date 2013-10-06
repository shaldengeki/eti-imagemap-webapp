<?php echo $this->Form->create('User', [
                               'class' => 'form-inline',
                               'role' => 'form',
                               'action' => 'login'
]); ?>
  <div class="form-group">
    <label class="sr-only" for="username">Username</label>
    <?php echo $this->Form->input('username', [
                                  'class' => 'form-control',
                                  'id' => 'username',
                                  'placeholder' => 'ETI Username',
                                  'div' => False,
                                  'label' => False
    ]); ?>
  </div>
  <?php echo $this->Form->button("Sign In", [
                                 'class' => 'btn btn-default',
                                 'type' => 'submit',
                                 'div' => False,
                                 'label' => False
                                 ]); ?>
  <?php echo $this->Html->link("Sign Up", [
                                'controller' => 'users',
                                'action' => 'add',
                               ], [
                                'class' => 'btn btn-success'
                               ]); ?>
<?php echo $this->Form->end(); ?>