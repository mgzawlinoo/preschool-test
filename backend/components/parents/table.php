<div class="row">

    <?php 

    $get_parent_list_query = "SELECT * FROM Parents LEFT JOIN Users ON Parents.user_id = Users.user_id";
    $statement = $pdo->prepare($get_parent_list_query);
    $statement->execute();
    $parents = [];

    // fetch parent with while loop
    while($parent = $statement->fetch(PDO::FETCH_ASSOC)) {
        $parents[] = $parent;
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
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Address</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                                        
                        <?php if(isset($parents) && count($parents) > 0) : ?>
                            <?php $i = 1; ?>
                            <!-- Show Parent List with foreach loop -->
                            <?php foreach($parents as $parent) : ?>
                                <tr>
                                    <td><?= $i++ ?></td>
                                    <td><?= $parent['name'] ?></td>
                                    <td><?= $parent['email'] ?></td>
                                    <td><?= $parent['phone'] ?></td>
                                    <td><?= $parent['address'] ?></td>
                                    <td>
                                        <a href="parents-edit.php?id=<?= $parent['parent_id'] ?>" class="btn btn-secondary btn-sm">Edit</a>
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

</div>