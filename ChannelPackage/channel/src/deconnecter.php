<?php
session_start();
if($_SESSION['GL_USER']['ID']!=session_id())header("location:index.php");
reset($_SESSION['GL_USER']);
header("location:index.php");

?>