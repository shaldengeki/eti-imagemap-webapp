<!-- File: /app/View/Images/add.ctp -->

<h1>Add Image</h1>
<?php
echo $this->Form->create('Image');
echo $this->Form->input('server');
echo $this->Form->input('hash');
echo $this->Form->input('type');
echo $this->Form->input('user_id');
echo $this->Form->input('added_on');
echo $this->Form->input('hits');
echo $this->Form->input('private');
echo $this->Form->end('Save Image');
?>