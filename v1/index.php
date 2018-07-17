<?php
// 1 April 2017

ob_start();
include 'library.php';

$Pattern = new Pattern();

$URL = (isset($_REQUEST['web']))?$_REQUEST['web']:die('No Web Address Set');
$data = file_get_contents($URL);
$Pattern->setHost($URL);
$Pattern->logData('<Proccess File Get>');
if(!is_dir($Pattern->SAVEWEB) && !file_exists($Pattern->SAVEWEB))
    mkdir($Pattern->SAVEWEB);
if(!is_dir($Pattern->SAVEWEB.$Pattern->isolateLink($Pattern->HOST)) && !file_exists($Pattern->SAVEWEB.$Pattern->isolateLink($Pattern->HOST)))
    mkdir($Pattern->SAVEWEB.$Pattern->isolateLink($Pattern->HOST));
$Pattern->logData('<Proccess: Folder Get>');
preg_match_all($Pattern->SourceUrl,$data,$result);
$result = $result[1];
$len = count($result);
$ready = $data;


for ($i=0;$i<$len;$i++){
    echo '<hr>';
    echo "Work With ".$result[$i]."<br>";
    $Pattern->logData('<Proccess call '.$i.' > '.$result[$i]);
    if($Pattern->checkAddress($result[$i]))
        continue;
    else {
        die('Error in '.$result[$i]);
        break;
    }
    $ready = str_replace($result[$i], './'.$result[$i], $ready);
}
$Pattern->logData('<Proccess call Finished >');
@file_put_contents($Pattern->SAVEWEB.$Pattern->isolateLink($Pattern->HOST).'/'.'index.html',$ready);
ob_end_clean();
echo @file_get_contents($Pattern->SAVEWEB.$Pattern->isolateLink($Pattern->HOST).'/'.'index.html');
$Pattern->END();

?>