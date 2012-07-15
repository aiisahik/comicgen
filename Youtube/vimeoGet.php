<?php

class vimeograbber{

    var $vimeo_video_url;
    var $final_flv_filename;
    
    var $vimeo_video_id;
    
    var $cookies_path;
    var $curl_headers;
    
    var $vimeo_video_info;

    function __construct($vimeo_video_url, $final_flv_filename){
    
        //echo "<p>vimeo video url: {$vimeo_video_url} final flv filename: {$final_flv_filename}</p>";
    
        $this->vimeo_video_url = $vimeo_video_url;
        $this->final_flv_filename = $final_flv_filename;
        
        $this->load_vimeo_video_id();
        
        $this->cookies_path = "cookies.txt";
        $clear_cookies = $this->clear_cookies();
        
        $this->curl_headers = array( // not sure why, but web servers like windows computers better than robots. ;)
        	"Accept-Language: en-us",
        	"User-Agent: Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.2.15) Gecko/20110303 Firefox/3.6.15",
        	"Connection: Keep-Alive",
        	"Cache-Control: no-cache"
        	);
        	
        $this->load_vimeo_video_info();

        $this->curl_get_flv_binary();
    
    }
    
    function load_vimeo_video_id(){
    
        preg_match("/http:\/\/(?:www\.)?vimeo\.com\/(?:clip:)?(\d+)/i", $this->vimeo_video_url, $matches);
        $id = $matches[1];
        
        $this->vimeo_video_id = $id;
        
        return $this->vimeo_video_id;
    
    }
    
    function load_vimeo_video_info(){
    
        $vimeo_data_url = "http://player.vimeo.com/config/{$this->vimeo_video_id}";
        
        //echo $vimeo_data_url;
        
        $vimeo_data = json_decode($this->curl_get_url($vimeo_data_url));

        
        $signature = $vimeo_data->request->signature;
        $time = $vimeo_data->request->timestamp;

        
        $video_url = "http://player.vimeo.com/play_redirect?clip_id={$this->vimeo_video_id}&sig={$signature}&time={$time}&quality=sd&codecs=H264,VP8,VP6&type=moogaloop&embed_location=";
        
        //echo "<br>video_url: ".$video_url."<br>";
        
        $this->vimeo_video_info = array('flv_url'=>$video_url);
    
    }
    
    function clear_cookies(){
    
        if(file_exists($this->cookies_path)){
            unlink($this->cookies_path);
        }
    
        $ourFileName = $this->cookies_path;
        $ourFileHandle = fopen($ourFileName, 'w') or die("can't open file");
        fclose($ourFileHandle);

    }
    
    function curl_get_url($url){
        $cookie_path = $this->cookies_path;
        
        $headers = $this->curl_headers;
        	
        
        $ch = curl_init();	

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); 

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);


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
    
    function curl_get_flv_binary(){
    
        $url = $this->vimeo_video_info['flv_url'];
        $cookie_path = $this->cookies_path;
        
        $headers = $this->curl_headers;
        
        $final_flv_filename = $this->final_flv_filename;
      	      	
      	//echo "<br>url: $url<br><br>";
      	      	
      	$ch = curl_init($url);
      	$fp = fopen($final_flv_filename, "w");
      	curl_setopt($ch, CURLOPT_FILE, $fp);
      	curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
      	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_path);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_path);
      	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); 
      	curl_exec($ch);
      	
      	

        //echo 'Curl error: ' . curl_error($ch);

      	
      	curl_close($ch);
      	fclose($fp);
    
    }


}

//example usage: 
$vimeo_grabber = new vimeograbber("http://vimeo.com/32001208", "vimeo_test.mp4");

?>