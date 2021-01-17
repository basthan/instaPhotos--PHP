<?php
//connect to database
require_once("config.php");
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
// logout
    if(isset($_POST['but_logout'])){
        session_destroy();
        header('Location: index.php');
    }

$display_block ="";

//create safe values for use
$safe_item_id = mysqli_real_escape_string($mysqli, $_GET['item_id']);
if(isset($_POST['add_comment'])){
	if(!isset($_SESSION['uname'])){
        header('Location: login.php');
    }
    $comment = mysqli_real_escape_string($mysqli, $_POST['comment']);
    $insertSQL = "INSERT INTO photo_comment (id,item_id,user_id,comment) values(null,?,?,?)";
    $stmt = $mysqli->prepare($insertSQL);
    $stmt->bind_param("sss",$safe_item_id,$_SESSION['uid'],$comment);
    $stmt->execute();
    $stmt->close();
}
    
//validate item
$get_item_sql = "select photos.id, user_id, name, image_url from photos LEFT JOIN users on photos.user_id = users.id WHERE photos.id = '".$safe_item_id."'";
$get_item_res = mysqli_query($mysqli, $get_item_sql) or die(mysqli_error($mysqli));

if (mysqli_num_rows($get_item_res) < 1) {
    //invalid item
    $display_block .= "<p><em>Invalid photo selection.</em></p>";
} else {
    //valid item, get info
    while ($item_info = mysqli_fetch_array($get_item_res)) {
       $photos_id = $item_info['id'];
       $user_id = $item_info['user_id'];
       $username = $item_info['name'];
       $image_url = $item_info['image_url'];
    }

//free result
mysqli_free_result($get_item_res);

    //get sizes
    $get_comments_sql = "SELECT name, comment FROM photo_comment LEFT JOIN users on photo_comment.user_id = users.id WHERE item_id = '".$safe_item_id."'";
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
    //close up the div
}
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

    <title>Hello, world!</title>
  </head>
  <body>
	<!-- navbar -->
    <nav class="navbar fixed-top navbar-expand-lg navbar-dark bg-dark">
      <a class="navbar-brand page-scroll" href="index.php">Photos App</a>
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
		<div class="row image-detail">
			<div class='col-md-7 offset-md-1'>
				<div class='image-shows'>
					<img src='<?php echo $s3path.$image_url?>' alt='Avatar' class='image'>
				</div>
			</div>
			<div class='col-md-3'>
				<div class='detail-head'>
					<p><?php echo $username?></p>
				</div>
				<div class='detail-comment'>
					<p><?php echo $display_block?></p>
				</div>
				<div class='detail-comment-send'>
					<form action="" method="POST">
						<div class="form-group">
							<input class="form-control" type="text" name="comment" placeholder="Comment"> </input>
						</div>
						<input type="submit" class="btn btn-info btn-block mt-4" name="add_comment" value="Add" />
					</form>
				</div>
			</div>
		</div>
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