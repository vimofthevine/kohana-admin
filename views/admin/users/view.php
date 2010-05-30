<h2><?php echo HTML::chars($user->username) ?></h2>
<p>
	<strong><?php echo __('Username') ?> : </strong>
		<?php echo HTML::chars($user->username) ?><br />
	<strong><?php echo __('Email') ?> : </strong>
		<?php echo HTML::chars($user->email) ?><br />
	<strong><?php echo __('Role') ?> : </strong>
		<?php echo HTML::chars(ucfirst($user->role)) ?><br />
	<strong><?php echo __('Last Login') ?> : </strong>
		<?php echo date("D j M, g:i a", $user->last_login) ?>
</p>

