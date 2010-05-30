		<ul id="navigation">
<?php foreach($links as $text=>$link): ?>
			<li id="nav_<?php echo URL::title($text) ?>">
				<?php echo empty($link)
					? '<span class="active">'.$text.'</span>'
					: HTML::anchor($link, $text); ?> 
			</li>
<?php endforeach; ?>
		</ul>
