<?php
session_start();
include("../config.php");
include("../firebaseRDB.php");

header('Content-Type: application/json');

// --- AUTHENTICATION CHECK ---
if (!isset($_SESSION['guard_logged_in'])) {
    echo json_encode(['success' => false, 'message' => 'Not authenticated']);
    exit;
}

date_default_timezone_set('Asia/Manila');
$db = new firebaseRDB($databaseURL);

$studentId = $_POST['student_id'] ?? '';
$violation = $_POST['violation'] ?? 'None';

if (empty($studentId)) {
    echo json_encode(['success' => false, 'message' => 'Missing Student ID']);
    exit;
}

// --- LOOKUP STUDENT ---
$students = json_decode($db->retrieve("Student"), true);
$studentData = null;

if ($students) {
    foreach ($students as $key => $student) {
        if (isset($student['student_id']) && $student['student_id'] === $studentId) {
            $studentData = $student;
            break;
        }
    }
}

if (!$studentData) {
    echo json_encode(['success' => false, 'message' => 'Student not found in database']);
    exit;
}

// --- GUARD DETAILS ---
$guardName   = $_SESSION['guard_name'] ?? '';
$guardCampus = $_SESSION['guard_campus'] ?? '';
$guardGate   = $_SESSION['guard_gate'] ?? '';

// --- VALIDATION BASED ON SCHEDULE ---
$studentSection = strtolower(trim($studentData['section'] ?? ''));
$schedules = json_decode($db->retrieve("Schedule"), true);
$validSchedules = [];

if ($schedules) {
    foreach ($schedules as $key => $sched) {
        $schedSection = strtolower(trim($sched['section'] ?? ''));
        if ($schedSection === $studentSection) {
            $validSchedules[] = $sched;
        }
    }
}

// --- VISITOR OVERRIDE ---
$dateKey = date('Y-m-d');
$visitorKey = $studentId . '-' . $dateKey;

$visitorRaw = $db->retrieve("Student_Visitor/$visitorKey");
$visitorRecord = json_decode($visitorRaw, true);

$hasVisitor = false;
$visitorPurpose = '';
$visitorDate = '';

if (is_array($visitorRecord) && isset($visitorRecord['student_id'])) {
    if (strtolower(trim($visitorRecord['student_id'])) === strtolower(trim($studentId))) {
        $hasVisitor = true;
        $visitorPurpose = $visitorRecord['purpose'] ?? '';
        $visitorDate = $visitorRecord['date'] ?? $dateKey;
    }
}

// --- CURRENT TIME + DAY VALIDATION ---
$currentTime = date('H:i');
$currentDay  = date('l'); // e.g. "Monday"
$now = DateTime::createFromFormat('H:i', $currentTime);

$allowed = false;
$allowedWindow = [];

if ($hasVisitor) {
    $allowed = true;
    $allowedWindow[] = [
        'subject' => 'Visitor',
        'day' => $visitorDate,
        'allowed_from' => '00:00',
        'allowed_to'   => '23:59'
    ];
} elseif (!empty($validSchedules)) {
    foreach ($validSchedules as $sched) {
        $schedDay = $sched['day'] ?? '';
        if (strcasecmp($schedDay, $currentDay) !== 0) {
            // Skip schedules that are not for today
            continue;
        }

        $timeFrom = DateTime::createFromFormat('H:i', $sched['time_from']);
        $timeTo   = DateTime::createFromFormat('H:i', $sched['time_to']);
        $allowedStart = (clone $timeFrom)->modify('-1 hour');
        $allowedEnd   = (clone $timeTo)->modify('+1 hour');

        $allowedWindow[] = [
            'subject' => $sched['subject'],
            'day' => $schedDay,
            'allowed_from' => $allowedStart->format('H:i'),
            'allowed_to'   => $allowedEnd->format('H:i')
        ];

        if ($now >= $allowedStart && $now <= $allowedEnd) {
            $allowed = true;
            break;
        }
    }
}

