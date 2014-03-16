<?php

define('ROOT_DIR', dirname(__FILE__));
require_once ROOT_DIR . '/include/includes.inc';

if (!defined('MYSQL_CONFIG_FILE_INCLUDED')) {
	redirect_to(HOST . '/install.php');
}

session_start();

core_manage_required_fields();

core_save_previous_url();

try {
	action_manage();
} catch(Exception $e) {
	debug($e);
	core_set_state(STATE_ERROR);
}

core_manage_page();

layout_manage();
core_clean_error_message();
?>