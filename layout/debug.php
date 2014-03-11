<div id="debug">
	<div class="title">Debug</div>
	<div class="body">
	<?php
		global $g_layout;

		debug_var('g_layout');
		debug_var('_GET');
		debug_var('_SESSION');
		debug_var('_SERVER');
		debug(HOST);
		log_flush();
	?>
	</div>
</div>