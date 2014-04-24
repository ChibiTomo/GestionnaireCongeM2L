<?php

/**
 * Ce fichier contient toutes les fonctions d'installation du site.
 * C'est la seule librairie qui n'est pas inclue par "includes.inc".
 */

require_once ROOT_DIR . '/include/autoloader.inc';
require_once ROOT_DIR . '/include/constants.inc';
require_once CONFIG_FILE;
require_once ROOT_DIR . '/include/globals.inc';
require_once ROOT_DIR . '/include/misc.inc';

log_set_autoflush(true);

function install() {
	install_create_config_file();
	install_create_db();
	install_populate_db();
}

function install_create_config_file() {
	$host = $_POST['host'];
	$db_name = $_POST['db_name'];
	$user = $_POST['user'];
	$pwd = $_POST['pwd'];

	$content = <<<EOF
<?php
define('MYSQL_CONFIG_FILE_INCLUDED', true);
define('MYSQL_HOST', '${host}');
define('MYSQL_DBNAME', '${db_name}');
define('MYSQL_USER', '${user}');
define('MYSQL_PASSWORD', '${pwd}');
?>
EOF;

	mkdir_p(dirname(MYSQL_CONFIG_FILE));
	file_put_contents(MYSQL_CONFIG_FILE, $content);

	require_once MYSQL_CONFIG_FILE;
}

function install_create_db() {
	// Connection à MySQL
	$pdo = new PDO(
		'mysql:host='.MYSQL_HOST.';charset=UTF-8',
		MYSQL_USER,
		MYSQL_PASSWORD
	);
	$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	// Configure PDO pour jeter des Exceptions
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

	// Suppression de la vieille base de donnée si elle existe
	$db_name = MYSQL_DBNAME;
	$pdo->exec('DROP DATABASE IF EXISTS ' . $db_name);

	// Créé la nouvelle base de donnée
	$create_query = <<<EOF
CREATE DATABASE IF NOT EXISTS ${db_name} DEFAULT CHARACTER SET = 'utf8';
USE ${db_name};
EOF;
	$pdo->exec($create_query);

	// Charge et installe les requêtes pour la nouvelle base de donnée
	$install_queries = file_get_contents(ROOT_DIR . '/install/install.sql');

	// Execute queries
	$pdo->exec($install_queries);
}

function install_populate_db() {
	$pdo = new PDO('mysql:host='.MYSQL_HOST.';dbname='.MYSQL_DBNAME.';charset=UTF-8', MYSQL_USER, MYSQL_PASSWORD);
	$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	install_populate_usertype($pdo);
	install_populate_service($pdo);
	install_populate_typeconge($pdo);
	install_populate_statusconge($pdo);
	install_populate_employee($pdo);
	install_populate_solde($pdo);

	// Uniquement pour le test.
	install_populate_conge($pdo);
}

function install_populate_usertype($pdo) {
	global $g_cfg_user_type;

	for ($i = 0; $i < count($g_cfg_user_type); $i++) {
		$usr = new UserType($pdo, $g_cfg_user_type[$i]);
		$usr->store();
	}
}

function install_populate_service($pdo) {
	global $g_cfg_services;

	for ($i = 0; $i < count($g_cfg_services); $i++) {
		$service = new Service($pdo, $g_cfg_services[$i]);
		$service->store();
	}
}

function install_populate_typeconge($pdo) {
	global $g_cfg_types_conge;

	for ($i = 0; $i < count($g_cfg_types_conge); $i++) {
		$type = new TypeConge($pdo, $g_cfg_types_conge[$i]);
		$type->store();
	}
}

function install_populate_statusconge($pdo) {
	global $g_cfg_status_conge;

	for ($i = 0; $i < count($g_cfg_status_conge); $i++) {
		$type = new StatusConge($pdo, $g_cfg_status_conge[$i]);
		$type->store();
	}
}

