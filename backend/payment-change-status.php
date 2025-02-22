<?php 
include './auth-check.php';
include '../database/db.php'; 

if(isset($_GET['id']) && isset($_GET['status']) && $_SERVER['REQUEST_METHOD'] === 'GET')  {
    $payment_id = trim($_GET['id']);
    $payment_status = trim($_GET['status']);
    $from = trim($_GET['from'] ?? 'payments.php');
    $page = trim($_GET['page'] ?? '1');

 
    // check text from array list 
    $payment_status_list = ['paid', 'checking', 'unpaid', 'decline'];
    if(!in_array($payment_status, $payment_status_list)) {
        $_SESSION['error'] = 'Invalid status';
        header("Location: {$from}");
        exit;
    }

    // change user status to 0 
    $update_status_query = "UPDATE Payments SET payment_status = :payment_status WHERE payment_id = :payment_id";
    $statement = $pdo->prepare($update_status_query);
    $statement->bindParam(":payment_id", $payment_id, PDO::PARAM_INT);
    $statement->bindParam(":payment_status", $payment_status, PDO::PARAM_STR);
    $statement->execute();

    // redirect to the page
    $_SESSION['success'] = 'Payment status change successfully';
    if(isset($page)) {
        header("Location: {$from}?page={$page}");
        exit;
    }else {
        header("Location: {$from}");
        exit;
    }
}
header("Location: payments.php");
exit;
