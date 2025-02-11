<?php 
    include '../database/db.php'; 

    // ှGet Count from Classes, Teachers and Students Table
    $get_count_query = "SELECT
    (SELECT COUNT(*) FROM Students) as student_count, 
    (SELECT COUNT(*) FROM Teachers) as teacher_count, 
    (SELECT COUNT(*) FROM Parents) as parent_count, 
    (SELECT COUNT(*) FROM Classes) as class_count";
    $statement = $pdo->prepare($get_count_query);
    $statement->execute();
    $count = $statement->fetch(PDO::FETCH_ASSOC);
?>

  
  <div class="row mb-4">
      <div class="col-md-3">
          <div class="card bg-primary text-white">
              <div class="card-body">
                  <h5>Total Students</h5>
                  <h2><?= $count['student_count']; ?></h2>
                  
              </div>
          </div>
      </div>
      <div class="col-md-3">
          <div class="card bg-success text-white">
              <div class="card-body">
                  <h5>Total Teachers</h5>
                  <h2><?= $count['teacher_count']; ?></h2>
                  
              </div>
          </div>
      </div>
      <div class="col-md-3">
          <div class="card bg-warning text-white">
              <div class="card-body">
                  <h5>Total Classes</h5>
                  <h2><?= $count['class_count']; ?></h2>
                  
              </div>
          </div>
      </div>
      <div class="col-md-3">
          <div class="card bg-info text-white">
              <div class="card-body">
                  <h5>Total Parents</h5>
                  <h2><?= $count['parent_count']; ?></h2>
              </div>
          </div>
      </div>
  </div>