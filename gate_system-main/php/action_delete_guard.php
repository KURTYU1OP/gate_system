<?php
include_once("../config.php");
include_once("../firebaseRDB.php");

$db = new firebaseRDB($databaseURL);

$id = $_GET['id'] ?? '';

if (!$id) {
    echo "<script>alert('Invalid Guard ID.'); window.history.back();</script>";
    exit;
}


$delete = $db->delete("Guard", $id);

if ($delete) {
    echo "<script>alert('Guard deleted successfully!'); window.location.href='../staff/staffMain.php#guard';</script>";
} else {
    echo "<script>alert('Error deleting guard!'); window.history.back();</script>";
}
