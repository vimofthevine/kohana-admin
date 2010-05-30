<!-- should be no reason to edit this file -->
<?php echo $header; ?>
<?php echo $menu; ?>

<div id="content" class="container_16 clearfix">
<?php $msg = Message::instance()->get();
	if ( ! empty($msg)): ?>
	<div class="grid_16">
	<?php echo $msg; ?>
	</div>
<?php endif; ?>

	<?php echo $content; ?>
</div>

<?php echo $footer; ?>
