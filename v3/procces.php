<?php
// 25-28 September 2017
require 'lib.php';

// echo "<link rel='stylesheet' href='css/index.css'/>";

if(isset($_SESSION['login']) and !$_SESSION['login']){
	session_destroy();
	header("location:./");
}
if(!isset($_POST['submit'])){
	header("location:./admin.php");
}

// var_dump($_POST);

$type = (isset($_POST['type']))?$_POST['type']:die(json_encode(["state"=>"type wont be empty."]));
$sitename = (isset($_POST['sitename']))?$_POST['sitename']:die(json_encode(["state"=>"sitename wont be empty."]));
// $dir = (isset($_POST['directory']))?$_POST['directory']:die(json_encode(["state"=>"directory wont be empty."]));
$wholesite = (isset($_POST['wholesite']) and strtolower($_POST['wholesite']) == 'on')?true:false;

	switch(strtolower($type)){
		case 'addnew':
			$of->Process($sitename,$wholesite);
		break;
	}
?>