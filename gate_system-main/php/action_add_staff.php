<?php
include("../config.php");
include("../firebaseRDB.php");

$db = new firebaseRDB($databaseURL);

$id = uniqid("s_");

$firstname  = $_POST['firstname'];
$middlename = $_POST['middlename'];
$lastname   = $_POST['lastname'];
$college    = $_POST['college'];
$email      = $_POST['email'];
$password   = $_POST['password'];
$campus     = $_POST['campus'];

$fullname = $firstname . " " . $middlename . " " . $lastname;

$insert = $db->insert("Staff", array(
    "id_no"      => $id,
    "firstname"  => $firstname,
    "middlename" => $middlename,
    "lastname"   => $lastname,
    "college"    => $college,
    "email"      => $email,
    "password"   => $password,
    "campus"     => $campus
));

echo "<script>
    alert('Staff Added Successfully');
    let campus = encodeURIComponent('{$campus}');
    window.location.href = '../admin/adminMain.php#staff';
</script>";
