<!DOCTYPE html>
<html>
  <title>ViMeGen!!!</title>
  <head>
    
      <link href="css/bootstrap.css" media="all" rel="stylesheet" type="text/css" />
      <link href="css/jquery-ui-1.8.21.custom.css" media="all" rel="stylesheet" type="text/css" />
      <script src="js/jquery-1.7.2.min.js" type="text/javascript"></script>
      <script src="js/jquery-ui-1.8.21.custom.min.js" type="text/javascript"></script>      
      <script src="js/json2.js" type="text/javascript"></script>
      <script src="js/json_parse.js" type="text/javascript"></script>
      <script src="js/json_parse_state.js" type="text/javascript"></script>
      <script src="js/underscore.js" type="text/javascript"></script>
      <script src="js/backbone.js" type="text/javascript"></script>
	  <script src="js/functions.js" type="text/javascript"></script>

      <script type="text/template" id="person-template">
          <div class="thumbnail">
            <img src="<%= pictureUrl %>" alt="">
            <h5><%= firstName %> <%= lastName %></h5>
            <p><%= headline %></p>
          </div>
      </script>
	  <?php
		include("server/phpFuncs.php");
		?>
  </head>
  <body>
    
		<?php
	/*
		$imageAndDim = editImage("cow-dolphin.jpg", array(630, 80), "So kick your flipper \n like this...", 24);
		$image1 = $imageAndDim[0];
		imagejpeg ( $image1, "cow-dolphin-altered.jpg" );

		$imageAndDim = editImage("cow-dolphin.jpg", array(320, 80), "I don't have a flipper.", 24);
		$image2 = $imageAndDim[0];
		$res = stitchImages(array($image1, $image2, $image1, $image2), $imageAndDim[1], $imageAndDim[2], true);
		//imagejpeg ( $image1, "cow-dolphin-altered1.jpg" );
		imagejpeg ( $res, "cow-dolphin-altered1.jpg" );
	
	*/?>
	
	<script type="text/javascript">
		$(function(){
			var myStuff = new Array("cow-dolphin.jpg", "cow-dolphin-altered.jpg");
			doStitch(myStuff);
		});
	</script>
	
	<!--<img src="cow-dolphin-altered.jpg" alt="this is edited">
	<img src="cow-dolphin-altered1.jpg" alt="this is edited">-->
  </body>
</html>