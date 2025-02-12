<?php
session_start();
include '../database/db.php';

$errors = [];

// Validation Start
if( ($_SERVER['REQUEST_METHOD'] == 'POST') AND isset($_POST['login'])) {

// Assign form data to variables and remove whitespace
$email = trim($_POST['email']);
$password = trim($_POST['password']);

// Filter the input to prevent SQL injection
$email = filter_var($email, FILTER_SANITIZE_EMAIL);
$password = filter_var($password, FILTER_SANITIZE_STRING);


// ####### VALIDATION START ######

// Check empty fields
empty($email) ? $errors['email'] = 'Email is required' : '';
empty($password) ? $errors['password'] = 'Password is required' : '';

// Check Email Format
if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = 'Invalid Email Format';
}

// Check User already exists
$check_user_exists_query = "SELECT * FROM Users WHERE email = :email";
$statement = $pdo->prepare($check_user_exists_query);
$statement->bindParam(':email', $email, PDO::PARAM_STR);
$statement->execute();

if($statement->rowCount() <= 0) {
    $errors['email'] = 'Email not found';
}
else {
    // user ကို တွေ့ပြီ ဆိုရင် fetch တကြောင်းဆွဲယူမယ်
    $user = $statement->fetch(PDO::FETCH_ASSOC);
    // ဆွဲယူပြီး ရလာတာကို မှ email နဲ့ password ကို တိုက်စစ်မယ်
    if($user['email'] === $email && password_verify($password, $user['password'])) {

        $get_user_name_query = "SELECT name, photo FROM Admins WHERE user_id = :user_id";

        $statement = $pdo->prepare($get_user_name_query);
        $statement->bindParam(':user_id', $user['user_id'], PDO::PARAM_INT);
        $statement->execute();
        $admin_user = $statement->fetch(PDO::FETCH_ASSOC);

        if($admin_user) {
            $_SESSION['admin_user']['user_id'] = $user['user_id'];
            $_SESSION['admin_user']['name'] = $admin_user['name'];
            $_SESSION['admin_user']['photo'] = $admin_user['photo'];
            $_SESSION['admin_user']['role'] = $user['role'];
            header('Location: index.php');
            exit;
        }
        else {
            $errors['auth_error'] = 'Invalid User Role';
        }
        }
    else {
        $errors['password'] = 'Invalid Email or Password';
    }
}
// ####### VALIDATION END ######

}
?>

<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Little Stars Preschool - Login</title>
<base href="/backend/">
<link rel="stylesheet" href="./css/bootstrap.min.css">
<link rel="stylesheet" href="./css/bootstrap-icons.min.css">
<link href="./css/style.css" rel="stylesheet">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Schoolbell&display=swap" rel="stylesheet">
<style>
    body {
        background-image: url("./img/bg.jpg");
        background-size: cover;
        font-family: "Schoolbell", serif;
        font-weight: 400;
        font-style: normal;
    }
</style>
</head>

<body class="d-flex vh-100">
    <div class="container align-self-center">

        <div class="row">
            <div class="col-md-4 mx-auto">
                <img src="./img/icon.png" width="150px" height="auto" />
                    <h4 class="mb-3">Login Form</h4>

                    <?php
                        // Output errors
                        if(count($errors) > 0) {
                            foreach($errors as $error) {
                                echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                                        $error
                                        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                                        </div>";
                            }
                        }
                    ?>

                    <form action="login.php" method="POST" class="bg-white m-auto w-100 shadow rounded-2 p-5">

                        <div class="mb-3">
                            <label for="">Email</label>
                            <input type="email" class="form-control" name="email" placeholder="Email Address" id="email">
                        </div>
                        <div class="mb-3">
                            <label for="">Password</label>
                            <input type="password" class="form-control" name="password" placeholder="Password" id="password">
                        </div>
                        <div class="text-center">
                            <input type="submit" value="Login" class="btn btn-primary px-4 py-2 text-white" name="login" />
                        </div>

                    </form>
            </div>
        </div>

    </div>

<script src="./js/bootstrap.bundle.min.js"></script>
</body>

</html>