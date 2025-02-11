<?php include '../database/db.php'; ?>
<?php include 'layouts/header.php'; ?>


<?php 
    // UPDATE TEACHER
    if (isset($_POST['update']) && $_SERVER['REQUEST_METHOD'] == 'POST') {

        // trim inputs value
        $user_id = trim($_POST['user-id']);
        $teacher_id = trim($_POST['teacher-id']);
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $phone = trim($_POST['phone']);
        $position = trim($_POST['position']);
        $photo = trim($_POST['photo']);
        $experience = trim($_POST['experience']);
        $qualification = trim($_POST['qualification']);
        $hire_date = trim($_POST['hiredate']);
        $salary = trim($_POST['salary']);
        $address = trim($_POST['address']);

        // filter inputs
        $user_id = filter_var($user_id, FILTER_SANITIZE_NUMBER_INT);
        $teacher_id = filter_var($teacher_id, FILTER_SANITIZE_NUMBER_INT);
        $name = filter_var($name, FILTER_SANITIZE_STRING);
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        $phone = filter_var($phone, FILTER_SANITIZE_STRING);
        $position = filter_var($position, FILTER_SANITIZE_STRING);
        $photo = filter_var($photo, FILTER_SANITIZE_STRING);
        $experience = filter_var($experience, FILTER_SANITIZE_STRING);
        $qualification = filter_var($qualification, FILTER_SANITIZE_STRING);
        $hire_date = filter_var($hire_date, FILTER_SANITIZE_STRING);
        $salary = filter_var($salary, FILTER_SANITIZE_NUMBER_INT);
        $address = filter_var($address, FILTER_SANITIZE_STRING);

        // check teacher and user's ID correct
        // $check_teacher_exists_query = "SELECT * FROM Teachers WHERE teacher_id = :teacher_id AND user_id = :user_id";
        // $statement = $pdo->prepare($check_teacher_exists_query);
        // $statement->bindParam(":teacher_id", $teacher_id, PDO::PARAM_INT);
        // $statement->bindParam(":user_id", $user_id, PDO::PARAM_INT);
        // $statement->execute();
        // $teacher = $statement->fetch(PDO::FETCH_ASSOC);

        // update query for teachers

        // check if file is uploaded
        if($_FILES['photo']['error'] == 0) {
            
            $photoname = $_FILES['photo']['name'];  

            // get file extension
            $photoextension = pathinfo($photoname, PATHINFO_EXTENSION);

            $phototype = $_FILES['photo']['type'];
            if($phototype != 'image/jpeg' && $phototype != 'image/png' && $phototype != 'image/jpg') {
                $error['photo'] = 'Invalid file type';
            }

            $phototmpname = $_FILES['photo']['tmp_name'];  

            // validate file size
            if($phototmpsize > 5000000) {
                $error['photo'] = 'File size not more than 5MB';
            }

            // if the file is not an image, throw an error
            $check = getimagesize($phototmpname);
            if($check === false) {
                $error['photo'] = "File is not an image";
            }

            // move the file to the uploads directory
            // check folder exists
            if(!is_dir('uploads')) {
                mkdir('uploads');
            }
            $photoname = $teacher_id . '_' . $user_id . '.' . $photoextension;
            $upload_result = move_uploaded_file($phototmpname, 'uploads/' . $photoname);
            if(!$upload_result) {
                $error['photo'] = "Failed to upload file";
            }
        }

        
        try {
            $update_teacher_query = "UPDATE Teachers SET name=:name, position=:position, photo=:photo, experience=:experience, qualification=:qualification, hire_date=:hire_date, salary=:salary, address=:address WHERE teacher_id=:teacher_id"; //error
            $statement = $pdo->prepare($update_teacher_query);
            $statement->bindParam(":teacher_id", $teacher_id, PDO::PARAM_INT);
            $statement->bindParam(":name", $name, PDO::PARAM_STR);
            $statement->bindParam(":position", $position, PDO::PARAM_STR);
            $statement->bindParam(":photo", $photoname, PDO::PARAM_STR);
            $statement->bindParam(":experience", $experience, PDO::PARAM_STR);
            $statement->bindParam(":qualification", $qualification, PDO::PARAM_STR);
            $statement->bindParam(":hire_date", $hire_date, PDO::PARAM_STR);
            $statement->bindParam(":salary", $salary, PDO::PARAM_INT);
            $statement->bindParam(":address", $address, PDO::PARAM_STR);
            $statement->execute();

            $update_user_query = "UPDATE Users SET email=:email WHERE user_id=:user_id"; //error
            $statement = $pdo->prepare($update_user_query);
            $statement->bindParam(":user_id", $user_id, PDO::PARAM_INT);
            $statement->bindParam(":email", $email, PDO::PARAM_STR);
            $statement->execute();

        }
        catch (PDOException $e) {
            echo $e->getMessage();
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
                <!-- <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="mb-0">Teacher Management</h2>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTeacherModal">
                        <i class="bi bi-plus-lg"></i> Add New Teacher
                    </button>
                </div> -->

                <!-- Teacher Cards -->
                <?php include 'components/teachers/cards.php'; ?>
            </div>
        </div>
    </div>

<?php include 'layouts/footer.php'; ?>