function install_populate_employee($pdo) {
	global $g_cfg_services;
	global $g_cfg_employee_firstnames;
	global $g_cfg_employee_lastnames;
	global $g_cfg_employee_pwd;
	global $g_cfg_employee_email;

	$lastnames_length = count($g_cfg_employee_lastnames);
	debug($g_cfg_employee_firstnames);

	$employees = array();
	for ($i = 0; $i < count($g_cfg_employee_firstnames); $i++) {
		$login = strtolower(delete_spe_char(
			substr($g_cfg_employee_lastnames[$i], 0, 2) . $g_cfg_employee_firstnames[$i]
		));
		$email = $login . $g_cfg_employee_email;
		$id_user_type = USER_TYPE_NORMAL;
		$id_superieur = floor(rand(0, count($employees)));

		if ($i == 0) {
			$id_user_type = USER_TYPE_ADMIN;
			$id_superieur = null;
		}

		$employees[$i] = array(
			'id' => $i + 1,
			'login' => $login,
			'password' => $g_cfg_employee_pwd,
			'firstname' => $g_cfg_employee_firstnames[$i],
			'lastname' => $g_cfg_employee_lastnames[$i % $lastnames_length],
			'email' => $email,
			'id_UserType' => $id_user_type,
			'id_Superieur' => $id_superieur,
		);
	}

	$service_size = count($g_cfg_services);
	for ($i = 0; $i < count($employees); $i++) {
		$service = $g_cfg_services[floor(rand(0, $service_size - 1))];
		$employee = new Employee($pdo, $employees[$i]);
		$employee->addService($service);
		debug($employee);
		$employee->store();
	}
}

function install_populate_solde($pdo) {
	global $g_cfg_solde_depart;

	$employees = Employee::getAll($pdo);
	$typesConge = TypeConge::getAll($pdo);
	$years = array(2013, 2014, 2015);

	foreach ($employees as $employee) {
		$idEmployee = $employee->getValue('id');
		$i = 0;
		foreach ($typesConge as $typeConge) {
			$solde = $g_cfg_solde_depart[$i % count($g_cfg_solde_depart)];
			foreach ($years as $year) {
				$values = array(
					'annee' => $year,
					'solde' => $solde,
					'id_Employee' => $idEmployee,
					'id_TypeConge' => $typeConge->getValue('id'),
				);

				$solde_o = new Solde($pdo, $values);
				$solde_o->store();
			}
			$i++;
		}
	}
}

function install_populate_conge($pdo) {
	debug('Select employee');
	$employee = new Employee($pdo, 1);
	debug($employee);
	$typesConge = TypeConge::getAll($pdo);
	$statusConge = StatusConge::getAll($pdo);

	$day = 24 * 60 * 60; // 24h x 60' x 60''
	$now = time();

	$dates = array(
		'passe' => array(
			'start' => $now - (3 * $day),
			'end' => $now - (2 * $day) - 1,
		),
		'present' => array(
			'start' => $now - (1 * $day),
			'end' => $now + (2 * $day) - 1,
		),
		'futur' => array(
			'start' => $now + (2 * $day),
			'end' => $now + (4 * $day) - 1,
		),
	);

	debug($dates);

	foreach ($statusConge as $status) {
		foreach ($dates as $date) {
			$typeId = floor(rand(0, count($typesConge) - 1));
			$type = $typesConge[$typeId];

			$conge = new Conge($pdo);
			$conge->setDebut($date['start']);
			$conge->setFin($date['end']);
			$conge->setEmployee($employee);
			$conge->setStatus($status);
			$conge->setType($type);
			debug($conge);
			$conge->store();
		}
	}

	$subordinates = $employee->getSubordinates();
	$status = new StatusConge($pdo, 1);

	foreach ($subordinates as $sub) {
		foreach ($dates as $date) {
			$typeId = floor(rand(0, count($typesConge) - 1));
			$type = $typesConge[$typeId];

			$conge = new Conge($pdo);
			$conge->setDebut($date['start']);
			$conge->setFin($date['end']);
			$conge->setEmployee($sub);
			$conge->setStatus($status);
			$conge->setType($type);
			$conge->store();
		}
	}
}

?>