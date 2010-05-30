<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
		<title>Administrative Control Panel</title>
		<?php echo HTML::style(Route::get('admin/media')->uri(array('file'=>'ThePixelDeveloper_Admin-Template/css/960.css')), array('media'=>'screen')) ?> 
		<?php echo HTML::style(Route::get('admin/media')->uri(array('file'=>'ThePixelDeveloper_Admin-Template/css/template.css')), array('media'=>'screen')) ?> 
		<?php echo HTML::style(Route::get('admin/media')->uri(array('file'=>'ThePixelDeveloper_Admin-Template/css/colour.css')), array('media'=>'screen')) ?> 
		<?php echo HTML::style(Route::get('admin/media')->uri(array('file'=>'css/admin/tpd_custom.css')), array('media'=>'screen')) ?> 
<?php foreach ($styles as $style => $media): ?>
		<?php echo HTML::style($style, array('media' => $media), TRUE), PHP_EOL ?>
<?php endforeach; ?>
<?php foreach ($scripts as $script): ?>
		<?php echo HTML::script($script, NULL, TRUE), PHP_EOL ?>
<?php endforeach; ?>
	</head>
	<body>

		<h1 id="head">Administrative Control Panel</h1>
