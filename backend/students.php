<?php include '../database/db.php'; ?>
<?php include 'layouts/header.php'; ?>

<?php 
        // Add Student
        if (isset($_POST['add_student']) && $_SERVER['REQUEST_METHOD'] == 'POST') {

            $error = [];

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

            if(empty($name) || empty($date_of_birth) || empty($gender) || empty($enrollment_date) || empty($class_id) || empty($parent_id)) {
                $error['name'] = 'All fields are required';
            }

            if(count($error) == 0) {
                try {
                    $add_class_query = "INSERT INTO Students (name, date_of_birth, gender, enrollment_date, class_id, parent_id) VALUES (:name, :date_of_birth, :gender, :enrollment_date, :class_id, :parent_id)";
                    $statement = $pdo->prepare($add_class_query);
                    $statement->bindParam(":name", $name, PDO::PARAM_STR);
                    $statement->bindParam(":date_of_birth", $date_of_birth, PDO::PARAM_STR);
                    $statement->bindParam(":gender", $gender, PDO::PARAM_STR);
                    $statement->bindParam(":enrollment_date", $enrollment_date, PDO::PARAM_STR);
                    $statement->bindParam(":class_id", $class_id, PDO::PARAM_INT);
                    $statement->bindParam(":parent_id", $parent_id, PDO::PARAM_INT);
                    $statement->execute();

                    $_SESSION['success'] = 'Student added successfully';
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
                    <h2 class="mb-0">Student Management</h2>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addStudentModal">
                        <i class="bi bi-plus-lg"></i> Add New Student
                    </button>
                </div>

                <?php if(isset($_SESSION['success'])) :  ?>
                    <div class="alert alert-success">
                        <?php echo $_SESSION['success']; ?>
                        <?php unset($_SESSION['success']); ?>
                    </div>
                <?php endif; ?>

                <!-- Filters -->
                <!-- <?php include 'components/students/filters.php'; ?> -->

                <?php if(isset($error) && count($error) > 0) : ?>
                    <div class="alert alert-danger">
                        <?php foreach($error as $e) : ?>
                            <li><?php echo $e; ?></li>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <!-- Students Table -->
                <?php include 'components/students/table.php'; ?>
            </div>
        </div>
    </div>

    <!-- Add Student Modal -->
    <div class="modal fade" id="addStudentModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Student</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">

                    <form action="students.php" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Student Name</label>
                            <input type="text" class="form-control" name="name" required>
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

<?php include 'layouts/footer.php'; ?>