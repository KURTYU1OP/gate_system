<?php
include("../config.php");
include("../firebaseRDB.php");

$db = new firebaseRDB($databaseURL);

// Get schedule ID from GET
$id = isset($_GET['id']) ? $_GET['id'] : '';
if (!$id) {
    echo "<script>alert('No schedule selected'); window.history.back();</script>";
    exit;
}

// Get schedule data
$scheduleData = json_decode($db->retrieve("Schedule/{$id}"), true);
if (!$scheduleData) {
    echo "<script>alert('Schedule not found'); window.history.back();</script>";
    exit;
}

$campus = $scheduleData['campus'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Schedule</title>

    <link rel="stylesheet" href="../assets/css/global.css?v=1">
    <link rel="stylesheet" href="../assets/css/modal.css?v=1">
</head>

<body>

    <div class="form_container">
        <a href="javascript:window.history.back();" class="back_btn">← Back</a>
        <h2>Edit Schedule</h2>

        <form action="../php/action_edit_schedule.php" method="POST">
            <input type="hidden" name="id" value="<?php echo $id; ?>">

            <label for="course_section">Course - Section</label>
            <input type="text" name="course_section" id="course_section" placeholder="e.g. BSIT - 2A" value="<?php echo htmlspecialchars($scheduleData['course'] . " - " . $scheduleData['section']); ?>" required>

            <label for="subject">Subject</label>
            <input type="text" name="subject" id="subject" placeholder="Subject Name" value="<?php echo htmlspecialchars($scheduleData['subject']); ?>" required>

            <label for="day">Day</label>
            <input type="text" name="day" id="day" placeholder="Day" value="<?php echo htmlspecialchars($scheduleData['day']); ?>" required>

            <label for="time_from">Time From</label>
            <input type="time" name="time_from" id="time_from" value="<?php echo htmlspecialchars($scheduleData['time_from']); ?>" required>

            <label for="time_to">Time To</label>
            <input type="time" name="time_to" id="time_to" value="<?php echo htmlspecialchars($scheduleData['time_to']); ?>" required>

            <label for="campus">Campus</label>
            <input type="text" name="campus" id="campus" value="<?php echo htmlspecialchars($campus); ?>" readonly style="background:#e9e9e9; cursor:not-allowed;">

            <button type="submit">Update</button>
        </form>
    </div>

</body>

</html>