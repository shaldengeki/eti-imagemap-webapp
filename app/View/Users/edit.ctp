<!-- File: /app/View/Users/edit.ctp -->

<h1>Edit User</h1>
<?php
echo $this->Form->create('User');
echo $this->Form->input('id', ['type' => 'hidden']);
echo $this->Form->input('name');
echo $this->Form->input('last_ip');
echo $this->Form->end('Save User');
?>