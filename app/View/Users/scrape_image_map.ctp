<div class="users form">
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