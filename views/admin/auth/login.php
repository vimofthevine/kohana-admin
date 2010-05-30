<h2>Login</h2>
<?php echo Form::open(); ?>

<?php echo isset($errors['username']) ? '<p class="error">'.$errors['username'].'</p>' : NULL ?>
<p>
	<?php echo Form::label('username', 'Username'); ?> 
	<?php echo Form::input('username', $post['username']); ?> 
</p>

<?php echo isset($errors['password']) ? '<p class="error">'.$errors['password'].'</p>'.PHP_EOL : NULL ?> 
<p>
	<?php echo Form::label('password', 'Password'); ?> 
	<?php echo Form::password('password', $post['password']), PHP_EOL; ?> 
</p>

<?php echo isset($errors['remember']) ? '<p class="error">'.$errors['remember'].'</p>'.PHP_EOL : NULL ?> 
<p>
	<?php echo Form::label('remember', 'Remember Me'); ?> 
	<?php echo Form::checkbox('remember', 'true'); ?> 
</p>

<p class="submit">
	<?php echo Form::submit('submit', 'Login'); ?> 
</p>

<?php echo Form::close(); ?> 
