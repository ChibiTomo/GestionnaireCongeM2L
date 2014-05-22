<?php

function format_date($timestamp) {
	$result = int2Day(date('w', $timestamp)); // jour
	$result .= date(' d', $timestamp); // num jour
	$result .= ' ' . int2Month(date('n', $timestamp) - 1); // num mois
	$result .= date(' Y', $timestamp); // annee
	$hour = date(' à H:i', $timestamp);
	if (preg_match('#(23:59|00:00)#', $hour)) {
		$hour = '';
	}
	return $result . $hour;
}

function int2Day($i) {
	$days = array('Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi');
	return $days[$i % count($days)];
};

function int2Month($i) {
	$months = array('Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre');
	return $months[$i % count($months)];
};

function format_round($n, $dec_nbr = 0) {
	return number_format($n, $dec_nbr, '.', ' ');
}

function format_mail_subject($subject) {
	return "=?UTF-8?B?".base64_encode($subject)."?=";
}

?>