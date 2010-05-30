<h2><?php echo __('Users List') ?></h2>

<?php if (count($users) == 0): ?>
<p>
	There are no users at this time
	(<?php echo HTML::anchor(Route::$current
		->uri(array('action'=>'new')), 'create one') ?>).
</p>
<?php else:
	// Create user list
	$grid = new Grid;
	$grid->column()->field('id')->title('ID');
	$grid->column('action')->title('Username')->text('{username}')
		->route(Route::get('admin'))->params(array('controller'=>'users', 'action'=>'view'));
	$grid->column()->field('role')->title('Role');
	$grid->column()->field('email')->title('Email');
	$grid->column('action')->title('Actions')->text('Edit')->class('edit')
		->route(Route::get('admin'))->params(array('controller'=>'users', 'action'=>'edit'));
	$grid->column('action')->title('')->text('Delete')->class('delete')
		->route(Route::get('admin'))->params(array('controller'=>'users', 'action'=>'delete'));
	$grid->data($users);

	echo $grid;

endif;

