<?php 

    $get_student_list_query = "SELECT *, Students.name AS student_name, Parents.name AS parent_name, Classes.class_name AS class_name FROM Students LEFT JOIN Parents ON Students.parent_id = Parents.parent_id LEFT JOIN Classes ON Students.class_id = Classes.class_id ORDER BY Students.student_id DESC";
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
                            <!-- Show Student List with foreach loop -->
                            <?php foreach($students as $student) : ?>
                                <tr>
                                    <td>#</td>
                                    <td><?= $student['student_name'] ?></td>
                                    <td><?= $student['date_of_birth'] ?></td>
                                    <td><?= $student['gender'] ?></td>
                                    <td><?= $student['class_name'] ?></td>
                                    <td><?= $student['parent_name'] ?></td>
                                    <td><?= $student['enrollment_date'] ?></td>
                                    <td>
                                        <a href="students-edit.php?id=<?= $student['student_id'] ?>" class="btn btn-secondary btn-sm">Edit</a>
                                        <button class="btn btn-danger btn-sm">Delete</button>
                                    </td>
                                </tr>

                            <?php endforeach; ?>
                        <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <!-- <nav class="mt-3">
            <ul class="pagination justify-content-end">
                <li class="page-item disabled"><a class="page-link" href="#">Previous</a></li>
                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item"><a class="page-link" href="#">Next</a></li>
            </ul>
        </nav> -->
    </div>
</div>