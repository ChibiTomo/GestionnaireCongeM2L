<!-- signin.php -->
<style>

#body {
	padding-top: 10px;
	text-align: center;
}

</style>
<div>
	<div id="connexion_box" title="Identification">
		<form id="connexion_form" method="POST" action="<?php echo '?action=' . ACTION_AUTHENTICATE; ?>">
			<table>
				<tr>
					<th><label for="login_field">Login</label></th>
					<td>
						<input id="login_field" type="text" name="<?php echo FIELD_LOGIN; ?>" />
					</td>
				</tr>
				<tr>
					<th><label for="pwd_field">Password</label></th>
					<td>
						<input id="pwd_field" type="password" name="<?php echo FIELD_PASSWORD; ?>" />
					</td>
				</tr>
			</table>
		</form>
	<?php
		$error_msg = layout_get_error_message();
		if (!is_null_or_empty($error_msg)) {
			echo '<span class="error_message">' . $error_msg . '</span>';
		}
	?>
	</div>
</div>
<script>

var box = new yt.Box('#connexion_box', {
	title: 'My title',
	buttons: {
		'Mot de passe oublié': {
			type: 'link',
			action: '<?php echo HOST . '?action=' . ACTION_RETRIEVE . '&amp;type=' . TYPE_PASSWORD; ?>'
		},
		Connexion: {
			type: 'button',
			class: 'green',
			action: function() {
				$('#connexion_form').submit();
			}
		},
	},
});
</script>
<!-- END signin.php -->
