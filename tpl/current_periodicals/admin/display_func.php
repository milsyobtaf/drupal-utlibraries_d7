<?php 
include("config.php");
include("functions.php");
include("login_test.php");

/* Filter Input */
if ((isset($_GET["ugl_serials_id"])) && (is_numeric($_GET['ugl_serials_id']))) {
	$ugl_serials_id = $_GET["ugl_serials_id"];
}  else {
	exit("Error: Invalid Entry");
}

if (isset($_GET["display_flag"]) && (is_numeric($_GET["display_flag"]))) {
	$display_flag = $_GET["display_flag"];
}

$connection = connect();

/* Toggle Display Value */

if ($display_flag == '1') {
	$display_flag = 0;
}
elseif ($display_flag == '0') {
	$display_flag = 1;
}

$sql = "UPDATE ugl_serials
	SET display_flag='$display_flag'
	WHERE ugl_serials_id='$ugl_serials_id'";

$result = @mysql_query($sql,$connection) or die ("whoops");

//header("Location: staff_index.php?ugl_serials_id=$ugl_serials_id");
header("Location: staff_index.php");

?>
