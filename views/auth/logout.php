<?php
require "../../config/helper.php";
session_start();

$_SESSION['is_logged_in']=false;
header("Location:".url("/views/auth/login.php"));
