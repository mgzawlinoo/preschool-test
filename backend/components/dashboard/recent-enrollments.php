 <?php 
    include '../database/db.php';

    $get_student_list_query = "SELECT *, Students.name AS student_name, Parents.name AS parent_name, Classes.class_name AS class_name FROM Students LEFT JOIN Parents ON Students.parent_id = Parents.parent_id LEFT JOIN Classes ON Students.class_id = Classes.class_id ORDER BY Students.student_id DESC LIMIT 5";
    $statement = $pdo->prepare($get_student_list_query);
    $statement->execute();
    $students = [];

    // fetch teacher with while loop
    while($student = $statement->fetch(PDO::FETCH_ASSOC)) {
        $students[] = $student;
    }

    // calculate age base on date of birth
    function calculate_age($date_of_birth) {
        $date_of_birth = new DateTime($date_of_birth);
        $interval = $date_of_birth->diff(new DateTime(date('Y-m-d')));
        return $interval->y;
    }

?>

 
 <div class="col-md-8">
         <div class="card">
             <div class="card-header">
                 <h5 class="card-title mb-0">Recent Enrollments</h5>
             </div>
             <div class="card-body">
                 <div class="table-responsive">
                     <table class="table">
                         <thead>
                             <tr>
                                <th>#</th>
                                 <th>Student Name</th>
                                 <th>Age</th>
                                 <th>Class</th>
                                 <th>Parent</th>
                                 <th>Enrollment Date</th>
                             </tr>
                         </thead>
                         <tbody>
                         <?php if(isset($students) && count($students) > 0) : ?>
                            <?php $id = 1; ?>

                            <!-- Show Student List with foreach loop -->
                            <?php foreach($students as $student) : ?>
                                <tr>
                                    <td><?= $id++ ?></td>
                                    <td><?= $student['student_name'] ?></td>
                                    <td><?= calculate_age($student['date_of_birth']) ?></td>
                                    <td><?= $student['class_name'] ?></td>
                                    <td><?= $student['parent_name'] ?></td>
                                    <td><?= $student['enrollment_date'] ?></td>
                                </tr>
                            <?php endforeach; ?>

                        <?php endif; ?>

                         </tbody>
                     </table>
                 </div>
             </div>
         </div>
     </div>