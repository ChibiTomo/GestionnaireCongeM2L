<?php

/**
 * Allow you to redirect to the {$path} destination
 *
 * @param string $path
 */
function redirect_to($path) {
	header('Location: ' . $path);
	header('Cache-Control: no-cache');
	debug('Redirecting to: ' . $path);
	log_flush();
	exit(0);
}

/**
 * Create all necessary folder for the given {$path}.
 *
 * @param string $path
 */
function mkdir_p($path) {
	@mkdir($path, 0777, true);
}

function debug($var, $autoflush = false, Exception $e = null) {
	global $g_logger;

	if (!defined('DEBUG_MODE')) {
		define('DEBUG_MODE', false);
	}

	if (!DEBUG_MODE) {
		return;
	}

	if ($e == null) {
		$e = new Exception();
	}

	$g_logger->log($var, Logger::DEBUG, $autoflush, $e);
}

function log_flush() {
	global $g_logger;

	$g_logger->flush();
}

function log_set_autoflush($b) {
	global $g_logger;

	$g_logger->set_autoflush($b);
}

function debug_request($request, array $replacements) {
	foreach ($replacements as $key => $value) {
		if (!startsWith($key, ':')) {
			$key = ':' . $key;
		}
		$request = preg_replace('#'.$key.'#', $value, $request, 1);
	}
	$e = new Exception();
	debug($request, false, $e);
}

function debug_var($varname) {
	global $$varname;

	$var = $$varname;
	ob_start();
	var_dump($var);
	$content = ob_get_contents();
	ob_clean();

	$e = new Exception();
	debug($varname . $content, false, $e);
}

function startsWith($haystack, $needle) {
    return $needle === "" || strpos($haystack, $needle) === 0;
}

function endsWith($haystack, $needle) {
    return $needle === "" || substr($haystack, -strlen($needle)) === $needle;
}

function delete_spe_char($str) {
	$spestr  = 'à|ä|ã|â|ȁ|ç|é|è|ê|ë|ï|î| | |ñ|ö|ô| | | |ü|û|ù| | | |À|Ä|Â| | |Ç|É|È|Ë|Ê|Ï|Î| | |Ñ|Ö|Ô| | | |Ü|Û|Ù| | ';
	$normstr = 'a|a|a|a|a|c|e|e|e|e|i|i|i|i|n|o|o|o|o|o|u|u|u|u|y|y|A|A|A|A|A|C|E|E|E|E|I|I|I|I|N|O|O|O|O|O|U|U|U|U|Y';

	if (!preg_match('#['.$spestr.']#', $str)) {
		return $str;
	}

	$spe_a = preg_split('#[|]+#', $spestr);
	$norm_a = preg_split('#[|]+#', $normstr);

	$str = str_replace($spe_a, $norm_a, $str);

	return $str;
}

function is_null_or_empty(&$var) {
	if (!isset($var)) {
		return TRUE;
	}
	if ($var == NULL) {
		return TRUE;
	}
	if ($var == "") {
		return TRUE;
	}
	return FALSE;
}

function get_actions() {
	return get_constants('ACTION_');
}

function get_states() {
	return get_constants('STATE_');
}

function get_types() {
	return get_constants('TYPE_');
}

function get_errors() {
	return get_constants('ERROR_');
}

function get_constants($str) {
	$result = array();
	foreach (get_defined_constants() as $name => $value) {
		if (startsWith($name, $str)) {
			$result[$name] = $value;
		}
	}
	return $result;
}

?>