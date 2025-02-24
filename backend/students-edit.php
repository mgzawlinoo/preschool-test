<?php include '../database/db.php'; ?>
<?php include 'layouts/header.php'; ?>

<?php 
    // ှShow Student Data from Get ID and Fill it to form
    if(isset($_GET['id'])) {
        $student_id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
        $student_id = trim($student_id);

        try {
            $get_student_query = "SELECT *, Students.photo AS student_photo, Students.name AS student_name, Classes.class_name, Parents.name AS parent_name FROM Students LEFT JOIN Classes ON Students.class_id = Classes.class_id LEFT JOIN Parents ON Students.parent_id = Parents.parent_id WHERE student_id = :student_id";
            $statement = $pdo->prepare($get_student_query);
            $statement->bindParam(":student_id", $student_id, PDO::PARAM_INT);
            $statement->execute();
            $student = $statement->fetch(PDO::FETCH_ASSOC);
        }
        catch(PDOException $e) {
            $errors['dberror'] = $e->getMessage();
        }
        
    }
?>

<?php 
  // UPDATE STUDENT
  if (isset($_POST['update_student']) && $_SERVER['REQUEST_METHOD'] == 'POST') {

    $errors = [];

    // trim inputs value
    $student_id = trim($_POST['student-id']);
    $name = trim($_POST['name']);
    $date_of_birth = trim($_POST['date-of-birth']);
    $gender = trim($_POST['gender']);
    $parent_id = trim($_POST['parent-id']);
    $class_id = trim($_POST['class-id']);
    $enrollment_date = trim($_POST['enrollment-date']);

    // filter inputs
    $student_id = filter_var($student_id, FILTER_SANITIZE_NUMBER_INT);
    $name = htmlspecialchars($name);
    $date_of_birth = htmlspecialchars($date_of_birth);
    $gender = htmlspecialchars($gender);
    $parent_id = filter_var($parent_id, FILTER_SANITIZE_NUMBER_INT);
    $class_id = filter_var($class_id, FILTER_SANITIZE_NUMBER_INT);
    $enrollment_date = htmlspecialchars($enrollment_date);

    // check empty fields
    if (empty($name) || empty($date_of_birth) || empty($gender) || empty($parent_id) || empty($class_id) || empty($enrollment_date)) {
        $errors['empty'] = 'All fields are required';
    }
    else {
        // check if file is uploaded
        if(isset($_FILES['photo']) && $_FILES['photo']['error'] == 0 && !empty($_FILES['photo']['name'])) {

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

            $upload_result = move_uploaded_file($phototmpname, 'uploads/' . $photoname);
            if(!$upload_result) {
                $errors['photo'] = "Failed to upload file";
            }
        }
        else {
            $photoname = $student['student_photo'];
        }
    }

    // update query for student
    if(count($errors) == 0) {
        try {
            $update_student_query = "UPDATE Students SET name=:name, date_of_birth=:date_of_birth, gender=:gender, parent_id=:parent_id, class_id=:class_id, enrollment_date=:enrollment_date, photo=:photo WHERE student_id=:student_id"; //error
            $statement = $pdo->prepare($update_student_query);
            $statement->bindParam(":student_id", $student_id, PDO::PARAM_INT);
            $statement->bindParam(":name", $name, PDO::PARAM_STR);
            $statement->bindParam(":date_of_birth", $date_of_birth, PDO::PARAM_STR);
            $statement->bindParam(":gender", $gender, PDO::PARAM_STR);
            $statement->bindParam(":parent_id", $parent_id, PDO::PARAM_INT);
            $statement->bindParam(":class_id", $class_id, PDO::PARAM_INT);
            $statement->bindParam(":enrollment_date", $enrollment_date, PDO::PARAM_STR);
            $statement->bindParam(":photo", $photoname, PDO::PARAM_STR);
            $statement->execute();

            $_SESSION['success'] = 'Student updated successfully';
            header("Location: students.php");
            exit();

        }
        catch (PDOException $e) {
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
                <div class="bg-warning text-black p-4 rounded  d-flex justify-content-between align-items-center mb-4">
                    <h2 class="mb-0"><i class="bi bi-pencil-square"></i> Update Student</h2>
                </div>

                <?php if(isset($errors) && count($errors) > 0) : ?>
                    <div class="alert alert-danger">
                        <?php foreach($errors as $error) : ?>
                            <li><?php echo $error; ?></li>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <form action="students-edit.php?id=<?= $student['student_id']; ?>" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="student-id" value="<?= $student['student_id']; ?>">
                    <div class="mb-3">
                        <label class="form-label">Student Name</label>
                        <input type="text" class="form-control" name="name" value="<?= $student['student_name']; ?>" required>
                    </div>
                    <div class="mb-3 d-flex align-items-center justify-content-center">
                    <div id="preview-image"><img src="<?= $student['student_photo'] ? 'uploads/' . $student['student_photo'] : 'https://placehold.co/150' ?>" class="rounded-circle" style="max-width: 150px; height: auto" alt="Student"></div>
                        <div class="w-100 ps-5">
                            <label class="form-label" for="photo">Photo</label>
                            <input accept="image/jpeg, image/png, image/jpg" type="file" name="photo" class="form-control" id="photo">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Date of Birth</label>
                        <input type="date" class="form-control" name="date-of-birth" value="<?= $student['date_of_birth']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Gender</label>

                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="gender" value="male" <?php if($student['gender'] == 'male' || $student['gender'] == 'Male') : ?> checked <?php endif; ?>>
                            <label class="form-check-label">Male</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="gender" value="female" <?php if($student['gender'] == 'female' || $student['gender'] == 'Female') : ?> checked <?php endif; ?>>
                            <label class="form-check-label">Female</label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Class</label>
                        <select class="form-select" name="class-id" required>
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
                                    <option 
                                    <?php if ($student['class_id'] == $class['class_id']) : ?> selected <?php endif; ?>
                                    value="<?= $class['class_id']; ?>"><?= $class['class_name']; ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>

                        </select>

                    </div>
                    

                    <div class="row">
                        <div class="col-lg-6 mb-3">
                            <label class="form-label">Parent Name</label>
                            <select class="form-select" name="parent-id" required>
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
                                        <option 
                                        <?php if($student['parent_id'] == $parent['parent_id']) : ?> selected <?php endif; ?>
                                        value="<?= $parent['parent_id']; ?>"><?= $parent['name']; ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <label class="form-label">Enrollment Date</label>
                            <input type="date" class="form-control" name="enrollment-date" value="<?= $student['enrollment_date']; ?>" required>
                        </div>
                    </div>

                    <div class="row text-center">
                        <div class="col-lg-12 my-3">
                            <a href="students.php" class="btn btn-secondary">Cancel</a>
                            <button type="submit" name="update_student" class="btn btn-primary">Update Student</button>
                        </div>
                    </div>
                </form>

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