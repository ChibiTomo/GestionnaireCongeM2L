<?php

class Employee extends MySQLTableEntry {
	const TABLE = 'employee';
	private $superieur;
	private $services = array();
	private $soldes = array();
	private $conges = array();

	public function __construct($pdo, $arg = array(), $load = false) {
		parent::__construct($pdo, Employee::TABLE, $arg, $load);
	}

	public function exists() {
		$values = $this->getValues();
		$array1 = array(
			'login' => $values['login'],
		);
		$array2 = array(
			'email' => $values['email'],
		);
		return $this->count($array1) > 0 || $this->count($array2) > 0;
	}

	public function load($values) {
		parent::load($values);

		if (!$this->getValues()) {
			debug('not loaded');
			return;
		}

		$this->loadServices();
		$this->loadSoldes();

		$id_superieur = intval($this->getValue('id_Superieur'));
		if ($id_superieur < 1 || $this->count(array('id' => $id_superieur)) < 1) {
			return;
		}
		$this->setSuperieur($id_superieur);
	}

	public function setSuperieur($id) {
		if ($id == null) {
			$this->superieur = null;
		} else {
			$this->superieur = new Employee($this->getPDO(), $id);
		}
		$this->setValue('id_Superieur', $id);
	}

	public function getSuperieur() {
		return $this->superieur;
	}

	public function addService($service) {
		if (is_int($service)) {
			$this->services[] = new Service($this->getPDO(), $service);
		} else if (is_string($service)) {
			$this->services[] = new Service($this->getPDO(), array('label' => $service), true);
		} else if (is_array($service)) {
			$this->services[] = new Service($this->getPDO(), $service, true);
		}
	}

	public function emptyService() {
		$this->services = array();
	}

	public function store() {
		$values = $this->getValues();

		// Verifie que le type d'utilisateur donné existe
		$ut = new UserType($this->getPDO(), $this->getValue('id_UserType'));
		if (!isset($values['id_UserType']) || !$ut->exists()) {
			$this->setValue('id_UserType', USER_TYPE_NORMAL);
		}

		// Verifie que le supérieur donné existe
		$id_superieur = intval($this->getValue('id_Superieur')); // 0 = Pas de superieur
		if ($id_superieur == 0) {
			$this->superieur = null;
			$this->setValue('id_Superieur', null);
		} else if ($this->count(array('id' => $id_superieur)) > 0) { // Verifie si l'employee existe
			$this->superieur = new Employee($this->getPDO(), $id_superieur);
		} else {
			throw new Exception('Le supérieur n\'existe pas: id=' . $id_superieur);
		}

		parent::store();

		$this->updateServices();
	}

	public function update() {
		if (is_null_or_empty($this->getValue('password'))) {
			$this->removeValue('password');
		}
		parent::update_p('id=' . $this->getValue('id'));
		$this->updateServices();
		$this->updateSoldes();
	}

	private function updateSoldes() {
		foreach ($this->soldes as $solde) {
			$solde->update();
		}
	}

	private function updateServices() {
		$app = new Appartenir($this->getPDO());
		$links = $app->selectAll('id_Employee=' . $this->getValue('id'));

		// Supprime les liens inutiles
		foreach ($links as $link) {
			$this->updateService($link);
		}

		// Ajoute les nouveaux liens
		foreach ($this->services as $service) {
			$values = array(
				'id_Employee' => $this->getValue('id'),
				'id_Service' => $service->getValue('id')
			);
			$link = new Appartenir($this->getPDO(), $values);
			if (!$link->exists()) {
				$link->store();
			}
		}
	}

	private function updateService($link) {
		foreach ($this->services as $service) {
			if ($service->getValue('id') == $link->getValue('id_Service')) {
				return;
			}
		}
		$link->delete();
	}

	private function loadServices() {
		$a = new Appartenir($this->getPDO());
		$links = $a->selectAll('id_Employee='.$this->getValue('id'));

		foreach ($links as $link) {
			$service = new Service($this->getPDO(), intval($link->getValue('id_Service')));
			$this->services[] = $service;
		}
	}

