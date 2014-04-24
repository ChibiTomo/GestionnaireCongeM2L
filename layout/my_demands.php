<!-- my_demands.php -->
<link rel="stylesheet" href="style/my_demands.css" />
<link rel="stylesheet" href="style/conge.css" />

<?php

$conges = layout_get_conges();
function sort_conge_asc(Conge $a, Conge $b) {
	return $a->getFin() - $b->getFin();
}

usort($conges, 'sort_conge_asc');

debug($conges);
?>
<div id="my_demands">
	<div id="tab">
<?php
$array = array();

debug($conges);

foreach ($conges as $conge) {
	$status = $conge->getStatus();
	if (!isset($array['Tous'])) {
		$array['Tous'] = '';
	}

	$label = $status->getValue('label');

	if (!isset($array[$label])) {
		$array[$label] = '';
	}
	$html = core_conge2html($conge);
	$array['Tous'] .= $html;
	$array[$label] .= $html;
}

foreach ($array as $key => $value) {
	echo '<div title="' . $key . '"><div>' . $value . '</div></div>';
}

?>
	</div>

	<div id="delete">
		Êtes-vous sûr de vouloir supprimer la demande de congé allant<br/>
		du <b></b><br/>
		au <b></b> ?
		<form method="POST" action="?action=<?php echo ACTION_DELETE; ?>&amp;type=<?php echo TYPE_CONGE; ?>">
			<input type="hidden" name="<?php echo FIELD_ID; ?>" />
		</form>
	</div>
</div>

<script>
yt.Tab('#tab');

var dialog = new yt.Dialog('#delete', {
	title: 'Supprimer une demande',
	buttons: {
		Supprimer: {
			type: 'button',
			class: 'red',
			action: function() {
				$('#delete').find('form').submit();
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

$('.button.delete').click(function() {

	var id = $(this).attr('data-id');
	var b = $(this).parent().parent().find('.content b');
	var start = b.eq(0).html();
	var end = b.eq(1).html();

	var el = dialog.getElement();
	b = el.find('b');
	b.eq(0).html(start);
	b.eq(1).html(end);
	el.find('[name=id]').val(id);
	dialog.open();
});
</script>
<!-- END my_demands.php -->