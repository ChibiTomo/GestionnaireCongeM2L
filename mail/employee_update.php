<?php
	$emp = $g_mail['employee'];
	$emp = new Employee($emp->getPDO(), intval($emp->getValue('id')));
?>
<p>
	Chèr(e) <?php echo $emp->getValue('firstname') . " " . $emp->getValue('lastname'); ?>,<br/>
	Vos informations personnelles ont été mise à jour. Veuillez vérifier leur exactitude :
</p>

<style>
table {
	border-collapse: collapse;
}

td:first-child {
	text-align: right;
	font-weight: normal;
}

td {
	padding: 5px 10px;
	vertical-align: top;
	font-weight: bold;
}

ul {
	margin: 0px;
}
</style>
<table>
	<tr>
		<td>Nom:</td>
		<td><?php echo $emp->getValue('lastname'); ?></td>
	</tr>
	<tr>
		<td>Prénom:</td>
		<td><?php echo $emp->getValue('firstname'); ?></td>
	</tr>
	<tr>
		<td>Identifiant:</td>
		<td><?php echo $emp->getValue('login'); ?></td>
	</tr>
	<tr>
		<td>Mot de passe:</td>
		<td><?php echo $emp->getValue('password'); ?></td>
	</tr>
	<tr>
		<td>Supérieur:</td>
		<td><?php
			$sup = $emp->getSuperieur();
			if ($sup) {
				echo $sup->getValue('firstname') . " " . $sup->getValue('lastname');
			} else {
				echo 'Aucun';
			}
		?></td>
	</tr>
	<tr>
		<td>Service(s):</td>
		<td><ul><?php
			foreach ($emp->getServices() as $service) {
				echo '<li>' . $service->getValue('label') . '</li>';
			}
		?></ul></td>
	</tr>
</table>
<p>
	Si ces informations sont incorrectes, veuillez en informer l'administrateur
	du Gestionnaire de congé.
</p>