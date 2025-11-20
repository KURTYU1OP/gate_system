<?php
include_once("../config.php");
include_once("../firebaseRDB.php");

$db = new firebaseRDB($databaseURL);

$id = $_GET['id'] ?? '';

if (!$id) {
    echo "<script>alert('Invalid Student ID.'); window.history.back();</script>";
    exit;
}

$delete = $db->delete("Student", $id);

if ($delete) {
    echo "<script>alert('Student deleted successfully!'); window.location.href='../staff/staffMain.php#student';</script>";
} else {
    echo "<script>alert('Error deleting student!'); window.history.back();</script>";
}
