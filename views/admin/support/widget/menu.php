<div class="box">
	<h2>Support</h2>
	<p>
		<ul>
			<li><?php echo HTML::anchor(Route::get('admin_main')->uri(array('controller'=>'support','action'=>'bug')), 'Create Bug Report') ?></li>
			<li><?php echo HTML::anchor(Route::get('admin_main')->uri(array('controller'=>'support','action'=>'contact')), 'Ask for Help') ?></li>
		</ul>
	</p>
</div>
