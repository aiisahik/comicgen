<?php

// Path to PHP-SDK
require '/Applications/XAMPP/xamppfiles/htdocs/src/facebook.php';

$facebook = new Facebook(array(
  'appId'  => '320641024695712',
  'secret' => '09284f41912377d7774392ca81066883',
  'fileUpload' => true,
));
 
// See if there is a user from a cookie
$user = $facebook->getUser();

if ($user) {
  try {
    // Proceed knowing you have a logged in user who's authenticated.
    $user_profile = $facebook->api('/me');
  } catch (FacebookApiException $e) {
    echo '<pre>'.htmlspecialchars(print_r($e, true)).'</pre>';
    $user = null;
  }

  $access_token = $facebook->getAccessToken();

  $photo = './test.JPG';
  $message = '@ the Dropbox hackathon w free food!';
  $attachment = array(
            // 'message' => $message,
            // 'link' => "http://twitter.com/#!/search/%23PHD3",
            // 'type'=>'photo',
            'source'=> '@'.$photo,
            // 'access_token' =>$access_token,
    );

	try {
	  $facebook->api('/me/photos', 'POST', $attachment);
	}
	catch (FacebookApiException $e) {
	        error_log('Could not post image to Facebook.');
	      }
}else{
	echo'Not logged in';
}

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	$url = (isset($_POST['url']) && !empty($_POST['url'])) ? $_POST['url'] : false;
	if (!$url) {
		echo "Please enter a URL.";
	} else {
		$source = file_get_contents($url);
		$source = urldecode($source);
		
		// Extract video title.
		$vTitle_results_1 = explode('<title>', $source);
		$vTitle_results_2 = explode('</title>', $vTitle_results_1[1]);
		
		$title = trim(str_replace(' - YouTube', '', trim($vTitle_results_2[0])));
		
		// Extract video download URL.
		$dURL_results_1 = explode('url_encoded_fmt_stream_map": "url=', $source);
		$dURL_results_2 = explode('\u0026quality', $dURL_results_1[1]);
		
		// Force download of video.
		$file = str_replace(' ', '_', strtolower($title)).'.mp4';
		
		header("Cache-Control: public");
		header("Content-Description: File Transfer");
		header("Content-Disposition: attachment; filename=$file");
		header("Content-Type: video/mp4");
		header("Content-Transfer-Encoding: binary");
		
		readfile($dURL_results_2[0]);

	}
}

?>

<form method="post">
	<label for="url">URL:</label> 
	<input type="text" name="url" value="" id="url"> 
	<input type="submit" name="submit" value="Download">
</form>

<!DOCTYPE html>
<html xmlns:fb="http://www.facebook.com/2008/fbml">
<head>
    <title>Facebook Application Local Development</title>
    <link type="text/css" rel="stylesheet" href="css/reset.css">
    <link type="text/css" rel="stylesheet" href="css/main.css">
</head>
  <body>
    <div id="login">
        <?php if (!$user) { ?>
          <fb:login-button scope="read_stream,publish_stream,user_photos,photo_upload"></fb:login-button>
        <?php } ?>
    </div>
    <div id="cont">
        <?php if ($user): ?>
        <img src="https://graph.facebook.com/<?php echo $user; ?>/picture">
        <p>Hello <?php echo $user_profile['name']; ?>!</p>
 
        <?php else: ?>
        <strong><em>You are not Connected.</em></strong>
        <?php endif ?>
    </div>
    <div id="fb-root"></div>
    <script>
      window.fbAsyncInit = function() {
        FB.init({
          appId: '<?php echo $facebook->getAppID() ?>',
          cookie: true,
          xfbml: true,
          oauth: true
        });
        FB.Event.subscribe('auth.login', function(response) {
          window.location.reload();
        });
        FB.Event.subscribe('auth.logout', function(response) {
          window.location.reload();
        });
      };
      (function() {
        var e = document.createElement('script'); e.async = true;
        e.src = document.location.protocol +
          '//connect.facebook.net/en_US/all.js';
        document.getElementById('fb-root').appendChild(e);
      }());
    </script>
  </body>
</html>