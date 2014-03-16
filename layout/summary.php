<!-- summary.php -->
<link rel="stylesheet" href="style/summary.css" />

<?php

$soldes = layout_get_soldes();
$conge = layout_get_conges();

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