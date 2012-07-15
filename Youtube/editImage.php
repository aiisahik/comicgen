<?php
  include 'phpFuncs.php';

  echo(json_encode(editImage($_GET["filename"], array($_GET["x"], $_GET["y"]), $_GET["text"], $_GET["fontsize"])));
?>