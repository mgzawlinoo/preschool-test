<div class="row">

    <?php 

    $get_teacher_list_query = "SELECT * FROM Teachers LEFT JOIN Users ON Teachers.user_id = Users.user_id";
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
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="text-center mb-3">
                            <img src="<?= $teacher['photo'] ? 'uploads/' . $teacher['photo'] : 'https://placehold.co/150' ?>" class="rounded-circle" style="max-width: 150px; height: auto" alt="Teacher">
                            <h5 class="mt-3 mb-1"><?= $teacher['name'] ?></h5>
                            <p class="text-muted"><?= $teacher['position'] ?></p>
                        </div>
                        <div class="border-top pt-3">
                            <div class="row text-center">
                                <div class="col">
                                    <h6>Experience</h6>
                                    <p><?= $teacher['experience'] ?></p>
                                </div>
                                <div class="col">
                                    <h6>Qualifications</h6>
                                    <p><?= $teacher['qualification'] ?></p>
                                </div>
                                <div class="col">
                                    <h6>Salary</h6>
                                    <p><?= $teacher['salary'] ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="mt-3">
                            <p><i class="bi bi-envelope me-2"></i><?= $teacher['email'] ?></p>
                            <p><i class="bi bi-telephone me-2"></i><?= $teacher['phone'] ?></p>
                        </div>
                        <div class="mt-3 d-flex justify-content-between">

                            <a class="btn btn-primary text-white text-decoration-none" href="teachers-edit.php?id=<?= $teacher['teacher_id'] ?>"><i class="bi bi-pencil text-white"></i>  Edit</a>

                            <button class="btn btn-outline-danger"><i class="bi bi-trash"></i> Remove</button>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

    <?php endif; ?>

</div>