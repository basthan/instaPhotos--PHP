<?php
//connect to database
	require_once("config.php");
	if(isset($_POST["imageURL"])){
		$photoURL = $_POST["imageURL"];
        //$comment = mysqli_real_escape_string($mysqli, $_POST['comment']);
		$insertSQL = "INSERT INTO photos(id, user_id, image_url, upload_date) values(null,?,?,now())";
		$stmt = $mysqli->prepare($insertSQL);
		$stmt->bind_param("ss",$_SESSION['uid'],$photoURL);
		$stmt->execute();
		$stmt->close();
    }
// Check user login or not
	if(!isset($_SESSION['uname'])){
        $nav_button ='<li class="nav-item">
            <a class="nav-link page-scroll" href="login.php">Login</a>
          </li>';
    }else{
		$nav_button ='<li class="nav-item">
            <a class="nav-link page-scroll" href="#">Hi, '.$_SESSION['uname'].'</a>
          </li>
          <li class="nav-item">
			<form method="POST" action="">
			  <input type="submit" value="Logout" name="but_logout">
			</form>
          </li>';
	}
	// check if comment added
	if(isset($_POST['add_comment'])){
		if(!isset($_SESSION['uname'])){
			header('Location: login.php');
		}
		$comment = mysqli_real_escape_string($mysqli, $_POST['comment']);
		$insertSQL = "INSERT INTO photo_comment (id,item_id,user_id,comment) values(null,?,?,?)";
		$stmt = $mysqli->prepare($insertSQL);
		$stmt->bind_param("sss",$_POST['photos_id'],$_SESSION['uid'],$comment);
		$stmt->execute();
		$stmt->close();
	}

// logout
if(isset($_POST['but_logout'])){
   session_destroy();
   header('Location: index.php');
}

$display_block ="";

//show categories first
$get_photos_sql = "select photos.id, user_id, name, image_url from photos LEFT JOIN users on photos.user_id = users.id ORDER BY upload_date DESC";
$get_photos_res =  mysqli_query($mysqli, $get_photos_sql) or die(mysqli_error($mysqli));

if (mysqli_num_rows($get_photos_res) < 1) {
   $display_block = "<div class='notices red'>Sorry, no photos uploaded.</b></div>";
} else {
   while ($photos = mysqli_fetch_array($get_photos_res)) {
        $photos_id  = $photos['id'];
        $user_id  = $photos['user_id'];
		$username = $photos['name'];
        $image_url  = $photos['image_url'];
        $display_block .="<div class='row'><div class='col-md-6 offset-md-3'>
				<div class='post-border'>
				<div class='post-header'>
					<div class='post-avatar'></div>
					<h5>".$username."</h5>
				</div>
				<div class='img-box'>
					<img src='https://store-bucket-app.s3.amazonaws.com/".$image_url."' alt='Avatar' class='image'>
					<div class='middle'>
						<div class='text'><a class='nav-link' href='shows.php?item_id=".$photos_id."'>Detail</a></div>
					</div>
				</div>
				<div class='caption'><strong><i>".$username."</i></strong></div>
				<div class='home-comment'>
					<p>";

		//get sizes
		$get_comments_sql = "SELECT name, comment FROM photo_comment LEFT JOIN users on photo_comment.user_id = users.id WHERE item_id = '".$photos_id."'";
		$get_comments_res = mysqli_query($mysqli, $get_comments_sql) or die(mysqli_error($mysqli));

		if (mysqli_num_rows($get_comments_res) > 0) {

		  while ($comments = mysqli_fetch_array($get_comments_res)) {
			 $user = $comments['name'];
			 $item_comment = $comments['comment'];
			 $display_block .= "<strong>".$user."</strong> ".$item_comment."<br>";
		  }
		}
		//free result
		mysqli_free_result($get_comments_res);
		if(isset($_SESSION['uname'])){
			$display_block .="</p>
				</div>
					<form action='' method='POST' class='comment-send'>
						  <input type='text' class='form-control post-comment' placeholder='Add comment...' name='comment'>
						  <input type='hidden' name='photos_id' value='".$photos_id."'>
						  <div class='input-group-append'>
							<button class=\"btn btn-info post-btn\" type=\"submit\" name=\"add_comment\" value='Add'>Send</button>
						  </div>
					</form>
				</div>
			</div></div>";
		}else{
			$display_block .="</p>
				</div>
				<div class='discomment'><a href=\"login.php\">Login</a>/<a href=\"register.php\">Signup</a> to add comment</div>
				</div>
			</div></div>";
		}
    }
}
//free results
mysqli_free_result($get_photos_res);

//close connection to MySQL
mysqli_close($mysqli);
?>
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">

    <title><?php echo $appname?></title>
  </head>
  <body>
	<!-- navbar -->
    <nav class="navbar fixed-top navbar-expand-lg navbar-dark bg-dark">
      <a class="navbar-brand page-scroll" href="index.php"><?php echo $appname?></a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto"></ul>
        <ul class="navbar-nav justify-content-end">
          <li class="nav-item">
            <a class="nav-link page-scroll" href="upload.php">Upload</a>
          </li>
          <?php echo $nav_button?>
        </ul>
      </div>
    </nav>
    <!-- akhir navbar -->
	<div class="container">
		<?php echo $display_block; ?>
	</div>

    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js" integrity="sha384-q2kxQ16AaE6UbzuKqyBE9/u/KzioAlnx2maXQHiDX9d4/zp8Ok3f+M7DPm+Ib6IU" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.min.js" integrity="sha384-pQQkAEnwaBkjpqZ8RU1fF1AKtTcHJwFl3pblpTlHXybJjHpMYo79HY3hIi4NKxyj" crossorigin="anonymous"></script>
    -->
  </body>
</html>