<?php 
include "config.php";
$error_message = "";$success_message = "";

// Register user
if(isset($_POST['btnsignup'])){
   $name = trim($_POST['name']);
   $email = trim($_POST['email']);
   $password = trim($_POST['password']);
   $confirmpassword = trim($_POST['confirmpassword']);
   $isValid = true;

   // Check fields are empty
   if($name == '' || $email == '' || $password == '' || $confirmpassword == ''){
     $isValid = false;
     $error_message = "Please fill all fields.";
   }

   // Check if confirm password matching
   if($isValid && ($password != $confirmpassword) ){
     $isValid = false;
     $error_message = "Confirm password not matching";
   }

   // Check if Email-ID is valid
   if ($isValid && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
     $isValid = false;
     $error_message = "Invalid Email.";
   }

   if($isValid){
     // Check if Email-ID already exists
     $stmt = $mysqli->prepare("SELECT * FROM users WHERE email = ?");
     $stmt->bind_param("s", $email);
     $stmt->execute();
     $result = $stmt->get_result();
     $stmt->close();
     if($result->num_rows > 0){
       $isValid = false;
       $error_message = "Email is already existed.";
     }
   }

   // Insert records
   if($isValid){
     $insertSQL = "INSERT INTO users(name,email,password) values(?,?,?)";
     $stmt = $mysqli->prepare($insertSQL);
     $stmt->bind_param("sss",$name,$email,$password);
     $stmt->execute();
     $stmt->close();
     $success_message = "Account created successfully.";
   }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo $appname?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
</head>
<body class="bg-light">
  <section>
  <div class="jumbotron jumbotron-fluid">
            <div class="container">
                <div class="row">
                    <div class="col-md-8 offset-md-2" style="text-align: center;">
                        <h1>Welcome to <?php echo $appname?></h1>
                    </div>
                </div>
            </div>
        </div>
    <div class="container">
      <div class="row">
        <div class="col-md-6 offset-md-3">
          <form method='post' action=''>
            <h1>Sign Up</h1>
            <?php 
              if(!empty($error_message)){
            ?>
            <div class="alert alert-danger" role ="alert">
              <strong>Error!</strong> <?= $error_message ?>
            </div>
            <?php
            }
            ?>

            <?php 
            // Display Success message
              if(!empty($success_message)){
            ?>
              <div class="alert alert-success" role ="alert">
                <strong>Success!</strong> <?= $success_message ?>
              </div>
            <?php
            }
            ?>

            <div class="form-group">
              <label for="name">Name:</label>
              <input type="text" class="form-control" name="name" id="name" required="required" maxlength="80">
            </div>
            <div class="form-group">
              <label for="email">Email address:</label>
              <input type="email" class="form-control" name="email" id="email" required="required" maxlength="80">
            </div>
            <div class="form-group">
              <label for="password">Password:</label>
              <input type="password" class="form-control" name="password" id="password" required="required" maxlength="80">
            </div>
            <div class="form-group">
              <label for="pwd">Confirm Password:</label>
              <input type="password" class="form-control" name="confirmpassword" id="confirmpassword" onkeyup='' required="required" maxlength="80">
            </div>
            <button type="submit" class="btn btn-primary btn-block mt-4" name="btnsignup">Submit</button>
          </form>
        </div>
			<div class="col-md-6 offset-md-3">
				<a href="login.php" class="btn btn-success btn-block mt-4">Login</a>
			</div>
      </div>
    </div>
  </section>
</body>
</html>