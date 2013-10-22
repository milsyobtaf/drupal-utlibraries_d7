<?php 
include_once("config.php");
include_once("functions.php");
include_once("login_test.php");

/* Filter Input */


/* These Fields will require filtering */
if (isset($_POST["title"])) {
	$title = $_POST["title"];
}
if (isset($_POST["issn"])) {
	$issn = $_POST["issn"];
}
if (isset($_POST["oclc_num"])) {
	$oclc_num = $_POST["oclc_num"];
}
if (isset($_POST["kardex_note"])) {
	$kardex_note = $_POST["kardex_note"];
}

$connection = connect();

if ($title != "") {

	$sql = "INSERT INTO ugl_serials
			(ugl_serials_id, title, issn, oclc_num, kardex_note)
			VALUES
			(\"\",\"$title\",\"$issn\",\"$oclc_num\",\"$kardex_note\")";
		
	$result = @mysql_query($sql,$connection) or die ("whoops create");
	$ugl_serials_id = @mysql_insert_id();
	header("Location: staff_full_rec.php?ugl_serials_id=$ugl_serials_id");
}

header("Location: $path/index.php");


?>
