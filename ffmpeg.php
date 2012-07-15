<html><?php
 
echo "test";
// Full path to ffmpeg (make sure this binary has execute permission for PHP)
$ffmpeg = "/applications/xampp/xamppfiles/htdocs/ffmpeg";
 
// Full path to the video file
$videoFile = "Youtube/cwM6oCR8DMo.mp4";
 
// Full path to output image file (make sure the containing folder has write permissions!)
$imgOut = "YouTube/frame.jpg";
 
// Number of seconds into the video to extract the frame
$second = 5;
 
// Setup the command to get the frame image
$cmd = $ffmpeg." -i \"".$videoFile."\" -an -ss ".$second.".001 -y -f mjpeg \"".$imgOut."\" 2>&1";
 
// Get any feedback from the command
$feedback = `$cmd`;
echo $feedback; 
// Use $imgOut (the extracted frame) however you need to 
// ... 
 
?></html>