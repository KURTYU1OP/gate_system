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

// Inputs
$name    = trim($_POST['name'] ?? '');
$address = trim($_POST['address'] ?? '');
$contact = trim($_POST['contact'] ?? '');
$reason  = trim($_POST['reason'] ?? '');

// Guard context
$guardName   = $_SESSION['guard_name'] ?? '';
$guardCampus = $_SESSION['guard_campus'] ?? '';
$guardGate   = $_SESSION['guard_gate'] ?? '';

if (empty($name)) {
    echo json_encode(['success' => false, 'message' => 'Missing visitor name']);
    exit;
}

$dateKey = date('Y-m-d');
$timeNow = date('H:i:s');

// Retrieve existing visitor entries
$entries = json_decode($db->retrieve("Entry_log/Visitor"), true) ?? [];

// Base key (name normalized to avoid spaces issues)
$baseKey = preg_replace('/\s+/', '_', strtolower($name)) . '-' . $dateKey;

// Find open entry (has time_in but no time_out) for same campus
$openKey = null;
foreach ($entries as $key => $value) {
    if (strpos($key, $baseKey) === 0 &&
        isset($value['time_in']) &&
        !isset($value['time_out']) &&
        ($value['campus'] ?? '') === $guardCampus) {
        $openKey = $key;
        break;
    }
}

if ($openKey) {
    // TIME OUT
    $updateData = [
        "time_out"  => $timeNow,
        "guard_out" => $guardName,
        "gate_out"  => $guardGate
    ];
    $db->update("Entry_log/Visitor", $openKey, $updateData);

    echo json_encode([
        'success'   => true,
        'action'    => 'time_out',
        'name'      => $name,
        'time'      => $timeNow,
        'guard_in'  => $entries[$openKey]['guard_in'] ?? '',
        'gate_in'   => $entries[$openKey]['gate_in'] ?? '',
        'guard_out' => $guardName,
        'gate_out'  => $guardGate,
        'campus'    => $guardCampus
    ]);
    exit;
}

// TIME IN (create new record)
$count = 0;
foreach ($entries as $key => $value) {
    if (strpos($key, $baseKey) === 0 && ($value['campus'] ?? '') === $guardCampus) {
        $count++;
    }
}
$entryKey = $baseKey . '-' . ($count + 1);

$data = [
    "name"     => $name,
    "address"  => $address,
    "contact"  => $contact,
    "reason"   => $reason,
    "time_in"  => $timeNow,
    "guard_in" => $guardName,
    "gate_in"  => $guardGate,
    "campus"   => $guardCampus
];

$db->update("Entry_log/Visitor", $entryKey, $data);

echo json_encode([
    'success'   => true,
    'action'    => 'time_in',
    'name'      => $name,
    'address'   => $address,
    'contact'   => $contact,
    'reason'    => $reason,
    'time'      => $timeNow,
    'guard_in'  => $guardName,
    'gate_in'   => $guardGate,
    'campus'    => $guardCampus,
    'entry_key' => $entryKey
]);
