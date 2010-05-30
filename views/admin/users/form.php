<h2><?php echo __($legend) ?></h2>
<?php echo Form::open() ?> 

<?php foreach ($user->inputs(FALSE) as $field=>$input): ?>
<?php echo isset($errors[$field]) ? '<p class="error">'.$errors[$field].'</p>' : NULL ?> 
<p>
	<?php echo $user->label($field) ?> 
	<?php echo $input ?> 
</p>
<?php endforeach ?>

<p class="submit">
	<?php echo Form::submit('submit', $submit) ?> 
</p>
<?php echo Form::close() ?> 
