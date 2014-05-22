<?php
function json_employee2JsonArray ($employee) {
	$json_emp = array();
	$json_emp['id'] = $employee->getValue('id');
	$json_emp['lastname'] = $employee->getValue('lastname');
	$json_emp['firstname'] = $employee->getValue('firstname');
	$json_emp["conges"] = json_congeList2JsonArray($employee->getConges());
	$json_emp["soldes"] = json_soldeList2JsonArray($employee->getSoldes());

	return $json_emp;
}

function json_congeList2JsonArray($conges) {
	$congesArray = array();
	foreach ($conges as $conge) {
		$congesArray[] = json_conge2JsonArray($conge);
	}
	return $congesArray;
}

function json_conge2JsonArray($conge) {
	$json_conge = array();
	$json_conge['id'] = $conge->getValue('id');
	$json_conge['start'] = $conge->getValue('debut_t');
	$json_conge['end'] = $conge->getValue('fin_t');

	$status = $conge->getType();
	$json_conge['type'] = $status->getValue('id');

	$status = $conge->getStatus();
	$json_conge['status'] = $status->getValue('id');
	return $json_conge;
}

function json_soldeList2JsonArray($soldes) {
	$soldesArray = array();
	foreach ($soldes as $solde) {
		$json_solde = array();

		$year = $solde->getValue('annee');
		if (!isset($soldesArray[$year])) {
			$json_emp['soldes'][$year] = array();
		}

		$type = $solde->getValue('id_TypeConge');
		if (!isset($soldesArray[$year][$type])) {
			$soldesArray[$year][$type] = array();
		}
		$soldesArray[$year][$type] = $solde->getValue('solde');
	}
	return $soldesArray;
}
?>