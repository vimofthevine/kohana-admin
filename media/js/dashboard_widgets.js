glow.ready(function() {
		new glow.widgets.Sortable(
			'#content .grid_5, #content .grid_6',
			{
				draggableOptions : {
					handle : 'h2'
				}
			}
		);
	});
