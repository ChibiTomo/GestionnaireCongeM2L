<?php
	global $g_display;

	$error = get_error($_GET['type']) . ' ' . $g_layout[FIELD_ERROR_MESSAGE];

?>
Page not found <?php echo $error;?>