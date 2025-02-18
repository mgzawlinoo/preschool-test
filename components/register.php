<?php

include './database/db.php';

$errors = [];
$now = new DateTime();
$now = $now->format('Y-m-d H:i:s');

// Validation Start
if( ($_SERVER['REQUEST_METHOD'] == 'POST') AND isset($_POST['register'])) {

    // Assign form data to variables and remove whitespace
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm-password']);
    $role = trim($_POST['role']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $staff_role = trim($_POST['staff_role']);
    $hire_date = trim($_POST['hire_date']);
    $salary = trim($_POST['salary']);

    // Filter the input to prevent SQL injection
    $name = htmlspecialchars($name);
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    $password = htmlspecialchars($password);
    $confirm_password = htmlspecialchars($confirm_password);
    $role = htmlspecialchars($role);
    $phone = htmlspecialchars($phone);
    $address = htmlspecialchars($address);
    $staff_role = htmlspecialchars($staff_role);
    $hire_date = htmlspecialchars($hire_date);
    $salary = htmlspecialchars($salary);


    // ####### VALIDATION START ######

    // Check empty fields
    empty($name) ? $errors['name'] = 'Name is required' : '';
    empty($email) ? $errors['email'] = 'Email is required' : '';
    empty($password) ? $errors['password'] = 'Password is required' : '';
    empty($confirm_password) ? $errors['confirm-password'] = 'Confirm Password is required' : '';
    empty($role) ? $errors['role'] = 'Role is required' : '';
    empty($phone) ? $errors['phone'] = 'Phone is required' : '';
    empty($address) ? $errors['address'] = 'Address is required' : '';

    // Check Validation for Teacher and Staff
    switch ($role) {
        case 'Teacher':
           empty($hire_date) ? $errors['hire_date'] = 'Hire Date is required' : '';
           empty($salary) ? $errors['salary'] = 'Salary is required' : '';
        break;

        case 'Staff':
            empty($staff_role) ? $errors['staff_role'] = 'Staff Role is required' : '';
            empty($hire_date) ? $errors['hire_date'] = 'Hire Date is required' : '';
            empty($salary) ? $errors['salary'] = 'Salary is required' : '';
        break;

        default:
        break;
    }

    // Check password match
    strcmp($password, $confirm_password) ? $errors['password'] = 'Password does not match' : '';

    // check password length
    if(strlen($password) < 8) {
        $error['password'] = 'Password must be at least 8 characters';
    }

    // Check Email Format
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Invalid Email Format';
    }

    // Check Email already exists
    $check_email_exists_query = "SELECT * FROM Users WHERE email = :email";
    $statement = $pdo->prepare($check_email_exists_query);
    $statement->bindParam(':email', $email, PDO::PARAM_STR);
    $statement->execute();

    if($statement->rowCount() > 0) {
        $errors['email'] = 'Email already exists';
    }

    // ####### VALIDATION END ######


    // Validation Completed and Insert Data
    if(count($errors) == 0) {

        // Hash Password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert User Data into Database
        $insert_user_query = "INSERT INTO Users (email, password, role, created_at, updated_at) 
        VALUES (:email, :password, :role, :created_at, :updated_at)";

        $statement = $pdo->prepare($insert_user_query);
        $statement->bindParam(":email", $email, PDO::PARAM_STR);
        $statement->bindParam(":password", $hashed_password, PDO::PARAM_STR);
        $statement->bindParam(":role", $role, PDO::PARAM_STR);
        $statement->bindParam(":created_at", $now, PDO::PARAM_STR);
        $statement->bindParam(":updated_at", $now, PDO::PARAM_STR);
        $statement->execute();
        $user_id = $pdo->lastInsertId();

        // Check Role and Insert Data
        switch ($role) {

            case "Teacher":
            // Insert Teacher Data into Database

            $insert_teacher_query = "INSERT INTO Teachers (user_id, name, phone, address, hire_date, salary) 
            VALUES (:user_id, :name, :phone, :address, :hire_date, :salary)";
            $statement = $pdo->prepare($insert_teacher_query);
            $statement->bindParam(":user_id", $user_id, PDO::PARAM_INT);
            $statement->bindParam("name", $name, PDO::PARAM_STR);
            $statement->bindParam("phone", $phone, PDO::PARAM_STR);
            $statement->bindParam("address", $address, PDO::PARAM_STR);
            $statement->bindParam("hire_date", $hire_date, PDO::PARAM_STR);
            $statement->bindParam("salary", $salary, PDO::PARAM_INT);
            $statement->execute();
            break;

            case "Staff":
            // Insert Staff Data into Database

            $insert_staff_query = "INSERT INTO Staff (user_id, name, phone, address, staff_role, hire_date, salary) 
            VALUES (:user_id, :name, :phone, :address, :staff_role, :hire_date, :salary)"; 
            $statement = $pdo->prepare($insert_staff_query);
            $statement->bindParam(":user_id", $user_id, PDO::PARAM_INT);
            $statement->bindParam(":name", $name, PDO::PARAM_STR);
            $statement->bindParam(":phone", $phone, PDO::PARAM_STR);
            $statement->bindParam(":address", $address, PDO::PARAM_STR);
            $statement->bindParam(":staff_role", $staff_role, PDO::PARAM_STR);
            $statement->bindParam(":hire_date", $hire_date, PDO::PARAM_STR);
            $statement->bindParam(":salary", $salary, PDO::PARAM_INT);
            $statement->execute();
            break;

            case "Parent":
            // Insert Parent Data into Database

            $insert_parent_query = "INSERT INTO Parents (user_id, name, phone, address) 
            VALUES (:user_id, :name, :phone, :address)"; 
            $statement = $pdo->prepare($insert_parent_query);
            $statement->bindParam(":user_id", $user_id, PDO::PARAM_INT);
            $statement->bindParam(":name", $name, PDO::PARAM_STR);
            $statement->bindParam(":phone", $phone, PDO::PARAM_STR);
            $statement->bindParam(":address", $address, PDO::PARAM_STR);
            $statement->execute();
            break;

            case "Admin":
            // Insert Admin Data into Database

            $insert_admin_query = "INSERT INTO Admins (user_id, name, phone, address) 
            VALUES (:user_id, :name, :phone, :address)"; 
            $statement = $pdo->prepare($insert_admin_query);
            $statement->bindParam(":user_id", $user_id, PDO::PARAM_INT);
            $statement->bindParam(":name", $name, PDO::PARAM_STR);
            $statement->bindParam(":phone", $phone, PDO::PARAM_STR);
            $statement->bindParam(":address", $address, PDO::PARAM_STR);
            $statement->execute();
            break;

        }

        // Show Register Success or Failure
        $statement ? $success['registration_success'] = true : $success['registration_success'] = false;

    }

}
?>

