<?php

/**
 * Ce fichier contient toutes les variables globales du site.
 */

$g_logger = new Logger();
$g_layout = array();
$g_mail = array();

$g_head_titles = array(
	STATE_SIGNIN => ' - Connexion',
	STATE_ERROR => ' - Error',
	STATE_ADMINISTRATE => ' - Administration',
	STATE_SUMMARY => ' - Résumés',
	STATE_MY_DEMANDS => ' - Mes demandes',
	STATE_DEMANDS => ' - Gestion des demandes',
);

$g_titles = array(
	STATE_DEFAULT => 'Calendrier',
	STATE_ERROR => 'Error',
	STATE_ADMINISTRATE => 'Administration',
	STATE_SUMMARY => 'Résumés',
	STATE_MY_DEMANDS => 'Mes demandes',
	STATE_DEMANDS => 'Gestion des demandes',
);

$g_action_save_url_black_list = array(
// 	ACTION_NONE,
	ACTION_ERROR,
	ACTION_SIGNIN,
	ACTION_SIGNOUT,
	ACTION_AUTHENTICATE,
	ACTION_CREATE,
	ACTION_UPDATE,
	ACTION_DELETE,
);

if (defined('MYSQL_CONFIG_FILE_INCLUDED')) {
	$g_pdo = new PDO(
			'mysql:host='.MYSQL_HOST.';dbname='.MYSQL_DBNAME.';charset=UTF-8',
			MYSQL_USER,
			MYSQL_PASSWORD
	);
	$g_pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	$g_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
// 	$g_pdo->exec("set names utf8"); // ovh php 5.2
}

?>