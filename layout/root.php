<!-- root.php -->
<?php

$conge_types = layout_get_all_conge_types();

?>

<link rel="stylesheet" href="style/root.css" />

<div id="root">
	<div id="add_conge">
		Demande de congé:
		<form method="POST" action="?action=<?php echo ACTION_CREATE; ?>&amp;type=<?php echo TYPE_CONGE; ?>">
			<table>
				<tr>
					<th>Date de début</th>
					<td>
						<input type="text" data-type="date" name="date_start" />
					</td>
				</tr>
				<tr>
					<td>
						<input type="radio" name="all_day_long_start" value="1" checked/> Toute la journée
					</td>
					<td>
						<input type="radio" name="all_day_long_start" value="0" /> Heure précise<br/>
						<input type="text" data-type="hour" name="hour_start" disabled/>
					</td>
				</tr>

				<tr>
					<th>Date de fin</th>
					<td>
						<input type="text" data-type="date" name="date_end" />
					</td>
				</tr>
				<tr>
					<td>
						<input type="radio" name="all_day_long_end" value="1" checked/> Toute la journée
					</td>
					<td>
						<input type="radio" name="all_day_long_end" value="0" /> Heure précise<br/>
						<input type="text" data-type="hour" name="hour_end" disabled/>
					</td>
				</tr>
				<tr>
					<th>Type de congé:</th>
					<td>
						<select name="type_conge">
<?php

foreach ($conge_types as $type) {
	echo '<option value="' . $type->getValue('id') . '">' . $type->getValue('label') . '</option>';
}

?>
						</select>
					</td>
				</tr>
			</table>
			<div class="button green">Effectuer une demande</div>
			<input type="hidden" name="start" />
			<input type="hidden" name="end" />
		</form>
	</div>
	<div id="container">
		<div id="calendar_menu">
			Menu calendrier
		</div>
		<div id="calendar">
			<h1>ICI un calendrier</h1>
		</div>
	</div>
</div>

<div id="confirm_conge">
	Demande de <b data-name="type"></b>,
	<div data-type="period">
		du <b data-name="start"></b><br/>
		au <b data-name="end"></b>
	</div>
	<div data-type="one_day">
		pour la journée du <b data-name="day"></b>
	</div>
</div>

<script>
$('#add_conge input[type=radio]').change(function() {
	var hour = $(this).parent().find('input[data-type]');
	if (hour.length == 0) {
		$(this).parent().parent().find('input[data-type]').prop('disabled', true);
	} else {
		hour.prop('disabled', false);
	}
});

dialog = new yt.Dialog('#confirm_conge', {
			title: 'Confirmer la demande de congé ?',
			buttons: {
				Confirmer: {
					type: 'button',
					class: 'green',
					action: function() {
						$('#add_conge form').submit();
					}
				},
				Annuler: {
					type: 'button',
					class: 'red',
					action: function() {
						dialog.close();
					}
				}
			},
		});

function check_form() {
	var isValide = true;
	var form = $('#add_conge form');
	var date_start = form.find('[name=date_start]');
	var date_end = form.find('[name=date_end]');
	var starts_at_precise_hour = $('#add_conge [name=all_day_long_start][value=0]').is(':checked');
	var hour_start = $('#add_conge [name=hour_start]');
	var ends_at_precise_hour = $('#add_conge [name=all_day_long_end][value=0]').is(':checked');
	var hour_end = $('#add_conge [name=hour_end]');

	if (!yt.datePattern.test(date_start.val())) {
		date_start.addClass('error');
		isValide = false;
	} else {
		date_start.removeClass('error');
	}

	if (!yt.datePattern.test(date_end.val())) {
		date_end.addClass('error');
		isValide = false;
	} else {
		date_end.removeClass('error');
	}

	if (starts_at_precise_hour && !yt.hourPattern.test(hour_start.val())) {
		hour_start.addClass('error');
		isValide = false;
	} else {
		hour_start.removeClass('error');
	}

	if (ends_at_precise_hour && !yt.hourPattern.test(hour_end.val())) {
		hour_end.addClass('error');
		isValide = false;
	} else {
		hour_end.removeClass('error');
	}

	if (isValide) {
		var hour_start_val = hour_start.val();
		if (!starts_at_precise_hour) {
			hour_start_val = '';
		}

		var hour_end_val = hour_end.val();
		if (!ends_at_precise_hour) {
			hour_end_val = '';
		}
		var t_start = yt.getTimestamp(date_start.val() + ' ' + hour_start_val, '%d/%m/%Y %Hh%M');
		var t_end = yt.getTimestamp(date_end.val() + ' ' + hour_end_val, '%d/%m/%Y %Hh%M');
		$('#add_conge [name=start]').val(t_start);
		$('#add_conge [name=end]').val(t_end);

		if (t_start == t_end) {
			t_end += (3600 * 24) - 1;
			$('#add_conge [name=end]').val(t_end);
		} else if (t_start > t_end) {
			date_start.addClass('error');
			date_end.addClass('error');
			if (starts_at_precise_hour) {
				hour_start.addClass('error');
			}
			if (ends_at_precise_hour) {
				hour_end.addClass('error');
			}
			isValide = false;
		}
	}

	return isValide;
}

$('#add_conge .button').click(function() {
	if (!check_form()) {
		return
	}
	var starts_at_precise_hour = $('#add_conge [name=all_day_long_start][value=0]').is(':checked');
	var ends_at_precise_hour = $('#add_conge [name=all_day_long_end][value=0]').is(':checked');
	var t_start = parseInt($('#add_conge [name=start]').val());
	var t_end = parseInt($('#add_conge [name=end]').val());
	var type_conge_label = $('#add_conge [name=type_conge] option:selected').html();

	var end_format = '%D %d %m %Y à %Hh%M';
	if (!ends_at_precise_hour) {
		end_format = '%D %d %m %Y';
	}
	var start_format = '%D %d %m %Y à %Hh%M';
	if (!starts_at_precise_hour) {
		start_format = '%D %d %m %Y';
	}
	var el = dialog.getElement();
	el.find('[data-name=type]').html(type_conge_label);
	console.log((t_end - t_start) + ' ' + (3600 * 24));
	if (t_end - t_start == (3600 * 24) - 1 && yt.getDate(t_start, '%d') == yt.getDate(t_end, '%d')) {
		el.find('[data-type=one_day]').show();
		el.find('[data-type=period]').hide();
		el.find('[data-name=day]').html(yt.getDate(t_start, '%D %d %m %Y'));
	} else {
		el.find('[data-type=one_day]').hide();
		el.find('[data-type=period]').show();
		el.find('[data-name=start]').html(yt.getDate(t_start, start_format));
		el.find('[data-name=end]').html(yt.getDate(t_end, end_format));
	}
	dialog.open();
});
</script>
<!-- END root.php -->