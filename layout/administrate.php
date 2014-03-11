<!-- administrate.php -->
<script src="jscript/widget_tab.js"></script>
<link rel="stylesheet" href="style/widget_tab.css" />

<style>
#tab .body {
	text-align: center;
}

#tab .body table {
	width: 100%;
	text-align: center;
}
</style>

<div id="administrate">
	<div id="tab">
		<div title="users">
			<table>
				<tr>
					<th>ID</th>
					<th>Nom</th>
					<th>Prenom</th>
					<th>E-Mail</th>
					<th>Login</th>
					<th>Type</th>
					<th>Supérieur</th>
					<th>Service(s)</th>
				</tr>
<?php

$user_types = layout_get_all_user_types();
$employees = layout_get_all_employee();
foreach ($employees as $employee) {
	$id = $employee->getValue('id');
	$firstname = $employee->getValue('firstname');
	$lastname = $employee->getValue('lastname');
	$email = $employee->getValue('email');
	$login = $employee->getValue('login');
	echo <<<EOF
				<tr>
					<td>$id</td>
					<td>$lastname</td>
					<td>$firstname</td>
					<td>$email</td>
					<td>$login</td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
EOF;
}

?>
			</table>
		</div>
		<div title="plouf">
			dflkjnvdsfljkngvlsdfknvndfl
		</div>
	</div>

	<script>
new yt.Tab('#tab');
	</script>

</div>
<!-- END administrate.php -->