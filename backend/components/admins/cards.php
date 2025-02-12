<div class="row">

    <?php 

    $get_admin_list_query = "SELECT * FROM Admins LEFT JOIN Users ON Admins.user_id = Users.user_id";
    $statement = $pdo->prepare($get_admin_list_query);
    $statement->execute();
    $admins = [];

    // fetch teacher with while loop
    while($admin = $statement->fetch(PDO::FETCH_ASSOC)) {
        $admins[] = $admin;
    }

    ?>

    <?php if(count($admins) > 0) : ?>

        <!-- Show Admin List with foreach loop -->
        <?php foreach($admins as $admin) : ?>
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="text-center mb-3">
                            <img src="<?= $admin['photo'] ? 'uploads/' . $admin['photo'] : 'https://placehold.co/150' ?>" class="rounded-circle" style="max-width: 150px; height: auto" alt="Teacher">
                            <h5 class="mt-3 mb-1"><?= $admin['name'] ?></h5>
                        </div>
                        <div class="border-top pt-3">
                            <div class="row text-center">
                                <div class="col">
                                    <b><i class="bi bi-envelope me-2"></i>Email</b>
                                    <p><?= $admin['email'] ?></p>
                                </div>
                                <div class="col">
                                    <b><i class="bi bi-telephone me-2"></i>Phone</b>
                                    <p><?= $admin['phone'] ?></p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-3 d-flex justify-content-between">

                            <a class="btn btn-primary text-white text-decoration-none" href="admins-edit.php?id=<?= $admin['admin_id'] ?>"><i class="bi bi-pencil text-white"></i>  Edit</a>

                            <button class="btn btn-outline-danger"><i class="bi bi-trash"></i> Suspend</button>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

    <?php endif; ?>

</div>