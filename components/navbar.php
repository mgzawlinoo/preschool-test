<?php 
    $user = isset($_SESSION['user']['user_id']);
?>

<nav class="navbar navbar-expand-lg bg-white navbar-light sticky-top px-4 px-lg-5 py-lg-0">
    <a href="index.php" class="navbar-brand">
        <h1 class="m-0 text-primary"><i class="fa fa-book-reader me-3"></i>Kider</h1>
    </a>
    <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarCollapse">
        <div class="navbar-nav mx-auto">
            <a href="index.php" class="nav-item nav-link active">Home</a>
            <a href="index.php#aboutus" class="nav-item nav-link">About Us</a>
            <a href="index.php#classes" class="nav-item nav-link">Classes</a>
            <div class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Pages</a>
                <div class="dropdown-menu rounded-0 rounded-bottom border-0 shadow-sm m-0">
                    <a href="index.php#facilities" class="dropdown-item">School Facilities</a>
                    <a href="index.php#team" class="dropdown-item">Popular Teachers</a>
                    <a href="index.php#call-to-action" class="dropdown-item">Become A Teachers</a>
                    <a href="index.php#appointment" class="dropdown-item">Make Appointment</a>
                    <a href="index.php#testimonial" class="dropdown-item">Testimonial</a>
                </div>
            </div>
            <a href="index.php#contact-form" class="nav-item nav-link">Contact Us</a>
        </div>

        <?php if($user): ?>
            Welcome, <?= $_SESSION['user']['role'] ?>
        <a href="logout.php" class="btn btn-danger rounded-pill mx-2 px-3 d-none d-lg-block">Logout<i class="fa fa-sign-in-alt ms-3"></i></a>
        <?php else: ?>
        <a href="login.php" class="btn btn-primary rounded-pill mx-2 px-3 d-none d-lg-block">Login<i class="fa fa-sign-in-alt ms-3"></i></a>
        <?php endif; ?>

    </div>
</nav>