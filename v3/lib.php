<?php
// 25-28 September 2017
error_reporting(0);
$st = new SiteStats();
$um = new UserManager();
$of = new Offler();
$db = new Database();

$DATABASE_HOST = '127.0.0.1';
$DATABASE_USER = 'root';
$DATABASE_PASSWORD = 'toor';
$DATABASE_PORT = '3306';
$DATABASE_NAME = 'Offler';

$db->Connect($DATABASE_HOST,$DATABASE_PORT,$DATABASE_USER,$DATABASE_PASSWORD,$DATABASE_NAME);

class SiteStats{
	#STATIC - UN_CHANGE_ABLE
	public $DOC_DIR = 'htm/';
	public $SITE_LOGIN_PAGE = 'login.php';
	public $DATABASE_TABLE = 'conf';
	public $DEFAULT_SEARCH_PARAM = 'parameter';
	#DYNAMIC - CHANGE_ABLE
	public $SQL = ["TABLE"=>"",
			"SELECT_COLUMN"=>"",
			"SEARCH_PARAM"=>"",
			"SEARCH_VALUE"=>"",
			"UPDATE_VALUE"=>"",
			"UPDATE_PARAM"=>"",
			"USERNAME"=>"",
			"PASSWORD"=>""
		];

	public function __construct(){
		global $db ;
		
		$this->SQL = (object)$this->SQL;
		$this->SQL->TABLE = $this->DATABASE_TABLE;
		$this->SQL->SEARCH_PARAM = $this->DEFAULT_SEARCH_PARAM;
		$this->SQL->SELECT_COLUMN = "value";
	}
	public function get($what=""){
		global $db;
		$sql = $this->SQL;
		$sql->SEARCH_VALUE = $what;
		$title = $db->SELECT($sql);
		if($sql->SELECT_COLUMN == "*")
			return $title[0];	
		else
			return $title[0][$sql->SELECT_COLUMN];
	}
	public function loadArchive($OBJ){
		global $db;
		$OBJ = (object)$OBJ;
		$sql = $this->SQL;
		$sql->TABLE = 'sites';
		$sql->SEARCH_PARAM = 'id';
		$sql->SEARCH_VALUE = '';
		if(isset($OBJ->SELECT_COLUMN))
			$sql->SELECT_COLUMN = $OBJ->SELECT_COLUMN;
		if(isset($OBJ->ID))
			$sql->SEARCH_VALUE = $OBJ->ID;
		else
			$sql->SEARCH_VALUE = '';
		$site = $db->Select($sql);
		if($sql->SELECT_COLUMN == "*")
			return $site;	
		else
			return $site[$sql->SELECT_COLUMN];
	}
	public function serverSpeed(){
		try{
			$start = time()*10;
			$fileSize = '1048576'; // if the file's size is 10MB
			for ($i=0; $i<10; $i++) {
			    @file_get_contents('<SOME_LINK_TO_DOWNLOAD_FILE>');
			    break;
			}
			$end = (time()*10)+1;
			$div = ($end - $start == 0)?2:$end - $start;
			$speed = ($fileSize / ($div)) / 1048;
			return $speed; // will return the speed in kbps
		}catch(Exception $e){
			// var_dump($e);
			return 0;
		}
	}
}
class UserManager{

