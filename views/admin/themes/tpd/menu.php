		<ul id="navigation">
<?php foreach($menu as $topic=>$item): ?>
			<li id="nav_<?php echo URL::title($topic) ?>">
				<?php echo empty($item[0])
					? '<span class="active">'.$topic.'</span>'
					: HTML::anchor($item[0], $topic); ?> 
			</li>
<?php endforeach; ?>
		</ul>
