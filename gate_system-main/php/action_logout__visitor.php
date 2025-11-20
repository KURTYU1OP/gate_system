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

$entryKey    = $_POST['entry_key'] ?? '';
$guardName   = $_SESSION['guard_name'] ?? '';
$guardGate   = $_SESSION['guard_gate'] ?? '';
$guardCampus = $_SESSION['guard_campus'] ?? '';

if (empty($entryKey)) {
    echo json_encode(['success' => false, 'message' => 'Missing entry key']);
    exit;
}

$entries = json_decode($db->retrieve("Entry_log/Visitor"), true) ?? [];
if (!isset($entries[$entryKey])) {
    echo json_encode(['success' => false, 'message' => 'Visitor entry not found']);
    exit;
}

// Additional safety: only allow logout for same campus
if (($entries[$entryKey]['campus'] ?? '') !== $guardCampus) {
    echo json_encode(['success' => false, 'message' => 'Campus mismatch']);
    exit;
}

// Only if not already logged out
if (isset($entries[$entryKey]['time_out'])) {
    echo json_encode(['success' => false, 'message' => 'Visitor already logged out']);
    exit;
}

$timeNow = date('H:i:s');
$updateData = [
    "time_out"  => $timeNow,
    "guard_out" => $guardName,
    "gate_out"  => $guardGate
];

$db->update("Entry_log/Visitor", $entryKey, $updateData);

echo json_encode([
    'success'   => true,
    'action'    => 'time_out',
    'entry_key' => $entryKey,
    'name'      => $entries[$entryKey]['name'] ?? '',
    'time'      => $timeNow,
    'guard_in'  => $entries[$entryKey]['guard_in'] ?? '',
    'gate_in'   => $entries[$entryKey]['gate_in'] ?? '',
    'guard_out' => $guardName,
    'gate_out'  => $guardGate,
    'campus'    => $guardCampus
]);
