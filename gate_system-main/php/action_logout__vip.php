<?php
session_start();
include("../config.php");
include("../firebaseRDB.php");

header('Content-Type: application/json');

// --- AUTH CHECK ---
if (!isset($_SESSION['guard_logged_in'])) {
    echo json_encode(['success' => false, 'message' => 'Not authenticated']);
    exit;
}

date_default_timezone_set('Asia/Manila');
$db = new firebaseRDB($databaseURL);

// --- INPUT ---
$entryKey    = $_POST['entry_key'] ?? '';
$guardName   = $_SESSION['guard_name'] ?? '';
$guardGate   = $_SESSION['guard_gate'] ?? '';
$guardCampus = $_SESSION['guard_campus'] ?? '';

if (empty($entryKey)) {
    echo json_encode(['success' => false, 'message' => 'Missing entry key']);
    exit;
}

// --- LOOKUP ---
$entries = json_decode($db->retrieve("Entry_log/VIP"), true) ?? [];
if (!isset($entries[$entryKey])) {
    echo json_encode(['success' => false, 'message' => 'VIP entry not found']);
    exit;
}

// --- CAMPUS SAFETY ---
if (($entries[$entryKey]['campus'] ?? '') !== $guardCampus) {
    echo json_encode(['success' => false, 'message' => 'Campus mismatch']);
    exit;
}

// --- CHECK IF ALREADY LOGGED OUT ---
if (isset($entries[$entryKey]['time_out'])) {
    echo json_encode(['success' => false, 'message' => 'VIP already logged out']);
    exit;
}

// --- UPDATE ---
$timeNow = date('H:i:s');
$updateData = [
    "time_out"  => $timeNow,
    "guard_out" => $guardName,
    "gate_out"  => $guardGate
];

$db->update("Entry_log/VIP", $entryKey, $updateData);

// --- RESPONSE ---
echo json_encode([
    'success'   => true,
    'action'    => 'time_out',
    'entry_key' => $entryKey,
    'name'      => $entries[$entryKey]['name'] ?? '',
    'time'      => $timeNow,
    'reason'    => $entries[$entryKey]['reason'] ?? '',
    'guard_in'  => $entries[$entryKey]['guard_in'] ?? '',
    'gate_in'   => $entries[$entryKey]['gate_in'] ?? '',
    'guard_out' => $guardName,
    'gate_out'  => $guardGate,
    'campus'    => $guardCampus
]);
