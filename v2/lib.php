<?php
// 10 July 2017
class lib{
    Public $DOMAIN;
    public $WEBINPUT;
    public $LOCALADDRESS;
    private $HAVE_TYPES_FILES_TYPE = '.html';
    private $TEMPLATE_FOLDER = 'templates/';
    private $DATA_SHOW_LENGTH = 30;
    private $VALID_FILE_TYPE = array('html','php','jpg','png','swf','mp3','css','js','mp4','aspx','shtml','htm','jps','gif','ico');

    public function Encode($data){
        return base64_encode($data);
    }

    public function Decode($data){
        return '/'.base64_decode($data).'/';
    }

    public function __construct(){
        $this->Pattern_URL_VALIDATE = $this->Decode("XGIoPzooPzpodHRwcz98ZnRwKTpcL1wvfFtcd1xkXC1dK1tcLl17MX1bXHdcZFwtXStbXC5dezF9W1x3XGRdKVstYS16MC05KyZAI1wvJT89fl98ITosLjtdKlstYS16MC05KyZAI1wvJT1+X3xd");
        $this->Pattern_RETURN_HOST = $this->Decode("XGIoPzooPzpodHRwcz98ZnRwKTpcL1wvKD86KD86W1x3XGRcLV0rW1wuXXsxfVtcd117MiwxMH0pfCg/Oltcd1xkXC1dK1tcLl17MX1bXHdcZFwtXStbXC5dezF9KD86Y29tfHVrfHVzfGJpenxvcmd8bmV0fGluZm98aXJ8YWZ8b25saW5lfHNpdGV8bWV8YmxvZ3xnb3Z8ZWR1KSkpfGh0dHA6XC9cL2xvY2FsaG9zdClbLWEtejAtOSsmQCNcJT89fl98ITosLjtdKlstYS16MC05KyZAI1wvJT1+X3xd");
        $this->Pattern_RETURN_CLIENT = $this->Decode("KD86W2EtejAtOVwtXC5dezMsMjU2fVwuW2Etel17MiwxMH1bXC9cXF0/KShbXHdcV10rKQ==");
        $this->Pattern_RETURN_SITE_SOURCE_LINKS = $this->Decode("KD86aHJlZnxzcmN8dXJsKT0oPzpcInxcJykoW1x3XGRcc1whXEBcI1wkXCVcXlwmXCpcKFwpXF9cK1w9XC1cXVxbXFxcO1wvXC5cLFw8XD5cP1w6XHxce1x9XSspKD86XCJ8XCcp");
        $this->Pattern_URL_HAS_PROTOCOL = $this->Decode("KD86aHR0cHxodHRwc3xmdHB8dGNwfG1hcmtldHx0ZWwpOltcL1wvXXsyLDEwMH0oPzpbYS16XC5cLV0rW1wvXFxdP3xbXGRdezEsM30uW1xkXXsxLDN9LltcZF17MSwzfS5bXGRdezEsM31bXC9cXF0/KQ==");
        	// http:////google.com => [YES] $0
        $this->Pattern_RETURN_DOMAIN = $this->Decode("KD86KD86aHR0cHxodHRwc3xmdHB8bWFya2V0fHRlbHx0Y3ApXDpcL1wvKSgoPzpbYS16MC05XC1cLl17MywyNTZ9XC5bYS16XXsyLDEwfVtcL1xcXT8pfFtcZF17MSwzfS5bXGRdezEsM30uW1xkXXsxLDN9LltcZF17MSwzfVtcL1xcXT98bG9jYWxob3N0W1wvXFxdPyk=");
        	// http://google.com => google.com $0
    }

    public function parseLog($data){
        echo $data;
        echo "<br>";
        flush();
        @ob_flush();
    }

    public function getDomain($link){
    	if(preg_match($this->Pattern_RETURN_DOMAIN, $link,$out))
    		if($out[1] == 'localhost/')
    			return $link;
    		else
	    		return $out[1];
    	// else if(empty($out[0]))
    	// 	return $link;
    	else
    		die($this->parseLog("[".$link."] cannot return Domain."));
    }

