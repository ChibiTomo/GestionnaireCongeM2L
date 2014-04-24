<?php

/**
 * Ce fichier contient toutes les constantes utilis� par le site, exept� les
 * constantes de configuration.
 */

// Génère la constante HOST pour l'url du site
$base_url = 'http://' . $_SERVER['SERVER_NAME'] . dirname($_SERVER['SCRIPT_NAME']);
if (substr($base_url, -1) == "/") {
	$base_url = substr($base_url, 0, 0);
}
define('HOST', $base_url);
define('LAYOUT_DIR', ROOT_DIR . '/layout');

// Définit les chemins des fichier de configurations
define('CONFIG_FILE', ROOT_DIR . '/config.inc');
define('MYSQL_CONFIG_FILE', ROOT_DIR . '/mysqlconfig.inc');

define('HAS_REWRITE_MOD', in_array('mod_rewrite', apache_get_modules()));
define('SESSION_TTL', 3600); // in sec

// Constantes des actions
define('ACTION_NONE', 'none');
define('ACTION_SIGNIN', 'signin');
define('ACTION_SIGNOUT', 'signout');
define('ACTION_AUTHENTICATE', 'authenticate');
define('ACTION_CREATE', 'create');
define('ACTION_RETRIEVE', 'retrieve');
define('ACTION_UPDATE', 'update');
define('ACTION_DELETE', 'delete');
define('ACTION_ADMINISTRATE', 'administrate');
define('ACTION_ERROR', 'error');

// Constantes des types
define('TYPE_PASSWORD', 'password');
define('TYPE_SUMMARY', 'summary');
define('TYPE_CONGE', 'conge');
define('TYPE_EMPLOYEE', 'employee');
define('TYPE_SERVICE', 'service');
define('TYPE_TYPE_CONGE', 'type_conge');
define('TYPE_MY_DEMANDS', 'my_demands');
define('TYPE_DEMANDS', 'demands');

// Constantes des etats
define('STATE_DEFAULT', 'root');
define('STATE_SIGNIN', 'signin');
define('STATE_ADMINISTRATE', 'administrate');
define('STATE_SUMMARY', 'summary');
define('STATE_MY_DEMANDS', 'my_demands');
define('STATE_DEMANDS', 'demands');
define('STATE_NOT_ALLOWED', 'not_allowed');
define('STATE_RESET_PASSWORD', 'reset_password');
define('STATE_ERROR_WRONG_STATE', 'error_wrong_state');
define('STATE_ERROR_404', 'error_404');
define('STATE_ERROR', 'error');

// Constantes des champs important (POST/GET/SESSION)
define('FIELD_ACTION', 'action');
define('FIELD_STATE', 'state');
define('FIELD_PREVIOUS_URL', 'previous_url');
define('FIELD_SESSION_BORN_T', 'born_t');
define('FIELD_LOGGED_USER_ID', 'logged_user_id');
define('FIELD_PAGE', 'page');
define('FIELD_TYPE', 'type');
define('FIELD_TYPE_CONGE', 'type_conge');
define('FIELD_LOGIN', 'login');
define('FIELD_EMPLOYEE', 'employee');
define('FIELD_PASSWORD', 'pwd');
define('FIELD_ERROR_MESSAGE', 'error_message');
define('FIELD_START_T', 'start_t');
define('FIELD_END_T', 'end_t');
define('FIELD_ID', 'id');
define('FIELD_STATUS', 'status');

// Constantes des champs display
define('DISPLAY_PAGE', 'page');
define('DISPLAY_EMPLOYEE', 'employee');
define('DISPLAY_EMPLOYEES', 'employees');
define('DISPLAY_USER_TYPES', 'user_types');
define('DISPLAY_CONGES', 'conges');
define('DISPLAY_TYPES_CONGE', 'types_conge');
define('DISPLAY_SERVICES', 'services');
define('DISPLAY_SOLDES', 'soldes');
define('DISPLAY_IS_LOGGED', 'is_logged');
define('DISPLAY_TITLE', 'title');
define('DISPLAY_HEAD_TITLE', 'head_title');

// Constantes des erreurs
define('ERROR_WRONG_ACTION', 0);
define('ERROR_404', 1);
define('ERROR_UNKNOWN', 2);
define('ERROR_WRONG_TYPE', 3);
define('ERROR_NO_ID', 4);
define('ERROR_NO_INFO', 5);

?>