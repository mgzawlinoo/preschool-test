<?php session_start(); ?>
<?php include './auth-check.php'; ?>
<?php include './database/db.php'; ?>
<!-- Header Start -->
<?php include("./layouts/header.php"); ?>

<?php 

    $errors = [];
    if(isset($_GET['payment_id']) && $_SERVER['REQUEST_METHOD'] == 'GET') {
        // check payment id 
        $payment_id = trim($_GET['payment_id']);
        $payment_id = filter_var($payment_id, FILTER_SANITIZE_NUMBER_INT);

        try {
            $check_payment_query = "SELECT * FROM Payments WHERE payment_id = :payment_id";
            $statement = $pdo->prepare($check_payment_query);
            $statement->bindParam(':payment_id', $payment_id, PDO::PARAM_INT);
            $statement->execute();
            $payment = $statement->fetch(PDO::FETCH_ASSOC);

            if(!$payment) {
                $errors['payment_not_found'] = 'Payment ID not found';
            }
            else {
                try {
                   $get_payment_query = "SELECT *, Students.name AS student_name, Classes.class_name AS class_name FROM Payments
                   LEFT JOIN Students ON Payments.student_id = Students.student_id
                   LEFT JOIN Classes ON Payments.class_id = Classes.class_id
                   WHERE payment_id = :payment_id";
                   $statement = $pdo->prepare($get_payment_query);
                   $statement->bindParam(':payment_id', $payment_id, PDO::PARAM_INT);
                   $statement->execute();
                   $payment = $statement->fetch(PDO::FETCH_ASSOC);
                }
                catch (Exception $e) {
                    $errors['dberror'] = $e->getMessage();
                }
            }
        }
        catch (Exception $e) {
            $errors['dberror'] = $e->getMessage();
        }
    }
?>

    <div class="container-xxl bg-white p-0">

        <!-- Navbar Start -->
        <?php include "./components/navbar.php"; ?>
        <!-- Navbar End -->

        <div class="container">
            <div class="row">
                 <!-- Show Error -->
                <?php if(isset($errors) && count($errors) > 0) : ?>
                    <div class="alert alert-danger mt-5">
                        <?php foreach($errors as $error) : ?>
                            <li><?php echo $error; ?></li>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
            <div class="row mt-5">
                <div class="col-md-6">
                    <div class="card mb-3">
                        <div class="row g-0 d-flex">
                            <div class="col-md-4">
                            <img src="<?= empty($user['photo']) ? 'http://placehold.co/300x300/000000/FFF' : $user['photo']  ?>" class="img-fluid rounded-start" alt="...">
                            </div>
                            <div class="col-md-8 align-self-center">
                            <div class="card-body ">
                                <h5 class="card-title"><?= $user['name'] ?></h5>
                                <small class="d-block my-1 text-muted">Phone : <?= $user['phone'] ?></small>
                                <small class="d-block my-1 text-muted">Email : <?= $user['email'] ?></small>
                                <small class="d-block my-1 text-muted">Address : <?= $user['address'] ?></small>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">

                <form action="register.php" method="POST" enctype="multipart/form-data" class="m-auto w-50 shadow rounded-2 p-5">
                     <input hidden type="text" name="payment_id" value="<?= $payment['payment_id'] ?>">
                     <input hidden type="text" name="student_id" value="<?= $payment['student_id'] ?>">
                     <input hidden type="text" name="class_id" value="<?= $payment['class_id'] ?>">
                    <div class="mb-3">
                          <?= $payment['student_name'] ?>
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

        <!-- Contact Start -->
        <?php include "./components/footer.php"; ?>
        <!-- Contact End -->

    </div>

<!-- Footer Start -->
<?php include "./layouts/footer.php"?>