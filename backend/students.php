<?php include '../database/db.php'; ?>
<?php include 'layouts/header.php'; ?>

<?php 

    $errors = [];

    // Get Student List
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $class_id = isset($_GET['class_id']) ? (int)$_GET['class_id'] : '';
    $limit = 10;
    $offset = ($page-1) * $limit;

    if($class_id) {
        $get_student_list_query = "SELECT *, 
        Students.photo AS student_photo, 
        Students.status AS student_status, 
        Students.name AS student_name, 
        Parents.name AS parent_name, 
        Classes.class_name AS class_name, 
        Payments.photo AS payment_photo 
        FROM Students 
        LEFT JOIN Payments ON Students.student_id = Payments.student_id 
        LEFT JOIN Parents ON Students.parent_id = Parents.parent_id 
        LEFT JOIN Classes ON Students.class_id = Classes.class_id 
        WHERE Students.class_id = $class_id
        ORDER BY Students.student_id DESC LIMIT $limit OFFSET $offset";
    }
    else {
        $get_student_list_query = "SELECT *, 
        Students.photo AS student_photo, 
        Students.status AS student_status, 
        Students.name AS student_name, 
        Parents.name AS parent_name, 
        Classes.class_name AS class_name, 
        Payments.photo AS payment_photo 
        FROM Students 
        LEFT JOIN Payments ON Students.student_id = Payments.student_id 
        LEFT JOIN Parents ON Students.parent_id = Parents.parent_id 
        LEFT JOIN Classes ON Students.class_id = Classes.class_id 
        ORDER BY Students.student_id DESC LIMIT $limit OFFSET $offset";
    }
    
    $statement = $pdo->prepare($get_student_list_query);
    $statement->execute();
    $students = [];

    // fetch teacher with while loop
    while($student = $statement->fetch(PDO::FETCH_ASSOC)) {
        $students[] = $student;
    }
    
    // Add Student
    if (isset($_POST['add_student']) && $_SERVER['REQUEST_METHOD'] == 'POST') {

        // trim inputs value
        $name = trim($_POST['name']);
        $date_of_birth = trim($_POST['date_of_birth']);
        $gender = trim($_POST['gender']);
        $enrollment_date = trim($_POST['enrollment_date']);
        $class_id = trim($_POST['class_id']);
        $parent_id = trim($_POST['parent_id']);

        // filter inputs
        $name = htmlspecialchars($name);
        $date_of_birth = htmlspecialchars($date_of_birth);
        $gender = htmlspecialchars($gender);
        $enrollment_date = htmlspecialchars($enrollment_date);
        $class_id = filter_var($class_id, FILTER_SANITIZE_NUMBER_INT);
        $parent_id = filter_var($parent_id, FILTER_SANITIZE_NUMBER_INT);

        if(empty($name) || empty($date_of_birth) || empty($gender) || empty($enrollment_date) || empty($class_id) || empty($parent_id) || empty($_FILES['photo']['name'])) {
            $errors['name'] = 'All fields are required';
        }
        else {
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
                if(!is_dir('uploads')) {
                    mkdir('uploads');
                }

                // check directory write permissions
                if(!is_writable('uploads')) {
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

                $photoname = 's_'.$student_id . '.' . $photoextension;
                $upload_result = move_uploaded_file($phototmpname, 'uploads/' . $photoname);
                if(!$upload_result) {
                    $errors['photo'] = "Failed to upload file";
                }

                $add_payment_query = "INSERT INTO Payments (student_id, class_id) VALUES (:student_id, :class_id)";
                $statement = $pdo->prepare($add_payment_query);
                $statement->bindParam(":student_id", $student_id, PDO::PARAM_INT);
                $statement->bindParam(":class_id", $class_id, PDO::PARAM_INT);
                $statement->execute();

                $_SESSION['success'] = 'Student added successfully';
            }
            catch (PDOException $e) {
                $errors['dberror'] = $e->getMessage();
            }
        }
        
    }


    // UPDATE Payment (Online)
  if (isset($_POST['update_online_payment']) && $_SERVER['REQUEST_METHOD'] == 'POST') {

    $payment_status = trim($_POST['payment_status']);
    $student_id = trim($_POST['student_id']);
    $student_name = trim($_POST['student_name']);
    $payment_id = trim($_POST['payment_id']);
    $class_id = trim($_POST['class_id']);

    // filter inputs
    $payment_status = htmlspecialchars($payment_status);
    $student_id = FILTER_VAR($student_id, FILTER_SANITIZE_NUMBER_INT);
    $payment_id = FILTER_VAR($payment_id, FILTER_SANITIZE_NUMBER_INT);
    $class_id = FILTER_VAR($class_id, FILTER_SANITIZE_NUMBER_INT);
    $student_name = htmlspecialchars($student_name);

    if(empty($payment_status) || empty($student_id) || empty($payment_id) || empty($class_id)) {
        $errors['name'] = 'All fields are required';
    }

    // check payment status from arry
    if(!in_array($payment_status, ['paid', 'unpaid', 'checking', 'decline'])) {
        $errors['payment_status'] = 'Invalid Payment Status';
    }

    if(count($errors) == 0) {
        // check payment exist
        try {
            $check_payment_query = "SELECT * FROM Payments WHERE payment_id = :payment_id AND student_id = :student_id AND class_id = :class_id";
            $statement = $pdo->prepare($check_payment_query);
            $statement->bindParam(':payment_id', $payment_id, PDO::PARAM_INT);
            $statement->bindParam(':student_id', $student_id, PDO::PARAM_INT);
            $statement->bindParam(':class_id', $class_id, PDO::PARAM_INT);
            $statement->execute();
            $payment = $statement->fetch(PDO::FETCH_ASSOC);

            if($payment) {
                // update payment status
                try {
                    $update_payment_query = "UPDATE Payments SET payment_status = :payment_status WHERE payment_id = :payment_id";
                    $statement = $pdo->prepare($update_payment_query);
                    $statement->bindParam(':payment_status', $payment_status, PDO::PARAM_STR);
                    $statement->bindParam(':payment_id', $payment_id, PDO::PARAM_INT);
                    $statement->execute();
                    $_SESSION['success'] = $student_name."'s Payment status updated successfully";
                    header('Location: students.php');
                    exit();
                } catch (Exception $e) {
                    $errors['dberror'] = $e->getMessage();
                }
            }
        } catch (Exception $e) {
            $errors['dberror'] = $e->getMessage();
        }
    }

  }

    // UPDATE Payment (Manually)
    if (isset($_POST['update_manually_payment']) && $_SERVER['REQUEST_METHOD'] == 'POST') {

        $payment_status = trim($_POST['payment_status']);
        $student_id = trim($_POST['student_id']);
        $student_name = trim($_POST['student_name']);
        $payment_id = trim($_POST['payment_id']);
        $class_id = trim($_POST['class_id']);
        $description = trim($_POST['description']);
    
        // filter inputs
        $payment_status = htmlspecialchars($payment_status);
        $student_id = FILTER_VAR($student_id, FILTER_SANITIZE_NUMBER_INT);
        $payment_id = FILTER_VAR($payment_id, FILTER_SANITIZE_NUMBER_INT);
        $class_id = FILTER_VAR($class_id, FILTER_SANITIZE_NUMBER_INT);
        $student_name = htmlspecialchars($student_name);
        $description = htmlspecialchars($description);
    
        if(empty($payment_status) || empty($student_id) || empty($payment_id) || empty($class_id) || empty($description)) {
            $errors['name'] = 'All fields are required';
        }
    
        // check payment status from arry
        if(!in_array($payment_status, ['paid', 'unpaid', 'checking', 'decline'])) {
            $errors['payment_status'] = 'Invalid Payment Status';
        }
    
        if(count($errors) == 0) {
            // check payment exist
            try {
                $check_payment_query = "SELECT * FROM Payments WHERE payment_id = :payment_id AND student_id = :student_id AND class_id = :class_id";
                $statement = $pdo->prepare($check_payment_query);
                $statement->bindParam(':payment_id', $payment_id, PDO::PARAM_INT);
                $statement->bindParam(':student_id', $student_id, PDO::PARAM_INT);
                $statement->bindParam(':class_id', $class_id, PDO::PARAM_INT);
                $statement->execute();
                $payment = $statement->fetch(PDO::FETCH_ASSOC);
    
                if($payment) {
                    // update payment status
                    $payment_method = 'cash';
                    try {
                        $update_payment_query = "UPDATE Payments SET payment_status = :payment_status, payment_method = :payment_method WHERE payment_id = :payment_id";
                        $statement = $pdo->prepare($update_payment_query);
                        $statement->bindParam(':payment_status', $payment_status, PDO::PARAM_STR);
                        $statement->bindParam(':payment_method', $payment_method, PDO::PARAM_STR);
                        $statement->bindParam(':payment_id', $payment_id, PDO::PARAM_INT);
                        $statement->execute();
                        $_SESSION['success'] = $student_name."'s Payment status updated successfully";
                        header('Location: students.php');
                        exit();
                    } catch (Exception $e) {
                        $errors['dberror'] = $e->getMessage();
                    }
                }
            } catch (Exception $e) {
                $errors['dberror'] = $e->getMessage();
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
                <div class="bg-warning text-black p-4 rounded d-flex justify-content-between align-items-center mb-4">
                    <h2 class="mb-0"><i class="bi bi-person-circle"></i> Student Management</h2>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addStudentModal">
                        <i class="bi bi-person-plus"></i> Add New Student
                    </button>
                </div>

                <?php if(isset($errors) && count($errors) > 0) : ?>
                    <div class="alert alert-danger">
                        <?php foreach($errors as $error) : ?>
                            <li><?php echo $error; ?></li>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <?php if(isset($_SESSION['success'])) :  ?>
                    <div class="alert alert-success">
                        <?php echo $_SESSION['success']; ?>
                        <?php unset($_SESSION['success']); ?>
                    </div>
                <?php endif; ?>

                <!-- Filters -->
                <?php include './components/students/filters.php'; ?>

                <!-- Students Table -->
                <?php include 'components/students/table.php'; ?>
            </div>
        </div>
    </div>

    <!-- Add Student Modal -->
    <div class="modal fade" id="addStudentModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="bi bi-person-plus me-2"></i> Add New Student</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <form action="students.php" method="POST" enctype="multipart/form-data">
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
                        <div class="mb-3">
                            <label class="form-label">Date of Birth</label>
                            <input type="date" class="form-control" name="date_of_birth" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Gender</label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="gender" value="male" checked>
                                <label class="form-check-label">Male</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="gender" value="female">
                                <label class="form-check-label">Female</label>
                            </div>
                        </div>
                        <div class="mb-3">
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
                        <div class="mb-3">
                            <label class="form-label">Parent Name</label>
                            <select class="form-select" name="parent_id" required>
                                <option value="">Select Parent</option>

                                <!-- Get Parent List -->
                                <?php 

                                    $get_parent_list_query = "SELECT * FROM Parents ORDER BY name ASC";
                                    $statement = $pdo->prepare($get_parent_list_query);
                                    $statement->execute();
                                    $parents = [];

                                    // fetch parent with while loop
                                    while($parent = $statement->fetch(PDO::FETCH_ASSOC)) {
                                        $parents[] = $parent;
                                    }

                                ?>

                                <?php if(count($parents) > 0) : ?>
                                    <!-- Show Parent List with foreach loop -->
                                    <?php foreach($parents as $parent) : ?>
                                        <option value="<?= $parent['parent_id']; ?>"><?= $parent['name']; ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>

                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Enrollment Date</label>
                            <input type="date" class="form-control" name="enrollment_date" required>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" name="add_student" class="btn btn-primary">Add Student</button>
                        </div>
                    </form>
                </div>
                
            </div>
        </div>
    </div>

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
                imgPreview.innerHTML = '<img class="rounded-circle" style="max-width: 150px; height: auto" alt="Teacher" src="' + this.result + '" />';
                });    
            }
        }
    </script>

<?php include 'layouts/footer.php'; ?>