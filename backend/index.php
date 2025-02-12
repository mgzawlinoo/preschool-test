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
            <!-- Main Cards -->
            <?php include './components/dashboard/main-cards.php'; ?>

            <div class="row">
                <!-- Recent Enrollments -->
                <?php include './components/dashboard/recent-enrollments.php'; ?>
                <!-- Upcoming Events -->
                <?php include './components/dashboard/upcoming-events.php'; ?>
            </div>
        </div>
    </div>
</div>

<?php include './layouts/footer.php'; ?>