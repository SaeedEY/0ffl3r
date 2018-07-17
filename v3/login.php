<?php
// 25-28 September 2017
require 'lib.php';
$USERNAME = ( isset($_POST['uname']) )?$_POST['uname']:'';
$PASSWORD = ( isset($_POST['pass']) )?$_POST['pass']:'';
if($um->isValidLogin($USERNAME,$PASSWORD)){
	session_start();
	$_SESSION['user']=$USERNAME;
	$_SESSION['login']=true;
	header("location:./admin.php");
}elseif(isset($_SESSION['login']) and $_SESSION['login']){
	header("location:./admin.php");
}else{
	var_dump("Error");
	try{
		if(isset($_SESSION['login']))
			session_destroy();
	}catch(Exception $e){
		die('Login Failed.');
	}
	header("location:./?failed");
}

?>