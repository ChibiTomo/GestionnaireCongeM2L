<?php

function format_date($timestamp) {
	$result = int2Day(date('w', $timestamp)); // jour
	$result .= date(' d', $timestamp); // num jour
	$result .= ' ' . int2Month(date('n', $timestamp) - 1); // num mois
	$result .= date(' Y', $timestamp); // annee
	return $result;
}

function int2Day($i) {
	$days = array('Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi');
	return $days[$i % count($days)];
};

function int2Month($i) {
	$months = array('Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre');
	return $months[$i % count($months)];
};

?>