<?php
	$conge = $g_mail['conge'];
	$emp = $g_mail['employee'];
	$type = $conge->getType()->getValue('label');
	$start = format_date($conge->getDebut());
	$end = format_date($conge->getFin());
?>
<p>
	Chèr(e) <?php echo $emp->getValue('firstname') . " " . $emp->getValue('lastname'); ?>,<br/>
	Votre demande de <b><?php echo $type; ?></b> allant<br/>
	du <b><?php echo $start; ?></b>,<br/>
	au <b><?php echo $end; ?></b><br/>
	a été <b>Supprimée</b>.
</p>