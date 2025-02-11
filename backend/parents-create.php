<?php include '../database/db.php'; ?>
<?php include 'layouts/header.php'; ?>

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
                    <h2 class="mb-0">Add Parent</h2>
                </div>

                <?php if(isset($error) && count($error) > 0) : ?>
                    <div class="alert alert-danger">
                        <?php foreach($error as $e) : ?>
                            <li><?php echo $e; ?></li>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <form action="parents-create.php" method="POST" enctype="multipart/form-data" >
                <h4 class="text-danger">Still in progress !!!</h4></p>
                        <div class="row">
                            <div class="col-lg-6 mb-3">
                                <label class="form-label">Name</label>
                                <input type="text" class="form-control" name="name" value="" required>
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" value="" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-control"  required >
                            </div>

                            <div class="col-lg-6 mb-3">
                                <label class="form-label">Confirm Password</label>
                                <input type="password" name="confirm_password" class="form-control"  required >
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 mb-3 d-flex align-items-center justify-content-center">
                            <div id="preview-image"><img src="https://placehold.co/150" class="rounded-circle" style="max-width: 150px; height: auto" alt="Parent"></div>
                                <div class="w-100 ps-5">
                                    <label class="form-label" for="photo">Photo</label>
                                    <input accept="image/jpeg, image/png, image/jpg" type="file" name="photo" class="form-control" id="photo">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-3">
                                <label class="form-label">Phone</label>
                                <input type="text" class="form-control" name="phone" value="" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Address</label>
                                <input type="text" class="form-control" name="address" value="" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-8 offset-lg-2 mb-3 text-center">
                                <a href="parents.php" class="btn btn-secondary">Cancel</a>
                                <button type="submit" name="add_parent" class="btn btn-primary">Add Parent</button>
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