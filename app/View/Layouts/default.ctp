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

$appDescription = __d('cake_dev', 'ImageGPS');
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
		echo $this->Html->css('jquery-ui-1.10.3.custom.min');

		echo $this->Html->script('jquery.min');
		echo $this->Html->script('//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js');
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
	<div class="row" id="logo">
		<div class='col-md-12'>
			<h1><?php echo $this->Html->link("ImageGPS", [
			                                 'controller' => 'images',
			                                 'action' => 'index'
			]); ?></h1>
		</div>
	</div>
	<?php //echo $this->Topbar->getTopbar(); ?>
	<?php echo $this->element('topbar'); ?>
	<div class="container">
		<div class="row">
<?php 
	if ($this->fetch('sidebar')) {
?>
			<div id="sidebar" class="col-md-2">
				<?php echo $this->fetch('sidebar'); ?>
			</div>
			<div id="content" class="col-md-10">
<?php
	} else {
?>
			<div id="content" class="col-md-12">
<?php
	}
?>
				<?php echo $this->Session->flash(); ?>
				<?php echo $this->Session->flash('auth'); ?>

				<?php echo $this->element('scrape_request_queue_banner'); ?>

				<?php echo $this->fetch('content'); ?>
			</div>
		</div>
		<hr />
		<div id="footer">
			Work in progress!
		</div>
	</div>
	<?php echo $this->Js->writeBuffer(); ?>
</body>
</html>
