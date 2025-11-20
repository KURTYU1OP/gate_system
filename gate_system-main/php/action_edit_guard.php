<?php
session_start();
include("../config.php");
include("../firebaseRDB.php");

if (!isset($_SESSION['staff_logged_in'])) {
    header("Location: ../index.php");
    exit;
}

$db = new firebaseRDB($databaseURL);

// Get POST data
$id               = $_POST['id'];
$firstname        = $_POST['firstname'];
$middlename       = $_POST['middlename'];
$lastname         = $_POST['lastname'];
$campus           = $_POST['campus'];
$email            = $_POST['email'];
$password         = $_POST['password'];
$gate_designation = $_POST['gate_designation'];

// Full name
$fullname = trim($firstname . " " . $middlename . " " . $lastname);

// Prepare update data
$updateData = array(
    "id_no"           => $db->retrieve("Guard/{$id}/id_no") ?? uniqid("g_"),
    "firstname"       => $firstname,
    "middlename"      => $middlename,
    "lastname"        => $lastname,
    "fullname"        => $fullname,
    "email"           => $email,
    "password"        => $password,
    "campus"          => $campus,
    "gate_designation" => $gate_designation
);

$update = $db->update("Guard", $id, $updateData);

if ($update) {
    echo "<script>
        alert('Guard Updated Successfully');
        window.location.href = '../staff/staffMain.php#guard';
    </script>";
} else {
    echo "<script>
        alert('Error updating guard!');
        window.history.back();
    </script>";
}