	public $DATABASE_TABLE = 'users';
	public $SQL = [
			"TABLE"=>"",
			"SEARCH_VALUE"=>"",
			"SEARCH_PARAM"=>"",
			"SELECT_COLUMN"=>""
			];
	public function __construct(){
		$this->SQL = (object)$this->SQL;
		$this->SQL->TABLE = $this->DATABASE_TABLE;
		$this->SQL->SELECT_COLUMN = "password";
		$this->SQL->SEARCH_PARAM = 'username';
	}
	public function isValidLogin($USER,$PASS){
		global $db;
		$sql = $this->SQL;
		$sql->SEARCH_VALUE = $USER;
		$res = $db->Select($sql);
		$pass = $res[0]['password'];
		return ( md5(md5($PASS)) == $pass )?true:false;
	}
}
class Offler{
	# STATIC VARIABLES 
	public $ARCHIVES_DIR = '0ffl3rs/';
	public $PATTERN_GET_SITE_NAME = '(?:http\:\/\/|https\:\/\/|https\:\/\/www\.|http\:\/\/www\.|^)([\w\d]+.*[\w]+)(?:\.?|$)';
	public $PATTERN_GET_GLOBAL_LINKS = '[\"\']{1}(?:\/\/|http\:\/\/|https\:\/\/|www\.|^)([\w]+.[\w]+.*?)[\"\']{1}';
	public $PATTERN_GET_LOCAL_LINKS_1 = '[\"\']{1}(?:\/|\.\.\/|^)([\w]+.[\w]+.*?)[\"\']{1}';
	public $PATTERN_GET_LOCAL_LINKS_2 = '(?:href[\s]*\=[\s]*|src[\s]*\=[\s]*)[\"\']{1}(?:\/|\.\.\/|)([\w]+.[\w]+.*?)[\"\']{1}';
	public $VALID_FILE_EXTENTIONS = ['php','asp','aspx','ashx','axd','json','data','xml','htm','html','shtml','js','css','cgi','jsp','png','jpg','jpeg','gif','ico','pdf','ini','config','txt','docx','xls','zip','rar','gz','apk','ipa'];
	# DYNAMIC VARIABLES
	public $SITENAME = '';
	public $USER_LINK_ENTERED = '';

