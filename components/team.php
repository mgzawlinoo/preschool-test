<?php 
include './database/db.php';

$get_teacher_list_query = "SELECT * FROM Teachers LEFT JOIN Users ON Teachers.user_id = Users.user_id";
$statement = $pdo->prepare($get_teacher_list_query);
$statement->execute();
$teachers = [];

// fetch teacher with while loop
while($teacher = $statement->fetch(PDO::FETCH_ASSOC)) {
    $teachers[] = $teacher;
}

?>

<div class="container-xxl py-5" id="team">
            <div class="container">
                <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
                    <h1 class="mb-3">Popular Teachers</h1>
                    <p>Eirmod sed ipsum dolor sit rebum labore magna erat. Tempor ut dolore lorem kasd vero ipsum sit
                        eirmod sit. Ipsum diam justo sed rebum vero dolor duo.</p>
                </div>
                <div class="row g-4">
                    
                <?php if(count($teachers) > 0) : ?>
                <!-- Show Teacher List with foreach loop -->
                <?php foreach($teachers as $teacher) : ?>

                    <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                        <div class="team-item position-relative text-center">
                            <img class="img-fluid rounded-circle w-75 text-center mx-auto" src="<?= $teacher['photo'] ? './backend/uploads/' . $teacher['photo'] : 'https://placehold.co/150' ?>" alt="">
                            <div class="team-text">
                                <h3><?= $teacher['name']; ?></h3>
                                <p><?= $teacher['position']; ?></p>
                            </div>
                        </div>
                    </div>

                <?php endforeach; ?>
                <?php endif; ?>
                </div>
            </div>
        </div>