<?php
// 10 July 2017
include 'lib.php';
$url=(isset($_REQUEST['web'])&&isset($_REQUEST['web'])!=null)?$_REQUEST['web']: die('No URL Entered');
		if(substr($url, -1) != '/')
		    $url = $url.'/';
$lib = new Lib();
$lib->web0ffl3r($url);



// echo $content;
?>