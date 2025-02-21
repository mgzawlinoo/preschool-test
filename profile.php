<?php session_start(); ?>
<?php include './auth-check.php'; ?>
<?php include './database/db.php'; ?>
<!-- Header Start -->
<?php include("./layouts/header.php"); ?>

<?php 
    // Get Child List
    $child_list = [];
    $errors = [];
    try {
        $child_list_query = "SELECT * FROM Students LEFT JOIN Payments ON Students.student_id = Payments.student_id WHERE parent_id = :parent_id";
        $statement = $pdo->prepare($child_list_query);
        $statement->bindParam(':parent_id', $user['parent_id'], PDO::PARAM_INT);
        $statement->execute();
        $child_list = $statement->fetchAll(PDO::FETCH_ASSOC);
    }
    catch (Exception $e) {
        $errors['dberror'] = $e->getMessage();
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
                    <!-- Child List -->
                    <?php if(count($child_list) > 0) : ?>
                        <div class="card mb-3">
                            <div class="card-header">
                                <h5 class="card-title">Related Student List</h5>
                            </div>
                            <div class="card-body">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Name</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">Payment</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($child_list as $key => $child) : ?>
                                            <tr>
                                                <th scope="row"><?= $key + 1 ?></th>
                                                <td><?= $child['name'] ?></td>
                                                <td>
                                                    <?php if($child['status'] == 'active') : ?>
                                                        <span class="text-success">Active</span>
                                                    <?php elseif($child['status'] == 'suspend') : ?>
                                                        <span class="text-danger">Suspend</span>
                                                    <?php else : ?>
                                                        <span class="text-warning">Pending</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if($child['payment_status'] == 'paid') : ?>
                                                        <span class="text-success">Paid</span>
                                                    <?php elseif($child['payment_status'] == 'checking') : ?>
                                                        <span class="text-warning">Checking</span>
                                                    <?php else : ?>
                                                        <span class="text-danger">Unpaid</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <a href="./payment.php?payment_id=<?= $child['payment_id'] ?>" class="btn btn-sm btn-danger">Make Payment</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="card mb-3">
                            <div class="card-header">
                                <h5 class="card-title">Child List</h5>
                            </div>
                            <div class="card-body">
                                <p>No child found.</p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- <div class="row mt-5">
                <div class="col-md-6">
                    <h4>Update Parent Information</h4>
                    <form action="#" method="POST">
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
                            <button type="submit" name="update_parent" class="btn btn-primary">Update Parent</button>
                        </div>
                    </form> 
                </div>

                <div class="col-md-6">
                    <h4>Enrollment for Child</h4>
                    <form action="#" method="POST">
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
                            <button type="submit" name="add_student" class="btn btn-primary">Add Student</button>
                        </div>
                    </form> 
                </div>

            </div> -->
        </div>

        <!-- Contact Start -->
        <?php include "./components/footer.php"; ?>
        <!-- Contact End -->

    </div>

<!-- Footer Start -->
<?php include "./layouts/footer.php"?>