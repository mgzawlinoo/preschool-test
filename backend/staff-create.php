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
                    <h2 class="mb-0">Add Staff</h2>
                </div>

                <?php if(isset($error) && count($error) > 0) : ?>
                    <div class="alert alert-danger">
                        <?php foreach($error as $e) : ?>
                            <li><?php echo $e; ?></li>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <form action="staff-create.php" method="POST" enctype="multipart/form-data" >
                    <h4 class="text-danger">Still in progress !!!</h4></p>
                    <div class="row">
                        <div class="col-lg-6 mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="name" class="form-control" value="" required>
                        </div>

                        <div class="col-lg-6 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="" required>
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
                        <div class="col-lg-6 mb-3">
                            <label class="form-label">Phone Number</label>
                            <input type="tel" name="phone" class="form-control" value="" required>
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
                            <input type="number" name="salary" class="form-control" value="" required>
                        </div>
                        
                        <div class="col-lg-6 mb-3">
                            <label class="form-label">Hire Date</label>
                            <input type="date" name="hiredate" class="form-control" value="" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12 mb-3">
                            <label class="form-label">Address</label>
                            <textarea class="form-control" name="address" rows="3" required></textarea>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-8 offset-lg-2 mb-3 text-center">
                            <a href="teachers.php" class="btn btn-secondary">Cancel</a>
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

