<?php
session_start();
include("../config.php");
include("../firebaseRDB.php");

if (!isset($_SESSION['staff_logged_in'])) {
    header("Location: ../index.php");
    exit;
}

$db = new firebaseRDB($databaseURL);

// Generate unique ID for schedule
$id = uniqid("sched_");

// Get form data
$course_section = $_POST['course_section'];
list($course, $section) = explode(" - ", $course_section);
$subject = $_POST['subject'];
$day = $_POST['day'];
$time_from = $_POST['time_from'];
$time_to = $_POST['time_to'];
$campus = $_POST['campus'];


$insert = $db->insert("Schedule", array(
    "id_no"   => $id,
    "course"  => $course,
    "section" => $section,
    "subject" => $subject,
    "day"     => $day,
    "time_from" => $time_from,
    "time_to"   => $time_to,
    "campus" => $campus
));


echo "<script>
alert('Schedule Added Successfully');
window.location.href = '../staff/staffMain.php#schedule';
</script>";
