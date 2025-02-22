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
        $child_list_query = "SELECT *, Students.photo AS student_photo , Students.status AS student_status
        FROM Students
        LEFT JOIN Classes ON Students.class_id = Classes.class_id
        LEFT JOIN Payments ON Students.student_id = Payments.student_id 
        WHERE parent_id = :parent_id";
        $statement = $pdo->prepare($child_list_query);
        $statement->bindParam(':parent_id', $user['parent_id'], PDO::PARAM_INT);
        $statement->execute();
        $child_list = $statement->fetchAll(PDO::FETCH_ASSOC);
    }
    catch (Exception $e) {
        $errors['dberror'] = $e->getMessage();
    }

    // Make Payment
    if(isset($_POST['make_payment']) && $_SERVER['REQUEST_METHOD'] == 'POST') {

        $errors = [];
        $now = new DateTime();
        $now = $now->format('Y-m-d H:i:s');

        $payment_id = trim($_POST['payment_id']);
        $student_id = trim($_POST['student_id']);
        $class_id = trim($_POST['class_id']);
        $amount = trim($_POST['amount']);
        $payment_method = trim($_POST['payment_method']);

        $payment_id = FILTER_VAR($payment_id, FILTER_SANITIZE_NUMBER_INT);
        $student_id = FILTER_VAR($student_id, FILTER_SANITIZE_NUMBER_INT);
        $class_id = FILTER_VAR($class_id, FILTER_SANITIZE_NUMBER_INT);
        $amount = FILTER_VAR($amount, FILTER_SANITIZE_NUMBER_INT);
        $payment_method = htmlspecialchars($payment_method);
        $description = htmlspecialchars($_POST['description']);

        // check empty field
        if( empty($payment_id) || empty($student_id) || empty($class_id) || empty($amount) || empty($payment_method) || empty($description) ) {
            $errors['emptyfield'] = "All fields are required";
        }
        else {

            // check payment id
            try {
                $payment_id_query = "SELECT * FROM Payments WHERE payment_id = :payment_id AND student_id = :student_id AND class_id = :class_id";
                $statement = $pdo->prepare($payment_id_query);
                $statement->bindParam(':payment_id', $payment_id, PDO::PARAM_INT);
                $statement->bindParam(':student_id', $student_id, PDO::PARAM_INT);
                $statement->bindParam(':class_id', $class_id, PDO::PARAM_INT);
                $statement->execute();
                $payment_id_result = $statement->fetch(PDO::FETCH_ASSOC);
            }
            catch (Exception $e) {
                $errors['dberror'] = $e->getMessage();
            }
            if(!$payment_id_result) {
                $errors['payment_id'] = "Invalid Payment ID";
            }

            // check payment method
            if($payment_method != 'cash' && $payment_method != 'kpay' && $payment_method != 'bank transfer') {
                $errors['payment_method'] = "Invalid Payment Method";
            }

            // check image 
            // check if file is uploaded
            if($_FILES['photo']['error'] == 0 && !empty($_FILES['photo']['name'])) {

                $photoname = $_FILES['photo']['name'];

                // get file extension
                $photoextension = pathinfo($photoname, PATHINFO_EXTENSION);

                $phototype = $_FILES['photo']['type'];
                if($phototype != 'image/jpeg' && $phototype != 'image/png' && $phototype != 'image/jpg') {
                    $errors['photo'] = 'Invalid file type';
                }
    
                $phototmpname = $_FILES['photo']['tmp_name'];  

                // validate file size
                $phototmpsize = $_FILES['photo']['size'];
                if($phototmpsize > 5000000) {
                    $errors['photo'] = 'File size not more than 5MB';
                }

                // if the file is not an image, throw an error
                $check = getimagesize($phototmpname);
                if($check === false) {
                    $errors['photo'] = "File is not an image";
                }

                // move the file to the uploads directory
                // check folder exists
                if(!is_dir('./backend/uploads')) {
                    mkdir('./backend/uploads');
                }

                // check directory write permissions
                if(!is_writable('./backend/uploads')) {
                    $errors['photo'] = "Uploads directory is not writable";
                }
            }
        }

        if(count($errors) == 0) {
            try{

                $payment_status = "checking";

                $photoname = 'p_' . $payment_id . '.' . $photoextension;
                $upload_result = move_uploaded_file($phototmpname, './backend/uploads/' . $photoname);

                $payment_query = "UPDATE Payments 
                SET payment_date=:payment_date, amount=:amount, payment_method=:payment_method, payment_status=:payment_status, description=:description, photo=:photo 
                WHERE payment_id = :payment_id AND student_id = :student_id AND class_id = :class_id";
                $statement = $pdo->prepare($payment_query);
                $statement->bindParam(':payment_date', $now, PDO::PARAM_STR);
                $statement->bindParam(':amount', $amount, PDO::PARAM_INT);
                $statement->bindParam(':payment_method', $payment_method, PDO::PARAM_STR);
                $statement->bindParam(':payment_status', $payment_status, PDO::PARAM_STR);
                $statement->bindParam(':description', $description, PDO::PARAM_STR);
                $statement->bindParam(':photo', $photoname, PDO::PARAM_STR);
                $statement->bindParam(':payment_id', $payment_id, PDO::PARAM_INT);
                $statement->bindParam(':student_id', $student_id, PDO::PARAM_INT);
                $statement->bindParam(':class_id', $class_id, PDO::PARAM_INT);
                $statement->execute();

                $_SESSION['success'] = 'Payment updated successfully';
                header('Location: profile.php');
                exit();
            }
            catch(PDOException $e) {
                $errors['dberror'] = $e->getMessage();
            }
        }
        
    }

    // Add Student
    if(isset($_POST['add_student']) && $_SERVER['REQUEST_METHOD'] == 'POST') {

        $parent_id = $user['parent_id'];
        $class_id = trim($_POST['class_id']);
        $name = trim($_POST['name']);
        $date_of_birth = trim($_POST['date_of_birth']);
        $gender = trim($_POST['gender']);
        $enrollment_date = trim($_POST['enrollment_date']);

        // filter inputs
        $parent_id = filter_var($parent_id, FILTER_SANITIZE_NUMBER_INT);
        $class_id = filter_var($class_id, FILTER_SANITIZE_NUMBER_INT);
        $name = htmlspecialchars($name);
        $date_of_birth = htmlspecialchars($date_of_birth);
        $gender = htmlspecialchars($gender);
        $enrollment_date = htmlspecialchars($enrollment_date);

        if(empty($name) || empty($date_of_birth) || empty($gender) || empty($enrollment_date) || empty($class_id) || empty($parent_id) || empty($_FILES['photo']['name'])) {
            $errors['name'] = 'All fields are required';
        }
        else {

            // check if file is uploaded
            if($_FILES['photo']['error'] == 0 && !empty($_FILES['photo']['name'])) {

                $photoname = $_FILES['photo']['name'];  

                // get file extension
                $photoextension = pathinfo($photoname, PATHINFO_EXTENSION);

                $phototype = $_FILES['photo']['type'];
                if($phototype != 'image/jpeg' && $phototype != 'image/png' && $phototype != 'image/jpg') {
                    $errors['photo'] = 'Invalid file type';
                }
    
                $phototmpname = $_FILES['photo']['tmp_name'];  

                // validate file size
                $phototmpsize = $_FILES['photo']['size'];
                if($phototmpsize > 5000000) {
                    $errors['photo'] = 'File size not more than 5MB';
                }

                // if the file is not an image, throw an error
                $check = getimagesize($phototmpname);
                if($check === false) {
                    $errors['photo'] = "File is not an image";
                }

                // move the file to the uploads directory
                // check folder exists
                if(!is_dir('./backend/uploads')) {
                    mkdir('./backend/uploads');
                }

                // check directory write permissions
                if(!is_writable('./backend/uploads')) {
                    $errors['photo'] = "Uploads directory is not writable";
                }
            }
        }

        if(count($errors) == 0) {
            try {
                $add_class_query = "INSERT INTO Students (name, date_of_birth, gender, enrollment_date, class_id, parent_id, photo) VALUES (:name, :date_of_birth, :gender, :enrollment_date, :class_id, :parent_id, :photo)";
                $statement = $pdo->prepare($add_class_query);
                $statement->bindParam(":name", $name, PDO::PARAM_STR);
                $statement->bindParam(":date_of_birth", $date_of_birth, PDO::PARAM_STR);
                $statement->bindParam(":gender", $gender, PDO::PARAM_STR);
                $statement->bindParam(":enrollment_date", $enrollment_date, PDO::PARAM_STR);
                $statement->bindParam(":class_id", $class_id, PDO::PARAM_INT);
                $statement->bindParam(":parent_id", $parent_id, PDO::PARAM_INT);
                $statement->bindParam(":photo", $photoname, PDO::PARAM_STR);
                $statement->execute();

                $student_id = $pdo->lastInsertId();

                $upload_result = move_uploaded_file($phototmpname, './backend/uploads/' . $photoname);
                if(!$upload_result) {
                    $errors['photo'] = "Failed to upload file";
                }

                $add_payment_query = "INSERT INTO Payments (student_id, class_id) VALUES (:student_id, :class_id)";
                $statement = $pdo->prepare($add_payment_query);
                $statement->bindParam(":student_id", $student_id, PDO::PARAM_INT);
                $statement->bindParam(":class_id", $class_id, PDO::PARAM_INT);
                $statement->execute();

                $_SESSION['success'] = 'Student added successfully';
                header("Location: /profile.php");
                exit();
            }
            catch (PDOException $e) {
                $errors['dberror'] = $e->getMessage();
            }
        }
    }
