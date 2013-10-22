<?php 
include_once("config.php");
include_once("functions.php");
include_once("login_test.php");

/* Filter Input */
if (! isset($_GET["kardex_id"])) {
	exit;
} else {
	$kardex_id = $_GET["kardex_id"];
	if (!is_numeric($kardex_id)) {
		exit();
	}
}
if (isset($_GET["ugl_serials_id"])) {
	$ugl_serials_id = $_GET["ugl_serials_id"];
	if (!is_numeric($ugl_serials_id)) {
		exit();
	}
} else {
	$ugl_serials_id = '';
}

$connection = connect();

$sql = "DELETE 
        FROM ugl_kardex
		WHERE kardex_id = $kardex_id";
		
$result = @mysql_query($sql,$connection) or die ("whoops");

header("Location: staff_full_rec.php?ugl_serials_id=$ugl_serials_id");

?>
