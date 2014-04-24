<!-- my_demands.php -->
<link rel="stylesheet" href="style/demands.css" />
<link rel="stylesheet" href="style/conge.css" />

<?php

$conges = layout_get_conges();
$employee = layout_get_employee();
$superior_service = $employee->getServices();
$validated = array();
$by_services = array();
foreach ($employee->getSubordinates() as $subordinate) {
	foreach ($subordinate->getServices() as $service) {
		debug($service);
		$by_services[$service->getValue('label')] = array();
	}
}
foreach ($conges as $conge) {
	$emp = $conge->getEmployee();
	$services = $emp->getServices();
	foreach ($services as $service) {
		$serv_name = $service->getValue('label');
		$by_services[$serv_name][] = $conge;
	}
}

function sort_conge_asc(Conge $a, Conge $b) {
	return $b->getFin() - $a->getFin();
}

usort($conges, 'sort_conge_asc');
foreach ($by_services as $label => $c) {
	usort($by_services[$label], 'sort_conge_asc');
}

debug($conges);
debug($by_services);

?>

<div id="demands">
	<div id="tab">
		<div title="Tous">
<?php
foreach ($conges as $conge) {
	echo core_conge2html($conge, true);
}
?>
		</div>

<?php

foreach ($by_services as $label => $serv_conges) {
	echo '<div title="' . $label . '">';
	foreach ($serv_conges as $conge) {
		echo core_conge2html($conge, true);
	}
	echo '</div>';
}

?>
	</div>

	<div id="dialog">
		Êtes-vous sûr de vouloir <span></span> la demande de congé <br/>
		de <b></b> allant<br/>
		du <b></b><br/>
		au <b></b> ?
		<form method="POST" action="?action=<?php echo ACTION_UPDATE; ?>&amp;type=<?php echo TYPE_CONGE; ?>">
			<input type="hidden" name="<?php echo FIELD_ID; ?>" />
			<input type="hidden" name="<?php echo FIELD_STATUS; ?>" />
		</form>
	</div>
</div>

<script>
yt.Tab('#tab');

var dialog = new yt.Dialog('#dialog', {
	title: 'Refuser une demande',
	buttons: {
		Refuser: {
			type: 'button',
			class: 'red',
			action: function() {
				$('#dialog').find('form').submit();
			}
		},
		Annuler: {
			type: 'button',
			action: function() {
				dialog.close();
			}
		}
	},
});

$('.button.validate').click(function() {
	var id = $(this).attr('data-id');
	var status = $(this).attr('data-status');

	var box = dialog.getElement();
	if (status == <?php echo CONGE_STATUS_REFUSED; ?>) {
		box.find('.button.green')
			.removeClass('green')
			.addClass('red')
			.html($(this).html());
	} else {
		box.find('.button.red')
		.removeClass('red')
		.addClass('green')
		.html($(this).html());
	}

	var b = $(this).parent().parent().find('.content b');
	var name = b.eq(0).html();
	var start = b.eq(1).html();
	var end = b.eq(2).html();

	var el = dialog.getElement();
	el.find('span').html($(this).html().toLowerCase());
	b = el.find('b');
	b.eq(0).html(name);
	b.eq(1).html(start);
	b.eq(2).html(end);
	el.find('[name=<?php echo FIELD_ID; ?>]').val(id);
	el.find('[name=<?php echo FIELD_STATUS; ?>]').val(status);

	dialog.setTitle($(this).html() + ' une demande');
	dialog.open();
});
</script>
<!-- END demands.php -->