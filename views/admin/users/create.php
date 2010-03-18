<div class="grid_4">
<?php
	$url = Route::get('admin_main')->uri(array(
		'controller' => 'users',
		'action' => 'menu',
	));
	echo Request::factory($url)->execute()->response;
?>
</div>

<div class="grid_8">
<?php
	$url = Route::get('admin_main')->uri(array(
		'controller' => 'users',
		'action' => 'new',
	));
	echo Request::factory($url) ->execute()->response;
?>
</div>

<div class="grid_4">&nbsp;</div>
