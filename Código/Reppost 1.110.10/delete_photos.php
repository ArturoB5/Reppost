<?php
include('Config/dbcon.php');
include('Controller/Backend/session.php');
$get_id = $_GET['id'];
$conn->query("delete from photos where photos_id='$get_id'");
header('location:profile.php');
