<div class="row">

    <?php 

    $get_staff_list_query = "SELECT * FROM Staff LEFT JOIN Users ON Staff.user_id = Users.user_id WHERE Users.status = 1";
    $statement = $pdo->prepare($get_staff_list_query);
    $statement->execute();
    $staffs = [];

    // fetch teacher with while loop
    while($staff = $statement->fetch(PDO::FETCH_ASSOC)) {
        $staffs[] = $staff;
    }

    ?>

    <?php if(count($staffs) > 0) : ?>

        <!-- Show Teacher List with foreach loop -->
        <?php foreach($staffs as $staff) : ?>
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="text-center mb-3">
                            <img src="<?= $staff['photo'] ? 'uploads/' . $staff['photo'] : 'https://placehold.co/150' ?>" class="rounded-circle" style="max-width: 150px; height: auto" alt="Teacher">
                            <h5 class="mt-3 mb-1"><?= $staff['name'] ?></h5>
                            <p class="text-muted"><?= $staff['staff_role'] ?></p>
                        </div>
                        <div class="border-top pt-3">
                            <div class="row text-center">
                                <div class="col">
                                    <b>Hire Date</b>
                                    <p><?= $staff['hire_date'] ?></p>
                                </div>
                                <div class="col">
                                    <b>Salary</b>
                                    <p><?= $staff['salary'] ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="border-top pt-3">
                            <div class="row text-center">
                                <div class="col">
                                    <b><i class="bi bi-envelope me-2"></i>Email</b>
                                    <p><?= $staff['email'] ?></p>
                                </div>
                                <div class="col">
                                    <b><i class="bi bi-telephone me-2"></i>Phone</b>
                                    <p><?= $staff['phone'] ?></p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-3 d-flex justify-content-between">

                            <a class="btn btn-primary text-white text-decoration-none" href="staff-edit.php?id=<?= $staff['staff_id'] ?>"><i class="bi bi-pencil text-white"></i>  Edit</a>

                            <a  href="user-suspend.php?id=<?= $staff['user_id'] ?>&from=staff.php" class="btn btn-outline-danger"><i class="bi bi-trash"></i> Suspend</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

    <?php endif; ?>

</div>