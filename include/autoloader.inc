<?php

function autoloader($class) {
	$file_path = ROOT_DIR . '/include/class/'.ucfirst(strtolower($class)).'.class.php';
	if (file_exists($file_path)) {
		require_once($file_path);
	}
}

spl_autoload_register('autoloader');

?>