<?php

class Conge extends MySQLTableEntry {
	private $employee;
	private $status;
	private $type;

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
		$this->employee = $employee;
		$this->setValue('id_Employee', $employee->getValue('id'));
	}

	public function setStatus(StatusConge $statusConge) {
		$this->status = $statusConge;
		$this->setValue('id_StatusConge', $statusConge->getValue('id'));
	}

	public function setType(TypeConge $typeConge) {
		$this->type = $typeConge;
		$this->setValue('id_TypeConge', $typeConge->getValue('id'));
	}

	public function getDebut() {
		return $this->getValue('debut_t');
	}

	public function getFin() {
		return $this->getValue('fin_t');
	}

	public function getEmployee() {
		return $this->employee;
	}

	public function getStatus() {
		return $this->status;
	}

	public function getType() {
		return $this->type;
	}

	public function loadType($id = null) {
		if ($id == null) {
			$id = $this->getIntValue('id_TypeConge');
		}

		if (!is_int($id)) {
			throw new Exception('No id given to load TypeConge.');
		}

		$this->type = new TypeConge($this->getPDO(), $id);
	}

	public function loadStatus($id = null) {
		if ($id == null) {
			$id = $this->getIntValue('id_StatusConge');
		}

		if (!is_int($id)) {
			throw new Exception('No id given to load StatusConge.');
		}

		$this->status = new StatusConge($this->getPDO(), $id);
	}

	public function store() {
		if ($this->getValue('debut_t') > $this->getValue('fin_t')) {
			throw new Exception('Conge start after the end.');
		}

		parent::store();
	}
}

?>