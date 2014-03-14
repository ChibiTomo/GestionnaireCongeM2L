<!-- header.php -->
<div id="header">
<?php

$not_logged = '';
if (!layout_is_logged()) {
	$not_logged = 'not_logged';
}

?>
	<div id="page_main_title" class="<?php echo $not_logged; ?>">Gestionnaire de Congé</div>
	<div id="date_heure" class="<?php echo $not_logged; ?>"></div>
<?php
if (layout_is_logged()) {
	$employee = layout_get_employee();
	$services_names = array();
	foreach ($employee->getServices() as $service) {
		$services_names[] = $service->getValue('label');
	}
?>
	<div id="user_info">
		<div id="user_name"><?php echo $employee->getDisplayName(); ?></div>
		<div id="user_service"><?php echo join(' - ', $services_names); ?></div>
	</div>
	<div id="page_title"><?php echo layout_get_title(); ?></div>
<?php
	layout_menu();
}
?>

	<script>
printDate();
//setInterval(printDate, 1000);

function printDate() {
	$('#date_heure').html(yt.getDate());
}
	</script>
</div>
<!-- END header.php -->
