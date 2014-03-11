<!-- error.php -->
<b>Error</b>: <?php echo error_get_error($_GET['type'])?><br/>
<?php echo layout_get_error_message($_GET['type'])?>
<!-- END error.php -->

