<?php

function error($code, $msg = '') {
	core_set_error_message($msg);
	redirect_to(HOST . '?action=' . ACTION_ERROR . '&type=' . ERROR_404);
}

function error_get_error($code) {
	foreach (get_errors() as $name => $value) {
		if ($value == $code) {
			return $name;
		}
	}
	return null;
}

?>