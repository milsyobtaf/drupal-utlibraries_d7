<?php 
include_once("config.php");
include_once("functions.php");
include_once("login_test.php");

if (!isset($_GET["ugl_serials_id"])) {
	exit("ugl_serials_id");
} 
if (!is_numeric($_GET["ugl_serials_id"])) {
	exit("ugl_serials_id");	
}

$ugl_serials_id = $_GET["ugl_serials_id"];
$connection = connect();

//create the loc_code_array

$sql = "SELECT * 
		FROM ugl_serials
		WHERE ugl_serials_id = '$ugl_serials_id'";
		
$result = @mysql_query($sql,$connection) or die ("whoops");
while ($row = mysql_fetch_array($result)) {
	$ugl_serials_id = $row['ugl_serials_id'];
    $title = $row['title'];
    $issn = $row['issn'];
    $oclc_num = $row['oclc_num'];
    $kardex_note = $row['kardex_note'];
    $display_flag = $row['display_flag'];
	}
		$display_record = "
		<div class=\"record\">
		<form action=\"modify_func.php\" method=\"post\">
		<input type=\"hidden\" name=\"ugl_serials_id\" value=\"$ugl_serials_id\" />
		<input type=\"hidden\" name=\"display_flag\" value=\"$display_flag\" />
		<table cellpadding=\"5\" cellspacing=\"2\">
		<tr>
		<td class=\"header\">
		Title:
		</td>
		<td>
		<input type=\"text\" size=\"60\" name=\"title\" 
		value=\"$title\" /> 
		</td></tr>
		<tr>
		<td class=\"header\">
		ISSN:
		</td>
		<td>
		<input type=\"text\" size=\"10\" name=\"issn\" 
		value=\"$issn\" /> 
		</td></tr>
		<tr>
		<td class=\"header\">
		OCLC Number:	
		</td>
		<td>
		<input type=\"text\" size=\"10\" name=\"oclc_num\" 
		value=\"$oclc_num\" /> 
		</td></tr>
		<tr>
		<td class=\"header\">
		Kardex Note:	
		</td>
		<td>
		<textarea rows=\"2\" cols=\"50\" name=\"kardex_note\"> 
		$kardex_note</textarea> 
		</td></tr>
		<tr>
		</table>
		<input type=\"submit\" value=\"save modifications\" />
		</form>
		</div>
		";
include("../header.php");
echo "
	$display_record
	<br />&nbsp;<br />
	<a href=\"$path/admin/staff_index.php\">Back to Complete List of Serials</a>
";

?>
