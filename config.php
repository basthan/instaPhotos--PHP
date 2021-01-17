<?php
    session_start();
    $host = "<DATABASE HOST>"; /* Host name */
    $user = "<DATABASE USER>"; /* User */
    $password = "<DATABASE PASSWORD>"; /* Password */
    $dbname = "DATABASE NAME"; /* Database name */
    $s3path = "YOUR S3 BUCKET";

    $mysqli = mysqli_connect($host, $user, $password, $dbname);
    // Check connection
    if (!$mysqli) {
        die("Connection failed: " . mysqli_connect_error());
    }