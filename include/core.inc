<?php

function core_manage_required_fields() {
	if (core_session_is_timedout()) {
		core_kill_session();
	}

	if (is_null_or_empty($_SESSION[FIELD_STATE])) {
		core_set_state(STATE_DEFAULT);
	}

	if (is_null_or_empty($_GET[FIELD_ACTION])) {
		core_set_state(STATE_DEFAULT);
		core_set_action(ACTION_NONE);
	}

	$_SESSION[FIELD_SESSION_BORN_T] = time();

	if (!is_null_or_empty($_SESSION[FIELD_ERROR_MESSAGE])) {
		core_set_error_message($_SESSION[FIELD_ERROR_MESSAGE]);
	}
}

function core_session_is_timedout() {
	if (!core_is_logged()) {
		return false;
	}

	if (!defined('SESSION_TTL') || SESSION_TTL < 0) {
		return false;
	}

	if (is_null_or_empty($_SESSION[FIELD_SESSION_BORN_T])) {
		return false;
	}

	return (time() - $_SESSION[FIELD_SESSION_BORN_T]) > SESSION_TTL;
}

function core_set_action($action) {
	if (core_action_exists($action)) {
		$_GET[FIELD_ACTION] = $action;
	} else {
		$_GET[FIELD_ACTION] = ACTION_ERROR;
	}
}

function core_set_state($state) {
	debug('Setting state to: ' . $state);
	$_SESSION[FIELD_STATE] = STATE_ERROR_WRONG_STATE;
	if (core_state_exists($state)) {
		$_SESSION[FIELD_STATE] = $state;
	}
}

function core_state_exists($state) {
	$states = get_states();
	return in_array($state, $states);
}

function core_action_exists($action) {
	$actions = get_actions();
	return in_array($action, $actions);
}

function core_save_previous_url() {
	global $g_action_save_url_black_list;

	debug_var('_SESSION');
	if (!in_array($_GET[FIELD_ACTION], $g_action_save_url_black_list)) {
		$_SESSION[FIELD_PREVIOUS_URL] = HOST."?".$_SERVER['QUERY_STRING'];
		debug('Saved previous URL: ' . $_SESSION[FIELD_PREVIOUS_URL]);
	}
}

function core_redirect_to_last_url() {
	if (is_null_or_empty($_SESSION[FIELD_PREVIOUS_URL])) {
		redirect_to(HOST);
	}
	redirect_to($_SESSION[FIELD_PREVIOUS_URL]);
}

function core_kill_session() {
	debug('Kill session');
	session_destroy();
	core_redirect_to_last_url();
}

function core_need_authentication() {
	global $g_pdo;

	debug('core_need_authentication');
	if (!core_is_logged()) {
		redirect_to(HOST . '?action=' . ACTION_SIGNIN);
	}
	return new Employee($g_pdo, $_SESSION[FIELD_LOGGED_USER_ID]);
}

function core_is_logged() {
	return !is_null_or_empty($_SESSION[FIELD_LOGGED_USER_ID]);
}

function core_authenticate($employee) {
	debug($employee);

	$_SESSION[FIELD_LOGGED_USER_ID] = $employee->getValue('id');
}

function core_manage_page() {
	global $g_layout;
	global $g_pdo;

	$page_name = $_SESSION[FIELD_STATE];
	if ($_SESSION[FIELD_STATE] == STATE_NOT_ALLOWED) {
		$page_name = PAGE_ERROR;
		error(STATE_NOT_ALLOWED, 'Vous n\'êtes pas authorisés à effectuer cette action.');
	}

	debug($page_name);
	$page = LAYOUT_DIR . '/' . $page_name . '.php';

	if (!file_exists($page)) {
		debug('file does not exists');
		error(ERROR_404, $page_name . '.php does not exists.');
	}

	debug('page_name=' . $page_name);
	$g_layout[DISPLAY_IS_LOGGED] = core_is_logged();
	$g_layout[DISPLAY_PAGE] = $page;
	$g_layout[DISPLAY_TITLE] = core_get_title($page_name);
	$g_layout[DISPLAY_HEAD_TITLE] = core_get_head_title($page_name);
	$g_layout[DISPLAY_EMPLOYEE] = new Employee($g_pdo, $_SESSION[FIELD_LOGGED_USER_ID]);
	debug_var('g_layout');
}

function core_get_title($page_name) {
	global $g_titles;

	if (!isset($g_titles[$page_name])) {
		return '';
	}
	return $g_titles[$page_name];
}

function core_get_head_title($page_name) {
	global $g_head_titles;

	if (!isset($g_head_titles[$page_name])) {
		return '';
	}
	return $g_head_titles[$page_name];
}

function core_set_error_message($msg) {
	global $g_layout;
	$g_layout[FIELD_ERROR_MESSAGE] = $msg;

	$_SESSION[FIELD_ERROR_MESSAGE] = $msg;
}

function core_clean_error_message() {
	unset($_SESSION[FIELD_ERROR_MESSAGE]);
}

?>