<div class="container-xxl py-5" id="classes">
      <div class="container">

            <?php
                // Output errors
                if(count($errors) > 0) {
                   foreach($errors as $error) {
                       echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                            $error
                            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                            </div>";
                   }
                }

                // Output Register Success or Failure
                if(isset($success['registration_success']) && $success['registration_success']) {
                       echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                            Registration Success
                            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                            </div>";
                }
                elseif(isset($success['registration_success']) && !$success['registration_success']) {
                    echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                         Registration Failed
                         <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                         </div>";
                }
            ?>

          <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px">
              <h1 class="mb-3">Register Form</h1>
          </div>
          <div class="row g-4">
              <div class="col-12 wow fadeInUp" data-wow-delay="0.1s">
                  <form action="register.php" method="POST" class="m-auto w-50 shadow rounded-2 p-5">
                     <div class="mb-3">
                          <label for="">Name</label>
                          <input type="text" class="form-control" name="name" placeholder="Name" id="name">
                      </div>
                      <div class="mb-3">
                          <label for="">Email</label>
                          <input type="email" class="form-control" name="email" placeholder="Email Address" id="email">
                      </div>
                      <div class="mb-3">
                          <label for="">Phone</label>
                          <input type="text" class="form-control" name="phone" placeholder="Phone" id="phone">
                      </div>
                      <div class="mb-3">
                          <label for="">Password</label>
                          <input type="password" class="form-control" name="password" placeholder="Password" id="password">
                      </div>
                      <div class="mb-3">
                          <label for="">ConfirmPassword</label>
                          <input type="password" class="form-control" name="confirm-password" placeholder="Confirm Password" id="confirm-password">
                      </div>
                      <div class="mb-3">
                          <label for="">Role</label>
                          <select name="role" id="role" class="form-control">
                              <option value="">Select Role</option>    
                              <option value="Teacher">Teacher</option>
                              <option value="Staff">Staff</option>
                              <option value="Parent">Parent</option>
                              <option value="Admin">Admin</option>
                          </select>
                      </div>

                      <!-- Teacher -->
                      <div id="teacher">
                          <!-- This form data will be inserted from javascript -->
                      </div>

                      <!-- Staff -->
                      <div id="staff">
                          <!-- This form data will be inserted from javascript -->
                      </div>

                      <div class="mb-3">
                          <label for="">Address</label>
                          <textarea class="form-control" name="address" placeholder="Address" id="address" rows="3"></textarea> 
                      </div>
                      <div class="text-center">
                          <input type="submit" value="Register" class="btn btn-primary text-white" name="register" />
                      </div>

                      <div class="text-center mt-3">
                          <p>Already have an account? <a href="login.php">Login</a></p>
                      </div>

                  </form>
              </div>
          </div>
      </div>
  </div>

  <script>
      let role = document.querySelector("#role");
      let teacher = document.querySelector("#teacher");
      let staff = document.querySelector("#staff");

      teacher.innerHTML = ``;
      staff.innerHTML = ``;
      
      role.onchange = function(e) {
          e.preventDefault();
          console.log(this.value);

          switch (this.value) {
            case "Teacher":
                staff.innerHTML = ``;
                teacher.innerHTML = `
                  <div class="mb-3">
                      <label for="">Hire Date</label>
                      <input type="date" class="form-control" name="hire_date" id="hire_date">
                  </div>
                  <div class="mb-3">
                      <label for="">Salary</label>
                      <input type="number" class="form-control" name="salary" id="salary">
                  </div>
                  `;
            break;

            case "Staff":
                teacher.innerHTML = ``;
                staff.innerHTML = `
                    <div class="mb-3">
                            <label for="">Role</label>
                            <select name="staff_role" id="role" class="form-control">
                                <option value="">Select Role</option>
                                <option value="Admin">Admin</option>
                                <option value="Assistant">Assistant</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="">Hire Date</label>
                            <input type="date" class="form-control" name="hire_date" id="hire_date">
                        </div>
                        <div class="mb-3">
                            <label for="">Salary</label>
                            <input type="number" class="form-control" name="salary" id="salary">
                        </div>
                 `;
            break;

            default:
              teacher.innerHTML = ``;
              staff.innerHTML = ``;
            break;
          
            }
          }

  </script>