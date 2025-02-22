 <?php 
    include '../database/db.php';

    $get_student_list_query = "SELECT *, 
    Students.name AS student_name, Parents.name AS parent_name, Classes.class_name AS class_name 
    FROM Students 
    LEFT JOIN Payments ON Students.student_id = Payments.student_id
    LEFT JOIN Parents ON Students.parent_id = Parents.parent_id 
    LEFT JOIN Classes ON Students.class_id = Classes.class_id 
    -- WHERE Payments.payment_status = 'paid'
    ORDER BY Students.student_id 
    DESC LIMIT 5";
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
             <div class="card-header p-4 bg-danger text-white  rounded-top">
                 <h5 class="card-title mb-0"><i class="bi bi-card-checklist me-2"></i>Recent Enrollments</h5>
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
                                 <th>Payment Status</th>
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

                                    <td>
                                    <?php if($student['payment_status'] == 'paid') : ?>
                                       <span class="badge bg-success">Paid</span>
                                    <?php elseif($student['payment_status'] == 'checking') : ?>
                                       <span class="badge bg-warning">Checking</span>
                                    <?php elseif($student['payment_status'] == 'decline') : ?>
                                        <span class="badge bg-danger">Decline</span>
                                    <?php else : ?>
                                        <span class="badge bg-danger">Unpaid</span>
                                    <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>

                        <?php endif; ?>

                         </tbody>
                     </table>
                 </div>
             </div>
         </div>
</div>