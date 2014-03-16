<?php

function layout_manage() {
	include LAYOUT_DIR . '/layout.php';
}

function layout_head() {
	include LAYOUT_DIR . '/head.php';
}

function layout_header() {
	include LAYOUT_DIR . '/header.php';
}

function layout_menu() {
	include LAYOUT_DIR . '/menu.php';
}

function layout_body() {
	global $g_layout;

	include $g_layout[FIELD_PAGE];
}

function layout_footer() {
	include LAYOUT_DIR . '/footer.php';
}

function layout_debug() {
	if (DEBUG_MODE) {
		include LAYOUT_DIR . '/debug.php';
	}
}

function layout_get_error_message() {
	global $g_layout;
	if (!is_null_or_empty($g_layout[FIELD_ERROR_MESSAGE])) {
		return $g_layout[FIELD_ERROR_MESSAGE];
	}
	return '';
}

function layout_get_title($is_for_head = false) {
	global $g_layout;

	if ($is_for_head) {
		return $g_layout[DISPLAY_HEAD_TITLE];
	}

	return $g_layout[DISPLAY_TITLE];
}

function layout_get_employee() {
	global $g_layout;
	return $g_layout[FIELD_EMPLOYEE];
}

function layout_get_employees() {
	global $g_layout;
	return $g_layout[DISPLAY_EMPLOYEES];
}

function layout_get_user_types() {
	global $g_layout;
	return $g_layout[DISPLAY_USER_TYPES];
}

function layout_get_conges() {
	global $g_layout;
	return $g_layout[DISPLAY_CONGES];
}

function layout_get_conge_types() {
	global $g_layout;
	return $g_layout[DISPLAY_TYPES_CONGE];
}

function layout_get_soldes() {
	global $g_layout;
	return $g_layout[DISPLAY_SOLDES];
}

function layout_get_services() {
	global $g_layout;
	return $g_layout[DISPLAY_SERVICES];
}

function layout_is_logged() {
	global $g_layout;
	return $g_layout[DISPLAY_IS_LOGGED];
}

function layout_get_copyright() {
	$start_year = 2014;
	$current_year = date('Y', time());
	if ($current_year == $start_year) {
		return $start_year;
	}
	return $start_year . ' - ' . $current_year;
}

?>