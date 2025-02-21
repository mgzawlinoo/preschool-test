<?php 
include './auth-check.php';
include '../database/db.php'; 

if(isset($_GET['id']) && $_SERVER['REQUEST_METHOD'] === 'GET')  {
    $id = trim($_GET['id']);
    $status = trim($_GET['status']);
    $from = trim($_GET['from'] ?? 'students.php');
    $page = trim($_GET['page'] ?? '1');

 
    // check text from array list 
    $status_list = ['active', 'suspend', 'pending'];
    if(!in_array($status, $status_list)) {
        $_SESSION['error'] = 'Invalid status';
        header("Location: {$from}");
        exit;
    }

    // change user status to 0 
    $update_status_query = "UPDATE Students SET status = :status WHERE student_id = :student_id";
    $statement = $pdo->prepare($update_status_query);
    $statement->bindParam(":student_id", $id, PDO::PARAM_INT);
    $statement->bindParam(":status", $status, PDO::PARAM_STR);
    $statement->execute();

    // redirect to the page
    $_SESSION['success'] = 'Student status change successfully';
    if(isset($page)) {
        header("Location: {$from}?page={$page}");
        exit;
    }else {
        header("Location: {$from}");
        exit;
    }
}
