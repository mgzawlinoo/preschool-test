<?php session_start(); ?>
<!-- Header Start -->
<?php include("./layouts/header.php"); ?>

    <!-- Show Welcome User Message -->
    <?php  if(isset($_GET['success']) && $_GET['success'] == "true") : ?>
        <div class="container my-3">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                Login Successful
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    <?php endif; ?>
    <!-- End Show Welcome User Message -->

    <div class="container-xxl bg-white p-0">

        <!-- Spinner Start -->
        <?php include "./components/spinner.php"; ?>
        <!-- Spinner End -->

        <!-- Navbar Start -->
        <?php include "./components/navbar.php"; ?>
        <!-- Navbar End -->

        <!-- Carousel Start -->
        <?php include "./components/carousel.php"; ?>
        <!-- Carousel End -->

        <!-- About Start -->
        <?php include "./components/aboutus.php"; ?>
        <!-- About End -->

        <!-- Facilities Start -->
        <?php include "./components/facilities.php"; ?>
        <!-- Facilities End -->

        <!-- About Start -->
        <?php include "./components/about.php"; ?>
        <!-- About End -->

        <!-- Call To Action Start -->
        <?php include "./components/call-to-action.php"; ?>
        <!-- Call To Action End -->

        <!-- Classes Start -->
        <?php include "./components/classes.php"; ?>
        <!-- Classes End -->

        <!-- Appointment Start -->
        <?php include "./components/appointment.php"; ?>
        <!-- Appointment End -->

        <!-- Team Start -->
        <?php include "./components/team.php"; ?>
        <!-- Team End -->

        <!-- Testimonial Start -->
        <?php include "./components/testimonial.php"; ?>
        <!-- Testimonial End -->

        <!-- Contact Form Start -->
        <?php include "./components/contact-form.php"; ?>
        <!-- Contact Form End -->

        <!-- Contact Start -->
        <?php include "./components/footer.php"; ?>
        <!-- Contact End -->

    </div>

<!-- Footer Start -->
<?php include "./layouts/footer.php"?>