<div class="grid_4">
<?php
	$url = Route::get('admin_main')->uri(array(
		'controller' => 'users',
		'action' => 'menu',
	));
	echo Request::factory($url)->execute()->response;
?>
</div>

<div class="grid_12">
<?php
	$url = Route::get('admin_main')->uri(array(
		'controller' => 'users',
		'action' => 'list',
	));
	echo Request::factory($url) ->execute()->response;
?>
</div>
