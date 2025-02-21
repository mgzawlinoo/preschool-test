<div class="card mb-4">
    <div class="card-body">
        <div class="row g-3">
            <!-- <div class="col-md-3">
                <input type="text" class="form-control" placeholder="Search students...">
            </div> -->
            <div class="col-md-2">
            <select class="form-select" name="class_id" required>
                <option value="">Select Class</option>
                <?php 
                    $get_class_list_query = "SELECT * FROM Classes";
                    $statement = $pdo->prepare($get_class_list_query);
                    $statement->execute();
                    $classes = [];
                    // fetch teacher with while loop
                    while($class = $statement->fetch(PDO::FETCH_ASSOC)) {
                        $classes[] = $class;
                    }
                ?>
                <?php if(count($classes) > 0) : ?>
                    <!-- Show Teacher List with foreach loop -->
                    <?php foreach($classes as $class) : ?>
                        <option value="<?= $class['class_id']; ?>"><?= $class['class_name']; ?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>

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