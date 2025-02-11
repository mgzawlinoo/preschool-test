<?php
session_start();
include './database/db.php';

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
            $_SESSION['user']['user_id'] = $user['user_id'];
            $_SESSION['user']['role'] = $user['role'];
            header('Location: index.php?success=true');
            exit;
         }
        else {
            $errors['password'] = 'Invalid Email or Password';
        }
    }
    // ####### VALIDATION END ######

}
?>

<div class="container-xxl py-5" id="classes">
      <div class="container">

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

          <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px">
              <h1 class="mb-3">Login Form</h1>
          </div>
          <div class="row g-4">
              <div class="col-12 wow fadeInUp" data-wow-delay="0.1s">
                  <form action="login.php" method="POST" class="m-auto w-50 shadow rounded-2 p-5">

                      <div class="mb-3">
                          <label for="">Email</label>
                          <input type="email" class="form-control" name="email" placeholder="Email Address" id="email">
                      </div>
                      <div class="mb-3">
                          <label for="">Password</label>
                          <input type="password" class="form-control" name="password" placeholder="Password" id="password">
                      </div>
                      <div class="text-center">
                          <input type="submit" value="Login" class="btn btn-primary text-white" name="login" />
                      </div>

                      <div class="text-center mt-3">
                          <p>Don't have an account? <a href="register.php">Register</a></p>
                      </div>

                  </form>
              </div>
          </div>
      </div>
  </div>
