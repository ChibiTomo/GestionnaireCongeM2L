<!-- menu.php -->
<div id="menu">
	<ul>
		<li data-url="">Calendrier</li>
		<li data-url="action=<?php echo ACTION_RETRIEVE; ?>&type=<?php echo TYPE_SUMMARY; ?>">Résumé</li>
		<li data-url="action=<?php echo ACTION_RETRIEVE; ?>&type=<?php echo TYPE_MY_DEMANDS; ?>">Mes demandes</li>
<?php
$employee = layout_get_employee();
if ($employee->hasSubordinate()) {
?>
		<li data-url="action=<?php echo ACTION_RETRIEVE; ?>&type=<?php echo TYPE_DEMANDS; ?>">Gestion des demandes</li>
<?php
}
if ($employee->isAdmin()) {
?>
		<li data-url="action=<?php echo ACTION_ADMINISTRATE; ?>">Administration</li>
<?php
}
?>
		<li data-url="action=<?php echo ACTION_SIGNOUT; ?>" >Déconnexion</li>
	</ul>
</div>

<script>
$('#menu li').each(function() {
	var url = $(this).attr('data-url');
	if (url != '') {
		url = '?' + url;
	}
	if (url == window.location.search) {
		$(this).addClass('selected');
	}
});
$('#menu li').click(function() {
	if ($(this).hasClass('selected')) {
		return;
	}
	var url = $(this).attr('data-url');
	yt.redirectTo('?' + url);
});
</script>
<!-- END menu.php -->
