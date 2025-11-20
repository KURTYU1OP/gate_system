<?php
include("../config.php");
include("../firebaseRDB.php");

$db = new firebaseRDB($databaseURL);

$id         = $_POST['id'];
$firstname  = $_POST['firstname'];
$middlename = $_POST['middlename'];
$lastname   = $_POST['lastname'];
$college    = $_POST['college'];
$email      = $_POST['email'];
$password   = $_POST['password'];
$campus     = $_POST['campus'];

$updateData = array(
    "firstname"  => $firstname,
    "middlename" => $middlename,
    "lastname"   => $lastname,
    "college"    => $college,
    "email"      => $email,
    "password"   => $password,
    "campus"     => $campus
);

$update = $db->update("Staff", $id, $updateData);
echo "<script>
    alert('Staff Updated Successfully');
    window.location.href = '../admin/adminMain.php#staff';
</script>";
