<div class="box">
	<h2>User Management</h2>
	<p>
		<ul>
			<li><?php echo HTML::anchor(Route::get('admin_main')->uri(array('controller'=>'users','action'=>'list')), 'User List') ?></li>
			<li><?php echo HTML::anchor(Route::get('admin_main')->uri(array('controller'=>'users','action'=>'new')), 'Add User') ?></li>
			<li><?php echo HTML::anchor(Route::get('admin_main')->uri(array('controller'=>'users','action'=>'profile')), 'Edit Profile') ?></li>
		</ul>
	</p>
</div>
