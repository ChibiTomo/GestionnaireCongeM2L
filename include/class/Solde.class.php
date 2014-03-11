<?php

class Solde extends MySQLTableEntry {
	const TABLE = 'solde';
	public function __construct($pdo, $arg = array()) {
		parent::__construct($pdo, Solde::TABLE, $arg);
	}

	public function exists() {
		$array = array(
			'id_Employee' => $this->getValue('id_Employee'),
			'id_TypeConge' => $this->getValue('id_TypeConge'),
		);
		return $this->count($array) > 0;
	}

	public static function getAll($pdo) {
		$records = parent::getAll($pdo, Solde::TABLE);
		$soldes = array();
		foreach ($records as $record) {
			$soldes[] = new Solde($pdo, $record);
		}
		return $soldes;
	}
}

?>