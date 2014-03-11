<?php

class Service extends MySQLTableEntry {
	const TABLE = 'service';

	public function __construct($pdo, $arg, $load = false) {
		if (is_string($arg)) {
			parent::__construct($pdo, Service::TABLE, array( 'label' => $arg ), $load);
		} else {
			parent::__construct($pdo, Service::TABLE, $arg, $load);
		}
	}

	public function exists() {
		$values = $this->getValues();
		$array = array(
			'label' => $values['label'],
		);
		return $this->count($array) > 0;
	}

	public static function getAll($pdo) {
		$records = parent::getAll($pdo, Service::TABLE);
		$services = array();
		foreach ($records as $record) {
			$services[] = new Service($pdo, $record);
		}
		return $services;
	}
}

?>