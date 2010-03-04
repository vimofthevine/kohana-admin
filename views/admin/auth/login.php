<section>
	<h1>Login</h1>
<?php
	echo Form::open();

	echo empty($errors['username']) ? '' : '<h2>'.$errors['username'].'</h2>'.PHP_EOL;
	echo Form::label('username', 'Username');
	echo Form::input('username', $form['username']), PHP_EOL;
	echo '<br />', PHP_EOL;

	echo empty($errors['password']) ? '' : '<h2>'.$errors['password'].'</h2>'.PHP_EOL;
	echo Form::label('password', 'Password');
	echo Form::password('password', $form['password']), PHP_EOL;
	echo '<br />', PHP_EOL;

	echo empty($errors['remember']) ? '' : '<h2>'.$errors['remember'].'</h2>'.PHP_EOL;
	echo Form::label('remember', 'Remember Me');
	echo Form::checkbox('remember', 'true', (bool) $form['remember']);
	echo '<br />', PHP_EOL;

	echo Form::submit('submit', 'Login');
	echo Form::close();
?>
</section>
