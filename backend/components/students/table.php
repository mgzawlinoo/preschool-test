<?php 

    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $limit = 5;
    $offset = ($page-1) * $limit;
    $get_student_list_query = "SELECT *, Students.photo AS student_photo, Students.status AS student_status, Students.name AS student_name, Parents.name AS parent_name, Classes.class_name AS class_name FROM Students LEFT JOIN Payments ON Students.student_id = Payments.student_id LEFT JOIN Parents ON Students.parent_id = Parents.parent_id LEFT JOIN Classes ON Students.class_id = Classes.class_id ORDER BY Students.student_id DESC LIMIT $limit OFFSET $offset";
    $statement = $pdo->prepare($get_student_list_query);
    $statement->execute();
    $students = [];

    // fetch teacher with while loop
    while($student = $statement->fetch(PDO::FETCH_ASSOC)) {
        $students[] = $student;
    }

?>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead class="table-danger">
                    <tr>
                        <th>#</th>
                        <th>Photo</th>
                        <th>Info</th>
                        <th>Class Name</th>
                        <th>Parent Name</th>
                        <th>Enrollment Date</th>
                        <th>Account Status</th>
                        <th>Payment Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                                        
                        <?php if(isset($students) && count($students) > 0) : ?>
                            <?php $i = ($page - 1) * $limit + 1; ?>
                            <!-- Show Student List with foreach loop -->
                            <?php foreach($students as $student) : ?>
                                <tr>
                                    <td><?= $i++ ?></td>
                                    <td><img src="<?= $student['student_photo'] ? './uploads/' . $student['student_photo'] : 'https://placehold.co/32' ?>" alt="" width="50px"></td>
                                    <td>
                                        Name : <?= $student['student_name'] ?><br>
                                        Date of Birth : <?= $student['date_of_birth'] ?><br>
                                        Gender : <?= $student['gender'] ?>
                                    </td>
                                    <td><?= $student['class_name'] ?></td>
                                    <td><?= $student['parent_name'] ?></td>
                                    <td><?= $student['enrollment_date'] ?></td>
                                    <td>
                                        <?php if($student['student_status'] == 'active') : ?>
                                            <span class="text-success">Active</span>
                                        <?php elseif($student['student_status'] == 'suspend') : ?>
                                            <span class="text-danger">Suspend</span>
                                        <?php else : ?>
                                            <span class="text-warning">Pending</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if($student['payment_status'] == 'paid') : ?>
                                            <span class="text-success">Paid</span>
                                        <?php elseif($student['payment_status'] == 'unpaid') : ?>
                                            <span class="text-danger">Unpaid</span>
                                        <?php else : ?>
                                            <span class="text-warning">Checking</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="students-edit.php?id=<?= $student['student_id'] ?>" class="btn btn-secondary"><i class="bi bi-pencil"></i> Edit</a>
                                        
                                        <div class="dropdown d-inline-block">
                                            <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="bi bi-gear"></i> Status
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item text-success" href="student-change-status.php?id=<?= $student['student_id'] ?>&status=active&from=students.php">Active</a></li>
                                                <li><a class="dropdown-item text-danger" href="student-change-status.php?id=<?= $student['student_id'] ?>&status=suspend&from=students.php">Suspend</a></li>
                                                <li><a class="dropdown-item text-warning" href="student-change-status.php?id=<?= $student['student_id'] ?>&status=pending&from=students.php">Pending</a></li>
                                            </ul>
                                        </div>

                                        <?php if($student['payment_status'] == 'unpaid' || $student['payment_status'] == 'checking') : ?>
                                            <a href="student-payment.php?id=<?= $student['student_id'] ?>&payment_id=<?= $student['payment_id'] ?>" class="btn btn-success"><i class="bi bi-cash"></i> Check Payment</a>
                                        <?php endif; ?>

                                    </td>
                                </tr>

                            <?php endforeach; ?>
                        <?php endif; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="8">
                            <!-- pagination -->

                            <nav class="mt-3">
                            <ul class="pagination justify-content-center">
                                <?php 
                                $get_student_count_query = "SELECT COUNT(student_id) AS total_records FROM Students";
                                $statement = $pdo->prepare($get_student_count_query);
                                $statement->execute();
                                $result = $statement->fetch(PDO::FETCH_ASSOC);

                                $total_pages = ceil($result['total_records'] / $limit);
                                for($i = 1; $i <= $total_pages; $i++) : ?>

                                    <?php if($i == $page) : ?>
                                        <li class="page-item active"><a class="page-link" href="students.php?page=<?= $i ?>"><?= $i ?></a></li>
                                    <?php else : ?>
                                        <li class="page-item"><a class="page-link" href="students.php?page=<?= $i ?>"><?= $i ?></a></li>
                                    <?php endif; ?>

                                <?php endfor; ?>
                            </ul>
                            </nav>
                        </th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>