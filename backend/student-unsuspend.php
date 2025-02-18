<?php 
include './auth-check.php';
include '../database/db.php'; 

if(isset($_GET['id']) && $_SERVER['REQUEST_METHOD'] === 'GET')  {
    $id = trim($_GET['id']);
    $page = trim($_GET['page'] ?? '1');

    // change student status to 0 
    $update_status_query = "UPDATE Students SET status = 1 WHERE student_id = :student_id";
    $statement = $pdo->prepare($update_status_query);
    $statement->bindParam(":student_id", $id, PDO::PARAM_INT);
    $statement->execute();

    // redirect to the page
    $_SESSION['success'] = 'Account unsuspend successfully';
    header("Location: students.php?page={$page}");
    exit;
}
