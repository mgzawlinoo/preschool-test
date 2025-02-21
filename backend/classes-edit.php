<?php include '../database/db.php'; ?>
<?php include './layouts/header.php'; ?>

<?php 

    $errors = [];
    // ှShow Class Data from Get ID and Fill it to form
    if(isset($_GET['id'])) {
        $class_id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
        $class_id = trim($class_id);

        try {
            $get_class_query = "SELECT * FROM Classes WHERE class_id = :class_id";
            $statement = $pdo->prepare($get_class_query);
            $statement->bindParam(":class_id", $class_id, PDO::PARAM_INT);
            $statement->execute();
            $class = $statement->fetch(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e) {
            $errors['dberror'] = $e->getMessage();
        }
    }
?>

<?php 
  // UPDATE CLASS
  if (isset($_POST['update_class']) && $_SERVER['REQUEST_METHOD'] == 'POST') {

    $errors = [];
    
    // trim inputs value
    $class_id = trim($_POST['class-id']);
    $class_name = trim($_POST['name']);
    $fees = trim($_POST['fees']);
    $age_group = trim($_POST['age-group']);
    $teacher_id = trim($_POST['teacher-id']);
    $schedule = trim($_POST['schedule']);
    $max_students = trim($_POST['max-students']);
    $start_date = trim($_POST['start-date']);
    $status = trim($_POST['status']);

    // filter inputs
    $class_id = filter_var($class_id, FILTER_SANITIZE_NUMBER_INT);
    $class_name = htmlspecialchars($class_name);
    $fees = htmlspecialchars($fees);
    $age_group = htmlspecialchars($age_group);
    $teacher_id = filter_var($teacher_id, FILTER_SANITIZE_NUMBER_INT);
    $schedule = htmlspecialchars($schedule);
    $max_students = filter_var($max_students, FILTER_SANITIZE_NUMBER_INT);
    $start_date = htmlspecialchars($start_date);
    $status = htmlspecialchars($status);

    // check empty fields
    if (empty($class_name) || empty($fees) || empty($age_group) || empty($teacher_id) || empty($schedule) || empty($max_students) || empty($start_date) || empty($status)) {
        $errors['empty'] = 'All fields are required';
    }

    // update query for student
    if(count($errors) == 0) {
        try {
            $update_student_query = "UPDATE Classes SET class_name=:class_name, fees=:fees, age_group=:age_group, teacher_id=:teacher_id, schedule=:schedule, max_students=:max_students, start_date=:start_date, status=:status WHERE class_id=:class_id"; //error
            $statement = $pdo->prepare($update_student_query);
            $statement->bindParam(":class_id", $class_id, PDO::PARAM_INT);
            $statement->bindParam(":class_name", $class_name, PDO::PARAM_STR);
            $statement->bindParam(":fees", $fees, PDO::PARAM_INT);
            $statement->bindParam(":age_group", $age_group, PDO::PARAM_STR);
            $statement->bindParam(":teacher_id", $teacher_id, PDO::PARAM_INT);
            $statement->bindParam(":schedule", $schedule, PDO::PARAM_STR);
            $statement->bindParam(":max_students", $max_students, PDO::PARAM_INT);
            $statement->bindParam(":start_date", $start_date, PDO::PARAM_STR);
            $statement->bindParam(":status", $status, PDO::PARAM_STR);

            $statement->execute();

            header("Location: classes.php");
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
                    <h2 class="mb-0"><i class="bi bi-pencil-square me-2"></i> Update Class</h2>
                </div>

                <?php if(isset($errors) && count($errors) > 0) : ?>
                    <div class="alert alert-danger">
                        <?php foreach($errors as $error) : ?>
                            <li><?php echo $error; ?></li>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <form action="classes-edit.php?id=<?= $class['class_id']; ?>" method="POST">
                    <input type="hidden" name="class-id" value="<?= $class['class_id']; ?>">
                    
                    <div class="row g-3">
                        <div class="col-lg-6 mb-3">
                            <label class="form-label">Class Name</label>
                            <input type="text" class="form-control" name="name" value="<?= $class['class_name']; ?>" required>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <label class="form-label">Fees</label>
                            <input type="number" class="form-control" name="fees" value="<?= $class['fees']; ?>" required>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-lg-6 mb-3">
                            <label class="form-label">Age Group</label>
                            <select class="form-select" name="age-group" required>

                            <option value="">Select Age Group</option>
                                <?php
                                    $ages = ['4-5 Years', '5-6 Years', '6-7 Years'];
                                ?>
                                <!-- Show Age Group List with foreach loop -->
                                <?php foreach($ages as $age) : ?>
                                    <option 
                                    <?php if ($class['age_group'] == $age) : ?> selected <?php endif; ?>
                                    value="<?= $age; ?>"><?= $age; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-lg-6 mb-3">
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
                                    <option 
                                    <?php if ($class['teacher_id'] == $teacher['teacher_id']) : ?> selected <?php endif; ?>
                                    value="<?= $teacher['teacher_id']; ?>"><?= $teacher['name']; ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>

                            </select>
                        </div>  
                    </div>

                    <div class="row g-3">
                        <div class="col-lg-6 mb-3">
                            <label class="form-label">Schedule</label>
                            <select class="form-select" name="schedule" id="schedule" required>
                                <option value="">Select Time Slot</option>
                                <?php
                                    $schedules = ['Mon-Fri, 9:00 AM - 12:00 PM', 'Mon-Fri, 1:00 PM - 4:00 PM'];
                                ?>
                                <!-- Show Schedule List with foreach loop -->
                                <?php foreach($schedules as $schedule) : ?>
                                    <option 
                                    <?php if ($class['schedule'] == $schedule) : ?> selected <?php endif; ?>
                                    value="<?= $schedule; ?>"><?= $schedule; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <label class="form-label">Maximum Students</label>
                            <input type="number" class="form-control" name="max-students" value="<?= $class['max_students']; ?>" required>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-lg-6 mb-3">
                            <label class="form-label">Start Date</label>
                            <input type="date" class="form-control" name="start-date" value="<?= $class['start_date']; ?>" required>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-select" name="status" id="status" required>
                                <option value="">Select Status</option>
                                <?php if($class['status'] == 'active') : ?>
                                    <option value="active" selected>Active</option>
                                    <option value="inactive">Inactive</option>
                                <?php else : ?>
                                    <option value="active">Active</option>
                                    <option value="inactive" selected>Inactive</option>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="my-3 text-center">
                            <a href="classes.php" class="btn btn-secondary">Cancel</a>
                            <button type="submit" name="update_class" class="btn btn-primary">Update Class</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>

  

<?php include './layouts/footer.php'; ?>