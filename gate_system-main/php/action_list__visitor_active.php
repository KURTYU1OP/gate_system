<?php
session_start();
include("../config.php");
include("../firebaseRDB.php");

header('Content-Type: application/json');

if (!isset($_SESSION['guard_logged_in'])) {
    echo json_encode(['success' => false, 'message' => 'Not authenticated']);
    exit;
}

date_default_timezone_set('Asia/Manila');
$db = new firebaseRDB($databaseURL);

$guardCampus = $_SESSION['guard_campus'] ?? '';

$entries = json_decode($db->retrieve("Entry_log/Visitor"), true) ?? [];
$active = [];

foreach ($entries as $key => $v) {
    $campus = $v['campus'] ?? '';
    if (isset($v['time_in']) && !isset($v['time_out']) && $campus === $guardCampus) {
        $active[] = [
            'entry_key' => $key,
            'name'      => $v['name'] ?? '',
            'address'   => $v['address'] ?? '',
            'contact'   => $v['contact'] ?? '',
            'reason'    => $v['reason'] ?? '',
            'time_in'   => $v['time_in'] ?? '',
            'guard_in'  => $v['guard_in'] ?? '',
            'gate_in'   => $v['gate_in'] ?? '',
            'campus'    => $campus
        ];
    }
}

echo json_encode(['success' => true, 'campus' => $guardCampus, 'active' => $active]);
