<!-- administrate.php -->
<?php

$user_types = layout_get_all_user_types();
$employees = layout_get_all_employee();
$services = layout_get_all_services();

?>

<style>
#administrate {
	overflow: hidden !important;
}

#tab .body {
	position: relative;
	text-align: center;
}

#tab .body > div {
	position: relative;
	overflow: hidden;
}

#tab .body > div > div {
	height: 100%;
	overflow: auto;
	padding: 10px 10px 10px 210px;
	box-sizing: border-box;
}

#tab .body table {
	width: 100%;
	text-align: center;
	box-sizing: border-box;
	background-color: #EAEAEA;
	border-collapse: collapse;
}
#tab .body table th {
	padding: 5px 5px;
	background-color: #CACACA;
}

#tab .body table tr {
	border: 1px solid black;
}

#tab .body table td {
	padding: 5px 5px;
}

#tab .body table tr:nth-child(even) {
	background-color: #FFFFFF;
}

#tab .body .left table {
	text-align: left;
	background-color: transparent;
}

#tab .body .left table td {
	padding: 0px;
	background-color: transparent;
}

#tab .body .left table tr {
	border: none;
	background-color: transparent;
}

#tab .sidebar {
	width: 150px;
	padding: 5px;
	padding-right: 0px;
	border-right: 1px solid black;
	background-color: #F0F0F0;
}

#tab .sidebar .button {
	position: relative;
	right: -1px;
	border: none;
	background-color: transparent;
	margin-bottom: 10px;
}

#tab .sidebar .button.selected {
	padding: 4px 5px;
	border: 1px solid black;
	border-right: 0px;
	border-radius: 4px 0px 0px 4px;

	background-color: #FAFAFA;
}

#page .left {
	position: absolute;
	left: 5px;
	width: 200px;
	text-align: center;
}
</style>

<div id="administrate">
	<div id="tab">
		<div title="Utilisateurs">
			<div>
				<div class="left add_box">
					Ajouter un employée:
					<form method="POST" action="?action=<?php echo ACTION_CREATE; ?>&amp;type=<?php echo TYPE_EMPLOYEE; ?>">
						<input type="text" name="lastname" placeholder="Nom" />
						<input type="text" name="firstname" placeholder="Prenom" />
						<input type="text" name="email" placeholder="E-Mail" />
						<input type="text" name="login" placeholder="Login" /><br/>
						Type d'utilisateur: <select name="type">
<?php

foreach ($user_types as $type) {
	$label = $type->getValue('label');
	$id = $type->getValue('id');
	echo '<option value="' . $id . '">' . $label . '</option>';
}

?>

						</select>
						<select name="superior">
							<option>Pas de supérieur</option>
<?php

foreach ($employees as $employee) {
	$name = $employee->getValue('lastname') . ' ' . $employee->getValue('firstname');
	$id = $employee->getValue('id');
	echo '<option value="' . $id . '">' . $name . '</option>';
}

?>
						</select>
						<table>
<?php
foreach ($services as $key => $service) {
	if ($key % 2 == 0) {
		echo '<tr>';
	}
	$id = $service->getValue('id');
	echo '<td><input type="checkbox" name="services[]" value="' . $id . '"/></td><td>' . $service->getValue('label') . '</td>';
	if ($key % 2 != 0) {
		echo '</tr>';
	}
}

?>
						</table>
						<input type="submit" class="button green"value="Ajouter" />
					</form>
				</div>
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
						<th colspan="2">Actions</th>
					</tr>
<?php

