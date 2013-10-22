<?php 
include_once("config.php");
include_once("functions.php");
include_once("login_test.php");

$connection = connect();

//create the loc_code_array

		$display_record = "
		<div class=\"record\">
		<form action=\"create_func.php\" method=\"post\">
		<input type=\"hidden\" name=\"display_flag\"  value=\"1\" />
		<table cellpadding=\"5\" cellspacing=\"2\">
		<tr>
		<td class=\"header\">
		Title:
		</td>
		<td>
		<input type=\"text\" size=\"60\" name=\"title\" 
		/> 
		</td></tr>
		<tr>
		<td class=\"header\">
		ISSN:
		</td>
		<td>
		<input type=\"text\" size=\"10\" name=\"issn\" 
		/> 
		</td></tr>
		<tr>
		<td class=\"header\">
		OCLC Number:	
		</td>
		<td>
		<input type=\"text\" size=\"10\" name=\"oclc_num\" 
		/> 
		</td></tr>
		<tr>
		<td class=\"header\">
		Kardex Note:	
		</td>
		<td>
		<textarea rows=\"2\" cols=\"50\" name=\"kardex_note\"></textarea> 
		</td></tr>
		<tr>
		</table>
		<input type=\"submit\" value=\"create record\" />
		</form>
		</div>
		";
include("../header.php");
echo "
	$display_record
	<br />&nbsp;<br />
	<a href=\"staff_index.php\">Back to Complete List of Serials</a>
";

?>
