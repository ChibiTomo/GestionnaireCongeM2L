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

	if (is_null_or_empty($_POST['start_t']) || is_null_or_empty($_POST['end_t'])
			|| is_null_or_empty($_POST['type']) || is_null_or_empty($_POST['employee_id'])) {
		throw new Exception('Start/end time and/or type of conge and/or id of employee missing.');
	}

	$emp = new Employee($g_pdo, intval($_POST['employee_id']));
	$idCongeStatus = CONGE_STATUS_PENDING;
	if ($emp->getSuperieur() == null) {
		$idCongeStatus = CONGE_STATUS_ACCEPTED;
	}

	$conge = new Conge($g_pdo);
	$conge->setEmployee($emp);
	$conge->setStatus(new StatusConge($g_pdo, array('id' => $idCongeStatus)));
	$conge->setType(new TypeConge($g_pdo, intval($_POST['type'])));
	$conge->setDebut($_POST['start_t']);
	$conge->setFin($_POST['end_t']);
	$conge->store();

	$result['message'] = "Success";
	$result['id'] = $conge->getValue('id');
} catch (Exception $e) {
	$result['error'] = $e->getMessage();
}

echo json_encode($result);
?>