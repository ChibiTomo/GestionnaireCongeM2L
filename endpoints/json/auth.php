<?php
header("Content-Type: text/json; charset=utf-8");

define('ROOT_DIR', dirname(dirname(dirname(__FILE__))));
require_once ROOT_DIR . '/include/includes.inc';

$error = false;
$result = array();

try {
	if (!defined('MYSQL_CONFIG_FILE_INCLUDED')) {
		throw new Exception('Sorry, the server is not yet installed.');
	}

	if (!isset($_POST['login']) || !isset($_POST['password'])) {
		throw new Exception('Password and/or login missing');
	}

	$employee = Employee::authenticate($g_pdo, $_POST['login'], $_POST['password']);

	if (!$employee) {
		throw new Exception('Wrong login/password.');
	}

	$json_emp = array();
	$json_emp['id'] = $employee->getValue('id');
	$json_emp['lastname'] = $employee->getValue('lastname');
	$json_emp['firstname'] = $employee->getValue('firstname');

	$json_emp["conges"] = array();
	foreach ($employee->getConges() as $conge) {
		$json_conge = array();
		$json_conge['id'] = $conge->getValue('id');
		$json_conge['start'] = $conge->getValue('debut_t');
		$json_conge['end'] = $conge->getValue('fin_t');

		$status = $conge->getType();
		$json_conge['type'] = $status->getValue('id');

		$status = $conge->getStatus();
		$json_conge['status'] = $status->getValue('id');

		$json_emp['conges'][] = $json_conge;
	}

	$json_emp["soldes"] = array();
	foreach ($employee->getSoldes() as $solde) {
		$json_solde = array();

		$year = $solde->getValue('annee');
		if (!isset($json_emp['soldes'][$year])) {
			$json_emp['soldes'][$year] = array();
		}

		$type = $solde->getValue('id_TypeConge');
		if (!isset($json_emp['soldes'][$year][$type])) {
			$json_emp['soldes'][$year][$type] = array();
		}
		$json_emp['soldes'][$year][$type] = $solde->getValue('solde');
	}

	$result['employee'] = $json_emp;

} catch (Exception $e) {
	$result['error'] = $e->getMessage();
}

echo json_encode($result);
?>