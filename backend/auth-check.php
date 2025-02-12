<?php 
session_start();
if(!isset($_SESSION['admin_user']) || !isset($_SESSION['admin_user']['user_id']) || $_SESSION['admin_user']['role'] !== "Admin") {
    header("Location: /backend/login.php");
    exit;  
}