    public function setValidProtocol($link){
    	if(preg_match($this->Pattern_URL_HAS_PROTOCOL,$link,$out))
    		return $link;
    	else
    		die($this->parseLog("[".$link."] cannot set Valid Protocol."));
    }

    public function validUrl($url){
        $isvalid = preg_match($this->Pattern_URL_VALIDATE,$url,$out);
        if($isvalid){
            return true;
        }else
            return false;
    }

    public function dirValidChar($dir){
    	$dir1 = str_replace('https://', '', $dir);
    	$dir2 = str_replace('http://', '', $dir1);
    	$dir3 = str_replace('tcp://', '', $dir2);
    	$dir4 = str_replace('://', '', $dir3);
    	$dir5 = str_replace('./', '', $dir4);
    	$dir6 = str_replace(':', '', $dir5);
    	return $dir6;
    }

    public function insertUrl($link){
    	$link = strtolower($link);
    	if($this->setValidProtocol($link) != null){
    		if($this->validUrl($link)){
    			return $link;
    		}
    	}
    }

    public function web0ffl3r($url){
    	
		$this->WEBINPUT = $url;
		$this->DOMAIN = $this->getDomain($url);
		$url = $this->insertUrl($url);
		$content = $this->Decode($this->loadSite($url));
		$linksArray = $this->getSourceLinks($url,$content);
		// var_dump($linksArray);
		// echo $url;
		foreach($linksArray as $link){
			$link1 = null;
    		if(!$this->isGlobal($link)){
    			$link1 = $link;
    			$link = $this->DOMAIN.$link;
    		}
    		$this->saveSource($link);
    		if(empty($this->LOCALADDRESS))
    			$this->LOCALADDRESS = 'index';
    		if($link1 != null){
    			var_dump($link1.'||'. $this->LOCALADDRESS.$this->HAVE_TYPES_FILES_TYPE);
    			if(substr($this->LOCALADDRESS, -1) == '/'){
    				$a = strrev($this->LOCALADDRESS);
					$a = substr($a,1);
					$a = strrev($a);
					$this->LOCALADDRESS = $a;
    			}
    			$content = str_replace($link1, $this->LOCALADDRESS.$this->HAVE_TYPES_FILES_TYPE, $content);
    		}
    		else{
    			var_dump($link.'||'.$this->LOCALADDRESS.$this->HAVE_TYPES_FILES_TYPE);
    			if(substr($this->LOCALADDRESS, -1) == '/'){
    				$a = strrev($this->LOCALADDRESS);
					$a = substr($a,1);
					$a = strrev($a);
					$this->LOCALADDRESS = $a;
    			}
    			$content = str_replace($link, $this->LOCALADDRESS.$this->HAVE_TYPES_FILES_TYPE, $content);
    		}
    		
    	}
    	file_put_contents(''.$this->TEMPLATE_FOLDER.'/../index.html',$content);
    	echo "<h1>END</h1><a href='index.html'>Click To Browse File</a>";
    }

    public function getClient($url){
    	$this->parseLog("[getClient]$url");
        if(!$this->validUrl($url)) die($this->parseLog("[getClient()]:95 in [validUrl()] url:'$url' [die()];"));
        preg_match($this->Pattern_RETURN_CLIENT,$url,$client);
        if(is_array($client))
            return ''.$client[1];
        else if($client != '')
            return ''.$client;
        else
            die($this->parseLog("[getClient()]:102 in [boolean:is_array($client)()] url:'$url' [die()];"));
    }

    public function isGlobal($url){
        if(preg_match($this->Pattern_URL_HAS_PROTOCOL,$url,$output)){
            return true;
        }
        else
            return false;
    }

    public function isValidFileType($ext){
    	$ext = strtolower($ext);
    	$len = explode('.', $ext);
    	if(count($len) > 1){
    		$ext = $len[count($len)-1];
    	}else return false;
    	foreach ($this->VALID_FILE_TYPE as $type) {
    		if($ext == $type)
    			return true;
    	}
    	return false;
    }

