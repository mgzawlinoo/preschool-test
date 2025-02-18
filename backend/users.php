<?php include '../database/db.php'; ?>
<?php include 'layouts/header.php'; ?>

    <div class="wrapper">
        <!-- Sidebar -->
        <?php include 'components/sidebar.php'; ?>

        <!-- Page Content -->
        <div id="content">
            <!-- Top Navigation -->
            <?php include 'components/top-nav.php'; ?>

            <!-- Main Content -->
            <div class="container-fluid p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="mb-0">User Management</h2>
                </div>

                <?php if(isset($_SESSION['success'])) :  ?>
                    <div class="alert alert-success">
                        <?php echo $_SESSION['success']; ?>
                        <?php unset($_SESSION['success']); ?>
                    </div>
                <?php endif; ?>

                <?php if(isset($_SESSION['error'])) :  ?>
                    <div class="alert alert-danger">
                        <?php echo $_SESSION['error']; ?>
                        <?php unset($_SESSION['error']); ?>
                    </div>
                <?php endif; ?>

                <div class="row">

                    <?php 

                    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                    $limit = 5;
                    $offset = ($page-1) * $limit;
                    $get_user_list_query = "
                    SELECT a.admin_id AS id, a.name, a.user_id, u.email, u.role, u.status FROM Admins a 
                    LEFT JOIN Users u ON a.user_id = u.user_id 
                    UNION
                    SELECT t.teacher_id AS id, t.name, t.user_id, u.email, u.role, u.status FROM Teachers t 
                    LEFT JOIN Users u ON t.user_id = u.user_id 
                    UNION  
                    SELECT s.staff_id AS id, s.name, s.user_id, u.email, u.role, u.status FROM Staff s 
                    LEFT JOIN Users u ON s.user_id = u.user_id
                    UNION
                    SELECT p.parent_id AS id, p.name, p.user_id, u.email, u.role, u.status FROM Parents p 
                    LEFT JOIN Users u ON p.user_id = u.user_id
                    
                    LIMIT $limit OFFSET $offset
                    ";

                    $statement = $pdo->prepare($get_user_list_query);
                    $statement->execute();
                    $users = [];

                    // fetch user with while loop
                    while($user = $statement->fetch(PDO::FETCH_ASSOC)) {
                        $users[] = $user;
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
                                        <th>Role</th>
                                        <th>Status</th>
                                        <th class="text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                                        
                                        <?php if(isset($users) && count($users) > 0) : ?>
                                            <?php $i = ($page - 1) * $limit + 1; ?>
                                            <!-- Show User List with foreach loop -->
                                            <?php foreach($users as $user) : ?>
                                                <tr>
                                                    <td><?= $i++ ?></td>
                                                    <td><?= $user['name'] ?></td>
                                                    <td><?= $user['email'] ?></td>
                                                    <td><?= $user['role'] ?></td>
                                                    <td>
                                                            <?php if($user['status'] == 1) : ?>
                                                                <span class="text-success">Active</span>
                                                            <?php else : ?>
                                                                <span class="text-danger">Suspend</span>
                                                            <?php endif; ?>
                                                    </td>
                                                    <td class="text-end">
                                                        <a href="reset-password.php?id=<?= $user['user_id'] ?>&name=<?= $user['name'] ?>" class="text-white btn btn-warning btn-sm">Reset Password</a>

                                                        <?php 
                                                            $role = [
                                                            "Admin" => "admins-edit.php", 
                                                            "Teacher" => "teachers-edit.php", 
                                                            "Staff" => "staff-edit.php", 
                                                            "Parent" => "parents-edit.php"
                                                            ]; 
                                                        ?>
                                                        
                                                        <?php if(array_key_exists($user['role'], $role)) : ?>
                                                            <a href="<?= $role[$user['role']] ?>?id=<?= $user['id'] ?>" class="btn btn-secondary btn-sm">Edit</a>
                                                        <?php endif; ?>

                                                        <?php if($user['status'] == 1) : ?>
                                                            <a href="user-suspend.php?id=<?= $user['user_id'] ?>&from=users.php&page=<?= $page ?>" class="btn btn-danger btn-sm">Suspend</a>
                                                        <?php else : ?>
                                                            <a href="user-unsuspend.php?id=<?= $user['user_id'] ?>&from=users.php&page=<?= $page ?>" class="btn btn-success btn-sm">Activate</a>
                                                        <?php endif; ?>

                                                    </td>
                                                    
                                                </tr>

                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="7">
                                            <!-- pagination -->

                                            <nav class="mt-3">
                                            <ul class="pagination justify-content-center">
                                             <?php 
                                                $get_user_count_query = "SELECT COUNT(user_id) AS total_records FROM Users";
                                                $statement = $pdo->prepare($get_user_count_query);
                                                $statement->execute();
                                                $result = $statement->fetch(PDO::FETCH_ASSOC);

                                                $total_pages = ceil($result['total_records'] / $limit);
                                                for($i = 1; $i <= $total_pages; $i++) : ?>

                                                <?php if($i == $page) : ?>
                                                    <li class="page-item active"><a class="page-link" href="users.php?page=<?= $i ?>"><?= $i ?></a></li>
                                                <?php else : ?>
                                                   <li class="page-item"><a class="page-link" href="users.php?page=<?= $i ?>"><?= $i ?></a></li>
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

                </div>
            
            </div>
        </div>
    </div>

<?php include 'layouts/footer.php'; ?>