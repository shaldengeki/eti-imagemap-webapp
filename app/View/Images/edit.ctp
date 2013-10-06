<!-- File: /app/View/Images/edit.ctp -->

<h1>Edit Image</h1>
<?php
echo $this->Form->create('Image');
echo $this->Form->input('id', ['type' => 'hidden']);
echo $this->Form->input('server');
echo $this->Form->input('hash');
echo $this->Form->input('type');
echo $this->Form->input('user_id');
echo $this->Form->input('created');
echo $this->Form->input('hits');
echo $this->Form->input('private');
echo $this->Form->end('Save Image');
?>