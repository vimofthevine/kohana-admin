<h2><?php echo ucfirst($user->username) ?></h2>
<p>
	<strong>Last Signed In : </strong> <?php echo date("D j M, g:i a", $user->last_login) ?><br />
	<strong>IP Address : </strong> <?php echo $ip ?>
</p>

