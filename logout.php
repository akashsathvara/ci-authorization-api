<?php
session_start();
unset($_SESSION['userData']);
session_destroy();
header("location: login.php");

?>