<?php include '../database/db.php'; ?>
<?php include './layouts/header.php'; ?>

    <div class="wrapper">
        <!-- Sidebar -->
        <?php include './components/sidebar.php'; ?>

        <!-- Page Content -->
        <div id="content">
            <!-- Top Navigation -->
            <?php include './components/top-nav.php'; ?>

            <!-- Main Content -->
            <div class="container-fluid p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="mb-0">Admin Management</h2>
                    <a href="admins-create.php" class="btn btn-primary">
                        <i class="bi bi-plus-lg"></i> Add New Admin
                    </a>
                </div>

                <?php if(isset($_SESSION['success'])) :  ?>
                    <div class="alert alert-success">
                        <?php echo $_SESSION['success']; ?>
                        <?php unset($_SESSION['success']); ?>
                    </div>
                <?php endif; ?>

                <!-- Admin Cards -->
                <?php include './components/admins/cards.php'; ?>
            </div>
        </div>
    </div>

<?php include './layouts/footer.php'; ?>