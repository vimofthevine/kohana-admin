<h2>Login</h2>
<?php echo Form::open(); ?>

<?php echo empty($errors['username']) ? '' : '<p class="error">'.$errors['username'].'</p>'; ?> 
<p>
	<?php echo Form::label('username', 'Username'); ?> 
	<?php echo Form::input('username', $form['username']); ?> 
</p>

<?php echo empty($errors['password']) ? '' : '<p class="error">'.$errors['password'].'</p>'.PHP_EOL; ?> 
<p>
	<?php echo Form::label('password', 'Password'); ?> 
	<?php echo Form::password('password', $form['password']), PHP_EOL; ?> 
</p>

<?php echo empty($errors['remember']) ? '' : '<p class="error">'.$errors['remember'].'</p>'.PHP_EOL; ?> 
<p>
	<?php echo Form::label('remember', 'Remember Me'); ?> 
	<?php echo Form::checkbox('remember', 'true', (bool) $form['remember']); ?> 
</p>

<p class="submit">
	<?php echo Form::submit('submit', 'Login'); ?> 
</p>

<?php echo Form::close(); ?> 
