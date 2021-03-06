<?php
header("Content-Type: text/json; charset=utf-8");

define('ROOT_DIR', dirname(dirname(dirname(__FILE__))));
require_once ROOT_DIR . '/include/includes.inc';
require_once ROOT_DIR . '/endpoints/json/json.inc';

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

	$json_emp = json_employee2JsonArray($employee);

	$result['employee'] = $json_emp;

} catch (Exception $e) {
	$result['error'] = $e->getMessage();
}

echo json_encode($result);
?>