	private function loadSoldes() {
		$s = new Solde($this->getPDO());
		$soldes = $s->selectAll('id_Employee='.$this->getValue('id'));

		$this->soldes = array();
		foreach ($soldes as $solde) {
			$this->soldes[] = new Solde($this->getPDO(), $solde);
		}
	}

	public static function getAll($pdo, $other = '') {
		$records = parent::getAll($pdo, Employee::TABLE, $other);
		$employees = array();
		foreach ($records as $record) {
			$employee = new Employee($pdo, $record);
			$employees[] = $employee;
			$employee->loadServices();
			$employee->loadSoldes();
			$employee->setSuperieur(intval($employee->getValue('id_Superieur')));
		}
		return $employees;
	}

	public static function authenticate($pdo, $login, $pwd) {
		$array = array(
			'login' => $login,
			'password' => $pwd,
		);
		$employee = new Employee($pdo, $array, true);
		$id = $employee->getValue('id');
		if (is_null_or_empty($id)) {
			return null;
		}
		debug($employee);
		return $employee;
	}

	public function getDisplayName() {
		return $this->getValue('firstname') . ' ' . $this->getValue('lastname');
	}

	public function getServices() {
		return $this->services;
	}

	public function getSoldes() {
		return $this->soldes;
	}

	public function getSolde($year, TypeConge $type) {
		foreach ($this->getSoldes() as $solde) {
			if ($solde->getValue('annee') == $year &&
					$solde->getValue('id_TypeConge') == $type->getValue('id')) {
				return $solde;
			}
		}
		return null;
	}

	public function getConges() {
		if ($this->conges == array()) {
			$this->conges = Conge::getAll($this->getPDO(), 'WHERE id_Employee='.$this->getValue('id'));
		}

		$this->manageConge();
		return $this->conges;
	}

	public function manageConge() {
		$now = time();
		$to_unset = array();
		for ($i = 0; $i < count($this->conges); $i++) {
			$conge = $this->conges[$i];
			if ($conge->getStatus()->is(CONGE_STATUS_PENDING)
					&& ($conge->getDebut() < $now || $conge->getFin() < $now)) {
				$conge->delete();
				$to_unset[] = $i;
			}
		}

		foreach ($to_unset as $i) {
			unset($this->conges[$i]);
		}
	}

	public function hasSubordinate() {
		$array = array(
			'id_Superieur' => $this->getValue('id'),
		);
		return $this->count($array) > 0;
	}

	public function getSubordinates() {
		return Employee::getAll($this->getPDO(), 'WHERE id_Superieur='.$this->getValue('id'));
	}

	public function isAdmin() {
		return $this->getValue('id_UserType') == USER_TYPE_ADMIN;
	}

	public function hasEnought($year, TypeConge $type, $askedAmount) {
		$soldeN = $this->getSolde($year, $type);
		$soldeN1 = $this->getSolde($year + 1, $type);
		$amount = 0;

		if ($soldeN != null) {
			$amount += $soldeN->getAmount();
		}
		if ($soldeN1 != null) {
			$amount += $soldeN1->getAmount();
		}

		return $amount >= $askedAmount;
	}

	public function decrement_solde($year, TypeConge $type, $amount) {
		$this->increment_solde($year, $type, -$amount);
	}

	public function increment_solde($year, TypeConge $type, $amount) {
		$soldeN = $this->getSolde($year, $type);
		$total = $soldeN->getAmount() + $amount;
		if ($total < 0) {
			$soldeN->set(0);
			$soldeN1 = $this->getSolde($year, $type);
			$total = $soldeN1->getAmount() + $total;
			$soldeN1->setAmount(0);
			$soldeN1->update();
		} else {
			$soldeN->setAmount($total);
		}

		$soldeN->update();
	}

	public function delete() {
		foreach ($this->soldes as $solde) {
			$solde->delete();
		}
		foreach ($this->getConges() as $conge) {
			$conge->delete();
		}
		$this->services = array();
		$this->updateServices();

		$emps = Employee::getAll($this->getPDO(), 'WHERE id_Superieur=' . $this->getValue('id'));
		foreach ($emps as $emp) {
			$emp->setSuperieur(null);
			$emp->update();
		}

		if ($this->superieur == null) {
			$this->removeValue('id_Superieur');
		}
		parent::delete();
	}
}

?>