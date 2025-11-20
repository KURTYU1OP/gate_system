<?php
include_once("../config.php");
include_once("../firebaseRDB.php");

$db = new firebaseRDB($databaseURL);

$id = $_GET['id'] ?? '';

if (!$id) {
    echo "<script>alert('Invalid Schedule ID.'); window.history.back();</script>";
    exit;
}


$delete = $db->delete("Schedule", $id);

if ($delete) {
    echo "<script>alert('Schedule deleted successfully!'); window.location.href='../staff/staffMain.php#schedule';</script>";
} else {
    echo "<script>alert('Error deleting schedule!'); window.history.back();</script>";
}
