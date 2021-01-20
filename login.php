<?php
include "config.php";
$message ="";
if(isset($_POST['login'])){
    $uname = mysqli_real_escape_string($mysqli,$_POST['email']);
    $password = mysqli_real_escape_string($mysqli,$_POST['password']);
    if ($uname != "" && $password != ""){
        $sql_query = "select id, name from users where email='".$uname."' and password='".$password."'";
        $result = mysqli_fetch_array(mysqli_query($mysqli,$sql_query));
        if(isset($result)){
            $_SESSION['uid'] = $result['id'];
            $_SESSION['uname'] = $result['name'];
            header('Location: index.php');
        }else{
            $message.="<div class='alert alert-danger' role='alert'>
                        Invalid Username/Password
                      </div>";
        }
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
                <?php echo $message; ?>
                <form action="" method="POST">
				<div class="form-group">
				  <label for="email">Email</label>
				  <input class="form-control" type="email" name="email"> </input>
				</div>
				<div class="form-group">
				  <label for="password">Password</label>
				  <input class="form-control" type="password" name="password"> </input>
				</div>
				<input type="submit" class="btn btn-info btn-block mt-4" name="login" value="Login" />
			  </form>
                </div>
				<div class="col-md-6 offset-md-3">
                    <a href="register.php" class="btn btn-success btn-block mt-4">Sign Up</a>
				</div>
            </div>
        </div>
    </section>

</body>
</html>