    public function makedir($direc){
    	$dirs = explode("/", $direc);
    	$tmp = '';
    	foreach ($dirs as $key => $dir) {
    		if($dir == '')
    			continue;
    		if($key == 0)
    			$tmp = $dir;
    		if($key == count($dirs)){

    			if(count(explode('.', $dir) >= 2) && $this->isValidFileType($dir) && !file_exists($tmp)){
					mkdir($tmp);
					file_put_contents($tmp,'');	
    			}else if(count(explode('.', $dir))<= 1 && !file_exists($tmp)){
    				mkdir($tmp);
    			}
    		}
    		else if($key > 0)
    			$tmp .= '/'.$dir;
    		if(!is_dir($tmp) && $key!= count($dirs)-1){
    			mkdir($tmp);
    		}
    	}
    }

    public function saveSource($url){
    	$this->parseLog("[Load From]".$url);
    	$source = $this->loadSite($url);
    	$url = explode('?',$url)[0];
    	$url = $this->dirValidChar($url);
        $url = $this->TEMPLATE_FOLDER.''.$this->dirValidChar($url);
        if(!is_dir($this->TEMPLATE_FOLDER))
            $this->makedir($this->TEMPLATE_FOLDER);
        $dirs = explode('/',$url);
        $tmp = '';
        $len = count($dirs);
        $this->parseLog("[ToDir]:$url");
        $this->LOCALADDRESS = $url;
        $this->makedir($this->dirValidChar($url));
        $urlPart = explode("/", $url);
        if(count(explode('.', $urlPart[count($urlPart)-1]))>=2 && $this->isValidFileType($urlPart[count($urlPart)-1])){
        	$this->HAVE_TYPES_FILES_TYPE = '';
        	file_put_contents($this->dirValidChar($url), $this->Decode($source));
        }
        else{
        	$this->HAVE_TYPES_FILES_TYPE = '.html';
        	if(substr($url,-1) == '/'){
	        	$a = strrev($url);
				$a = substr($a,1);
				$a = strrev($a);
				$url = $a;
			}
        	file_put_contents($this->dirValidChar($url.$this->HAVE_TYPES_FILES_TYPE), $this->Decode($source));
        }
    }

    public function loadSite($url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        if(strlen($output) < 5)
            $output = @file_get_contents($url);
        return $this->Encode($output);
    }
    //     public function Process($url){
    //         if($this->isGlobal($url)){
    // //            if(!$this->isExistSource($this->getClient($url))) {
    //                 $content = $this->loadSite($url);
    //                 $url = $this->getClient($url);
    //                 $this->parseLog("[Process][Global] with url:" . $url . " and data:" . substr($this->Decode($content), 0, $this->DATA_SHOW_LENGTH) . '...');
    // //            }
    //         }else if(!($this->isGlobal($url))){
    // //            if(!$this->isExistSource($url)) {
    //                 $content = $this->loadSite($this->DOMAIN . '/' . $url);
    //                 $this->parseLog("[Process][Client] with url:" . $url . " and data:" . substr($this->Decode($content), 0, $this->DATA_SHOW_LENGTH) . '...');
    // //            }
    //         }
    //         if(!$this->isExistSource($url))
    //             $this->saveSource($url,$content);
    //     }
    public function getSourceLinks($url,$data){
        if($this->isGlobal($url)) {
            if(preg_match_all($this->Pattern_RETURN_SITE_SOURCE_LINKS, $data, $linksArray))
                return $linksArray[1];
            else
                die($this->parseLog('[preg_match]:209 [getSourceLinks()] url:'.$url.'[die()]'));
        }else if(!$this->isGlobal($url)){
            $url = $this->DOMAIN.'/'.$url;
            if(preg_match_all($this->Pattern_RETURN_SITE_SOURCE_LINKS, $data, $linksArray))
                return $linksArray[1];
            else
                die($this->parseLog('[preg_match]:215 [getSourceLinks()] url:'.$url.'[die()]'));
        }
    }
}