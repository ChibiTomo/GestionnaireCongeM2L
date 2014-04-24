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

	if (!isset($_POST['id'])) {
		throw new Exception('No conge id given.');
	}

	$conge = new Conge($g_pdo, intval($_POST['id']));
// 	print_r($conge);
	$status = $conge->getStatus();

	if (!$status) {
		throw new Exception('Wrong id.');
	}

	if (!$status->is(CONGE_STATUS_PENDING)) {
		throw new Exception('Demand is not pendding');
	}

	$conge->delete();
	$result['message'] = "Success";
} catch (Exception $e) {
	$result['error'] = $e->getMessage();
}

echo json_encode($result);
?>