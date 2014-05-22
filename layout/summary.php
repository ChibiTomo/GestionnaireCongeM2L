<!-- summary.php -->
<link rel="stylesheet" href="style/summary.css" />
<link rel="stylesheet" href="style/conge.css" />

<?php

$soldes = layout_get_soldes();
$conges = layout_get_conges();

$now = time();
$history = array();
$futur = array();
foreach ($conges as $conge) {
	$start = $conge->getDebut();
	$end = $conge->getFin();
	if ($end < $now && $conge->getStatus()->is(CONGE_STATUS_ACCEPTED)) {
		$history[] = $conge;
	} else if ($start > $now && $conge->getStatus()->is(CONGE_STATUS_ACCEPTED)) {
		$futur[] = $conge;
	}
}

function sort_conge_desc(Conge $a, Conge $b) {
	return $a->getFin() - $b->getFin();
}

function sort_conge_asc(Conge $a, Conge $b) {
	return $b->getFin() - $a->getFin();
}

function sort_sodle_asc(Solde $a, Solde $b) {
	return $a->getYear() - $b->getYear();
}

usort($soldes, 'sort_sodle_asc');
usort($history, 'sort_conge_desc');
usort($futur, 'sort_conge_asc');

debug($history);
debug($futur);

?>

<div id="summary">
	<div id="tab">
		<div title="Soldes">
<?php
$year = 0;
foreach ($soldes as $solde) {
	if ($solde->getYear() != $year) {
		$year = $solde->getYear();
		echo '<hr/><h1>' . $year . '</h1>';
	}
	$type = $solde->getType()->getValue('label');
	$amount = $solde->getAmount();
	echo $type . ' : ' . format_round($amount, 2) . '<br/>';
}
?>
		</div>

		<div title="Historique">
<?php
foreach ($history as $conge) {
	echo core_conge2html($conge);
}
?>
		</div>

		<div title="À venir">
<?php
foreach ($futur as $conge) {
	echo core_conge2html($conge);
}
?>
		</div>
	</div>
</div>

<script>
yt.Tab('#tab');
</script>
<!-- END summary.php -->