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

	public static function getAll($pdo, $other = '') {
		$records = parent::getAll($pdo, Solde::TABLE, $other);
		$soldes = array();
		foreach ($records as $record) {
			$soldes[] = new Solde($pdo, $record);
		}
		return $soldes;
	}

	public function update() {
		parent::update_p('id_Employee=' . $this->getValue('id_Employee') .
				' AND id_TypeConge=' . $this->getValue('id_TypeConge') .
				' AND annee=' . $this->getValue('annee'));
	}

	public function setAmount($qty) {
		return $this->setValue('solde', $qty);
	}

	public function getAmount() {
		return $this->getValue('solde');
	}

	public function getYear() {
		return $this->getValue('annee');
	}

	public function getType() {
		return new TypeConge($this->getPDO(), $this->getValue('id_TypeConge'));
	}
}

?>