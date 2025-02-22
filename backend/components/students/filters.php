<div class="card mb-4">
    <div class="card-body">
        <div class="row g-3">
            <!-- <div class="col-md-3">
                <input type="text" class="form-control" placeholder="Search students...">
            </div> -->
            <div class="col-md-2">

                <div class="dropdown d-inline-block">
                    <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-filter"></i> Select Class
                    </button>
                    <ul class="dropdown-menu">

                    <?php 
                        $get_class_list_query = "SELECT * FROM Classes";
                        $statement = $pdo->prepare($get_class_list_query);
                        $statement->execute();
                        $classes = [];
                        // fetch class with while loop
                        while($class = $statement->fetch(PDO::FETCH_ASSOC)) {
                            $classes[] = $class;
                        }
                    ?>
                    <?php if(isset($classes) && count($classes) > 0) : ?>
                        <!-- Show Class List with foreach loop -->
                        <li><a class="dropdown-item text-primary" href="students.php?class_id=">All Classes</a></li>
                        <?php foreach($classes as $class) : ?>
                            <li><a class="dropdown-item text-primary" href="students.php?class_id=<?= $class['class_id'] ?>"><?= $class['class_name'] ?></a></li>
                        <?php endforeach; ?>
                    <?php endif; ?>

                    </ul>
                </div>

            </div>
            <!-- <div class="col-md-2">
                <select class="form-select">
                    <option value="">All Ages</option>
                    <option>3 years</option>
                    <option>4 years</option>
                    <option>5 years</option>
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-select">
                    <option value="">All Status</option>
                    <option>Active</option>
                    <option>Inactive</option>
                    <option>Pending</option>
                </select>
            </div> -->
        </div>
    </div>
</div>