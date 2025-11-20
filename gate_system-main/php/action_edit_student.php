<?php
include("../config.php");
include("../firebaseRDB.php");

$db = new firebaseRDB($databaseURL);

// Get POST data
$id         = $_POST['id'];
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
$full_name = trim($firstname . ' ' . $middlename . ' ' . $lastname);

// Prepare update data
$updateData = array(
    "student_id" => $student_id,
    "firstname"  => $firstname,
    "middlename" => $middlename,
    "lastname"   => $lastname,
    "full_name"  => $full_name,
    "gender"     => $gender,
    "year_level" => $year_level,
    "course"     => $course,
    "section"    => $section,
    "email"      => $email,
    "contact"    => $contact,
    "campus"     => $campus
);

$update = $db->update("Student", $id, $updateData);

if ($update) {
    echo "<script>
        alert('Student Updated Successfully');
        window.location.href = '../staff/staffMain.php#student';
    </script>";
} else {
    echo "<script>
        alert('Error updating student!');
        window.history.back();
    </script>";
}
