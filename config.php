<?php
    session_start();
    $host = "<HOSTNAME>"; /* Host name */
    $user = "<USER>"; /* User */
    $password = "<PASSWORD>"; /* Password */
    $dbname = "<DATABASE NAME>"; /* Database name */
    $s3path = "<BUCKET>";
	$appname = "InstaPhotos"

    $mysqli = mysqli_connect($host, $user, $password, $dbname);
    // Check connection
    if (!$mysqli) {
        die("Connection failed: " . mysqli_connect_error());
    }