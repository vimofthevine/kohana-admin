<section>
    <h1><?php echo $legend; ?></h1>
<?php

	echo Form::open();

	foreach ($user->inputs(FALSE) as $field=>$input)
	{
		echo isset($errors[$field]) ? '<h2>'.$errors[$field].'</h2>' : '';
		echo $user->label($field),PHP_EOL;
		echo $input,PHP_EOL;
		echo '<br />',PHP_EOL;
	}

	echo Form::submit('submit', $submit);
	echo Form::close();

?>
</section>
