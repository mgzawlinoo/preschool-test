<?php include '../database/db.php'; ?>
<?php include 'layouts/header.php'; ?>

<?php

    $get_parent_list_query = "SELECT *, Payments.photo AS payment_photo, Students.name AS student_name, Parents.name AS parent_name, Classes.class_name AS class_name
    FROM Payments 
    LEFT JOIN Students ON Payments.student_id = Students.student_id 
    LEFT JOIN Parents ON Students.parent_id = Parents.parent_id 
    LEFT JOIN Classes ON Payments.class_id = Classes.class_id

    ORDER BY payment_id DESC
    ";
    $statement = $pdo->prepare($get_parent_list_query);
    $statement->execute();
    $payments = [];

    // fetch parent with while loop
    while ($payment = $statement->fetch(PDO::FETCH_ASSOC)) {
        $payments[] = $payment;
    }

?>

<div class="wrapper">
    <!-- Sidebar -->
    <?php include 'components/sidebar.php'; ?>

    <!-- Page Content -->
    <div id="content">
        <!-- Top Navigation -->
        <?php include 'components/top-nav.php'; ?>

        <!-- Main Content -->
        <div class="container-fluid p-4">
            <div class="bg-warning text-black p-4 rounded  d-flex justify-content-between align-items-left mb-4">
                <h2 class="mb-0"><i class="bi bi-cash-stack me-2"></i> Payment Management</h2>
            </div>

            <?php if(isset($errors) && count($errors) > 0) : ?>
                <div class="alert alert-danger">
                    <?php foreach($errors as $error) : ?>
                        <li><?php echo $error; ?></li>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php if(isset($_SESSION['success'])) :  ?>
                <div class="alert alert-success">
                    <?php echo $_SESSION['success']; ?>
                    <?php unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>

            <div class="row">

                <div class="card">
                    <div class="card-body">

                        <div class="table-responsive" style="min-height: 320px;">
                            <table class="table" id="parentsTable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Student Name</th>
                                        <th>Parent Name</th>
                                        <th>Class Name</th>
                                        <th>Payment Date</th>
                                        <th>Amount</th>
                                        <th>Payment Method</th>
                                        <th>Payment Status</th>
                                        <th>Photo</th>
                                        <th>Description</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <?php if (isset($payments) && count($payments) > 0) : ?>
                                        <?php $i = 1; ?>
                                        <!-- Show Parent List with foreach loop -->
                                        <?php foreach ($payments as $payment) : ?>
                                            <tr 
                                            <?php if($payment['payment_status'] == 'unpaid') : ?>
                                                class="table-danger"
                                            <?php endif; ?>
                                            <?php if($payment['payment_status'] == 'decline') : ?>
                                                class="table-danger"
                                            <?php endif; ?>
                                            <?php if($payment['payment_status'] == 'paid') : ?>
                                                class="table-success"
                                            <?php endif; ?>
                                            <?php if($payment['payment_status'] == 'checking') : ?>
                                                class="table-warning"
                                            <?php endif; ?>
                                            >
                                                <td><?= $i++ ?></td>
                                                <td><?= $payment['student_name'] ?></td>
                                                <td><?= $payment['parent_name'] ?></td>
                                                <td><?= $payment['class_name'] ?></td>
                                                <td><?= $payment['payment_date'] ?></td>
                                                <td><?= $payment['amount'] ?></td>
                                                <td><?= $payment['payment_method'] ?></td>
                                                <td><?= $payment['payment_status'] ?></td>
                                                <td>
                                                    <?php if($payment['payment_photo']) : ?> 
                                                        <a href="<?= './uploads/'.$payment['payment_photo'] ?>" data-lightbox="image-1" data-title="My caption">
                                                            <img src="<?= './uploads/'.$payment['payment_photo'] ?>" alt="" width="50px"> 
                                                        </a>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?= $payment['description'] ?></td>
                                                <td>

                                                    <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="bi bi-gear"></i>Change Payment Status
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li><a class="dropdown-item text-success" href="payment-change-status.php?id=<?= $payment['payment_id'] ?>&status=paid&from=payments.php">Paid</a></li>
                                                        <li><a class="dropdown-item text-warning" href="payment-change-status.php?id=<?= $payment['payment_id'] ?>&status=checking&from=payments.php">Checking</a></li>
                                                        <li><a onclick="return confirm('Are you sure you want to unpaid this payment?')" class="dropdown-item text-danger" href="payment-change-status.php?id=<?= $payment['payment_id'] ?>&status=unpaid&from=payments.php">Unpaid</a></li>
                                                        <li><a onclick="return confirm('Are you sure you want to decline this payment?')" class="dropdown-item text-danger" href="payment-change-status.php?id=<?= $payment['payment_id'] ?>&status=decline&from=payments.php">Decline</a></li>
                                                    </ul>

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

<!-- light box cdn -->

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox-plus-jquery.min.js"></script>

<?php include 'layouts/footer.php'; ?>