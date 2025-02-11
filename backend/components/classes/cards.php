<div class="row">

<?php 

    $get_class_list_query = "SELECT * , Teachers.name as teacher_name FROM Classes LEFT JOIN Teachers ON Classes.teacher_id = Teachers.teacher_id";
    $statement = $pdo->prepare($get_class_list_query);
    $statement->execute();
    $classes = [];

    // fetch teacher with while loop
    while($class = $statement->fetch(PDO::FETCH_ASSOC)) {
        $classes[] = $class;
    }

?>

<?php if(count($classes) > 0) : ?>
    <?php foreach($classes as $class) : ?>  
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="card-title mb-0"><?= $class['class_name'] ?></h4>
                    <span class="badge bg-success">Active</span>
                </div>
                <div class="mb-3">
                    <p class="text-muted mb-2">Lead Teacher: <?= $class['teacher_name'] ?></p>
                    <p class="text-muted mb-2">Age Group: <?= $class['age'] ?></p>
                    <p class="text-muted mb-0">Schedule: <?= $class['schedule'] ?></p>
                </div>
                <div class="progress mb-3" style="height: 10px;">
                    <div class="progress-bar bg-primary" role="progressbar" style="width: 75%;"
                        aria-valuenow="15" aria-valuemin="0" aria-valuemax="20">15/20</div>
                </div>
                <div class="d-flex justify-content-between text-muted small mb-3">
                    <span>15 Students</span>
                    <span>5 spots left</span>
                </div>
                <div class="d-flex justify-content-between">
                    <a href="classes-edit.php?id=<?= $class['class_id'] ?>" class="btn btn-outline-primary"><i class="bi bi-pencil"></i> Edit</a>
                    <button class="btn btn-outline-info"><i class="bi bi-eye"></i> View Details</button>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
<?php endif; ?>