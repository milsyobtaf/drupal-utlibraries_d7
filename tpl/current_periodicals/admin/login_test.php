<?php 
if (isset($_COOKIE['id'])) {
	$cookie = $_COOKIE['id'];
}
$connection = connect();
        $sql = "
        SELECT cookie
        FROM ugl_kardex_cookie
        WHERE cookie = '$cookie'
        ";
        $result = @mysql_query($sql,$connection) or die("Couldn't execute cookietest query.");
        $num_cook = @mysql_num_rows($result);
        if ($num_cook == 0) {
       		header("Location: $path/admin/index.php");
        }
?>
