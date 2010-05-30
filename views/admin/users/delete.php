<h2><?php echo __('Delete :name?', array(':name' => $user->username)) ?></h2>
<p>
	Are you sure you want to delete <?php echo HTML::chars($user->username) ?>?
	This action cannot be undone.
</p>
<p class="submit">
<?php
	echo Form::open();
	echo Form::submit('yes', 'Yes');
	echo Form::submit('no', 'No');
	echo Form::close();
?>
</p>
