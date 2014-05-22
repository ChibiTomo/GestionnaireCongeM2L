<?php
	$conge = $g_mail['conge'];
	$emp = $g_mail['employee'];
	$superieur = $g_mail['superieur'];
	$type = $conge->getType()->getValue('label');
	$status = $conge->getStatus()->getValue('label');
	$start = format_date($conge->getDebut());
	$end = format_date($conge->getFin());

	$services = "";
	$emp_serv = $emp->getServices();
	for ($i = 0; $i < count($emp_serv); $i++) {
		if ($i != 0) {
			if ($i == count($emp_serv) - 1) {
				$services .= '</b> et <b>';
			} else {
				$services .= '</b>, <b>';
			}
		}
		$services .= $emp_serv[$i]->getValue('label');
	}
?>
<p>
	Chèr(e) <?php echo $superieur->getValue('firstname') . " " . $superieur->getValue('lastname'); ?>,<br/>
	<?php echo $emp->getValue('firstname') . " " . $emp->getValue('lastname'); ?>,
	du/des service(s) <b><?php echo $services; ?></b>,
	viens d'effectuer une demande de <b><?php echo $type; ?></b> allant<br/>
	du <b><?php echo $start; ?></b>,<br/>
	au <b><?php echo $end; ?></b>.<br/>
	Elle est actuellement <b><?php echo $status; ?></b>.
</p>