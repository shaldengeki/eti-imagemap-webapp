<?php
/**
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

$appDescription = __d('cake_dev', 'ImageGPS: Simple imagemap tracker');
?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
	<?php echo $this->Html->charset(); ?>
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="description" content="">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>
		<?php echo $appDescription ?>:
		<?php echo $title_for_layout; ?>
	</title>
	<?php
		echo $this->Html->meta('icon');

		echo $this->Html->css('site');

		echo $this->fetch('meta');
		echo $this->fetch('css');

		echo $this->Html->script('jquery.min');
		echo $this->Html->script('ZeroClipboard.min');
		echo $this->Html->script('bootstrap.min');
		echo $this->Html->script('site');
		echo $this->fetch('script');
	?>
</head>
<body>
	<!--[if lt IE 7]>
	    <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
	<![endif]-->
	<div class="navbar navbar-inverse navbar-fixed-top">
	  <div class="container">
	    <div class="navbar-header">
	      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
	        <span class="sr-only">Toggle navigation</span>
	        <span class="icon-bar"></span>
	        <span class="icon-bar"></span>
	        <span class="icon-bar"></span>
	      </button>
	      <a href="/" class="navbar-brand">ImageGPS</a>
	    </div>
	    <div class="collapse navbar-collapse">
	      <ul class="nav navbar-nav">
	        <li><a href="/">Images</a></li>
	        <li><a href="/users">Users</a></li>
	      </ul>
        <ul class='nav navbar-nav navbar-right'>
<?php
	if (isset($authUser)) {
?>
	        <li id='navbar-user' class='dropdown'>
	          <a href='#' class='dropdown-toggle' data-toggle='dropdown'><i class='glyphicon glyphicon-user glyphicon-white'></i><?php echo h($authUser['name']); ?><b class='caret'></b></a>
	          <ul class='dropdown-menu'>
	            <li><?php echo $this->Html->link('Profile', 
	                   [
	                     'controller' => 'users',
	                     'action' => 'view',
	                     $authUser['id']
	                  ]); ?></li>
	            <li><?php echo $this->Html->link('Settings', 
	                   [
	                     'controller' => 'users',
	                     'action' => 'edit',
	                     $authUser['id']
	                  ]); ?></li>
	            <li><?php echo $this->Html->link('Log Out', 
	                   [
	                     'controller' => 'users',
	                     'action' => 'logout'
	                  ]); ?></li>
	          </ul>
	         </li>
<?php
	} else {
?>
          <li>
            <?php echo $this->element('login_inline'); ?>
          </li>
<?php
  }
?>
        </ul>
	    </div>
	  </div>
	</div>
	<div class="container">
		<div id="content">
			<?php echo $this->Session->flash(); ?>
			<?php echo $this->Session->flash('auth'); ?>

			<?php echo $this->fetch('content'); ?>
		</div>
		<hr />
		<div id="footer">
			Work in progress!
		</div>
	</div>
	<?php echo $this->Js->writeBuffer(); ?>
</body>
</html>
