<?php
$localhost = "localhost";
$db = "dbpuskesmas";
$username = "root";
$password = "";

$koneksi = mysqli_connect($localhost, $username, $password, $db) or die("connection failed: ". mysqli_connect_error());
?>