if (!$allowed) {
    echo json_encode([
        'success' => false,
        'message' => 'Entry not allowed. You can only enter on the correct day and 1 hour before/after your scheduled classes, or if you have a visitor record.',
        'allowed_windows' => $allowedWindow,
        'schedules' => array_map(function($s) {
            return [
                'subject' => $s['subject'],
                'day' => $s['day'] ?? '',
                'time_from' => $s['time_from'],
                'time_to' => $s['time_to']
            ];
        }, $validSchedules)
    ]);
    exit;
}

// --- ENTRY LOGGING LOGIC ---
$timeNow = date('H:i:s');
$baseKey = $studentId . '-' . $dateKey;

$entries = json_decode($db->retrieve("Entry_log/Student"), true) ?? [];

$openKey = null;
foreach ($entries as $key => $value) {
    if (strpos($key, $baseKey) === 0 && isset($value['time_in']) && !isset($value['time_out'])) {
        $openKey = $key;
        break;
    }
}

if ($openKey) {
    // --- TIME OUT ---
    $updateData = [
        "time_out"   => $timeNow,
        "guard_out"  => $guardName,
        "gate_out"   => $guardGate,
        "is_visitor" => $hasVisitor ? true : false
    ];
    $db->update("Entry_log/Student", $openKey, $updateData);

    echo json_encode([
        'success'    => true,
        'action'     => 'time_out',
        'student_id' => $studentId,
        'full_name'  => ($studentData['firstname'] ?? '') . ' ' . ($studentData['lastname'] ?? ''),
        'course'     => $studentData['course'] ?? '',
        'section'    => $studentData['section'] ?? '',
        'campus'     => $entries[$openKey]['campus'] ?? '', 
        'time'       => $timeNow,
        'violation'  => $violation,
        'guard_in'   => $entries[$openKey]['guard_in'] ?? '',
        'gate_in'    => $entries[$openKey]['gate_in'] ?? '',
        'guard_out'  => $guardName,
        'gate_out'   => $guardGate,
        'is_visitor' => $hasVisitor ? true : false,
        'visitor_purpose' => $visitorPurpose,
        'visitor_date' => $visitorDate,
        'schedules'  => array_map(function($s) {
            return [
                'subject' => $s['subject'],
                'day' => $s['day'] ?? '',
                'time_from' => $s['time_from'],
                'time_to' => $s['time_to']
            ];
        }, $validSchedules)
    ]);
} else {
    // --- TIME IN ---
    $count = 0;
    foreach ($entries as $key => $value) {
        if (strpos($key, $baseKey) === 0) {
            $count++;
        }
    }

    $entryKey = $baseKey . '-' . ($count + 1);

    $data = [
        "time_in"     => $timeNow,
        "violation"   => $violation,
        "full_name"   => ($studentData['firstname'] ?? '') . ' ' . ($studentData['lastname'] ?? ''),
        "course"      => $studentData['course'] ?? '',
        "section"     => $studentData['section'] ?? '',
        "guard_in"    => $guardName,
        "gate_in"     => $guardGate,
        "campus"      => $guardCampus,
        "is_visitor"  => $hasVisitor ? true : false,
        "visitor_purpose" => $visitorPurpose,
        "visitor_date" => $visitorDate
    ];

    $db->update("Entry_log/Student", $entryKey, $data);

    echo json_encode([
        'success'    => true,
        'action'     => 'time_in',
        'student_id' => $studentId,
        'full_name'  => ($studentData['firstname'] ?? '') . ' ' . ($studentData['lastname'] ?? ''),
        'course'     => $studentData['course'] ?? '',
        'section'    => $studentData['section'] ?? '',
        'time'       => $timeNow,
        'violation'  => $violation,
        'guard_in'   => $guardName,
        'gate_in'    => $guardGate,
        'campus'     => $guardCampus,
        'is_visitor' => $hasVisitor ? true : false,
        'visitor_purpose' => $visitorPurpose,
        'visitor_date' => $visitorDate,
        'schedules'  => array_map(function($s) {
            return [
                'subject' => $s['subject'],
                'time_from' => $s['time_from'],
                'time_to' => $s['time_to']
            ];
        }, $validSchedules)
    ]);
}
