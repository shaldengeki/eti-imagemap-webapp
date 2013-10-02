<!-- File: /app/View/Users/view.ctp -->

<h1><?php echo h($user['User']['name']); ?></h1>
<hr />
<ul>
  <li>Last IP: <?php echo $user['User']['last_ip']; ?></li>
</ul>

<h2>Images:</h2>
<?php
  foreach ($user['PublicImages'] as $image) {
    

  }
?>