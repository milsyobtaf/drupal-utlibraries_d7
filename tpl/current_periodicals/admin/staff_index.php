<?php  
include_once("config.php");
include_once("functions.php");
include_once("login_test.php");
include_once("../header.php");

$connection = connect();

$sql = "SELECT title, ugl_serials_id, display_flag  
	FROM ugl_serials 
	ORDER BY title";

$result = @mysql_query($sql, $connection) or die("no go");
while ($row = @mysql_fetch_array($result)) {
	$title = $row['title'];
	$ugl_serials_id = $row['ugl_serials_id'];
	$display_flag = $row['display_flag'];

	if ($display_flag == '0') {
		$display_toggle = "<a href=\"display_func.php?ugl_serials_id=".$ugl_serials_id."&display_flag=".$display_flag."\" class=\"modify\">display</a>";
	}
	if ($display_flag == '1') {
		$display_toggle = "<a href=\"display_func.php?ugl_serials_id=".$ugl_serials_id."&display_flag=".$display_flag."\" class=\"modify\">hide</a>";
	}
	$modify = "<a href=\"modify_full_rec.php?ugl_serials_id=".$ugl_serials_id."\" class=\"modify\">modify</a>";
	$display .= "<li><a href=\"staff_full_rec.php?ugl_serials_id=".$ugl_serials_id."\">".$title."</a> | ".$display_toggle." | ".$modify."</li>";
}

echo "
<div class=\"content\">
<ul>
<a href=\"create_rec.php\">Create a Serial Record</a>
<br />
$display
</ul>
</div>
</body>
</html>"; 
?>
