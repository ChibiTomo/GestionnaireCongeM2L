<?php
class Logger {
	const INFO = 0;
	const DEBUG = 1;
	const WARNING = 2;
	const ERROR = 3;

	const ON = true;
	const OFF = false;

	private $labels = array(
		Logger::INFO => 'INFO',
		Logger::DEBUG => 'DEBUG',
		Logger::WARNING => 'WARN',
		Logger::ERROR => 'ERROR',
	);

	private $switch = Logger::ON;
	private $trace = array();
	private $autoflush = false;


	public function log($var, $lvl = Logger::DEBUG, $autoflush = false, Exception $e = null) {
		if (!$this->is_on()) {
			return;
		}
		if (!is_int($lvl)) {
			throw new Exception('Log level have to be an int, ' . gettype($lvl) . ' given');
		}
		if (!isset($this->labels[$lvl])) {
			throw new Exception('Unknown log level: ' . $lvl);
		}

		if ($e == null) {
			$e = new Exception();
		}

		$traces = $e->getTrace();
		$trace = $traces[0];
		$log = array();
		$log[] = '[' . $this->labels[$lvl] . ']' . Logger::tab()
			. $trace['file'] . ':' . $trace['line'];

		$txt = $var;
//		if (is_array($txt)) {
//			$txt = Logger::a2s($txt, 1);
//		} else if (is_object($txt)) {
//			$txt = Logger::o2s($txt, 1);
//		}

		if (is_string($txt)) {
			$lines = explode("\n", $txt);
			foreach ($lines as $line) {
				$line = trim($line);
				$log[] = Logger::tab(1) . $line;
			}
		}

		if (is_array($txt) || is_object($txt)) {
			ob_start();
			var_dump($txt);
			$log[] = ob_get_contents();
			ob_clean();
		}
		$log[] = '';
		if ($this->autoflush || $autoflush) {
			echo "<pre>\n" . join("\n", $log) . "</pre>";
		} else {
			foreach ($log as $l) {
				$this->trace[] = $l;
			}
		}
	}

	public function flush($print = true) {
		array_pop($this->trace);
		$str = join("\n", $this->trace);
		$this->trace = array();

		$result = "<pre>\n" . $str . "\n</pre>";
		if ($print) {
			echo $result;
		}
		return $result;
	}

	public function set_autoflush($b) {
		if (!is_bool($b)) {
			throw new Exception('set_autoflush expect Argument 1 to be bool, ' . gettype($b) . ' given');
		}
		$this->autoflush = $b;
	}

	public function is_on() {
		return $this->switch;
	}

	public function on() {
		$this->switch = Logger::ON;
	}

	public function off() {
		$this->switch = Logger::OFF;
	}

// PRIVATE STATIC FUNCTIONS

	private static function tab($qty = 1) {
		if (!is_int($qty)) {
			throw new Exception('tab expect Argument 1 to be int, ' . gettype($qty) . ' given');
		}

		$result = '';
		for ($i = 0; $i < $qty; $i++) {
			//$result .= '+';
			if ($i != 0) {
				$result .= "&nbsp;";
			}
			$result .= "\t";
		}
		return $result;
	}

	private static function a2s(array $txt, $tab, $only_values = false) {
		$array = array();

		$i = 0;
		foreach ($txt as $key => $value) {
			if (is_array($value)) {
				$value = Logger::a2s($value, $tab + 1, $only_values);
			} else if (is_object($value)) {
				if (method_exists($txt, '__toString')) {
					$value = (string) $txt;
				} else {
					$value = Logger::o2s($value, $tab);
				}
			}

			if ($only_values) {
				$array[] = Logger::to_string($value, $tab);
			} else {
				$debut = '';
				if ($i == 0) {
					$debut = "\n" . Logger::tab($tab + 1);
				}
				$array[] = $debut . '[' . $key . '] => ' . $value;
			}
			$i++;
		}
		$fin = '';
		if (count($array) > 0) {
			$fin = "\n" .Logger::tab($tab);
		}

		$str = '';
		if ($only_values) {
			$str = join(', ', $array);
		} else {
			$str = join("\n" . Logger::tab($tab + 1), $array);
		}
		return 'Array ('.$str.$fin.')';
	}

	private static function o2s($obj, $tab) {
		$reflect = new ReflectionClass($obj);
		$tab++;

		$array = array();
		$array[] = $reflect->name . ' {';
		$public_vars = $reflect->getProperties(ReflectionProperty::IS_PUBLIC);
		$vars = Logger::get_values($obj, $public_vars, $tab + 1);
		if (count($vars) > 0) {
			$val = preg_replace('#^Array \('."\n" .'#', '', Logger::a2s($vars, $tab));
			$val = preg_replace('#'."\n" .'(' . "\t". ')*\)$#', '', $val);
			$str = '[PUBLIC]'."\n";
			$str .= $val;
			$array[] = $str;
		}

		$private_vars = $reflect->getProperties(ReflectionProperty::IS_PRIVATE);

		$vars = Logger::get_values($obj, $private_vars, $tab + 1);
		if (count($vars) > 0) {
			$val = preg_replace('#^Array \('."\n" .'#', '', Logger::a2s($vars, $tab));
			$val = preg_replace('#'."\n" .'(' . "\t". ')*\)$#', '', $val);
			$str = '[PRIVATE]'."\n";
			$str .= $val;

			$array[] = $str;
		}

		$protected_vars = $reflect->getProperties(ReflectionProperty::IS_PROTECTED);
		$vars = Logger::get_values($obj, $protected_vars, $tab + 1);
		if (count($vars) > 0) {
			$val = preg_replace('#^Array \('."\n" .'#', '', Logger::a2s($vars, $tab));
			$val = preg_replace('#'."\n" .'(' . "\t". ')*\)$#', '', $val);
			$str = '[PROTECTED]'."\n";
			$str .= $val;
			$array[] = $str;
		}
		$result = join("\n" .Logger::tab($tab), $array);
		$result .= "\n}";

		return $result;
	}

	private static function get_values($o, $vars, $tab) {
		$result = array();
		foreach ($vars as $var) {
			if ($var->isStatic()) {
				continue;
			}
			$var->setAccessible(true);
			$result[$var->getName()] = Logger::to_string($var->getValue($o), $tab);
		}
		return $result;
	}

	private static function to_string($var, $tab = 0) {
		$result = null;
		if (is_array($var)) {
			$result = Logger::a2s($var, $tab);
		} else if (is_object($var)) {
			if (method_exists($var, '__toString')) {
				$classname = 'Object '.get_class($var).': '.(string) $var;
			} else {
				$result = Logger::o2s($var, $tab);
			}
		} else if (is_bool($var)) {
			$result = ($var)? 'B_TRUE' : 'B_FALSE';
		} else if (is_null($var)) {
			$result = 'NULL_VALUE';
		} else {
			$result = strval($var);
		}
		return $result;
	}
}
?>