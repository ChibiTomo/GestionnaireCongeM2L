<?php

class Conge extends MySQLTableEntry {
	public function __construct($pdo, $arg = array()) {
		parent::__construct($pdo, 'conge', $arg);
	}

	public function setDebut($timestamp) {
		if (!is_numeric($timestamp) || intval($timestamp) < 0) {
			throw new Exception('A timestamp have to be a positive numeric.');
		}
		$this->setValue('debut_t', $timestamp);
	}

	public function setFin($timestamp) {
		if (!is_numeric($timestamp) || intval($timestamp) < 0) {
			throw new Exception('A timestamp have to be a positive numeric.');
		}
		$this->setValue('fin_t', $timestamp);
	}

	public function setEmployee(Employee $employee) {
		$this->setValue('id_Employee', $employee->getValue('id'));
	}

	public function setStatus(StatusConge $statusConge) {
		$this->setValue('id_StatusConge', $statusConge->getValue('id'));
	}

	public function setType(TypeConge $typeConge) {
		$this->setValue('id_TypeConge', $typeConge->getValue('id'));
	}

	public function store() {
		if ($this->getValue('debut_t') > $this->getValue('fin_t')) {
			throw new Exception('Conge start after the end.');
		}


		parent::store();
	}
}

?>