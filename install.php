<?php

define('ROOT_DIR', dirname(__FILE__));
require_once ROOT_DIR . '/include/install.inc';
session_start();
session_destroy();

if (file_exists(MYSQL_CONFIG_FILE)) {
	redirect_to(HOST);
}

if (isset($_POST['host'])) {
	try {
		install();
		session_destroy();
		redirect_to(HOST);
	} catch (Exception $e) {
		echo $e->getMessage();
		echo '<pre>';
		print_r($e);
		echo '</pre>';
		unlink(MYSQL_CONFIG_FILE);
	}
}

?>
<!DOCTYPE html>
<html>
	<head>
	</head>

	<body>
		<form action="" method="POST">
			<table>
				<tr>
					<td>Host</td>
					<td><input type="text" name="host" value="<?php echo $_SERVER['SERVER_NAME']; ?>" /></td>
				</tr>
				<tr>
					<td>Database name</td>
					<td><input type="text" name="db_name" value="conge_m2l" /></td>
				</tr>
				<tr>
					<td>User</td>
					<td><input type="text" name="user" value="root" /></td>
				</tr>
				<tr>
					<td>Password</td>
					<td><input type="password" name="pwd" /></td>
				</tr>
			</table>
			<input type="submit" value="Submit" />
		</form>
	</body>
</html>