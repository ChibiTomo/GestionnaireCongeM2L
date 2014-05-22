<?php
	$conge = $g_mail['conge'];
	$emp = $g_mail['employee'];
	$superieur = $g_mail['superieur'];
	$type = $conge->getType()->getValue('label');
	$status = $conge->getStatus()->getValue('label');
	$start = format_date($conge->getDebut());
	$end = format_date($conge->getFin());
?>
<p>
	Chèr(e) <?php echo $superieur->getValue('firstname') . " " . $superieur->getValue('lastname'); ?>,<br/>
	La demande de <b><?php echo $type; ?></b> de
	<b><?php echo $emp->getValue('firstname') . " " . $emp->getValue('lastname'); ?></b>
	allant<br/>
	du <b><?php echo $start; ?></b>,<br/>
	au <b><?php echo $end; ?></b><br/>
	a été <b><?php echo $status; ?></b>.
</p>