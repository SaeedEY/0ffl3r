<?php
// 25-28 September 2017
require "lib.php";
if(isset($_SESSION['login']) and !$_SESSION['login']){
	session_destroy();
	header("location:./");
}
$TITLE = $st->get("SITE_TITLE");
$CONTENT = '';


include $st->DOC_DIR."manage.htm";;


?>