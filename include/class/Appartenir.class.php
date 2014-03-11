<?php

class Appartenir extends MySQLTableEntry {
	const TABLE = 'appartenir';

	public function __construct($pdo, $arg = array(), $load = false) {
		parent::__construct($pdo, Appartenir::TABLE, $arg, $load);
	}

	public function exists() {
		$values = $this->getValues();
		$array = array(
			'id_Service' => $values['id_Service'],
			'id_Employee' => $values['id_Employee'],
		);
		return $this->count($array) > 0;
	}

	public function selectAll($where = '', $other = '') {
		$links = parent::selectAll($where, $other);

		$result = array();
		foreach ($links as $link) {
			$result[] = new Appartenir($this->getPDO(), $link);
		}
		return $result;
	}

	public static function getAll($pdo) {
		$records = parent::getAll($pdo, Appartenir::TABLE);
		$links = array();
		foreach ($records as $record) {
			$links[] = new Appartenir($pdo, $record);
		}
		return $links;
	}
}

?>