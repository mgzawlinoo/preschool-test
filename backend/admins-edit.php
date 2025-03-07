<?php include '../database/db.php'; ?>
<?php include './layouts/header.php'; ?>

<?php 
    // ှShow Teacher Data from Get ID and Fill it to form
    if(isset($_GET['id'])) {
        $admin_id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
        $admin_id = trim($admin_id);

        $get_admin_query = "SELECT * FROM Admins LEFT JOIN Users ON Admins.user_id = Users.user_id WHERE admin_id = :admin_id";
        $statement = $pdo->prepare($get_admin_query);
        $statement->bindParam(":admin_id", $admin_id, PDO::PARAM_INT);
        $statement->execute();
        $admin = $statement->fetch(PDO::FETCH_ASSOC);
    }
?>

<?php 
        // UPDATE TEACHER
        if (isset($_POST['update_admin']) && $_SERVER['REQUEST_METHOD'] == 'POST') {

            $errors = [];
            
            // trim inputs value
            $user_id = trim($_POST['user-id']);
            $admin_id = trim($_POST['admin-id']);
            $name = trim($_POST['name']);
            $phone = trim($_POST['phone']);
            $photo = trim(isset($_POST['photo']) ? $_POST['photo'] : '');
            $address = trim($_POST['address']);

            // filter inputs
            $user_id = filter_var($user_id, FILTER_SANITIZE_NUMBER_INT);
            $admin_id = filter_var($admin_id, FILTER_SANITIZE_NUMBER_INT);
            $name = htmlspecialchars($name);
            $phone = htmlspecialchars($phone);
            $photo = htmlspecialchars($photo);
            $address = htmlspecialchars($address);

            // check empty fields
            if (empty($name) || empty($phone) || empty($address)) {
                $errors['empty'] = 'All fields are required';
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
                    if(!is_dir('uploads')) {
                        mkdir('uploads');
                    }

                    // check directory write permissions
                    if(!is_writable('uploads')) {
                        $errors['photo'] = "Uploads directory is not writable";
                    }

                    $photoname = $user_id . '.' . $photoextension;
                    $upload_result = move_uploaded_file($phototmpname, 'uploads/' . $photoname);
                    if(!$upload_result) {
                        $errors['photo'] = "Failed to upload file";
                    }
                }
                else {
                    $photoname = $admin['photo'];
                }
            }

            if(count($errors) == 0) {
                try {
                    $update_staff_query = "UPDATE Admins SET name=:name, phone=:phone, photo=:photo, address=:address WHERE admin_id=:admin_id"; //error
                    $statement = $pdo->prepare($update_staff_query);
                    $statement->bindParam(":admin_id", $admin_id, PDO::PARAM_INT);
                    $statement->bindParam(":name", $name, PDO::PARAM_STR);
                    $statement->bindParam(":phone", $phone, PDO::PARAM_STR);
                    $statement->bindParam(":photo", $photoname, PDO::PARAM_STR);
                    $statement->bindParam(":address", $address, PDO::PARAM_STR);
                    $statement->execute();
    
                    $_SESSION['success'] = 'Admin updated successfully';
                    header("Location: admins.php");
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
        <?php include './components/sidebar.php'; ?>

        <!-- Page Content -->
        <div id="content">
            <!-- Top Navigation -->
            <?php include './components/top-nav.php'; ?>
      
            <!-- Main Content -->
            <div class="container-fluid p-4">
                <div class="bg-warning text-black p-4 rounded  d-flex justify-content-between align-items-center mb-4">
                    <h2 class="mb-0"><i class="bi bi-pencil-square me-2"></i> Update Admin</h2>
                </div>

                <?php if(isset($errors) && count($errors) > 0) : ?>
                    <div class="alert alert-danger">
                        <?php foreach($errors as $error) : ?>
                            <li><?php echo $error; ?></li>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <form action="admins-edit.php?id=<?= $admin['admin_id'] ?>" method="POST" enctype="multipart/form-data" >
                    <input type="hidden" name="admin-id" value="<?= $admin['admin_id'] ?>" required>
                    <!-- ဒီ user id လိုတာက ပုံမှာ နာမည်ကို generate အသေထုတ်ချင်တဲ့ အတွက် သုံးဖို့ လိုတာ , အမှန်က မလိုအပ်ပါ -->
                    <input type="hidden" name="user-id" value="<?= $admin['user_id'] ?>" required>
                    <div class="row">
                        <div class="col-lg-6 mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="name" class="form-control" value="<?= $admin['name'] ?>" required>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" value="<?= $admin['email'] ?>" disabled>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 mb-3">
                            <label class="form-label">Phone Number</label>
                            <input type="text" name="phone" class="form-control" value="<?= $admin['phone'] ?>" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 mb-3 d-flex align-items-center justify-content-center">
                            <div id="preview-image"><img src="<?= $admin['photo'] ? 'uploads/' . $admin['photo'] : 'https://placehold.co/150' ?>" class="rounded-circle" style="max-width: 150px; height: auto" alt="Staff"></div>
                            <div class="w-100 ps-5">
                                <label class="form-label" for="photo">Photo</label>
                                <input accept="image/jpeg, image/png, image/jpg" type="file" name="photo" class="form-control" id="photo">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 mb-3">
                            <label class="form-label">Address</label>
                            <textarea class="form-control" name="address" rows="3" required><?= $admin['address'] ?></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-8 offset-lg-2 mb-3 text-center">
                            <a href="admins.php" class="btn btn-secondary">Cancel</a>
                            <button type="submit" name="update_admin" class="btn btn-primary">Update Admin</button>
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

