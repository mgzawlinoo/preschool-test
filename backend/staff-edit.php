<?php include '../database/db.php'; ?>
<?php include 'layouts/header.php'; ?>

<?php 
    // ှShow Teacher Data from Get ID and Fill it to form
    if(isset($_GET['id'])) {
        $staff_id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
        $staff_id = trim($staff_id);

        $get_staff_query = "SELECT * FROM Staff LEFT JOIN Users ON Staff.user_id = Users.user_id WHERE staff_id = :staff_id";
        $statement = $pdo->prepare($get_staff_query);
        $statement->bindParam(":staff_id", $staff_id, PDO::PARAM_INT);
        $statement->execute();
        $staff = $statement->fetch(PDO::FETCH_ASSOC);
    }
?>

<?php 
        // UPDATE TEACHER
        if (isset($_POST['update_staff']) && $_SERVER['REQUEST_METHOD'] == 'POST') {

            $error = [];
            
            // trim inputs value
            $user_id = trim($_POST['user-id']);
            $staff_id = trim($_POST['staff-id']);
            $name = trim($_POST['name']);
            $phone = trim($_POST['phone']);
            $staff_role = trim($_POST['staff-role']);
            $photo = trim(isset($_POST['photo']) ? $_POST['photo'] : '');
            $hire_date = trim($_POST['hiredate']);
            $salary = trim($_POST['salary']);
            $address = trim($_POST['address']);

            // filter inputs
            $user_id = filter_var($user_id, FILTER_SANITIZE_NUMBER_INT);
            $staff_id = filter_var($staff_id, FILTER_SANITIZE_NUMBER_INT);
            $name = htmlspecialchars($name);
            $phone = htmlspecialchars($phone);
            $staff_role = htmlspecialchars($staff_role);
            $photo = htmlspecialchars($photo);
            $hire_date = htmlspecialchars($hire_date);
            $salary = filter_var($salary, FILTER_SANITIZE_NUMBER_INT);
            $address = htmlspecialchars($address);

            // check empty fields
            if (empty($name) || empty($phone) || empty($staff_role) || empty($hire_date) || empty($salary) || empty($address)) {
                $error['empty'] = 'All fields are required';
            }

            // update query for teachers

            // check if file is uploaded
            if($_FILES['photo']['error'] == 0 && !empty($_FILES['photo']['name'])) {
                
                $photoname = $_FILES['photo']['name'];  

                // get file extension
                $photoextension = pathinfo($photoname, PATHINFO_EXTENSION);

                $phototype = $_FILES['photo']['type'];
                if($phototype != 'image/jpeg' && $phototype != 'image/png' && $phototype != 'image/jpg') {
                    $error['photo'] = 'Invalid file type';
                }
    
                $phototmpname = $_FILES['photo']['tmp_name'];  

                // validate file size
                $phototmpsize = $_FILES['photo']['size'];
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

                // check directory write permissions
                if(!is_writable('uploads')) {
                    $error['photo'] = "Uploads directory is not writable";
                }

                $photoname = $user_id . '.' . $photoextension;
                $upload_result = move_uploaded_file($phototmpname, 'uploads/' . $photoname);
                if(!$upload_result) {
                    $error['photo'] = "Failed to upload file";
                }
            }
            else {
                $photoname = $staff['photo'];
            }

            if(count($error) == 0) {
                try {
                    $update_staff_query = "UPDATE Staff SET name=:name, staff_role=:staff_role, phone=:phone, photo=:photo, hire_date=:hire_date, salary=:salary, address=:address WHERE staff_id=:staff_id"; //error
                    $statement = $pdo->prepare($update_staff_query);
                    $statement->bindParam(":staff_id", $staff_id, PDO::PARAM_INT);
                    $statement->bindParam(":name", $name, PDO::PARAM_STR);
                    $statement->bindParam(":staff_role", $staff_role, PDO::PARAM_STR);
                    $statement->bindParam(":phone", $phone, PDO::PARAM_STR);
                    $statement->bindParam(":photo", $photoname, PDO::PARAM_STR);
                    $statement->bindParam(":hire_date", $hire_date, PDO::PARAM_STR);
                    $statement->bindParam(":salary", $salary, PDO::PARAM_INT);
                    $statement->bindParam(":address", $address, PDO::PARAM_STR);
                    $statement->execute();
    
                    $_SESSION['success'] = 'Staff updated successfully';
                    header("Location: staff.php");
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
                    <h2 class="mb-0">Update Staff</h2>
                </div>

                <?php if(isset($error) && count($error) > 0) : ?>
                    <div class="alert alert-danger">
                        <?php foreach($error as $e) : ?>
                            <li><?php echo $e; ?></li>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <form action="staff-edit.php?id=<?= $staff['staff_id'] ?>" method="POST" enctype="multipart/form-data" >
                        <div class="modal-body">

                                <input type="hidden" name="staff-id" value="<?= $staff['staff_id'] ?>" required>
                                <!-- ဒီ user id လိုတာက ပုံမှာ နာမည်ကို generate အသေထုတ်ချင်တဲ့ အတွက် သုံးဖို့ လိုတာ , အမှန်က မလိုအပ်ပါ -->
                                <input type="hidden" name="user-id" value="<?= $staff['user_id'] ?>" required>

                                <div class="row">
                                    <div class="col-lg-6 mb-3">
                                        <label class="form-label">Full Name</label>
                                        <input type="text" name="name" class="form-control" value="<?= $staff['name'] ?>" required>
                                    </div>

                                    <div class="col-lg-6 mb-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" class="form-control" value="<?= $staff['email'] ?>" disabled>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6 mb-3">
                                        <label class="form-label">Phone Number</label>
                                        <input type="tel" name="phone" class="form-control" value="<?= $staff['phone'] ?>" required>
                                    </div>
                                    <div class="col-lg-6 mb-3">
                                        <label class="form-label">Staff Role</label>
                                        <select class="form-select" name="staff-role" id="staff-role" required>
                                            <option value="">Select Staff Role</option>
                                            <?php
                                                // staff role array 
                                                $staff_roles = ['Admin', 'Assistant', 'Other'];
                                            ?>
                                            <!-- Show Teacher List with foreach loop -->
                                            <?php foreach($staff_roles as $staff_role) : ?>
                                                <option 
                                                <?php if ($staff['staff_role'] == $staff_role) : ?> selected <?php endif; ?>
                                                value="<?= $staff_role; ?>"><?= $staff_role; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                   
                                </div>

                                <div class="row">
                                    <div class="col-lg-12 mb-3 d-flex align-items-center justify-content-center">
                                        <div id="preview-image"><img src="<?= $staff['photo'] ? 'uploads/' . $staff['photo'] : 'https://placehold.co/150' ?>" class="rounded-circle" style="max-width: 150px; height: auto" alt="Staff"></div>
                                        <div class="w-100 ps-5">
                                            <label class="form-label" for="photo">Photo</label>
                                            <input accept="image/jpeg, image/png, image/jpg" type="file" name="photo" class="form-control" id="photo">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-lg-6 mb-3">
                                        <label class="form-label">Salary</label>
                                        <input type="number" name="salary" class="form-control" value="<?= $staff['salary'] ?>" required>
                                    </div>
                                    
                                    <div class="col-lg-6 mb-3">
                                        <label class="form-label">Hire Date</label>
                                        <input type="date" name="hiredate" class="form-control" value="<?= $staff['hire_date'] ?>" required>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-12 mb-3">
                                        <label class="form-label">Address</label>
                                        <textarea class="form-control" name="address" rows="3" required><?= $staff['address'] ?></textarea>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-8 offset-lg-2 mb-3 text-center">
                                        <a href="staff.php" class="btn btn-secondary">Cancel</a>
                                        <button type="submit" name="update_staff" class="btn btn-primary">Update Staff</button>
                                    </div>
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

