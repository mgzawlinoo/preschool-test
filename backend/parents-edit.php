<?php include '../database/db.php'; ?>
<?php include 'layouts/header.php'; ?>

<?php 
    // ှShow Parent Data from Get ID and Fill it to form
    if(isset($_GET['id'])) {
        $parent_id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
        $parent_id = trim($parent_id);

        $get_parent_query = "SELECT * FROM Parents LEFT JOIN Users ON Parents.user_id = Users.user_id WHERE parent_id = :parent_id";
        $statement = $pdo->prepare($get_parent_query);
        $statement->bindParam(":parent_id", $parent_id, PDO::PARAM_INT);
        $statement->execute();
        $parent = $statement->fetch(PDO::FETCH_ASSOC);
    }
?>

<?php 
  // UPDATE STUDENT
  if (isset($_POST['update_parent']) && $_SERVER['REQUEST_METHOD'] == 'POST') {

    $error = [];
    
    // trim inputs value
    $parent_id = trim($_POST['parent-id']);
    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);

    // filter inputs
    $parent_id = filter_var($parent_id, FILTER_SANITIZE_NUMBER_INT);
    $name = htmlspecialchars($name);
    $phone = htmlspecialchars($phone);
    $address = htmlspecialchars($address);

    // check empty fields
    if (empty($name) || empty($phone) || empty($address)) {
        $error['empty'] = 'All fields are required';
    }

    // update query for student
    if(count($error) == 0) {
        try {
            $update_parent_query = "UPDATE Parents SET name=:name, phone=:phone, address=:address WHERE parent_id=:parent_id"; //error
            $statement = $pdo->prepare($update_parent_query);
            $statement->bindParam(":parent_id", $parent_id, PDO::PARAM_INT);
            $statement->bindParam(":name", $name, PDO::PARAM_STR);
            $statement->bindParam(":phone", $phone, PDO::PARAM_STR);
            $statement->bindParam(":address", $address, PDO::PARAM_STR);
            $statement->execute();

            header("Location: parents.php");
            exit();

        }
        catch (PDOException $e) {
            echo $e->getMessage();
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
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="mb-0">Update Parent</h2>
                </div>

                <?php if(isset($error) && count($error) > 0) : ?>
                    <div class="alert alert-danger">
                        <?php foreach($error as $e) : ?>
                            <li><?php echo $e; ?></li>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <form action="parents-edit.php?id=<?= $parent['parent_id']; ?>" method="POST">
                        <input type="hidden" name="parent-id" value="<?= $parent['parent_id']; ?>">
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" class="form-control" name="name" value="<?= $parent['name']; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" value="<?= $parent['email']; ?>" disabled>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Phone</label>
                            <input type="text" class="form-control" name="phone" value="<?= $parent['phone']; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Address</label>
                            <input type="text" class="form-control" name="address" value="<?= $parent['address']; ?>" required>
                        </div>
                        <div class="mb-3">
                            <a href="parents.php" class="btn btn-secondary">Cancel</a>
                            <button type="submit" name="update_parent" class="btn btn-primary">Update Parent</button>
                        </div>
                    </form>

            </div>
        </div>
    </div>

<?php include 'layouts/footer.php'; ?>