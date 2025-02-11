<?php include '../database/db.php'; ?>
<?php include 'layouts/header.php'; ?>

<?php 
    // ှShow Student Data from Get ID and Fill it to form
    if(isset($_GET['id'])) {
        $student_id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
        $student_id = trim($student_id);

        $get_student_query = "SELECT *, Students.name AS student_name, Classes.class_name, Parents.name AS parent_name FROM Students LEFT JOIN Classes ON Students.class_id = Classes.class_id LEFT JOIN Parents ON Students.parent_id = Parents.parent_id WHERE student_id = :student_id";
        $statement = $pdo->prepare($get_student_query);
        $statement->bindParam(":student_id", $student_id, PDO::PARAM_INT);
        $statement->execute();
        $student = $statement->fetch(PDO::FETCH_ASSOC);
    }
?>

<?php 
  // UPDATE STUDENT
  if (isset($_POST['update_student']) && $_SERVER['REQUEST_METHOD'] == 'POST') {

    $error = [];

    // trim inputs value
    $student_id = trim($_POST['student-id']);
    $name = trim($_POST['name']);
    $date_of_birth = trim($_POST['date-of-birth']);
    $gender = trim($_POST['gender']);
    $parent_id = trim($_POST['parent-id']);
    $class_id = trim($_POST['class-id']);
    $enrollment_date = trim($_POST['enrollment-date']);

    // filter inputs
    $student_id = filter_var($student_id, FILTER_SANITIZE_NUMBER_INT);
    $name = htmlspecialchars($name);
    $date_of_birth = htmlspecialchars($date_of_birth);
    $gender = htmlspecialchars($gender);
    $parent_id = filter_var($parent_id, FILTER_SANITIZE_NUMBER_INT);
    $class_id = filter_var($class_id, FILTER_SANITIZE_NUMBER_INT);
    $enrollment_date = htmlspecialchars($enrollment_date);

    // check empty fields
    if (empty($name) || empty($date_of_birth) || empty($gender) || empty($parent_id) || empty($class_id) || empty($enrollment_date)) {
        $error['empty'] = 'All fields are required';
    }

    // update query for student
    if(count($error) == 0) {
        try {
            $update_student_query = "UPDATE Students SET name=:name, date_of_birth=:date_of_birth, gender=:gender, parent_id=:parent_id, class_id=:class_id, enrollment_date=:enrollment_date WHERE student_id=:student_id"; //error
            $statement = $pdo->prepare($update_student_query);
            $statement->bindParam(":student_id", $student_id, PDO::PARAM_INT);
            $statement->bindParam(":name", $name, PDO::PARAM_STR);
            $statement->bindParam(":date_of_birth", $date_of_birth, PDO::PARAM_STR);
            $statement->bindParam(":gender", $gender, PDO::PARAM_STR);
            $statement->bindParam(":parent_id", $parent_id, PDO::PARAM_INT);
            $statement->bindParam(":class_id", $class_id, PDO::PARAM_INT);
            $statement->bindParam(":enrollment_date", $enrollment_date, PDO::PARAM_STR);
            $statement->execute();

            header("Location: students.php");
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
                    <h2 class="mb-0">Update Student</h2>
                </div>

                <?php if(isset($error) && count($error) > 0) : ?>
                    <div class="alert alert-danger">
                        <?php foreach($error as $e) : ?>
                            <li><?php echo $e; ?></li>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <form action="students-edit.php?id=<?= $student['student_id']; ?>" method="POST">
                         <input type="hidden" name="student-id" value="<?= $student['student_id']; ?>">
                        <div class="mb-3">
                            <label class="form-label">Student Name</label>
                            <input type="text" class="form-control" name="name" value="<?= $student['student_name']; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Date of Birth</label>
                            <input type="date" class="form-control" name="date-of-birth" value="<?= $student['date_of_birth']; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Gender</label>

                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="gender" value="male" <?php if($student['gender'] == 'male' || $student['gender'] == 'Male') : ?> checked <?php endif; ?>>
                                <label class="form-check-label">Male</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="gender" value="female" <?php if($student['gender'] == 'female' || $student['gender'] == 'Female') : ?> checked <?php endif; ?>>
                                <label class="form-check-label">Female</label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Class</label>
                            <select class="form-select" name="class-id" required>
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
                                        <option 
                                        <?php if ($student['class_id'] == $class['class_id']) : ?> selected <?php endif; ?>
                                        value="<?= $class['class_id']; ?>"><?= $class['class_name']; ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>

                            </select>

                        </div>
                        <div class="mb-3">
                            <label class="form-label">Parent Name</label>
                            <select class="form-select" name="parent-id" required>
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
                                        <option 
                                        <?php if($student['parent_id'] == $parent['parent_id']) : ?> selected <?php endif; ?>
                                        value="<?= $parent['parent_id']; ?>"><?= $parent['name']; ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>

                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Enrollment Date</label>
                            <input type="date" class="form-control" name="enrollment-date" value="<?= $student['enrollment_date']; ?>" required>
                        </div>
                        <div class="mb-3">
                            <a href="students.php" class="btn btn-secondary">Cancel</a>
                            <button type="submit" name="update_student" class="btn btn-primary">Update Student</button>
                        </div>
                    </form>

            </div>
        </div>
    </div>

<?php include 'layouts/footer.php'; ?>