	public function setLink($link){
		$this->SITENAME = $this->getSiteName($link);
		$this->USER_LINK_ENTERED = $link;
	}
	// public function takeScreenShot($Site){
	// 	// $shot = @file_get_contents("".$Site);
	// 	return $shot;
	// }
	public function getSiteName($URL){
		if(preg_match_all("/".$this->PATTERN_GET_SITE_NAME."/", $URL,$matches)){
			if($this->SITENAME == '')
				$this->SITENAME = $matches[1][0];
			return $matches[1][0];
		}else{
			//NOTHING TO DO
		}
	}
	public function getSiteData($Link){
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL, $Link);
		curl_setopt($ch,CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
		$result = curl_exec($ch);
		if(empty($result))
			$result = @file_get_contents($Link);
		curl_close($ch);
		return $result;
	}
	private function _getGlobalUrls($siteData){
		if(preg_match_all("/".$this->PATTERN_GET_GLOBAL_LINKS."/", $siteData, $matches)){
			return $matches[1];
		}
	}
	private function _getLocalUrls($siteData){
		$urls1 = array();
		$urls2 = array();
		if(preg_match_all("/".$this->PATTERN_GET_LOCAL_LINKS_1."/", $siteData, $matches)){
			$urls1 = $matches[1];
		}
		if(preg_match_all("/".$this->PATTERN_GET_LOCAL_LINKS_2."/", $siteData, $matches)){
			$urls2 = $matches[1];
		}
		$globe = $this->_getGlobalUrls($siteData);

		$c = array_flip(array_diff( array_flip($urls2),array_flip($globe) ));
		$d = array();
		$tmp = 0;
		foreach ($c as $url) {
			if(strpos($url,"://") == false){
				$d[$tmp] = $url;
				$tmp+=1;
			}
		}
		return $d;
	}
	public function mixArrays($a1,$a2){
		if(is_array($a1) and is_array($a2)){
			$urls = array_merge($a1,$a2);
			return $urls;
		}
	}
	public function deciplineUrl($Url){
		$ur = $Url;
		$last = '';
		if( count(explode("?", $Url)) <= 1){
			// return $Url;
		}else{
			$a = explode("?", $Url);
			$url = $a[0];
			$param = $a[1];
			$filename = explode("/", $url)[count(explode("/", $url))-1];
			$url = str_replace($filename, "", $url);
			$url = $url.( str_replace(explode(".",$filename)[count(explode(".",$filename))-1] ,"", $filename)."[".urlencode(substr($param,0,50))."].".explode(".",$filename)[count(explode(".",$filename))-1]);
			$ur = $url;
		}
		while(strpos($ur,"//")){
			$ur = str_replace("//", "/", $ur);//REMOVE undeciplined path	
		}
		
		if(substr($ur,-1) == '=' or substr($ur,-1) == '"'){//REMOVE '=' from end of file
			$ur = substr($ur,1,-1);
		}

		return $ur;
	}
	public function hasValidType($param){
		if(strpos("/",$param) != -1){
			$param = explode("/", $param)[count(explode("/", $param))-1];
			$param = explode(".", $param)[count(explode(".", $param))-1];
		}
		foreach ($this->VALID_FILE_EXTENTIONS as $ext) {
			if(strtolower($param) == $ext)
				return true;
		}
		return false;
	}
	public function writeToFile($path,$url,$indexdata=null){
		$path = $this->ARCHIVES_DIR."/".$path;
		$path = $this->deciplineUrl($path);
		if(substr($path, -1) == '/')
			$path = $path."index.html";
		if($indexdata != null){
			file_put_contents($path, $indexdata);
		}elseif(!file_exists($path)){
			$url_data = $this->getSiteData($url);
			$this->makeDir($path);
			try {
				$fp = fopen($path, "w+");
				fwrite($fp, $url_data);
				fclose($fp);
			} catch (customException $e) {
				
			}
			// echo "<font color='green'>file has been putten in '".$path."</font><br><hr><br>";
		}else{
			// echo "<font color='yellow'>file has been exist in '".$path."</font><br><hr><br>";
		}
		if(!file_exists($path)){
			// echo "<font color='darkred'>file cannot be put into '".$path."</font><br><hr><br>";
		}
	}
	public function makeDir($Path){
		$dirs = '';
		while(count(explode("//",$Path)) > 1){
			$Path = explode("//",$Path)[1];
		}
		// if(substr($Path,-1) != '/'){ //While use this function to save file either
		if(strpos(explode("/", $Path)[ count(explode("/", $Path))-1 ],".")){
			foreach (explode("/",$Path) as $key => $value) {
				if( $key != count(explode("/",$Path))-1 ){
					$dirs .= $value.'/';
					if(!is_dir($dirs))
						mkdir($dirs);
				}else{
					//NOTHING TO DO
				}
			}
		}
		// var_dump($Path);
		// }
	}
	public function Process($siteLink,$whole=false){
		if($this->SITENAME == ''){
			$a = $this->getSiteName($siteLink);
		}
		if($this->USER_LINK_ENTERED == ''){
			$this->USER_LINK_ENTERED = $siteLink;
		}
		if(!$whole){
			$index_data = $this->getSiteData($siteLink);
			$local_urls = $this->_getLocalUrls($index_data);
			$globe_urls = $this->_getGlobalUrls($index_data);
			$tmp = 0;
			foreach ($local_urls as $local_url) {
				$path = $this->ARCHIVES_DIR.$this->deciplineUrl($local_url);
				$index_data = str_replace($local_url, $path, $index_data);
				$local_urls[$tmp] = $this->SITENAME.'/'.$local_url;
				$tmp+=1;
			}
			foreach ($globe_urls as $globe_url) {
				$path = $this->ARCHIVES_DIR.$this->deciplineUrl($globe_url);
				$index_data = str_replace($globe_url, $path, $index_data);
			}
			$index_data = str_replace(array("https://","http://","src='",'src="','href="',"href='"),array("","","src='/",'src="/','href="/','href="/'), $index_data);
			$URLS = $this->mixArrays($local_urls,$globe_urls);
			// var_dump($URLS);
			foreach ($URLS as $url) {
				if($this->hasValidType($url)){
					$this->writeToFile($url,$url);
				}else{
					if(substr($url,-1) == '/')
						$url = substr($url, 0,-1);
					$this->writeToFile($url.".html",$url);
				}
				// flush();
				// @ob_flush();
			}
			
		}
		$this->writeToFile($this->SITENAME.'/index.html',$this->USER_LINK_ENTERED,$index_data);
		// file_put_contents("test.html",$index_data);
		// echo "<h2 style='font-color:darkred;font-weight:900;background:green;'>Finished</h2>";
		// echo "<h4 style='font-color:blue;font-weight:800;background:gray;'><a href='".($this->deciplineUrl($this->ARCHIVES_DIR.'/'.$this->SITENAME.'/index.html'))."'>OPEN LINK</a></h4>";
		global $db;
		$sql = (object)["TABLE"=>'sites',
				"COLUMN"=>'`site`, `dir`',
				"VALUES"=>'"'.$this->SITENAME.'","'.$this->deciplineUrl($this->ARCHIVES_DIR.'/'.$this->SITENAME.'/').'"'];
		$db->Insert($sql,$db->__CON__);
		$id = $db->Select((object)["TABLE"=>'sites',"SELECT_COLUMN"=>"id","SEARCH_PARAM"=>"site","SEARCH_VALUE"=>$this->SITENAME],$db->__CON__);
		var_dump("location:./admin.php?view=".$id[0]['id']);
		header("location:./admin.php?view=".$id[0]['id']);
	}
}
class Database{
	public $_ERRORS_ ;
	public $__CON__;
	public function __construct($HOST='127.0.0.1',$PORT='3306',$USER='root',$PASS='toor',$DATABASE='Offler'){
		// $this->Connect($HOST='127.0.0.1',$PORT='3306',$USER='root',$PASS='toor',$DATABASE='Offler');
	}
	public function Connect($HOST='127.0.0.1',$PORT='3306',$USER='root',$PASS='toor',$DATABASE='Offler'){
		try{
			$this->__CON__ = mysqli_connect($HOST.":".$PORT,$USER,$PASS,$DATABASE);
			if(mysqli_error($this->__CON__))
				$this->_ERRORS_ = mysqli_error($this->__CON__);
		}catch(Exception $e){
			die($e);
		}
	}
	public function Creation(){

	}
	public function Insert($SQL=[],$CON=''){
		if($CON=='')
			$CON = $this->__CON__;
		if($SQL!=[]){
			$TABLE = $SQL->TABLE;
			$COLUMN = $SQL->COLUMN;
			$VALUES = $SQL->VALUES;
			$sql = "INSERT INTO `".$TABLE."` (".$COLUMN.") VALUES (".$VALUES.")";
			mysqli_query($CON,$sql);
		}else{
			die("SQL EMPTY");
		}
	}
	public function Delete($SQL=[],$CON=''){
		if($CON=='')
			$CON = $this->__CON__;
		if($SQL!=[] and is_array($SQL)){
			$TABLE = $SQL->TABLE;
			$SEARCH_PARAM = $SQL->SEARCH_PARAM;
			$SEARCH_VALUE = $SQL->SEARCH_VALUE;
			mysqli_query($CON,"DELETE FROM `".$TABLE."` WHERE `".$SEARCH_PARAM."` LIKE ('".$SEARCH_VALUE."')");
		}
	}
	public function Update($SQL=[],$CON=''){
		if($CON=='')
			$CON = $this->__CON__;
		if($SQL!=[] and is_array($SQL)){
			$TABLE = $SQL->TABLE;
			$SEARCH_PARAM = $SQL->SEARCH_PARAM;
			$SEARCH_VALUE = $SQL->SEARCH_VALUE;
			$UPDATE_PARAM = $SQL->UPDATE_PARAM;
			$UPDATE_VALUE = $SQL->UPDATE_VALUE;
			mysqli_query($CON,"UPDATE `".$TABLE."` SET '".$UPDATE_PARAM."'=[".$UPDATE_VALUE."] WHERE `".$SEARCH_PARAM."` LIKE ('".$SEARCH_VALUE."')");
		}
	}
	public function Select($SQL=[],$CON=''){
		if($CON==null)
			$CON = $this->__CON__;
		if($CON != null and $SQL!=[] ){
			$TABLE = $SQL->TABLE;
			$SEARCH_PARAM = $SQL->SEARCH_PARAM;
			$SEARCH_VALUE = $SQL->SEARCH_VALUE;
			$SELECT_COLUMN = $SQL->SELECT_COLUMN;
			if($SQL->SEARCH_VALUE != '')
				$sql = "SELECT `".$SELECT_COLUMN."` FROM `".$TABLE."` WHERE `".$SEARCH_PARAM."` LIKE ('".$SEARCH_VALUE."')";
			else
				$sql = "SELECT `".$SELECT_COLUMN."` FROM `".$TABLE."` WHERE `".$SEARCH_PARAM."`";
			// var_dump($sql);
			$res = mysqli_query($CON,$sql);
			if($res and mysqli_num_rows($res) > 0){
				$ret = array();
				$a = 0;
				while($results = mysqli_fetch_assoc($res)){
					$ret[$a] = $results;
					$a += 1;
				}
				return $ret;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
}
?>