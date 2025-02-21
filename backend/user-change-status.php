<?php 
include './auth-check.php';
include '../database/db.php'; 

if(isset($_GET['id']) && $_SERVER['REQUEST_METHOD'] === 'GET')  {
    $id = trim($_GET['id']);
    $status = trim($_GET['status']);
    $from = trim($_GET['from'] ?? 'users.php');
    $page = trim($_GET['page'] ?? '');

    // check if user is admin
    $check_user_role_query = "SELECT * FROM Users WHERE user_id = :user_id";
    $statement = $pdo->prepare($check_user_role_query);
    $statement->bindParam(":user_id", $id, PDO::PARAM_INT);
    $statement->execute();
    $user = $statement->fetch(PDO::FETCH_ASSOC);

    if($user['role'] === "Admin") {
        $_SESSION['error'] = 'You are not allowed to suspend this user';
        header("Location: {$from}");
        exit;
    }

    // check text from array list 
    $status_list = ['active', 'suspend', 'pending'];
    if(!in_array($status, $status_list)) {
        $_SESSION['error'] = 'Invalid status';
        header("Location: {$from}");
        exit;
    }

    // change user status to 0 
    $update_status_query = "UPDATE Users SET status = :status WHERE user_id = :user_id";
    $statement = $pdo->prepare($update_status_query);
    $statement->bindParam(":user_id", $id, PDO::PARAM_INT);
    $statement->bindParam(":status", $status, PDO::PARAM_STR);
    $statement->execute();

    // redirect to the page
    $_SESSION['success'] = 'Account status change successfully';
    if(isset($page)) {
        header("Location: {$from}?page={$page}");
        exit;
    }else {
        header("Location: {$from}");
        exit;
    }
}
