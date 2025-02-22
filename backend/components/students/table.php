

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead class="table-secondary">
                    <tr>
                        <th>#</th>
                        <th>Photo</th>
                        <th>Info</th>
                        <th>Class Name</th>
                        <th>Parent Name</th>
                        <th>Enrollment Date</th>
                        <th>Account Status</th>
                        <th>Action</th>
                        <th colspan="2">Payment Status</th>
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
                                        <?php elseif($student['student_status'] == 'pending') : ?>
                                            <span class="text-warning">Pending</span>
                                        <?php else : ?>
                                            <span class="text-danger">Suspend</span>
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
                                                <li><a class="dropdown-item text-warning" href="student-change-status.php?id=<?= $student['student_id'] ?>&status=pending&from=students.php">Pending</a></li>
                                                <li><a onclick="return confirm('Are you sure you want to suspend this student?')" class="dropdown-item text-danger" href="student-change-status.php?id=<?= $student['student_id'] ?>&status=suspend&from=students.php">Suspend</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                    <td>
                                        <?php if($student['payment_status'] == 'paid') : ?>
                                            <span class="text-success">Paid</span>
                                        <?php elseif($student['payment_status'] == 'unpaid') : ?>
                                            <span class="text-danger">Unpaid</span>
                                        <?php elseif($student['payment_status'] == 'decline') : ?>
                                            <span class="text-danger">Decline</span>
                                        <?php else : ?>
                                            <span class="text-warning">Checking</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                         <?php if( $student['payment_status'] == 'checking' ) : ?>
                                            <!-- Check payment modal -->
                                            <div class="d-inline">
                                                <button type="button" class="btn btn-warning d-block" data-bs-toggle="modal" data-bs-target="#checkPaymentModal<?= $student['payment_id'] ?>">
                                                <i class="bi bi-cash"></i> Check Online Payment
                                                </button>
                                                <div class="modal fade" id="checkPaymentModal<?= $student['payment_id'] ?>" tabindex="-1" aria-labelledby="checkPaymentModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h1 class="modal-title fs-5" id="checkPaymentModalLabel">Payment Detail</h1>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="row">
                                                                    <div class="col-md-12 text-start align-self-center">
                                                                        <form action="students.php" method="post" >
                                                                            <input type="hidden" name="payment_id" value="<?= $student['payment_id'] ?>">
                                                                            <input type="hidden" name="student_id" value="<?= $student['student_id'] ?>">
                                                                            <input type="hidden" name="class_id" value="<?= $student['class_id'] ?>">
                                                                            <input type="hidden" name="student_name" value="<?= $student['student_name'] ?>">

                                                                            <ul class="list-group">
                                                                                <li class="list-group-item text-muted">Name : <?= $student['student_name'] ?></li>
                                                                                <li class="list-group-item text-muted">Class : <?= $student['class_name'] ?></li>
                                                                                <li class="list-group-item text-muted">Amount : <?= $student['fees'] ?></li>
                                                                                <li class="list-group-item text-muted">Payment Method : <?= $student['payment_method'] ?></li>
                                                                            </ul>

                                                                            <div class="my-4">
                                                                                <lable for="photo">Payment Slip or Screenshot</lable>
                                                                                <img class="img-fluid" src="<?= './uploads/'.$student['payment_photo'] ?>" alt="slip">
                                                                            </div>

                                                                            <div class="my-4">
                                                                                <lable for="description">Description</lable>
                                                                                <p><?= $student['description'] ?></p>
                                                                            </div>

                                                                            <div class="my-4">
                                                                                <lable for="payment_status">Change Payment Status</lable>
                                                                                <select name="payment_status" class="form-select">
                                                                                    <option value="checking" selected>Checking</option>
                                                                                    <option value="paid">Paid</option>
                                                                                    <option value="unpaid">Unpaid</option>
                                                                                    <option value="decline">Decline</option>
                                                                                </select>
                                                                            </div>

                                                                            <div class="modal-footer">
                                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                                <button type="submit" name="update_online_payment" class="my-4 btn btn-primary">Submit</button>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Check payment modal -->
                                        <?php endif; ?>

                                        <?php if(  $student['payment_status'] == 'unpaid' || $student['payment_status'] == 'decline' ) : ?>
                                             <!-- Manually payment modal -->
                                             <div class="d-inline">
                                                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#manuallyPaymentModal<?= $student['payment_id'] ?>">
                                                <i class="bi bi-cash"></i> Accept Payment Manually
                                                </button>
                                                <div class="modal fade" id="manuallyPaymentModal<?= $student['payment_id'] ?>" tabindex="-1" aria-labelledby="manuallyPaymentModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h1 class="modal-title fs-5" id="manuallyPaymentModalLabel">Payment Detail</h1>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="row">
                                                                    <div class="col-md-12 text-start align-self-center">
                                                                        <form action="students.php" method="post" >
                                                                            <input type="hidden" name="payment_id" value="<?= $student['payment_id'] ?>">
                                                                            <input type="hidden" name="student_id" value="<?= $student['student_id'] ?>">
                                                                            <input type="hidden" name="class_id" value="<?= $student['class_id'] ?>">
                                                                            <input type="hidden" name="student_name" value="<?= $student['student_name'] ?>">
                                                                            <input type="hidden" name="fees" value="<?= $student['fees'] ?>">

                                                                            <ul class="list-group">
                                                                                <li class="list-group-item text-muted">Name : <?= $student['student_name'] ?></li>
                                                                                <li class="list-group-item text-muted">Class : <?= $student['class_name'] ?></li>
                                                                                <li class="list-group-item text-muted">Amount : <?= $student['fees'] ?></li>
                                                                            </ul>

                                                                            <div class="my-4">
                                                                                <lable for="slip">Payment Method</lable>
                                                                                <select name="payment_method" id="payment_method" class="form-select" required>
                                                                                    <option value="cash" <?= $student['payment_method'] == 'cash' ? 'selected' : '' ?>>Cash</option>
                                                                                    <option value="kpay" <?= $student['payment_method'] == 'kpay' ? 'selected' : '' ?>>KPay</option>
                                                                                    <option value="bank transfer" <?= $student['payment_method'] == 'bank transfer' ? 'selected' : '' ?>>Bank Transfer</option>
                                                                                </select>
                                                                            </div>

                                                                            <div class="my-4">
                                                                                <lable for="photo">Payment Slip or Screenshot</lable>
                                                                                <div id="slip-preview"></div>
                                                                                <input type="file" accept="image/png, image/jpeg, image/jpg" name="photo" class="form-control" id="slip"  >
                                                                            </div>

                                                                            <div class="my-4">
                                                                                <lable for="description">Description</lable>
                                                                                <textarea name="description" class="form-control" rows="3" required><?= $student['description'] ?></textarea>
                                                                            </div>

                                                                            <div class="my-4">
                                                                                <lable for="payment_status">Change Payment Status</lable>
                                                                                <select name="payment_status" class="form-select">
                                                                                    <option value="checking" <?= $student['payment_status'] == 'checking' ? 'selected' : '' ?>>Checking</option>
                                                                                    <option value="paid" <?= $student['payment_status'] == 'paid' ? 'selected' : '' ?>>Paid</option>
                                                                                    <option value="unpaid" <?= $student['payment_status'] == 'unpaid' ? 'selected' : '' ?>>Unpaid</option>
                                                                                    <option value="decline" <?= $student['payment_status'] == 'decline' ? 'selected' : '' ?>>Decline</option>
                                                                                </select>
                                                                            </div>

                                                                            <div class="modal-footer">
                                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                                <button type="submit" name="update_manually_payment" class="my-4 btn btn-primary">Submit</button>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Manually payment modal -->
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
                                if(isset($_GET['class_id']) && $_GET['class_id'] != '') {
                                    $get_student_count_query = "SELECT COUNT(student_id) AS total_records FROM Students WHERE class_id = {$_GET['class_id']}";
                                }
                                try {
                                    $statement = $pdo->prepare($get_student_count_query);
                                    $statement->execute();
                                    $result = $statement->fetch(PDO::FETCH_ASSOC);
    
                                } catch(PDOException $e) {
                                    $errors['dberror'] = $e->getMessage();
                                }
                               
                                $total_pages = ceil($result['total_records'] / $limit);
                                ?>

                                <?php if($total_pages > 1) : ?>
                                    <?php for($i = 1; $i <= $total_pages; $i++) : ?>
                                        <?php if($i == $page) : ?>
                                            <li class="page-item active"><a class="page-link" href="students.php?class_id=<?= $_GET['class_id'] ?>&page=<?= $i ?>"><?= $i ?></a></li>
                                        <?php else : ?>
                                            <li class="page-item"><a class="page-link" href="students.php?class_id=<?= $_GET['class_id'] ?>&page=<?= $i ?>"><?= $i ?></a></li>
                                        <?php endif; ?>
                                    <?php endfor; ?> 
                                <?php endif; ?>
                                
                               
                            </ul>
                            </nav>
                        </th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>