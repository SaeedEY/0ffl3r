<?php
// 25-28 September 2017
require "lib.php";

include $st->DOC_DIR."index.htm";

if(isset($_SESSION['login'])){
	$_SESSION['login']=false;
	session_destroy();
}


?>