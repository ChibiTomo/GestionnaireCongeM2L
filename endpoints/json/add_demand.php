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

	if (!isset($_POST['start_t']) || !isset($_POST['end_t'])
			|| !isset($_POST['type']) || !isset($_POST['employee_id'])) {
		throw new Exception('Start/end time and/or type of conge and/or id of employee missing.');
	}

	$conge = new Conge($g_pdo);
	$conge->setEmployee(new Employee($g_pdo, $_POST['employee_id']));
	$conge->setStatus(new StatusConge($g_pdo, array('id' => CONGE_STATUS_PENDING)));
	$conge->setType(new TypeConge($g_pdo, $_POST['type']));
	$conge->setDebut($_POST['start_t']);
	$conge->setFin($_POST['end_t']);
	$conge->store();

	$result['message'] = "Success";
} catch (Exception $e) {
	$result['error'] = $e->getMessage();
}

echo json_encode($result);
?>