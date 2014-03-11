<?php

class UserType extends MySQLTableEntry {
	const TABLE = 'usertype';
	public function __construct($pdo, $arg = array()) {
		parent::__construct($pdo, UserType::TABLE, $arg);
	}

	public function exists() {
		$values = $this->getValues();
		$array = array(
			'label' => $values['label'],
		);
		return $this->count($array) > 0;
	}

	public static function getAll($pdo, $other = '') {
		$records = parent::getAll($pdo, UserType::TABLE, $other);
		$types = array();
		foreach ($records as $record) {
			$type = new Employee($pdo, $record);
			$types[] = $type;
			$type->loadServices();
			$type->loadSoldes();
		}
		return $types;
	}
}

?>