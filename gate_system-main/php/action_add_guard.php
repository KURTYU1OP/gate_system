<?php
session_start();
include("../config.php");
include("../firebaseRDB.php");

if (!isset($_SESSION['staff_logged_in'])) {
    header("Location: ../index.php");
    exit;
}

$db = new firebaseRDB($databaseURL);

// Generate unique ID for guard
$id = uniqid("g_");

// Get form data
$firstname      = $_POST['firstname'];
$middlename     = $_POST['middlename'];
$lastname       = $_POST['lastname'];
$campus         = $_POST['campus'];
$email          = $_POST['email'];
$password       = $_POST['password'];
$gate_designation = $_POST['gate_designation'];

// Full name
$fullname = trim($firstname . " " . $middlename . " " . $lastname);

$insert = $db->insert("Guard", array(
    "id_no"           => $id,
    "firstname"       => $firstname,
    "middlename"      => $middlename,
    "lastname"        => $lastname,
    "fullname"        => $fullname,
    "email"           => $email,
    "password"        => $password,
    "campus"          => $campus,
    "gate_designation" => $gate_designation
));

echo "<script>
    alert('Guard Added Successfully');
    window.location.href = '../staff/staffMain.php#guard';
</script>";
