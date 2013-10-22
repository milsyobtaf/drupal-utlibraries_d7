<?php 
include("config.php");
include("functions.php");
include("login_test.php");

/* Filter Input */
if (!isset($_POST["ugl_serials_id"])) {
	exit();
} 
if (!is_numeric($_POST["ugl_serials_id"])) {
	exit();	
}
$ugl_serials_id = $_POST["ugl_serials_id"];


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
if (isset($_POST["display_flag"])) {
	$display_flag = $_POST["display_flag"];
}

$connection = connect();
if ($title != "") {
	$title		= mysql_real_escape_string($title);
	$issn		= mysql_real_escape_string($issn);
	$oclc_num	= mysql_real_escape_string($oclc_num);
	$kardex_note	= mysql_real_escape_string($kardex_note);
	$display_flag	= mysql_real_escape_string($display_flag);
	$ugl_serials_id	= mysql_real_escape_string($ugl_serials_id);

	$sql = "UPDATE ugl_serials
			SET
			title='$title',
	 		issn='$issn',
	 		oclc_num='$oclc_num',
	 		kardex_note='$kardex_note',
	 		display_flag='$display_flag'
			WHERE ugl_serials_id='$ugl_serials_id'";
echo $sql;
	$result = @mysql_query($sql,$connection) or die ("whoops mod");
	header("Location: staff_full_rec.php?ugl_serials_id=$ugl_serials_id");
} else {
	header("Location: $path/index.php");
}

?>
