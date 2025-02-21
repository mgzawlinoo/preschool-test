<?php include '../database/db.php'; ?>
<?php include 'layouts/header.php'; ?>

<!-- Add Staff -->
<?php 
        // Add New Staff
        if (isset($_POST['add_staff']) && $_SERVER['REQUEST_METHOD'] == 'POST') {

            $errors = [];
            
            // trim inputs value
            $name = trim($_POST['name']);
            $email = trim($_POST['email']);
            $phone = trim($_POST['phone']);
            $staff_role = trim($_POST['staff-role']);
            $hire_date = trim($_POST['hiredate']);
            $salary = trim($_POST['salary']);
            $address = trim($_POST['address']);
            $password = trim($_POST['password']);
            $confirm_password = trim($_POST['confirm-password']);

            // filter inputs
            $name = htmlspecialchars($name);
            $email = filter_var($email, FILTER_SANITIZE_EMAIL);
            $phone = htmlspecialchars($phone);
            $staff_role = htmlspecialchars($staff_role);
            $hire_date = htmlspecialchars($hire_date);
            $salary = filter_var($salary, FILTER_SANITIZE_NUMBER_INT);
            $address = htmlspecialchars($address);
            $password = htmlspecialchars($password);
            $confirm_password = htmlspecialchars($confirm_password);

            // check empty fields
            if (empty($name) || empty($email) || empty($phone) || empty($staff_role) || empty($hire_date) || empty($salary) || empty($address) || empty($password) || empty($confirm_password) || empty($_FILES['photo']['name'])) {
                $errors['empty'] = 'All fields are required';
            }

            else {
                // check valid email
                if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $errors['email'] = 'Invalid Email Format';
                }

                // check password match
                if ($password != $confirm_password) {
                    $errors['password'] = 'Password does not match';
                }

                // check password length
                if(strlen($password) < 6) {
                    $errors['password'] = 'Password must be at least 6 characters';
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
                    if(!is_dir('uploads')) {
                        mkdir('uploads');
                    }

                    // check directory write permissions
                    if(!is_writable('uploads')) {
                        $errors['photo'] = "Uploads directory is not writable";
                    }

                }

                // check email already exist
                try {
                    $check_email_query = "SELECT user_id FROM Users WHERE email = :email";
                    $statement = $pdo->prepare($check_email_query);
                    $statement->bindParam(":email", $email, PDO::PARAM_STR);
                    $statement->execute();
                    $user = $statement->fetch(PDO::FETCH_ASSOC);
                    if($user) {
                        $errors['email'] = 'Email already exists';
                    }
                }
                catch (PDOException $e) {
                    $errors['dberror'] = $e->getMessage();
                }
            }

            if(count($errors) == 0) {
                try {
                    $role = 'Staff';
                    $password = password_hash($password, PASSWORD_DEFAULT);
                    $add_user_query = "INSERT INTO Users (email, password, role) VALUES (:email, :password, :role)";
                    $statement = $pdo->prepare($add_user_query);
                    $statement->bindParam(":email", $email, PDO::PARAM_STR);
                    $statement->bindParam(":password", $password, PDO::PARAM_STR);
                    $statement->bindParam(":role", $role, PDO::PARAM_STR);
                    $statement->execute();
                    $user_id = $pdo->lastInsertId();

                    $photoname = $user_id . '.' . $photoextension;
                    $upload_result = move_uploaded_file($phototmpname, 'uploads/' . $photoname);
                    $add_teacher_query = "INSERT INTO Staff (user_id, name, photo, phone, staff_role, hire_date, salary, address) VALUES (:user_id, :name, :photo, :phone, :staff_role, :hire_date, :salary, :address)";
                    $statement = $pdo->prepare($add_teacher_query);
                    $statement->bindParam(":user_id", $user_id, PDO::PARAM_INT);
                    $statement->bindParam(":name", $name, PDO::PARAM_STR);
                    $statement->bindParam(":photo", $photoname, PDO::PARAM_STR);
                    $statement->bindParam(":phone", $phone, PDO::PARAM_STR);
                    $statement->bindParam(":staff_role", $staff_role, PDO::PARAM_STR);
                    $statement->bindParam(":hire_date", $hire_date, PDO::PARAM_STR);
                    $statement->bindParam(":salary", $salary, PDO::PARAM_STR);
                    $statement->bindParam(":address", $address, PDO::PARAM_STR);
                    $statement->execute();

                    $_SESSION['success'] = 'Staff created successfully';
                    header("Location: staff.php");
                    exit;
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
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="mb-0">Add Staff</h2>
                </div>

                <?php if(isset($errors) && count($errors) > 0) : ?>
                    <div class="alert alert-danger">
                        <?php foreach($errors as $error) : ?>
                            <li><?php echo $error; ?></li>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <form action="staff-create.php" method="POST" enctype="multipart/form-data" >
                    <div class="row">
                        <div class="col-lg-6 mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="name" class="form-control" value="<?= isset($_POST['name']) ? $_POST['name'] : '' ?>" >
                        </div>

                        <div class="col-lg-6 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="<?= isset($_POST['email']) ? $_POST['email'] : '' ?>" >
                        </div>
                    </div>

                    <div class="row">
                            <div class="col-lg-6 mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-control"  >
                            </div>

                            <div class="col-lg-6 mb-3">
                                <label class="form-label">Confirm Password</label>
                                <input type="password" name="confirm-password" class="form-control"  >
                            </div>
                        </div>

                    <div class="row">
                        <div class="col-lg-6 mb-3">
                            <label class="form-label">Phone Number</label>
                            <input type="tel" name="phone" class="form-control" value="<?php echo isset($_POST['phone']) ? $_POST['phone'] : '' ?>" >
                        </div>
                        <div class="col-lg-6 mb-3">
                            <label class="form-label">Staff Role</label>
                            <select class="form-select" name="staff-role" id="staff-role" >
                                <option value="">Select Staff Role</option>
                                <?php
                                    // staff role array 
                                    $staff_roles = ['HR', 'Accountant', 'Driver', 'Cleaner'];
                                ?>
                                <!-- Show Teacher List with foreach loop -->
                                <?php foreach($staff_roles as $staff_role) : ?>
                                    <option value="<?= $staff_role; ?>"><?= $staff_role; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                    </div>

                    <div class="row">
                        <div class="col-lg-12 mb-3 d-flex align-items-center justify-content-center">
                            <div id="preview-image"><img src="https://placehold.co/150" class="rounded-circle" style="max-width: 150px; height: auto" alt="Staff"></div>
                            <div class="w-100 ps-5">
                                <label class="form-label" for="photo">Photo</label>
                                <input accept="image/jpeg, image/png, image/jpg" type="file" name="photo" class="form-control" id="photo">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-lg-6 mb-3">
                            <label class="form-label">Salary</label>
                            <input type="number" name="salary" class="form-control" value="<?= isset($_POST['salary']) ? $_POST['salary'] : '' ?>" >
                        </div>
                        
                        <div class="col-lg-6 mb-3">
                            <label class="form-label">Hire Date</label>
                            <input type="date" name="hiredate" class="form-control" value="<?= isset($_POST['hiredate']) ? $_POST['hiredate'] : '' ?>" >
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12 mb-3">
                            <label class="form-label">Address</label>
                            <textarea class="form-control" name="address" rows="3" ><?= isset($_POST['address']) ? $_POST['address'] : '' ?></textarea>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-8 offset-lg-2 mb-3 text-center">
                            <a href="staff.php" class="btn btn-secondary">Cancel</a>
                            <button type="submit" name="add_staff" class="btn btn-primary">Add Staff</button>
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

