<?php include '../database/db.php'; ?>
<?php include 'layouts/header.php'; ?>
<!-- Add Class -->
<?php 
        // Add New Class
        if (isset($_POST['addClass']) && $_SERVER['REQUEST_METHOD'] == 'POST') {

            $errors = [];

            // trim inputs value
            $class_name = trim($_POST['class-name']);
            $fees = trim($_POST['fees']);
            $age_group = trim($_POST['age-group']);
            $teacher_id = trim($_POST['teacher-id']);
            $schedule = trim($_POST['schedule']);
            $max_students = trim($_POST['max-students']);
            $start_date = trim($_POST['start-date']);

            // filter inputs
            $class_name = htmlspecialchars($class_name);
            $fees = htmlspecialchars($fees);
            $age_group = htmlspecialchars($age_group);
            $teacher_id = filter_var($teacher_id, FILTER_SANITIZE_NUMBER_INT);
            $schedule = htmlspecialchars($schedule);
            $max_students = filter_var($max_students, FILTER_SANITIZE_NUMBER_INT);
            $start_date = htmlspecialchars($start_date);

            // check empty fields
            if (empty($class_name) || empty($fees) || empty($age_group) || empty($teacher_id) || empty($schedule) || empty($max_students) || empty($start_date)) {
                $errors['empty'] = 'All fields are required';
            }

            if(count($errors) == 0) {
                try {
                    $add_class_query = "INSERT INTO Classes (class_name, fees, age_group, teacher_id, schedule, max_students, start_date) VALUES (:class_name, :fees, :age_group, :teacher_id, :schedule, :max_students, :start_date)";
                    $statement = $pdo->prepare($add_class_query);
                    $statement->bindParam(":class_name", $class_name, PDO::PARAM_STR);
                    $statement->bindParam(":fees", $fees, PDO::PARAM_STR);
                    $statement->bindParam(":age_group", $age_group, PDO::PARAM_STR);
                    $statement->bindParam(":teacher_id", $teacher_id, PDO::PARAM_INT);
                    $statement->bindParam(":schedule", $schedule, PDO::PARAM_STR);
                    $statement->bindParam(":max_students", $max_students, PDO::PARAM_INT);
                    $statement->bindParam(":start_date", $start_date, PDO::PARAM_STR);
                    $statement->execute();
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

                <div class="bg-warning text-black p-4 rounded d-flex justify-content-between align-items-center mb-4">
                    <h2 class="mb-0"><i class="bi bi-book me-2"></i> Class Management</h2>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addClassModal">
                        <i class="bi bi-plus-lg"></i> Add New Class
                    </button>
                </div>

                <?php if(isset($errors) && count($errors) > 0) : ?>
                    <div class="alert alert-danger">
                        <?php foreach($errors as $error) : ?>
                            <li><?php echo $error; ?></li>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <!-- Class Cards -->
                <?php include 'components/classes/cards.php'; ?>

                <!-- Weekly Schedule -->
                <!-- <?php include 'components/classes/weekly-schedule.php'; ?> -->

            </div>
        </div>
    </div>

    <!-- Add Class Modal -->
    <div class="modal fade" id="addClassModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Add New Class</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                <form action="classes.php" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label class="form-label">Class Name</label>
                            <input type="text" class="form-control" name="class-name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Fees</label>
                            <input type="number" class="form-control" name="fees" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Age Group</label>
                            <select class="form-select" name="age-group" required>
                                <option value="">Select Age Group</option>
                                <option value="4-5 years">4-5 years</option>
                                <option value="5-6 years">5-6 years</option>
                                <option value="6-7 years">6-7 years</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Lead Teacher</label>

                            <select class="form-select" name="teacher-id" required>
                                <option value="">Select Teacher</option>

                                <?php 

                                    $get_teacher_list_query = "SELECT * FROM Teachers";
                                    $statement = $pdo->prepare($get_teacher_list_query);
                                    $statement->execute();
                                    $teachers = [];

                                    // fetch teacher with while loop
                                    while($teacher = $statement->fetch(PDO::FETCH_ASSOC)) {
                                        $teachers[] = $teacher;
                                    }

                                ?>

                                <?php if(count($teachers) > 0) : ?>
                                    <!-- Show Teacher List with foreach loop -->
                                    <?php foreach($teachers as $teacher) : ?>
                                        <option value="<?= $teacher['teacher_id']; ?>"><?= $teacher['name']; ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>

                            </select>
                            
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Schedule</label>
                            <select class="form-select" name="schedule" required>
                                <option value="">Select Time Slot</option>
                                <option value="Mon-Fri, 9:00 AM - 12:00 PM">Mon-Fri, 9:00 AM - 12:00 PM</option>
                                <option value="Mon-Fri, 1:00 PM - 4:00 PM">Mon-Fri, 1:00 PM - 4:00 PM</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Maximum Capacity</label>
                            <input type="number" class="form-control" name="max-students" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Start Date</label>
                            <input type="date" class="form-control" name="start-date" required>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" name="addClass" class="btn btn-primary">Add Class</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php include 'layouts/footer.php'; ?>