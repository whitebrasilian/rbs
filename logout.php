<?php
include("starter.php");

unset($_SESSION['user'],$_SESSION['manager']);

setcookie("cookname","",0,"/");
setcookie("cookpass","",0,"/");

header("Location:".$CFG->host."index.php");
?>