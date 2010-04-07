<div class="grid_5">
	<div class="box">
<?php
	$url = Route::get('admin_main')->uri(array('action' => 'user_info'));
	echo Request::factory($url)->execute()->response;
?>
	</div>
	<div class="box">
<?php
	$url = Route::get('admin_main')->uri(array('action' => 'system_info'));
	echo Request::factory($url)->execute()->response;
?>
	</div>
</div>

<div class="grid_6">
	<div class="box"
<?php
	$url = Route::get('admin_main')->uri(array('action' => 'updates'));
	echo Request::factory($url)->execute()->response;
?>
	</div>
</div>

<div class="grid_5">
	<div class="box"
<?php
	$url = Route::get('admin_main')->uri(array(
		'controller' => 'support',
		'action' => 'menu',
	));
	echo Request::factory($url)->execute()->response;
?>
	</div>
</div>

