<?php 

//Filter Input
if (isset($_POST["password"])) {
	$password = $_POST["password"];
	$password = trim($password);
	if (strlen($password) > 50) {
		exit();
	}
}

include_once("config.php");
include_once("functions.php");
        if ($password == "$kardex_password"){
                srand((double)microtime()*1000000);
                $randval = rand();
                setcookie("id","delete",time()-86400,"/",".utexas.edu",0);
                setcookie("id","$randval",time()+7200,"/",".utexas.edu",0);
	$connection = connect();
        $sql = "
	UPDATE ugl_kardex_cookie
	SET cookie = '$randval'
        ";
        $result = @mysql_query($sql,$connection) or die("Couldn't execute cookie update query.");
       header("Location: staff_index.php");
        }
	else {
       header("Location: $path/index.php");
       exit;
}
