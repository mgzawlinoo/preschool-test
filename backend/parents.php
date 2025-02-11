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
                    <h2 class="mb-0">Parent Management</h2>
                    <a href="parents-create.php" class="btn btn-primary">
                        <i class="bi bi-plus-lg"></i> Add New Parent
                    </a>
                </div>

                <!-- Parent Cards -->
                <?php include 'components/parents/table.php'; ?>
            </div>
        </div>
    </div>

<?php include 'layouts/footer.php'; ?>