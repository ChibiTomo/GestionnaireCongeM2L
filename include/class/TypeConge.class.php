<?php

class TypeConge extends MySQLTableEntry {
	const TABLE = 'typeconge';
	public function __construct($pdo, $args = array(), $load = false) {
		if (is_string($args)) {
			parent::__construct($pdo, TypeConge::TABLE, array( 'label' => $args ), $load);
		} else {
			parent::__construct($pdo, TypeConge::TABLE, $args, $load);
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

	public function delete() {
		$soldes = Solde::getAll($this->getPDO(), 'WHERE id_TypeConge=' . $this->getValue('id'));
		foreach ($soldes as $solde) {
			$solde->delete();
		}
		$conges = Conge::getAll($this->getPDO(), 'WHERE id_TypeConge=' . $this->getValue('id'));
		foreach ($conges as $conge) {
			$conge->delete();
		}
		parent::delete();
	}
}

?>