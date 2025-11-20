<?php
session_start();
include("../config.php");
include("../firebaseRDB.php");

if (!isset($_SESSION['staff_logged_in'])) {
    header("Location: ../index.php");
    exit;
}

$db = new firebaseRDB($databaseURL);

// Retrieve all students
$students = json_decode($db->retrieve("Student"), true);

// Collect unique courses and sections
$course_sections = [];
if ($students) {
    foreach ($students as $stu) {
        if (isset($stu['course']) && isset($stu['section'])) {
            $key = $stu['course'] . " - " . $stu['section'];
            $course_sections[$key] = true;
        }
    }
}
$course_sections = array_keys($course_sections);

$staffCampus = ucfirst($_SESSION['staff_campus']); // current staff campus
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Schedule</title>

    <link rel="stylesheet" href="../assets/css/global.css?v=1">
    <link rel="stylesheet" href="../assets/css/modal.css?v=1">

</head>

<body>

    <div class="form_container">
        <a href="javascript:window.history.back();" class="back_btn">← Back</a>
        <h2>Add Schedule</h2>

        <form action="../php/action_add_schedule.php" method="POST">

            <label for="course_section">Course - Section</label>
            <select id="course_section" name="course_section" required>
                <option value="" disabled selected>Select Course - Section</option>
                <?php foreach ($course_sections as $cs) : ?>
                    <option value="<?php echo htmlspecialchars($cs); ?>"><?php echo htmlspecialchars($cs); ?></option>
                <?php endforeach; ?>
            </select>

            <label for="subject">Subject</label>
            <input type="text" id="subject" name="subject" placeholder="Subject" required>

            <label for="day">Day</label>
            <select id="day" name="day" required>
                <option value="" disabled selected>Select Day</option>
                <option value="Monday">Monday</option>
                <option value="Tuesday">Tuesday</option>
                <option value="Wednesday">Wednesday</option>
                <option value="Thursday">Thursday</option>
                <option value="Friday">Friday</option>
                <option value="Friday">Saturday</option>
                <option value="Friday">Sunday</option>
            </select>

            <label for="time_from">Time From</label>
            <input type="time" id="time_from" name="time_from" required>

            <label for="time_to">Time To</label>
            <input type="time" id="time_to" name="time_to" required>

            <label for="campus">Campus</label>
            <input type="text" id="campus" name="campus" value="<?php echo htmlspecialchars($staffCampus); ?>" readonly style="background:#e9e9e9; cursor:not-allowed;">

            <button type="submit">Add Schedule</button>
        </form>
    </div>

    <script>
        // Generate floating particles
        for (let i = 0; i < 35; i++) {
            const particle = document.createElement("div");
            particle.className = "particle";
            particle.style.left = Math.random() * 100 + "vw";
            particle.style.top = Math.random() * 100 + "vh";
            particle.style.animationDuration = (10 + Math.random() * 10) + "s";
            particle.style.opacity = Math.random();
            document.body.appendChild(particle);
        }
    </script>

</body>

</html>