<div class="users form">
<?php echo $this->Session->flash('auth'); ?>
<?php echo $this->Form->create('User'); ?>
    <fieldset>
        <legend><?php echo __('Please enter your ETI username.'); ?></legend>
        <?php echo $this->Form->input('username');
    ?>
    </fieldset>
<?php echo $this->Form->end(__('Login')); ?>
</div>