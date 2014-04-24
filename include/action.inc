<?php

function action_manage() {
	debug_var('_SESSION');
	switch($_GET[FIELD_ACTION]) {
		case ACTION_CREATE:
			action_create();
			break;

		case ACTION_RETRIEVE:
			action_retrieve();
			break;

		case ACTION_UPDATE:
			action_update();
			break;

		case ACTION_DELETE:
			action_delete();
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

function action_create() {
	if (is_null_or_empty($_GET[FIELD_TYPE])) {
		error(ERROR_WRONG_TYPE, 'No type given.');
		return;
	}

	$employee = core_need_authentication();

	switch($_GET[FIELD_TYPE]) {
		case TYPE_CONGE:
			action_create_conge($employee);
			// send_mail
			break;

		case TYPE_SERVICE:
			if (!$employee->isAdmin()) {
				core_set_state(STATE_NOT_ALLOWED);
				return;
			}
			action_create_service();
			break;

		case TYPE_TYPE_CONGE:
			if (!$employee->isAdmin()) {
				core_set_state(STATE_NOT_ALLOWED);
				return;
			}
			action_create_type_conge();
			break;

		case TYPE_EMPLOYEE:
			if (!$employee->isAdmin()) {
				core_set_state(STATE_NOT_ALLOWED);
				return;
			}
			action_create_employee();
			// send_mail
			break;

		default:
			error(ERROR_WRONG_TYPE, 'Unknown given type: ' . $_GET[FIELD_TYPE]);
			break;
	}
	core_redirect_to_last_url();
}

function action_retrieve() {
	if (is_null_or_empty($_GET[FIELD_TYPE])) {
		error(ERROR_WRONG_TYPE, 'No type given.');
		return;
	}

	switch($_GET[FIELD_TYPE]) {
		case TYPE_PASSWORD:
			core_set_state(STATE_RETRIEVE_PASSWORD);
			break;

		case TYPE_SUMMARY:
			$employee = core_need_authentication();
			core_set_state(STATE_SUMMARY);
			action_retrieve_solde($employee);
			action_retrieve_conge($employee);
			break;

		case TYPE_MY_DEMANDS:
			$employee = core_need_authentication();
			core_set_state(STATE_MY_DEMANDS);
			action_retrieve_my_demands($employee);
			break;

		case TYPE_DEMANDS:
			$employee = core_need_authentication();
			core_set_state(STATE_DEMANDS);
			action_retrieve_demands($employee);
			break;

		default:
			error(ERROR_WRONG_TYPE, 'Unknown given type: ' . $_GET[FIELD_TYPE]);
			break;
	}
}

function action_update() {
	if (is_null_or_empty($_GET[FIELD_TYPE])) {
		error(ERROR_WRONG_TYPE, 'No type given.');
		return;
	}

	if (!isset($_POST[FIELD_ID])) {
		error(ERROR_NO_ID, 'No id given.');
		return;
	}

	$id = $_POST[FIELD_ID];
	$employee = core_need_authentication();

	switch($_GET[FIELD_TYPE]) {
		case TYPE_CONGE:
			action_update_conge($id);
			// send_mail
			break;

		case TYPE_EMPLOYEE:
			action_update_employee();
			// send_mail
			break;

		default:
			error(ERROR_WRONG_TYPE, 'Unknown given type: ' . $_GET[FIELD_TYPE]);
			break;
	}

	core_redirect_to_last_url();
}

function action_delete() {
	debug($_GET);
	if (is_null_or_empty($_GET[FIELD_TYPE])) {
		error(ERROR_WRONG_TYPE, 'DELETE: No type given.');
		return;
	}

	if (!isset($_POST[FIELD_ID])) {
		error(ERROR_NO_ID, 'DELETE: No id given.');
		return;
	}

	$id = $_POST[FIELD_ID];
	$employee = core_need_authentication();

	switch($_GET[FIELD_TYPE]) {
		case TYPE_CONGE:
			$conge = new Conge($employee->getPDO(), intval($id));
			$year = date('Y', $conge->getDebut());
			$employee->increment_solde($year, $conge->getType(), $conge->getQuantity());
			$conge->delete();
			// send_mail
			break;

		case TYPE_EMPLOYEE:
			if (!$employee->isAdmin()) {
				core_set_state(STATE_NOT_ALLOWED);
				return;
			}
			$emp = new Employee($employee->getPDO(), intval($id));
			$emp->delete();
			// send_mail
			break;

		case TYPE_SERVICE:
			if (!$employee->isAdmin()) {
				core_set_state(STATE_NOT_ALLOWED);
				return;
			}
			$service = new Service($employee->getPDO(), intval($id));
			$service->delete();
			break;

		case TYPE_TYPE_CONGE:
			if (!$employee->isAdmin()) {
				core_set_state(STATE_NOT_ALLOWED);
				return;
			}
			$type = new TypeConge($employee->getPDO(), intval($id));
			$type->delete();
			break;

		default:
			error(ERROR_WRONG_TYPE, 'Unknown given type: ' . $_GET[FIELD_TYPE]);
			break;
	}

	core_redirect_to_last_url();
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

function action_create_conge($employee) {
	global $g_pdo;

	$conge = new Conge($g_pdo);
	$conge->setEmployee($employee);
	$id_status = CONGE_STATUS_PENDING;
	if ($employee->getSuperieur() == null) {
		$id_status = CONGE_STATUS_ACCEPTED;
	}
	$conge->setStatus(new StatusConge($g_pdo, $id_status));
	$type = new TypeConge($g_pdo, intval($_POST[FIELD_TYPE_CONGE]));
	$conge->setType($type);
	$conge->setDebut($_POST[FIELD_START_T]);
	$conge->setFin($_POST[FIELD_END_T]);

	$year = date('Y', $_POST[FIELD_START_T]);

	if (!$employee->hasEnought($year, $type, $conge->getQuantity())) {
		core_set_error_message('Il ne vous reste pas suffisement de congé.');
	} else {
		$conge->store();
		$employee->decrement_solde($year, $conge->getType(), $conge->getQuantity());
	}
}

function action_create_service() {
	global $g_pdo;

	$service = new Service($g_pdo, $_POST['label']);
	$service->store();
}

function action_create_type_conge() {
	global $g_pdo;

	$conge = new TypeConge($g_pdo, $_POST['label']);
	$conge->store();

	$conge = new TypeConge($g_pdo, $_POST['label'], true);

	$qty = $_POST['base'];

	$values = array(
			'id_TypeConge' => $conge->getValue('id'),
			'annee' => intval(date('Y', time())),
	);
	foreach (Employee::getAll($g_pdo) as $emp) {
		$values['id_Employee'] = $emp->getValue('id');
		$solde = new Solde($g_pdo, $values);
		$solde->setAmount($qty);
		$solde->store();
	}
}

function action_create_employee() {
	global $g_pdo;

	if ($_POST['id_Superieur'] == -1) {
		$_POST['id_Superieur'] = null;
	}
	$_POST['id_UserType'] = intval($_POST['id_UserType']);
	$_POST['id_Superieur'] = intval($_POST['id_Superieur']);
	$values = $_POST;
	unset($values['services']);
	$emp = new Employee($g_pdo, $values);

	foreach ($_POST['services'] as $service) {
		$emp->addService(intval($service));
	}

	if ($emp->exists()) {
		core_set_error_message('Le login/email existe déjà .');
		return;
	}

	$emp->store();
}

function action_retrieve_solde($employee) {
	global $g_layout;
	$g_layout[DISPLAY_SOLDES] = $employee->getSoldes();
}

function action_retrieve_conge($employee) {
	global $g_layout;
	$g_layout[DISPLAY_CONGES] = $employee->getConges();
}

function action_retrieve_demands($employee) {
	global $g_layout;
	if (!$employee->hasSubordinate()) {
		core_set_state(STATE_NOT_ALLOWED);
		return;
	}

	$now = time();
	$g_layout[DISPLAY_CONGES] = array();
	foreach ($employee->getSubordinates() as $emp) {
		foreach ($emp->getConges() as $conge) {
			if ($conge->getStatus()->is(CONGE_STATUS_PENDING) &&
					$conge->getDebut() > $now && $conge->getFin() > $now) {
				$g_layout[DISPLAY_CONGES][] = $conge;
			}
		}
	}
}

function action_retrieve_my_demands($employee) {
	global $g_pdo;
	global $g_layout;

	$g_layout[DISPLAY_CONGES] = array();
	$now = time();
	foreach ($employee->getConges() as $conge) {
		$start = $conge->getDebut();
		$end = $conge->getFin();
		if ($start > $now || $end > $now) {
			$g_layout[DISPLAY_CONGES][] = $conge;
		}
	}
}

function action_update_conge($id) {
	global $g_pdo;

	if (!isset($_POST[FIELD_STATUS])) {
		error(ERROR_NO_INFO, 'No status given.');
		return;
	}

	$status = $_POST[FIELD_STATUS];
	$conge = new Conge($g_pdo, intval($id));
	$conge->setStatus(new StatusConge($g_pdo, intval($status)));
	$conge->update();

	if ($status == CONGE_STATUS_REFUSED) {
		$emp = $conge->getEmployee();
		$year = date('Y', $conge->getDebut());
		$emp->increment_solde($year, $conge->getType(), $conge->getQuantity());
	}
}

function action_update_employee() {
	global $g_pdo;

	if ($_POST['id_Superieur'] == -1) {
		$_POST['id_Superieur'] = null;
	}
	$values = $_POST;
	unset($values['services']);
	$emp = new Employee($g_pdo, $values);

	foreach ($_POST['services'] as $service) {
		$emp->addService(intval($service));
	}

	$emp->update();
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