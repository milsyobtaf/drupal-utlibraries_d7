<?php 
include_once("config.php");
include_once("functions.php");
$connection = connect();

include_once("../header.php");
echo "
		<div class=\"record\">
		<form action=\"login_func.php\" method=\"post\">
		<h3>Enter Password:</h3>
		<input type=\"text\" name=\"password\" 
		/> 
		<p />
		<input type=\"submit\" value=\"login\" />
		</form>
		</div>
	<a href=\"$path/admin/staff_index.php\">Back to Complete List of Serials</a>";

?>
