<?php

class StatusConge extends MySQLTableEntry {
	const TABLE = 'statusconge';

	public function __construct($pdo, $arg = array()) {
		parent::__construct($pdo, StatusConge::TABLE, $arg);
	}

	public function exists() {
		$values = $this->getValues();
		$array = array(
			'label' => $values['label'],
		);
		return $this->count($array) > 0;
	}

	public static function getAll($pdo) {
		$records = parent::getAll($pdo, StatusConge::TABLE);
		$status = array();
		foreach ($records as $record) {
			$status[] = new StatusConge($pdo, $record);
		}
		return $status;
	}
}

?>