<!-- summary.php -->
<link rel="stylesheet" href="style/summary.css" />

<?php

$soldes = layout_get_soldes();
$conges = layout_get_conges();

$now = time();
$history = array();
$futur = array();
foreach ($conges as $conge) {
	$end = $conge->getFin();
	if ($end > $now) {
		$history[] = $conge;
	} else {
		$futur[] = $conge;
	}
}

function sort_conge_desc(Conge $a, Conge $b) {
	return $a->getFin() - $b->getFin();
}

function sort_conge_asc(Conge $a, Conge $b) {
	return $b->getFin() - $a->getFin();
}

usort($history, 'sort_conge_desc');
usort($futur, 'sort_conge_asc');

debug($history);
debug($futur);

?>

<div id="summary">
	<div id="tab">
		<div title="Soldes">
		</div>

		<div title="Historique">
		</div>

		<div title="À venir">
		</div>
	</div>
</div>

<script>
yt.Tab('#tab');
</script>
<!-- END summary.php -->