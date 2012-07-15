<?php

class youtubegrabber{

    var $youtube_video_url;
    var $test;
    var $final_flv_filename;
    var $cookies_path;
    var $curl_headers;
    var $flv_url;

    function __construct($youtube_video_url, $final_flv_filename, $test = 0){
        $this->youtube_video_url = $youtube_video_url;
        $this->test = $test;
        $this->final_flv_filename = $final_flv_filename;
        
        $this->youtube_video_id = $this->get_youtube_video_id();
        
        $this->cookies_path = "cookies.txt";
        $clear_cookies = $this->clear_cookies();
        
        $this->curl_headers = array(
        	"Accept-Language: en-us",
        	"User-Agent: Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.2.15) Gecko/20110303 Firefox/3.6.15",
        	"Connection: Keep-Alive",
        	"Cache-Control: no-cache"
        	);
        	
        $this->flv_url = $this->get_flv_url();
        
        $save_binary = $this->get_curl_binary();
        $clear_cookies = $this->clear_cookies();
    
    }
    
    function get_youtube_video_id(){
        $thearray = explode("watch?v=", $this->youtube_video_url);
        return $thearray[1];
    }
    
    
    function clear_cookies(){
    
        if(file_exists($this->cookies_path)){
            unlink($this->cookies_path);
        }
        
        $ourFileName = $this->cookies_path;
        $ourFileHandle = fopen($ourFileName, 'w') or die("can't open file");
        fclose($ourFileHandle);
        
    }
    
    function get_flv_url(){
    
        $html = $this->curl_get_url($this->youtube_video_url);
        
       
        preg_match_all("/var.*?swf.*?=.*?\"(.*?)watch-player.*?innerHTML.*?=.*?swf/is", $html, $matches);
        
        
        $decoded = urldecode($matches[1][0]);
        preg_match_all("/url=(.*?)\,/is", $decoded, $matches);
        $matches = $matches[1];
        
        
        for($i = 0; $i < count($matches); $i++){
            $test = explode("&", $matches[$i]);
            $matches[$i] = $test[0];
            $matches[$i] = urldecode($matches[$i]);
        }
        
       
        $final_flv_url = "";
        
        foreach($matches AS $this_url){
            $headers = $this->curl_get_headers($this_url);
            
            $headers = split("\n", trim($headers));
            foreach($headers as $line) {
                if (strtok($line, ':') == 'Content-Type') {
                    $parts = explode(":", $line);
                    $content_type = strtolower(trim($parts[1]));
                    if ( $this->contains("video/x-flv", $content_type) ){
                        $final_flv_url = $this_url;
                        return $final_flv_url;
                    }
                }
            }
        }
        
        return false;
        
    }
    
    function curl_get_url($url){
        $cookie_path = $this->cookies_path;
        
        $headers = $this->curl_headers;
        	
        
        $ch = curl_init();	
        //$referer = 'http://www.google.com/search';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); // this pretends this scraper to be browser client IE6 on Windows XP, of course you can pretend to be other browsers just you have to know the correct headers
        //curl_setopt($get, CURLOPT_REFERER, $referer); // lie to the server that we are some visitor who arrived here through google search
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // set user agent
        //curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13");
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_path);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_path);
        curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
        curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 5 );
        
        $output = curl_exec($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);
        
        return $output;
    }
    
    function curl_get_headers($url){
        $cookie_path = $this->cookies_path;
        
        $headers = $this->curl_headers;
        	
        $ch = curl_init();	
        //$referer = 'http://www.google.com/search';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); 
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_NOBODY, 1);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_path);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_path);
        curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
        curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 5 );
        
        $results = curl_exec($ch);
        
        return $results;
    }
    
    function get_curl_binary(){
        $url = $this->flv_url;
        $cookie_path = $this->cookies_path;
        $headers = $this->curl_headers;
      	$final_flv_filename = $this->final_flv_filename;
      	
      	$ch = curl_init($url);
      	$fp = fopen($final_flv_filename, "w");
      	curl_setopt($ch, CURLOPT_FILE, $fp);
      	curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
      	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_path);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_path);
      	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); // this pretends this scraper to be browser client IE6 on Windows XP, of course you can pretend to be other browsers just you have to know the correct headers
      	curl_exec($ch);
      	curl_close($ch);
      	fclose($fp);
    }
    
    function contains($substring, $string) {
            $pos = strpos($string, $substring);
     
            if($pos === false) {
                    // string needle NOT found in haystack
                    return false;
            }
            else {
                    // string needle found in haystack
                    return true;
            }
     
    }

}

$url = "http://www.youtube.com/watch?v=XZxo7IznQnk";
$filename = "test.flv";
$youtubegrabber = new youtubegrabber($url, $filename, 0);
echo "Should be done.<br>";
?>