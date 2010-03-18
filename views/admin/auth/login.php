<div class="grid_4">&nbsp;</div>
<div class="grid_8">
<?php
	$url = Route::get('admin_auth')->uri(array('action' => 'login'));
	echo Request::factory($url)->execute()->response;
?>
</div>
<div class="grid_4">&nbsp;</div>
