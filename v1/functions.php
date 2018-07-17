<?php
// 1 April 2017
class Pattern
{
    public $HOST;
    public $SAVEWEB = 'templates/';
    private $ERROR;
    private $ExportedType ='';
    public function __construct()
    {
        $this->SampleTag = self::decode("PChcdyopPlx3KjwvXHcrPg==");
        $this->ComplicatedTag = self::decode("PChcdyspXHMoW2EtekEtWjAtOVwuXC9cXCciXD1cOlxzXXsxLH0pPihcdyopPC9cdys+");
        $this->AllTags = self::decode("KD86KD86XHMqfFx0KnxcdyopfFxCKTwoXHcrXGQqKSg/Oig/OlxzKlx3K1w9KD86XCdcIilcLyl8KT4oPzooW1x3KlxkKlxzKlwoXClcJ1wiXDtcOlwtXCtcLlw/XFxcIVx7XH1cW1xdXD1cL1wsXHxcQFwjXCRcJVxeXCZcKl17MCx9KSkoPzooPzo8XC9cdytcZCo+KXwp");
        $this->Link = self::decode("KD86KD86aHR0cHxodHRwc3xmdHB8c210cHx0ZnRwfHNmdHB8dGVsbHxtYXJrZXR8dGVsbmV0fHRjcHx1ZHB8c210cHxzbm1wfHBvcHxzc2h8c21ifHNzbHx0bHMpOlwvXC8oPzpbYS16QS1aMC05XC5cLVxfXD1dKilbXC9dPyhbYS16QS1aMC05XC9cXFwrXD1cPlw8XC5cOlw7XF9cLV0qKSk=");
        $this->Host = self::decode("KCg/Oig/Omh0dHB8aHR0cHN8ZnRwfHNtdHB8dGZ0cHxzZnRwfHRlbGx8bWFya2V0fHRlbG5ldHx0Y3B8dWRwfHNtdHB8c25tcHxwb3B8c3NofHNtYnxzc2x8dGxzKTpcL1wvfCkoKD86W2EtekEtWjAtOVwtXF9cLl17Myx9KSg/OltcLl0rKSg/OlthLXpdezIsNH0pKD86KD86OlswLTldezEsNX0pfCkpfGxvY2FsaG9zdClcLyg/Oltcd1wuXC1cX1w/XCZcPVwrXC9cJFwlXCpcKFwpXCxcPlw8XD9cIVx+XCNcQFwiXDpcO1x7XH1cXVxbXF5cfF0qKQ==");
        $this->Client = self::decode("KD86KD86KD86aHR0cHxodHRwc3xmdHB8c210cHx0ZnRwfHNmdHB8dGVsbHxtYXJrZXR8dGVsbmV0fHRjcHx1ZHB8c210cHxzbm1wfHBvcHxzc2h8c21ifHNzbHx0bHMpOlwvXC98KSg/OlthLXpBLVowLTlcLVxfXC5dezMsfSkuKD86W2Etel17Miw0fSkoPzooPzo6WzAtOV17MSw1fSl8KSlcLyhbXHdcLlwtXF9cP1wmXD1cK1wvXCRcJVwqXChcKVwsXD5cPFw/XCFcflwjXEBcIlw6XDtce1x9XF1cW1xeXHxdKik=");
        $this->SourceUrl = self::decode("PCg/Oltcd1xkXHNcJ1wiXD1cXFwvXDpcK1xAXCFcO1w/XCxcLVxfXSooPzpocmVmfHNyY3x1cmwpKT0oPzpcJ3xcIikoW1x3XGRccypcPVwvXDpcK1xAXCFcO1w/XCxcLVxfXC5cW1xdXClcKFwqXCZcJFx+XCVdKikoPzpbXCdeXHN8XCJeXHNdKQ==");
        $this->CheckGlobal = self::decode("KD86aHR0cHxodHRwc3xmdHB8c210cHx0ZnRwfHNmdHB8dGVsbHxtYXJrZXR8dGVsbmV0fHRjcHx1ZHB8c210cHxzbm1wfHBvcHxzc2h8c21ifHNzbHx0bHMpOlwvXC8oPzpbYS16QS1aMC05XC1cX1wuXXszLH0pLig/OlthLXpdezB9KSg/Oig/Olw6WzAtOV17MSw1fSl8KSg/OihcL1tcd1wuXC1cX1w/XCZcPVwrXC9cJFwlXCpcKFwpXCxcPlw8XD9cIVx+XCNcQFwiXDpcO1x7XH1cXVxbXF5cfF0qKXwp");
        $this->CheckGlobalClient = self::decode("KD86KD86aHR0cHxodHRwc3xmdHB8c210cHx0ZnRwfHNmdHB8dGVsbHxtYXJrZXR8dGVsbmV0fHRjcHx1ZHB8c210cHxzbm1wfHBvcHxzc2h8c21ifHNzbHx0bHMpOlwvXC8oPzpbYS16QS1aMC05XC5cLVxfXD9cPV0qKVtcL10/KFthLXpBLVowLTlcL1xcXCtcPVw/XD5cPFwuXDpcO1xfXC1dKykp");
    }
    public function saveIt($file,$data){
//       $tmp = explode("?",$file);
//      $file = $tmp[0].$this->ExportedType;
//        $file = $this->isolateLink($file);
        $file = $this->isolateLink($file);
        $file = str_replace("?",".htm",$file);
        $dirs = explode("/",$file);
        if(count($dirs) > 1) {
            $tmp = './'.$dirs[0];
            if(!file_exists($tmp) && !is_file($tmp))
                mkdir($tmp);
            for ($i=1;$i<count($dirs);$i++){
                $tmp = $tmp .'/'. $dirs[$i];
                if(!file_exists($tmp) && $i!=count($dirs)-1){
                    $this->logData("[mkdir:34]$tmp $dirs[$i]");
                    mkdir($tmp);
                }else if($i==count($dirs)-1) {
                    if(strpos("?",$tmp) >= 5){
                        $a = $dirs[$i];
                        $a = explode("?",$a);
                        $newTemp = str_replace($dirs[$i],$a[0].'('.$a[1].')index.html',$tmp);
                        $this->logData("[File_Save_Request] file:$file $dirs[$i]");
                        file_put_contents($newTemp, base64_decode($data));
                    }else{
                        $a = $dirs[$i];
                        $a = explode("?",$a);
                        $newTemp = str_replace($dirs[$i],$a[0],$tmp);
                        $this->logData("[File_Save_Request] file:$file $dirs[$i]");
                        file_put_contents($newTemp, base64_decode($data));
                    }
                }
            }

        }
    }
    public function execURL($URL){
        
        return $this->isolateExecute($URL);
    }
    public function decode($data)
    {
        return '/' . base64_decode($data) . '/';
    }
    public function encode($data)
    {
        return base64_encode($data);
    }
    public function isolateLink($URL){
        $url = $URL;
        $url = str_replace("https://",'',$url);
        $url = str_replace("http://",'',$url);
        $url = str_replace("./",'',$url);
        $url = str_replace("=",'',$url);
        $url = str_replace(":",'',$url);
        $url = explode('?',$url);
        $url = $url[0].'?';
        return $url;
    }
    public function isolateExecute($URL){
    	$URL = explode("?", $URL);
    	$URL = $URL[0];
    	$URL = str_replace("///", "/", $URL);
    	$URL = str_replace("/./", "/", $URL);
    	$URL = strtolower($URL);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $URL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
       	$output = curl_exec($ch);
       	if(strlen($output) < 5)
        	$output = @file_get_contents($URL);
        return $output;
    }
    public function logData($data){
        $this->ERROR = $data.'\n'.$this->ERROR;
        echo $data;
        flush();
        @ob_flush();
    }
    public function checkAddress($link)
    {
        if($this->isGlobal($link)) {
            $globalData = '';
            preg_match($this->CheckGlobalClient,$link,$outlink);
            if(is_array($outlink)) {
                for($i=0;$i<count($outlink);$i++){
                    if(strlen($outlink[$i]) == 1 || strlen($outlink[$i]) == 2) {
                        $globalData = $this->execURL($link);
                        $outlink[0] = 'index.html';
                        echo "strlen($outlink[$i]) == 1";
                        break;
                    }
                }
            }
            else {
                $link = str_replace('://','',$link);
                $link = str_replace('/','',$link);
                $globalData = $this->execURL($link);
            }
            $this->logData("[Host]".$link."[Global]".$this->SAVEWEB.$this->isolateLink($this->HOST).'/'.$outlink[0]."<Br>");
            $this->saveIt($this->SAVEWEB.$this->isolateLink($this->HOST).'/'.$outlink[0],$globalData);

            return true;
        }else if(!($this->isGlobal($link))){
            $this->logData("[Host]".$this->HOST.'/'.$link."[Client]".$this->SAVEWEB.$this->isolateLink($this->HOST).'/'.$link."<br>");
            $data = $this->execURL($this->HOST.'/'.$link);
            $this->saveIt($this->SAVEWEB.$this->isolateLink($this->HOST).'/'.$link,$data);
            return true;
        }
        return false;
    }
    public function isGlobal($link)
    {
        if(preg_match($this->Link,$link))
            return true;
        else
            return false;
    }
    public function setHost($h){
        $this->HOST = $h;
    }
    public function getHost($link)
    {
        if(strtolower($link) == 'localhost')
            $link = 'http://localhost/';
        return preg_match($this->CheckGlobal,$link,$result)?$result[0]:null;
    }
    public function getClient($link)
    {
        if(strtolower($link) == 'localhost')
            $link = 'http://localhost/';
        return preg_match($this->Client,$link,$result)?$result[0]:null;
    }
   	public function modifyLinks($link){
   		if(!($this->isGlobal($link))){
   			$link = './'.$link;
   		}
   		return $link;
   	}
    public function END(){
        if(is_dir('logs/') && file_exists('logs/')){
            file_put_contents('logs/'.$this->HOST.time().'.log',$this->ERROR);
        }
    }

}

?>