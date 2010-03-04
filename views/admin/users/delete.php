<section>
	<h1>Delete <?php echo $user->username ?>?</h1>
	<p>
		Are you sure you want to delete <?php echo $user->username ?>?
		This action cannot be undone.
	</p>
<?php
	echo Form::open();
	echo Form::submit('yes', 'Yes');
	echo Form::submit('no', 'No');
	echo Form::close();
?>
</section>
