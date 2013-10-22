<?php

function connect() {
$db_name = "FAL";
$db_url = ini_get("mysql.default_host");
$u = "faluser";
$p = "faluser";
$connection = mysql_connect("$db_url","$u","$p") or die ("Couldn't connect.");
//$db = @mysql_select_db($db_name, $connection) or die("Couldn't select database.");
@mysql_select_db($db_name, $connection) or die("Couldn't select database.");
return $connection;
}

?>
