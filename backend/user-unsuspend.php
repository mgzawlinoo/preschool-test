<?php 
include './auth-check.php';
include '../database/db.php'; 

if(isset($_GET['id']) && $_SERVER['REQUEST_METHOD'] === 'GET')  {
    $id = trim($_GET['id']);
    $from = trim($_GET['from'] ?? 'users.php');
    $page = trim($_GET['page'] ?? '');

    // change user status to 0 
    $update_status_query = "UPDATE Users SET status = 1 WHERE user_id = :user_id";
    $statement = $pdo->prepare($update_status_query);
    $statement->bindParam(":user_id", $id, PDO::PARAM_INT);
    $statement->execute();

    // redirect to the page
    $_SESSION['success'] = 'Account unsuspend successfully';
    if(isset($page)) {
        header("Location: {$from}?page={$page}");
        exit;
    }else {
        header("Location: {$from}");
        exit;
    }
}
