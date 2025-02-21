<div class="row">

    <?php 

    $get_parent_list_query = "SELECT * FROM Parents LEFT JOIN Users ON Parents.user_id = Users.user_id WHERE Users.status = 'active'";
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

        <div class="table-responsive" style="min-height: 320px;">
            <table class="table" id="parentsTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Photo</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Address</th>
                        <th>Status</th>
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
                                    <td><img src="<?= $parent['photo'] ? './uploads/' . $parent['photo'] : 'https://placehold.co/32' ?>" alt="" width="50px"></td>
                                    <td><?= $parent['name'] ?></td>
                                    <td><?= $parent['email'] ?></td>
                                    <td><?= $parent['phone'] ?></td>
                                    <td><?= $parent['address'] ?></td>
                                    <td>
                                        <?php if($parent['status'] == 'active') : ?>
                                            <span class="text-success">Active</span>
                                        <?php elseif($parent['status'] == 'suspend') : ?>
                                            <span class="text-danger">Suspend</span>
                                        <?php else : ?>
                                            <span class="text-warning">Pending</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="parents-edit.php?id=<?= $parent['parent_id'] ?>" class="btn btn-secondary">Edit</a>
                                        
                                        <div class="dropdown d-inline-block">
                                            <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                Change Status
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item text-success" href="user-change-status.php?id=<?= $parent['user_id'] ?>&status=active&from=parents.php">Active</a></li>
                                                <li><a class="dropdown-item text-danger" href="user-change-status.php?id=<?= $parent['user_id'] ?>&status=suspend&from=parents.php">Suspend</a></li>
                                                <li><a class="dropdown-item text-warning" href="user-change-status.php?id=<?= $parent['user_id'] ?>&status=pending&from=parents.php">Pending</a></li>
                                            </ul>
                                        </div>

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

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function() {
        $('#parentsTable').DataTable();
    });
</script>