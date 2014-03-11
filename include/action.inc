<?php

function action_manage() {
	debug_var('_SESSION');
	switch($_GET[FIELD_ACTION]) {
		case ACTION_RETRIEVE:
			$employee = core_need_authentication();
			action_retrieve($employee);
			break;

		case ACTION_ADMINISTRATE:
			$employee = core_need_authentication();
			action_administrate($employee);
			break;

		case ACTION_SIGNIN:
			action_signin();
			break;

		case ACTION_SIGNOUT:
			action_signout();
			break;

		case ACTION_AUTHENTICATE:
			action_authenticate();
			break;

		case ACTION_NONE:
			$employee = core_need_authentication();
			break;

		case ACTION_ERROR:
			action_error();
			break;

		// Action inconnue
		default:
			error(ERROR_WRONG_ACTION, 'Unknown given action: ' . $_GET[FIELD_ACTION]);
			break;
	}
	debug_var('_SESSION');
}

function action_error() {
	if (is_null_or_empty($_GET[FIELD_TYPE])) {
		$_GET[FIELD_TYPE] = ERROR_UNKNOWN;
	}

	debug($_GET[FIELD_TYPE]);

	switch($_GET[FIELD_TYPE]) {
		case STATE_ERROR_404:
			core_set_state(STATE_ERROR_404);
			break;
		case ERROR_UNKNOWN:
		default:
			core_set_state(STATE_ERROR);
			break;
	}
}

function action_signin() {
	core_set_state(STATE_SIGNIN);
}

function action_signout() {
	core_kill_session();
}

function action_authenticate() {
	global $g_pdo;
	debug_var('_POST');

	$login = $_POST[FIELD_LOGIN];
	$pwd = $_POST[FIELD_PASSWORD];
	debug_var('_SESSION');

	$employee = Employee::authenticate($g_pdo, $login, $pwd);

	if ($employee != null) {
		core_authenticate($employee);
//		core_set_state(STATE_DEFAULT);
//		return;
		debug_var('_SESSION');
		core_redirect_to_last_url();
	}
	core_set_error_message('Mauvais login et/ou mot de passe.');
	redirect_to('?action=' . ACTION_SIGNIN);
}

function action_retrieve($employee) {
	if (is_null_or_empty($_GET[FIELD_TYPE])) {
		error(ERROR_WRONG_TYPE, 'No type given.');
		return;
	}

	switch($_GET[FIELD_TYPE]) {
		default:
			error(ERROR_WRONG_TYPE, 'Unknown given type: ' . $_GET[FIELD_TYPE]);
			break;
	}
}

function action_administrate($employee) {
	core_set_state(STATE_ADMINISTRATE);
}

?>