?>

    <div class="container-xxl bg-white p-0">

    <!-- Navbar Start -->
    <?php include "./components/navbar.php"; ?>
    <!-- Navbar End -->

    </div>

    <div class="container-fluid p-0 banner-small position-relative">
        <img src="img/banner-small.jpg" alt="" class="img-fluid">
    </div>

        <div class="container">
            <div class="row">

                <?php if(isset($_SESSION['success'])) :  ?>
                    <div class="alert alert-success">
                        <?php echo $_SESSION['success']; ?>
                        <?php unset($_SESSION['success']); ?>
                    </div>
                <?php endif; ?>

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
                <div class="col-md-3">
                    <div class="card mb-3">
                        <div class="row g-0 d-flex">
                            <div class="col-lg-12">
                            <img src="<?= empty($user['photo']) ? 'http://placehold.co/300x300/000000/FFF' : $user['photo']  ?>" class="img-fluid rounded-start" alt="...">
                            </div>
                            <div class="col-lg-12 align-self-center">
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
                <div class="col-md-9">
                    <!-- Child List -->
                    <?php if(count($child_list) > 0) : ?>
                        <div class="col-md-12 border rounded mb-4">
                            <div class="rounded-top py-3 bg-warning text-white">
                                <h5 class="card-title text-end me-4"><i class="bi bi-columns-gap"></i> Related Student List</h5>
                            </div>
                            <div class="p-5">
                                <table class="table table-striped text-center align-middle">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Name</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">Payment</th>
                                            <th scope="col" class="text-end pe-4">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($child_list as $key => $child) : ?>
                                            <tr>
                                                <th scope="row"><?= $key + 1 ?></th>
                                                <td><?= $child['name'] ?></td>
                                                <td>
                                                    <?php if($child['student_status'] == 'active') : ?>
                                                        <span class="text-success">Active</span>
                                                    <?php elseif($child['student_status'] == 'suspend') : ?>
                                                        <span class="text-danger">Suspend</span>
                                                    <?php else : ?>
                                                        <span class="text-warning">Pending</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if($child['payment_status'] == 'paid') : ?>
                                                        <span class="text-success">Paid</span>
                                                    <?php elseif($child['payment_status'] == 'decline') : ?>
                                                        <span class="text-danger">Decline</span>
                                                    <?php elseif($child['payment_status'] == 'checking') : ?>
                                                        <span class="text-warning">Checking</span>
                                                    <?php else : ?>
                                                        <span class="text-danger">Unpaid</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-end">
                                                    
                                                <!-- Student Modal -->
                                                <div class="d-inline">
                                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#studentModal<?= $child['student_id'] ?>">
                                                        View Detail
                                                    </button>
                                                    <div class="modal fade" id="studentModal<?= $child['student_id'] ?>" tabindex="-1" aria-labelledby="studentModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h1 class="modal-title fs-5" id="studentModalLabel">Student Detail</h1>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <img src="<?= empty($child['student_photo']) ? 'http://placehold.co/300x300/000000/FFF' : './backend/uploads/'.$child['student_photo']  ?>" class="img-fluid rounded-start" alt="...">
                                                                        </div>
                                                                        <div class="col-md-6 text-start align-self-center">
                                                                            <h5 class="card-title">Name :<?= $child['name'] ?></h5>
                                                                            <small class="d-block my-1 text-muted">Class : <?= $child['class_name'] ?></small>
                                                                            <small class="d-block my-1 text-muted">Date of Birth : <?= $child['date_of_birth'] ?></small>
                                                                            <small class="d-block my-1 text-muted">Gender : <?= $child['gender'] ?></small>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Student Modal -->

                                                <?php if(
                                                $child['student_status'] == 'active' &&  $child['payment_status'] == 'unpaid'): ?>
                                                
                                                <!-- Payment Modal -->
                                                <div class="d-inline">
                                                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#paymentModal<?= $child['payment_id'] ?>">
                                                    <i class="bi bi-cash"></i> Make Online Payment
                                                    </button>
                                                    <div class="modal fade" id="paymentModal<?= $child['payment_id'] ?>" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h1 class="modal-title fs-5" id="paymentModalLabel">Payment Detail</h1>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="row">
                                                                        <div class="col-md-12 text-start align-self-center">
                                                                            <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data">
                                                                                <input type="hidden" name="student_id" value="<?= $child['student_id'] ?>">
                                                                                <input type="hidden" name="payment_id" value="<?= $child['payment_id'] ?>">
                                                                                <input type="hidden" name="class_id" value="<?= $child['class_id'] ?>">
                                                                                <input type="hidden" name="amount" value="<?= $child['fees'] ?>">

                                                                                <ul class="list-group">
                                                                                    <li class="list-group-item text-muted">Name : <?= $child['name'] ?></li>
                                                                                    <li class="list-group-item text-muted">Class : <?= $child['class_name'] ?></li>
                                                                                    <li class="list-group-item text-muted">Amount : <?= $child['fees'] ?></li>
                                                                                </ul>

                                                                                <div class="my-4">
                                                                                    <lable for="slip">Payment Method</lable>
                                                                                    <select name="payment_method" id="payment_method" class="form-control" required>
                                                                                        <option value="kpay" selected>KPay</option>
                                                                                        <option value="bank transfer">Bank Transfer</option>
                                                                                    </select>
                                                                                </div>

                                                                                <div class="my-4">
                                                                                    <lable for="photo">Payment Slip or Screenshot</lable>
                                                                                    <div id="preview-image"></div>
                                                                                    <input type="file" accept="image/png, image/jpeg, image/jpg" name="photo" class="form-control" id="photo" required >
                                                                                </div>

                                                                                <div class="my-4">
                                                                                    <lable for="description">Description</lable>
                                                                                    <textarea class="form-control" name="description" rows="3" ></textarea>
                                                                                </div>

                                                                                <div class="modal-footer">
                                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                                    <button type="submit" name="make_payment" class="my-4 btn btn-primary">Submit</button>
                                                                                </div>
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Payment Modal -->

                                                <?php endif; ?>

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
                

                    <div class="col-md-12 border rounded">
                            <div class="rounded-top py-3 bg-danger text-white border-bottom">
                                <h5 class="card-title text-end me-4"><i class="bi bi-person-plus"></i> Add New Student</h5>
                            </div>
                            <div class="bg-danger text-white p-5">
                                <form action="profile.php" method="POST" enctype="multipart/form-data">
                                    <div class="mb-3">
                                        <label class="form-label">Student Name</label>
                                        <input type="text" class="form-control" name="name" required>
                                    </div>
                                    <div class="mb-3 d-flex align-items-center justify-content-center">
                                        <div id="preview-image"><img src="https://placehold.co/150" class="rounded-circle" style="max-width: 150px; height: auto" alt="Staff"></div>
                                        <div class="w-100 ps-5">
                                            <label class="form-label" for="photo">Photo</label>
                                            <input accept="image/jpeg, image/png, image/jpg" type="file" name="photo" class="form-control" id="photo">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Date of Birth</label>
                                            <input type="date" class="form-control" name="date_of_birth" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label d-block">Gender</label>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="gender" value="male" checked>
                                                <label class="form-check-label">Male</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="gender" value="female">
                                                <label class="form-check-label">Female</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Class</label>
                                            <select class="form-select" name="class_id" required>
                                                <option value="">Select Class</option>

                                                <!-- Get Class List -->
                                                <?php 

                                                    $get_class_list_query = "SELECT * FROM Classes";
                                                    $statement = $pdo->prepare($get_class_list_query);
                                                    $statement->execute();
                                                    $classes = [];

                                                    // fetch teacher with while loop
                                                    while($class = $statement->fetch(PDO::FETCH_ASSOC)) {
                                                        $classes[] = $class;
                                                    }

                                                ?>

                                                <?php if(count($classes) > 0) : ?>
                                                    <!-- Show Teacher List with foreach loop -->
                                                    <?php foreach($classes as $class) : ?>
                                                        <option value="<?= $class['class_id']; ?>"><?= $class['class_name']; ?></option>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>

                                            </select>

                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Enrollment Date</label>
                                            <input type="date" class="form-control" name="enrollment_date" required>
                                        </div>
                                    </div>
                                    <div class="mt-4 text-end">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" name="add_student" class="btn btn-primary">Add Student</button>
                                    </div>
                                </form>
                            </div> 
                    </div>

                </div>
            </div>

        </div>

        <!-- Contact Start -->
        <?php include "./components/footer.php"; ?>
        <!-- Contact End -->

    </>

    <script>
        const chooseFile = document.getElementById("photo");
        const imgPreview = document.getElementById("preview-image");

        chooseFile.addEventListener("change", function () {
            getImgData();
        });

        function getImgData() {
            const files = chooseFile.files[0];
            if (files) {
                const fileReader = new FileReader();
                fileReader.readAsDataURL(files);
                fileReader.addEventListener("load", function () {
                imgPreview.style.display = "block";
                imgPreview.innerHTML = '<img class="rounded my-4 d-block" style="max-width: 100%; height: 100px" alt="Slip" src="' + this.result + '" />';
                });    
            }
        }
    </script>

<!-- Footer Start -->
<?php include "./layouts/footer.php"?>