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
			action_root($employee);
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

function action_root() {
	global $g_layout;
	global $g_pdo;
	$g_layout[DISPLAY_TYPES_CONGE] = TypeConge::getAll($g_pdo, 'ORDER BY label');
}

function action_signin() {
	core_set_state(STATE_SIGNIN);
}

function action_signout() {
	core_kill_session();
}

function action_authenticate() {
	global $g_pdo;

	$login = $_POST[FIELD_LOGIN];
	$pwd = $_POST[FIELD_PASSWORD];
	$employee = Employee::authenticate($g_pdo, $login, $pwd);

	if ($employee != null) {
		core_authenticate($employee);
// 		core_redirect_to_last_url();
		redirect_to(HOST);
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
		case TYPE_SUMMARY:
			core_set_state(STATE_SUMMARY);
			action_retrieve_solde($employee);
			action_retrieve_conge($employee);
			break;

		default:
			error(ERROR_WRONG_TYPE, 'Unknown given type: ' . $_GET[FIELD_TYPE]);
			break;
	}
}

function action_retrieve_solde($employee) {
	global $g_layout;
	$g_layout[DISPLAY_SOLDES] = $employee->getSoldes();
}

function action_retrieve_conge($employee) {
	global $g_layout;
	$g_layout[DISPLAY_CONGES] = $employee->getConges();
}

function action_administrate($employee) {
	global $g_layout;
	global $g_pdo;

	if (!$employee->isAdmin()) {
		core_set_state(STATE_NOT_ALLOWED);
		return;
	}

	$g_layout[DISPLAY_EMPLOYEES] = Employee::getAll($g_pdo, 'ORDER BY lastname');
	$g_layout[DISPLAY_USER_TYPES] = UserType::getAll($g_pdo, 'ORDER BY label');
	$g_layout[DISPLAY_TYPES_CONGE] = TypeConge::getAll($g_pdo, 'ORDER BY label');
	$g_layout[DISPLAY_SERVICES] = Service::getAll($g_pdo, 'ORDER BY label');
	core_set_state(STATE_ADMINISTRATE);
}

?>