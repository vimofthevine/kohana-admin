<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<title>Admin Kyle Treubig's Website</title>
	<!--[if IE]>
	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	<style type="text/css">
		body { margin:0px; padding:0px; }
		header { background:#ccc; display:block; height:40px; margin:0px; padding:0px; width:100%; }
		header h1 { float:left; font-size:32px; margin:0px 10px 0px 10px; padding:0px; }
		header h2 { font-size:24px; line-height:40px; margin:0px; padding:0px; }
		nav { background:#ddd; display:block; float:left; margin:0px; padding:0px; width:260px; }
		nav h1 { font-size:24px; margin:0px; padding:5px 5px 0px; }
		nav ul { list-style:none outside; margin:0px; padding:10px 0px; }
		nav ul li { background:#eee; border-left:10px solid #000; border-right:10px solid #000; margin:5px 10px 0px; padding:0px; }
		nav ul li:first-child { margin-top:0px; }
		nav ul li:hover { background:#ddd; border-color:#555; }
		nav ul li a { margin:0px 10px; text-decoration:none; }
		section { display:block; float:left; width:680px; }
		footer { background:#ccc; clear:both; display:block; height:40px; line-height:40px; text-align:center; width:100%; }
		table.sortable { width:100%; }
		table.sortable thead tr { background:#ddd; }
		table.sortable tbody tr:nth-child(even) { background:#eee; }
	</style>
<?php foreach ($styles as $style => $media) echo HTML::style($style, array('media' => $media), TRUE), "\n" ?>
<?php foreach ($scripts as $script) echo HTML::script($script, NULL, TRUE), "\n" ?>
</head>
<?php
	echo '<body';
	echo isset($bodyid) ? ' id="'.$bodyid.'"' : '';
	echo isset($bodyclass) ? ' class="'.$bodyclass.'"' : '';
	echo '>',PHP_EOL;
?>

<header>
	<hgroup>
		<h1>Control Panel</h1>
		<h2>for kyletreubig.com</h2>
	</hgroup>
</header>

<?php echo Message::instance()->get() ?>
