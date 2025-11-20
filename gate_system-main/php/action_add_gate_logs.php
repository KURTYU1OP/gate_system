<?php
include("../config.php");
include("../firebaseRDB.php");

$db = new firebaseRDB($databaseURL);

$sampleData = [
    "student_name" => "Juan Dela Cruz",
    "student_id"   => "2021-12345",
    "datetime"     => date("Y-m-d H:i:s"),
    "gate"         => "Main Gate",
    "guard_name"   => "Guard Santos",
    "action"       => "IN"   // or "OUT"
];

$insert = $db->insert("Gate_Logs", $sampleData);

if ($insert) {
    echo "Sample gate log added successfully.";
} else {
    echo "Error inserting log.";
}
