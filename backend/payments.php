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
                    <h2 class="mb-0">Payment Management</h2>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#recordPaymentModal">
                        <i class="bi bi-plus-lg"></i> Record Payment
                    </button>
                </div>

                <!-- Payment Summary Cards -->
                <?php include 'components/payments/summary.php'; ?>

                <!-- Payment History -->
                <?php include 'components/payments/history.php'; ?>
            </div>
        </div>
    </div>

    <!-- Record Payment Modal -->
    <div class="modal fade" id="recordPaymentModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Record Payment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label class="form-label">Student</label>
                            <select class="form-select" required>
                                <option value="">Select Student</option>
                                <option>Emily Johnson</option>
                                <option>Michael Smith</option>
                                <option>Sophie Davis</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Payment Amount</label>
                            <input type="number" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Payment Date</label>
                            <input type="date" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Payment Method</label>
                            <select class="form-select" required>
                                <option value="">Select Payment Method</option>
                                <option>Credit Card</option>
                                <option>Bank Transfer</option>
                                <option>Cash</option>
                                <option>Check</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Notes</label>
                            <textarea class="form-control" rows="3"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Record Payment</button>
                </div>
            </div>
        </div>
    </div>

<?php include 'layouts/footer.php'; ?>