<?php

class Conge extends MySQLTableEntry {
	const TABLE = 'conge';

	private $employee;
	private $status;
	private $type;

	public function __construct($pdo, $arg = array()) {
		parent::__construct($pdo, Conge::TABLE, $arg);
	}

	public function setDebut($timestamp) {
		if (!is_numeric($timestamp) || intval($timestamp) < 0) {
			throw new Exception('A timestamp have to be a positive numeric.');
		}
		$this->setValue('debut_t', intval($timestamp));
	}

	public function setFin($timestamp) {
		if (!is_numeric($timestamp) || intval($timestamp) < 0) {
			throw new Exception('A timestamp have to be a positive numeric.');
		}
		$this->setValue('fin_t', intval($timestamp));
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

	public function getQuantity() {
		$qty = $this->getFin() - $this->getDebut();
		$qty /= (60 * 60 * 24);

		$qty = ceil($qty * 100) / 100;

		return $qty;
	}

	public function load($values) {
		parent::load($values);

		if (!$this->getValues()) {
			return;
		}

		$this->loadType();
		$this->loadStatus();
		$this->loadEmployee();
	}

	public function loadType($id = null) {
		if ($id == null) {
			$id = $this->getIntValue('id_TypeConge');
		}

		if (!is_int($id)) {
			throw new Exception('No id given to load TypeConge.');
		}

		$this->setType(new TypeConge($this->getPDO(), $id));
	}

	public function loadStatus($id = null) {
		if ($id == null) {
			$id = $this->getIntValue('id_StatusConge');
		}

		if (!is_int($id)) {
			throw new Exception('No id given to load StatusConge.');
		}

		$this->setStatus(new StatusConge($this->getPDO(), $id));
	}

	public function loadEmployee($id = null) {
		if ($id == null) {
			$id = $this->getIntValue('id_Employee');
		}

		if (!is_int($id)) {
			throw new Exception('No id given to load StatusConge.');
		}

		$this->setEmployee(new Employee($this->getPDO(), $id));
	}

	public static function getAll($pdo, $other = '') {
		$records = parent::getAll($pdo, Conge::TABLE, $other);
		$conges = array();
		foreach ($records as $record) {
			$conge = new Conge($pdo, $record);
			$conge->loadEmployee();
			$conge->loadStatus();
			$conge->loadType();
			$conges[] = $conge;
		}
		return $conges;
	}

	public function store() {
		if ($this->getValue('debut_t') > $this->getValue('fin_t')) {
			throw new Exception('Conge start after the end.');
		}

		parent::store();
	}

	public function update() {
		parent::update_p('id=' . $this->getValue('id'));
	}
}

?>