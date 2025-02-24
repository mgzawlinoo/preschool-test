<?php
session_start();
if(isset($_SESSION['user']) 
&& isset($_SESSION['user']['role']) 
&& isset($_SESSION['user']['user_id'])
&& isset($_SESSION['user']['name'])
&& $_SESSION['user']['role'] == 'Parent') {
    header('Location: index.php');
    exit;
}
include './database/db.php';

$errors = [];

// Validation Start
if( isset($_POST['login']) && $_SERVER['REQUEST_METHOD'] == 'POST' ) {

    // Assign form data to variables and remove whitespace
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Filter the input to prevent SQL injection
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    $password = htmlspecialchars($password);


    // ####### VALIDATION START ######

    // Check empty fields
    empty($email) ? $errors['email'] = 'Email is required' : '';
    empty($password) ? $errors['password'] = 'Password is required' : '';

    // Check Email Format
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Invalid Email Format';
    }

    if( count($errors) == 0 ) {
        // Check User already exists
        try {
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

                    // email နဲ့ password မှန်သွားပြီ ဆိုမှ role က parent ဟုတ်မဟုတ် စစ်မယ် , active ဖြစ်မဖြစ် စစ်မယ်
                    if ($user['role'] != 'Parent') {
                        $errors['role'] = 'Invalid Role, Currently Parent role can login!';
                    }
                    elseif ($user['status'] != 'active') {
                        $errors['status'] = 'Your account is not active yet!';
                    }
                    else {
                        try {
                            $get_parent_query = "SELECT * FROM Parents WHERE user_id = :user_id";
                            $statement = $pdo->prepare($get_parent_query);
                            $statement->bindParam(":user_id", $user['user_id'], PDO::PARAM_INT);
                            $statement->execute();
                            $parent = $statement->fetch(PDO::FETCH_ASSOC);
    
                            unset($_SESSION['user']);
                            $_SESSION['user']['user_id'] = $user['user_id'];
                            $_SESSION['user']['parent_id'] = $parent['parent_id'];
                            $_SESSION['user']['name'] = $parent['name'];
                            $_SESSION['user']['photo'] = $parent['photo'] ? './backend/uploads/' . $parent['photo'] : '';
                            $_SESSION['user']['role'] = $user['role'];
                            $_SESSION['user']['email'] = $user['email'];
                            $_SESSION['user']['phone'] = $parent['phone'];
                            $_SESSION['user']['address'] = $parent['address'];
                            header('Location: profile.php');
                            exit;
                        }
                        catch(PDOException $e) {
                            $errors['dberror'] = $e->getMessage();
                        }
                    }  
                }
                else {
                    $errors['password'] = 'Invalid Email or Password';
                }
            }
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
              <h1 class="mb-3">Parent Login Form</h1>
          </div>
          <div class="row g-4">
              <div class="col-12 wow fadeInUp" data-wow-delay="0.1s">

                  <form action="login.php" method="POST" class="m-auto w-50 shadow rounded-2 p-5">

                      <div class="mb-3">
                          <label for="">Email</label>
                          <input type="email" class="form-control" name="email" placeholder="Email Address" id="email" autofocus >
                      </div>
                      <div class="mb-3">
                          <label for="">Password</label>
                          <input type="password" class="form-control" name="password" placeholder="Password" id="password" >
                      </div>
                      <div class="text-center">
                          <input type="submit" value="Login" class="btn btn-primary text-white" name="login" />
                      </div>

                      <div class="mx-auto text-center mt-3 alert alert-warning">
                        <span><i class="fas fa-exclamation-triangle"></i> Don't have an account? <a href="register.php">Register</a></span>
                    </div>

                  </form>
              </div>
          </div>
      </div>
  </div>
