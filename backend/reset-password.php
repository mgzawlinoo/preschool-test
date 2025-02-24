<?php include '../database/db.php'; ?>
<?php include './layouts/header.php'; ?>

<?php 
    $errors = [];
    // ှShow Parent Data from Get ID and Fill it to form
    if(isset($_GET['id']) && isset($_GET['name'])) {
        $user_id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
        $user_id = trim($user_id);
        $name = trim(htmlspecialchars($_GET['name']));

        try {
            $get_user_query = "SELECT user_id FROM Users WHERE user_id = :user_id";
            $statement = $pdo->prepare($get_user_query);
            $statement->bindParam(":user_id", $user_id, PDO::PARAM_INT);
            $statement->execute();
            $user = $statement->fetch(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            $errors['db_error'] = $e->getMessage();
        }
    }
?>

<?php 
  // UPDATE STUDENT
  if (isset($_POST['update_password']) && $_SERVER['REQUEST_METHOD'] == 'POST') {

    $errors = [];
    
    // trim inputs value
    $user_id = trim($_POST['user-id']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm-password']);

    // filter inputs
    $user_id = filter_var($user_id, FILTER_SANITIZE_NUMBER_INT);
    $password = htmlspecialchars($password);
    $confirm_password = htmlspecialchars($confirm_password);

    // check empty fields
    if (empty($user_id) || empty($password) || empty($confirm_password)) {
        $errors['empty'] = 'All fields are required';
    }

    // Check password match
    if(strcmp($password, $confirm_password) !== 0) {
        $errors['password'] = 'Password does not match';
    }

    // check password length
    if(strlen($password) < 8) {
        $errors['password'] = 'Password must be at least 8 characters';
    }

    // update query for student
    if(count($errors) == 0) {

        // Hash Password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        try {
            $update_parent_query = "UPDATE Users SET password=:password WHERE user_id=:user_id"; //error
            $statement = $pdo->prepare($update_parent_query);
            $statement->bindParam(":user_id", $user_id, PDO::PARAM_INT);
            $statement->bindParam(":password", $hashed_password, PDO::PARAM_STR);
            $statement->execute();

            $_SESSION['success'] = 'Password reset successfully';
            header("Location: users.php");
            exit();

        }
        catch (PDOException $e) {
            $errors['db_error'] = $e->getMessage();
        }
    }
}
?>

    <div class="wrapper">
        <!-- Sidebar -->
        <?php include 'components/sidebar.php'; ?>

        <!-- Page Content -->
        <div id="content">
            <!-- Top Navigation -->
            <?php include 'components/top-nav.php'; ?>

            <!-- Main Content -->
            <div class="container-fluid p-4">
                <div class="bg-warning text-black p-4 rounded   d-flex justify-content-between align-items-center mb-4">
                    <h2 class="mb-0"><i class="bi bi-key"></i> Reset Password</h2>
                </div>

                <?php if(isset($errors) && count($errors) > 0) : ?>
                    <div class="alert alert-danger">
                        <?php foreach($errors as $error) : ?>
                            <li><?php echo $error; ?></li>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <form action="reset-password.php?id=<?= $user['user_id']; ?>&name=<?= $name; ?>" method="POST">
                    <input type="hidden" name="user-id" value="<?= $user['user_id']; ?>">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" class="form-control" value="<?= $name ?? '' ?>" disabled>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" class="form-control" name="password" >
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" name="confirm-password" >
                    </div>
                    <div class="mb-3">
                        <a href="users.php" class="btn btn-secondary">Cancel</a>
                        <button type="submit" name="update_password" class="btn btn-primary">Reset Password</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

<?php include 'layouts/footer.php'; ?>