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
$id             = $_POST['id']; // existing schedule ID
$course_section = $_POST['course_section'];
list($course, $section) = explode(" - ", $course_section);
$subject        = $_POST['subject'];
$day            = $_POST['day'];
$time_from      = $_POST['time_from'];
$time_to        = $_POST['time_to'];
$campus         = $_POST['campus'];

// Preserve existing id_no or generate if missing
$existingSchedule = json_decode($db->retrieve("Schedule/{$id}"), true);
$id_no = $existingSchedule['id_no'] ?? uniqid("sched_");

// Prepare update data
$updateData = array(
    "id_no"   => $id_no,
    "course"  => $course,
    "section" => $section,
    "subject" => $subject,
    "day"     => $day,
    "time_from" => $time_from,
    "time_to"   => $time_to,
    "campus" => $campus
);

$update = $db->update("Schedule", $id, $updateData);

if ($update) {
    echo "<script>
        alert('Schedule Updated Successfully');
        window.location.href = '../staff/staffMain.php#schedule';
    </script>";
} else {
    echo "<script>
        alert('Error updating schedule!');
        window.history.back();
    </script>";
}
