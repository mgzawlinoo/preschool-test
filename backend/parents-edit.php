<?php include '../database/db.php'; ?>
<?php include 'layouts/header.php'; ?>

<?php 
    // ှShow Parent Data from Get ID and Fill it to form

    $errors = [];

    if(isset($_GET['id'])) {
        $parent_id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
        $parent_id = trim($parent_id);
        try {
            $get_parent_query = "SELECT * FROM Parents LEFT JOIN Users ON Parents.user_id = Users.user_id WHERE parent_id = :parent_id";
            $statement = $pdo->prepare($get_parent_query);
            $statement->bindParam(":parent_id", $parent_id, PDO::PARAM_INT);
            $statement->execute();
            $parent = $statement->fetch(PDO::FETCH_ASSOC);
        }
        catch (Exception $e) {
            $errors['dberror'] = $e->getMessage();
        }
    }
?>

<?php 
  // UPDATE STUDENT
  if (isset($_POST['update_parent']) && $_SERVER['REQUEST_METHOD'] == 'POST') {

    $errors = [];
    
    // trim inputs value
    $user_id = trim($_POST['user-id']);
    $parent_id = trim($_POST['parent-id']);
    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);

    // filter inputs
    $user_id = filter_var($user_id, FILTER_SANITIZE_NUMBER_INT);
    $parent_id = filter_var($parent_id, FILTER_SANITIZE_NUMBER_INT);
    $name = htmlspecialchars($name);
    $phone = htmlspecialchars($phone);
    $address = htmlspecialchars($address);

    // check empty fields
    if (empty($user_id) || empty($parent_id) || empty($name) || empty($phone) || empty($address)) {
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

            if(empty($errors['photo'])) {
                $photoname = $user_id . '.' . $photoextension;
                $upload_result = move_uploaded_file($phototmpname, 'uploads/' . $photoname);
                if(!$upload_result) {
                    $errors['photo'] = "Failed to upload file";
                }
            }
        }
        else {
            $photoname = $parent['photo'];
        }
    }

    // update query for student
    if(count($errors) == 0) {
        try {
            $update_parent_query = "UPDATE Parents SET name=:name, phone=:phone, address=:address, photo=:photo WHERE parent_id=:parent_id"; //error
            $statement = $pdo->prepare($update_parent_query);
            $statement->bindParam(":parent_id", $parent_id, PDO::PARAM_INT);
            $statement->bindParam(":name", $name, PDO::PARAM_STR);
            $statement->bindParam(":photo", $photoname, PDO::PARAM_STR);
            $statement->bindParam(":phone", $phone, PDO::PARAM_STR);
            $statement->bindParam(":address", $address, PDO::PARAM_STR);
            $statement->execute();

            $_SESSION['success'] = 'Parent updated successfully';
            header("Location: parents.php");
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
                    <h2 class="mb-0"><i class="bi bi-pencil-square"></i> Update Parent</h2>
                </div>

                <!-- Show Error -->
                <?php if(isset($errors) && count($errors) > 0) : ?>
                    <div class="alert alert-danger">
                        <?php foreach($errors as $error) : ?>
                            <li><?php echo $error; ?></li>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <form action="parents-edit.php?id=<?= $parent['parent_id']; ?>" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="parent-id" value="<?= $parent['parent_id']; ?>" required>
                        <input type="hidden" name="user-id" value="<?= $parent['user_id']; ?>" required>
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" class="form-control" name="name" value="<?= $parent['name']; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" value="<?= $parent['email']; ?>" disabled>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 mb-3 d-flex align-items-center justify-content-center">
                                <div id="preview-image"><img src="<?= $parent['photo'] ? 'uploads/' . $parent['photo'] : 'https://placehold.co/150' ?>" class="rounded-circle" style="max-width: 150px; height: auto" alt="Parent"></div>
                                <div class="w-100 ps-5">
                                    <label class="form-label" for="photo">Photo</label>
                                    <input accept="image/jpeg, image/png, image/jpg" type="file" name="photo" class="form-control" id="photo">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Phone</label>
                            <input type="text" class="form-control" name="phone" value="<?= $parent['phone']; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Address</label>
                            <input type="text" class="form-control" name="address" value="<?= $parent['address']; ?>" required>
                        </div>
                        <div class="mb-3 text-center">
                            <a href="parents.php" class="btn btn-secondary">Cancel</a>
                            <button type="submit" name="update_parent" class="btn btn-primary">Update Parent</button>
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