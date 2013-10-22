<?php 
include_once("config.php");
include_once("functions.php");
include_once("login_test.php");

/* Filter Input */
if (! isset($_POST["ugl_serials_id"])) {
	exit();
} 

if (is_numeric($_POST["ugl_serials_id"])) {
	$ugl_serials_id = $_POST["ugl_serials_id"];
}

/* These Fields will require filtering */
if (isset($_POST["received_date"])) {
	$received_date = $_POST["received_date"];
}
if (isset($_POST["cover_date"])) {
	$cover_date = $_POST["cover_date"];
}
if (isset($_POST["volume"])) {
	$volume = $_POST["volume"];
}
if (isset($_POST["issue"])) {
	$issue = $_POST["issue"];
}
if (isset($_POST["sort_index"])) {
	$sort_index = $_POST["sort_index"];
}

$connection = connect();

$sql = "INSERT 
      	INTO ugl_kardex 
       	(kardex_id, ugl_serials_id, received_date, cover_date, volume, issue, sort_index)
       	VALUES
       	(\"\",\"$ugl_serials_id\",\"$received_date\",\"$cover_date\",\"$volume\",\"$issue\", \"$sort_index\")";
       	
$result = @mysql_query($sql,$connection) or die ("whoops");
header("Location: staff_full_rec.php?ugl_serials_id=$ugl_serials_id");

?>