foreach ($employees as $employee) {
	debug($user_types);
	$id = $employee->getValue('id');
	$firstname = $employee->getValue('firstname');
	$lastname = $employee->getValue('lastname');
	$email = $employee->getValue('email');
	$login = $employee->getValue('login');
	$type_id = $employee->getValue('id_UserType');
	$type = $user_types[$type_id]->getValue('label');
	$superieur = $employee->getSuperieur();
	$emp_services = $employee->getServices();
	echo <<<EOF
					<tr>
						<td data-name="id">$id</td>
						<td data-name="lastname">$lastname</td>
						<td data-name="firstname">$firstname</td>
						<td data-name="email">$email</td>
						<td data-name="login">$login</td>
						<td data-name="type" data-value="$type_id">$type</td>
						<td data-name="superior" data-value=
EOF;
if ($superieur != null) {
	echo '"' . $superieur->getValue('id') . '">' .
		$superieur->getValue('firstname') . ' ' . $superieur->getValue('lastname');
}
	echo <<<EOF
</td>
						<td data-name="services" data-value=

EOF;
	$servs = [];
	$servs_id = [];
	foreach ($emp_services as $service) {
		$servs_id[] = $service->getValue('id');
		$servs[] = $service->getValue('label');
	}
	echo '"' . join(' ', $servs_id) . '">' . join(', ', $servs);
	echo <<<EOF
</td>
						<td><div class="button update" data-type="user">Modifier</div></td>
						<td><div class="button red delete" data-type="user">Supprimer</div></td>
					</tr>
EOF;
}

?>
				</table>
			</div>
		</div>
		<div title="Services">
			<div>
				<div class="left">
					Ajouter un service:
					<form method="POST" action="?action=<?php echo ACTION_CREATE; ?>&amp;type=<?php echo TYPE_SERVICE; ?>">
						<input type="text" name="label" placeholder="Nom" />
						<input type="submit" class="button green"value="Ajouter" />
					</form>
				</div>
				<table>
					<tr>
						<th>ID</th>
						<th data-type="label">Label</th>
						<th data-type="action" colspan="2">Actions</th>
					</tr>
<?php

foreach ($services as $service) {
	$id = $service->getValue('id');
	$label = $service->getValue('label');
	echo <<<EOF
					<tr>
						<td data-name="id">$id</td>
						<td data-name="label">$label</td>
						<td><div class="button update" data-type="service">Modifier</div></td>
						<td><div class="button red delete" data-type="service">Supprimer</div></td>
					</tr>
EOF;
}

?>
				</table>
			</div>
		</div>
	</div>

	<div id="update_user">
		<form method="POST" action="?action=<?php echo ACTION_UPDATE; ?>&amp;type=<?php echo TYPE_EMPLOYEE; ?>">
			<input type="hidden" name="id" />
			<input type="text" name="lastname" placeholder="Nom" />
			<input type="text" name="firstname" placeholder="Prenom" />
			<input type="text" name="email" placeholder="E-Mail" />
			<input type="text" name="login" placeholder="Login" /><br/>
			Type d'utilisateur: <select name="type">
<?php

foreach ($user_types as $type) {
	$label = $type->getValue('label');
	$id = $type->getValue('id');
	echo '<option value="' . $id . '">' . $label . '</option>';
}

?>

			</select>
			<select name="superior">
				<option>Pas de supérieur</option>
<?php

foreach ($employees as $employee) {
	$name = $employee->getValue('lastname') . ' ' . $employee->getValue('firstname');
	$id = $employee->getValue('id');
	echo '<option value="' . $id . '">' . $name . '</option>';
}

?>
			</select>
			<table>
<?php
foreach ($services as $key => $service) {
	if ($key % 2 == 0) {
		echo '<tr>';
	}
	$id = $service->getValue('id');
	echo '<td><input type="checkbox" name="services[]" value="' . $id . '"/></td><td>' . $service->getValue('label') . '</td>';
	if ($key % 2 != 0) {
		echo '</tr>';
	}
}

