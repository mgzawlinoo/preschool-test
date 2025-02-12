<?php 

    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $limit = 5;
    $offset = ($page-1) * $limit;
    $get_student_list_query = "SELECT *, Students.name AS student_name, Parents.name AS parent_name, Classes.class_name AS class_name FROM Students LEFT JOIN Parents ON Students.parent_id = Parents.parent_id LEFT JOIN Classes ON Students.class_id = Classes.class_id ORDER BY Students.student_id DESC LIMIT $limit OFFSET $offset";
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
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Date of Birth</th>
                        <th>Gender</th>
                        <th>Class Name</th>
                        <th>Parent Name</th>
                        <th>Enrollment Date</th>
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
                                    <td><?= $student['student_name'] ?></td>
                                    <td><?= $student['date_of_birth'] ?></td>
                                    <td><?= $student['gender'] ?></td>
                                    <td><?= $student['class_name'] ?></td>
                                    <td><?= $student['parent_name'] ?></td>
                                    <td><?= $student['enrollment_date'] ?></td>
                                    <td>
                                        <a href="students-edit.php?id=<?= $student['student_id'] ?>" class="btn btn-primary btn-sm">Edit</a>
                                        <button class="btn btn-danger btn-sm">Suspend</button>
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