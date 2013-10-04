<!-- File: /app/View/Users/add.ctp -->

<h1>Add User</h1>
<?php
echo $this->Form->create('User');
echo $this->Form->input('id', ['type' => '']);
echo $this->Form->input('name');
echo $this->Form->input('role', [
    'options' => ['admin' => 'Admin', 'user' => 'User']
]);
echo $this->Form->end('Save User');
?>