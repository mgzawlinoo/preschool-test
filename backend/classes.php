<?php include '../database/db.php'; ?>
<?php include 'layouts/header.php'; ?>
<!-- Add Class -->
<?php 
        // Add New Class
        if (isset($_POST['addClass']) && $_SERVER['REQUEST_METHOD'] == 'POST') {

            $error = [];

            // trim inputs value
            $class_name = trim($_POST['class_name']);
            $age = trim($_POST['age']);
            $teacher_id = trim($_POST['teacher_id']);
            $schedule = trim($_POST['schedule']);
            $max_students = trim($_POST['max_students']);
            $start_date = trim($_POST['start_date']);

            // filter inputs
            $class_name = htmlspecialchars($class_name);
            $age = htmlspecialchars($age);
            $teacher_id = filter_var($teacher_id, FILTER_SANITIZE_NUMBER_INT);
            $schedule = htmlspecialchars($schedule);
            $max_students = filter_var($max_students, FILTER_SANITIZE_NUMBER_INT);
            $start_date = htmlspecialchars($start_date);

            // check empty fields
            if (empty($class_name) || empty($age) || empty($teacher_id) || empty($schedule) || empty($max_students) || empty($start_date)) {
                $error['empty'] = 'All fields are required';
            }

            if(count($error) == 0) {
                try {
                    $add_class_query = "INSERT INTO Classes (class_name, age, teacher_id, schedule, max_students, start_date) VALUES (:class_name, :age, :teacher_id, :schedule, :max_students, :start_date)";
                    $statement = $pdo->prepare($add_class_query);
                    $statement->bindParam(":class_name", $class_name, PDO::PARAM_STR);
                    $statement->bindParam(":age", $age, PDO::PARAM_STR);
                    $statement->bindParam(":teacher_id", $teacher_id, PDO::PARAM_INT);
                    $statement->bindParam(":schedule", $schedule, PDO::PARAM_STR);
                    $statement->bindParam(":max_students", $max_students, PDO::PARAM_INT);
                    $statement->bindParam(":start_date", $start_date, PDO::PARAM_STR);
                    $statement->execute();
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
                    <h2 class="mb-0">Class Management</h2>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addClassModal">
                        <i class="bi bi-plus-lg"></i> Add New Class
                    </button>
                </div>

                <?php if(isset($error) && count($error) > 0) : ?>
                    <div class="alert alert-danger">
                        <?php foreach($error as $e) : ?>
                            <li><?php echo $e; ?></li>
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
                <div class="modal-header">
                    <h5 class="modal-title">Add New Class</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                <form action="classes.php" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Class Name</label>
                            <input type="text" class="form-control" name="class_name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Age Group</label>
                            <select class="form-select" name="age" required>
                                <option value="">Select Age Group</option>
                                <option value="2-3 years">2-3 years</option>
                                <option value="3-4 years">3-4 years</option>
                                <option value="4-5 years">4-5 years</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Lead Teacher</label>

                            <select class="form-select" name="teacher_id" required>
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
                            <input type="number" class="form-control" name="max_students" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Start Date</label>
                            <input type="date" class="form-control" name="start_date" required>
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