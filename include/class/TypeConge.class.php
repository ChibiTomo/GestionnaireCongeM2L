<?php

class TypeConge extends MySQLTableEntry {
	const TABLE = 'typeconge';
	public function __construct($pdo, $args = array()) {
		if (is_string($args)) {
			parent::__construct($pdo, TypeConge::TABLE, array( 'label' => $args ));
		} else {
			parent::__construct($pdo, TypeConge::TABLE, $args);
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
		$records = parent::getAll($pdo, TypeConge::TABLE);
		$types = array();
		foreach ($records as $record) {
			$types[] = new TypeConge($pdo, $record);
		}
		return $types;
	}
}

?>