?>
			</table>
		</form>
	</div>

	<div id="update_service">
		<form method="POST" action="?action=<?php echo ACTION_UPDATE; ?>&amp;type=<?php echo TYPE_SERVICE; ?>">
			<input type="hidden" name="id" />
			<input type="text" name="label" placeholder="Nom" />
		</form>
	</div>

	<div id="delete_user">
		Êtes-vous sûr de vouloir supprimer l'utilisateur <b></b> ?
		<form method="POST" action="?action=<?php echo ACTION_DELETE; ?>&amp;type=<?php echo TYPE_EMPLOYEE; ?>">
			<input type="hidden" name="id" />
		</form>
	</div>

	<div id="delete_service">
		Êtes-vous sûr de vouloir supprimer le service <b></b> ?
		<form method="POST" action="?action=<?php echo ACTION_DELETE; ?>&amp;type=<?php echo TYPE_SERVICE; ?>">
			<input type="hidden" name="id" />
		</form>
	</div>

	<script>
new yt.Tab('#tab');

var dialogs = {};
dialogs.update_user = new yt.Dialog('#update_user', {
			title: 'Modifier un utilisateur',
			buttons: {
				Confirmer: {
					type: 'button',
					class: 'green',
					action: function() {
						$('#update_user').find('form').submit();
					}
				},
				Annuler: {
					type: 'button',
					action: function() {
						dialogs.update_user.close();
					}
				}
			},
		});

dialogs.update_service = new yt.Dialog('#update_service', {
			title: 'Modifier un service',
			buttons: {
				Confirmer: {
					type: 'button',
					class: 'green',
					action: function() {
						$('#update_service').find('form').submit();
					}
				},
				Annuler: {
					type: 'button',
					action: function() {
						dialogs.update_service.close();
					}
				}
			},
		});

dialogs.delete_user = new yt.Dialog('#delete_user', {
			title: 'Supprimer un utilisateur',
			buttons: {
				Supprimer: {
					type: 'button',
					class: 'red',
					action: function() {
						$('#delete_user').find('form').submit();
					}
				},
				Annuler: {
					type: 'button',
					action: function() {
						dialogs.delete_user.close();
					}
				}
			},
		});

dialogs.delete_service = new yt.Dialog('#delete_service', {
			title: 'Supprimer un service',
			buttons: {
				Supprimer: {
					type: 'button',
					class: 'red',
					action: function() {
						$('#delete_service').find('form').submit();
					}
				},
				Annuler: {
					type: 'button',
					action: function() {
						dialogs.delete_service.close();
					}
				}
			},
		});

$('.button.update').click(function() {
	var dname = 'update_' + $(this).attr('data-type');
	var tds = $(this).parent().parent().find('td');
	tds.each(function() {
		var dialog = dialogs[dname].getElement();
		var name = $(this).attr('data-name');
		if (name == 'services') {
			var values = $(this).attr('data-value').split(' ');
			console.log(values);
			var checkbox = dialog.find('[name^=' + name + ']');
			checkbox.prop('checked', false);
			for (var i = 0; i < values.length; i++) {
				checkbox.each(function() {
					if ($(this).val() == values[i]) {
						console.log('check ' + name + ':' + $(this).val())
						$(this).prop('checked', true);
					}
				});
			}
		} else if (name == 'superior' || name == 'type') {
			var value = $(this).attr('data-value');
			var select = dialog.find('[name=' + name + ']');
			select.find(':selected').removeAttr('selected');
			select.find('[value=' + value + ']')
				.attr('selected','selected');
		} else {
			dialog.find('[name=' + name + ']').val($(this).html());
		}
	});
	dialogs[dname].open();
});

$('.button.delete').click(function() {
	var dname = 'delete_' + $(this).attr('data-type');
	var tr = $(this).parent().parent();
	var id = tr.find('td[data-name=id]').html();
	var name = tr.find('td[data-name=label]').html()
	if (dname == 'delete_user') {
		name = tr.find('td[data-name=firstname]').html() + ' ' + tr.find('td[data-name=lastname]').html();
	}
	var dialog = dialogs[dname].getElement();
	dialog.find('b').html(name);
	dialog.find('[name=id]').val(id);
	dialogs[dname].open();
});
	</script>

</div>
<!-- END administrate.php -->