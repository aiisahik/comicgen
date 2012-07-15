<?php
  include 'phpFuncs.php';
  $var = json_decode($_GET['image_files'], 1);
  echo(json_encode(stitchImages($var)));
  //echo(json_encode("hello world"));
?>