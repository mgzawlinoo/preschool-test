<?php 
session_start();
if(!isset($_SESSION['user']) 
|| !isset($_SESSION['user']['user_id']) 
|| !isset($_SESSION['user']['name']) 
|| !isset($_SESSION['user']['role']) 
|| $_SESSION['user']['role'] !== "Parent") {
    header("Location: ./login.php");
    exit;  
}
else {
    $user = $_SESSION['user'];
}

