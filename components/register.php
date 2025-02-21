<?php
session_start();
include './database/db.php';

$errors = [];
$now = new DateTime();
$now = $now->format('Y-m-d H:i:s');

// Validation Start
if( isset($_POST['register']) && $_SERVER['REQUEST_METHOD'] == 'POST') {

    // Assign form data to variables and remove whitespace
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm-password']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);

    // Filter the input to prevent SQL injection
    $name = htmlspecialchars($name);
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    $password = htmlspecialchars($password);
    $confirm_password = htmlspecialchars($confirm_password);
    $phone = htmlspecialchars($phone);
    $address = htmlspecialchars($address);

    // ####### VALIDATION START ######
    // Check empty fields
    if(empty($name) || empty($email) || empty($password) || empty($confirm_password) || empty($phone) || empty($address)) {
        $errors['empty'] = 'All fields are required';
    }
    else {
        // Check Email Format
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Invalid Email Format';
        }

        // Check password match
        if($password != $confirm_password) {
            $errors['password'] = 'Password does not match';
        }

        // check password length
        if(strlen($password) < 6) {
            $errors['password'] = 'Password must be at least 6 characters';
        }

        // Check Email already exists
        try {
            $check_email_exists_query = "SELECT email FROM Users WHERE email = :email";
            $statement = $pdo->prepare($check_email_exists_query);
            $statement->bindParam(':email', $email, PDO::PARAM_STR);
            $statement->execute();
            if($statement->rowCount() > 0) {
                $errors['email'] = 'Email already exists';
            }
        } catch (Exception $e) {
            $errors['dberror'] = $e->getMessage();
        }
    }
    // ####### VALIDATION END ######

    // Validation Completed and Insert Data
    if(count($errors) == 0) {

        // Hash Password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert User Data into Database
        try {
            $role = 'Parent';
            $insert_user_query = "INSERT INTO Users (email, password, role, created_at, updated_at) 
            VALUES (:email, :password, :role, :created_at, :updated_at)";
    
            $statement = $pdo->prepare($insert_user_query);
            $statement->bindParam(":email", $email, PDO::PARAM_STR);
            $statement->bindParam(":password", $hashed_password, PDO::PARAM_STR);
            $statement->bindParam(":role", $role, PDO::PARAM_STR);
            $statement->bindParam(":created_at", $now, PDO::PARAM_STR);
            $statement->bindParam(":updated_at", $now, PDO::PARAM_STR);
            $statement->execute();
            $user_id = $pdo->lastInsertId();
    
            $insert_parent_query = "INSERT INTO Parents (user_id, name, phone, address) 
            VALUES (:user_id, :name, :phone, :address)"; 
            $statement = $pdo->prepare($insert_parent_query);
            $statement->bindParam(":user_id", $user_id, PDO::PARAM_INT);
            $statement->bindParam(":name", $name, PDO::PARAM_STR);
            $statement->bindParam(":phone", $phone, PDO::PARAM_STR);
            $statement->bindParam(":address", $address, PDO::PARAM_STR);
            $statement->execute();

            $_SESSION['success'] = 'Parent registered successfully';
            header("Location: ../index.php");
            exit();
        }
        catch (Exception $e) {
            $errors['dberror'] = $e->getMessage();
        }
    }

}
?>

<div class="container-xxl py-5" id="classes">
      <div class="container">

            <!-- Show Error -->
            <?php if(isset($errors) && count($errors) > 0) : ?>
                <div class="alert alert-danger">
                    <?php foreach($errors as $error) : ?>
                        <li><?php echo $error; ?></li>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

          <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px">
              <h1 class="mb-3">Parent Register Form</h1>
          </div>
          <div class="row g-4">
              <div class="col-12 wow fadeInUp" data-wow-delay="0.1s">

                  <form action="register.php" method="POST" class="m-auto w-50 shadow rounded-2 p-5">
                     <div class="mb-3">
                          <label for="">Name</label>
                          <input type="text" class="form-control" name="name" placeholder="Name" id="name"
                           value="<?= $_POST['name'] ?? '' ?>" autofocus>
                      </div>
                      <div class="mb-3">
                          <label for="">Email</label>
                          <input type="email" class="form-control" name="email" placeholder="Email Address" id="email"
                          value="<?= $_POST['email'] ?? '' ?>">
                      </div>
                      <div class="mb-3">
                          <label for="">Phone</label>
                          <input type="text" class="form-control" name="phone" placeholder="Phone" id="phone"
                          value="<?= $_POST['phone'] ?? '' ?>">
                      </div>
                      <div class="mb-3">
                          <label for="">Password</label>
                          <input type="password" class="form-control" name="password" placeholder="Password" id="password">
                      </div>
                      <div class="mb-3">
                          <label for="">ConfirmPassword</label>
                          <input type="password" class="form-control" name="confirm-password" placeholder="Confirm Password" id="confirm-password">
                      </div>

                      <div class="mb-3">
                          <label for="">Address</label>
                          <textarea class="form-control" name="address" placeholder="Address" id="address" rows="3"><?= $_POST['address'] ?? '' ?></textarea> 
                      </div>
                      <div class="text-center">
                          <input type="submit" value="Register" class="btn btn-primary text-white" name="register" />
                      </div>

                      <div class="mx-auto text-center mt-3 alert alert-warning">
                        <span><i class="fas fa-exclamation-triangle"></i> Already have an account? <a href="login.php">Login</a></span>
                      </div>

                  </form>
              </div>
          </div>
      </div>
  </div>
