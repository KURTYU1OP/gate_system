<?php
session_start();
include("../config.php");
include("../firebaseRDB.php");

if (!isset($_SESSION['staff_logged_in'])) {
    header("Location: ../index.php");
    exit;
}

$db = new firebaseRDB($databaseURL);

// Generate unique ID for student
$id = uniqid("stu_");

// Get form data
$student_id = $_POST['student_id'];
$firstname  = $_POST['firstname'];
$middlename = $_POST['middlename'];
$lastname   = $_POST['lastname'];
$gender     = $_POST['gender'];
$year_level = $_POST['year_level'];
$course     = $_POST['course'];
$section    = $_POST['section'];
$email      = $_POST['email'];
$contact    = $_POST['contact'];
$campus     = $_POST['campus'];

// Full name
$fullname = trim($firstname . " " . $middlename . " " . $lastname);

$insert = $db->insert("Student", array(
    "id_no"      => $id,
    "student_id" => $student_id,
    "firstname"  => $firstname,
    "middlename" => $middlename,
    "lastname"   => $lastname,
    // "fullname"   => $fullname,
    "gender"     => $gender,
    "year_level" => $year_level,
    "course"     => $course,
    "section"    => $section,
    "email"      => $email,
    "contact"    => $contact,
    "campus"     => $campus
));

// Redirect with alert
echo "<script>
    alert('Student Added Successfully');
    window.location.href = '../staff/staffMain.php#student';
</script>";
