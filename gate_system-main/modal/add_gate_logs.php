<?php
session_start();
include("../config.php");
include("../firebaseRDB.php");

if (!isset($_SESSION['staff_logged_in'])) {
    header("Location: ../index.php");
    exit;
}

date_default_timezone_set('Asia/Manila'); // Manila timezone

$db = new firebaseRDB($databaseURL);
$students = json_decode($db->retrieve("Student"), true);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Gate Log</title>
    <link rel="stylesheet" href="../assets/css/global.css?v=1">
    <link rel="stylesheet" href="../assets/css/modal.css?v=1">
</head>

<body>

    <div class="form_container">
        <a href="javascript:window.history.back();" class="back_btn">← Back</a>
        <h2>Add Gate Log</h2>

        <form action="../php/action_add_gate_logs.php" method="POST">

            <label for="student_id">Student ID</label>
            <select id="student_id" name="student_id" required onchange="fillStudentData()">
                <option value="">Select Student</option>
                <?php
                if ($students) {
                    foreach ($students as $id => $student) {
                        echo '<option value="' . $id . '" data-name="' . $student['full_name'] . '" data-role="' . $student['role'] . '">' . $id . '</option>';
                    }
                }
                ?>
            </select>

            <input type="hidden" id="student_name" name="student_name">
            <input type="hidden" id="role" name="role">

            <label for="gate">Gate Entered</label>
            <input type="text" id="gate" name="gate" placeholder="e.g. Main Gate" required>

            <label for="datetime">Date & Time</label>
            <input type="datetime-local" id="datetime" name="datetime" required value="<?php echo date('Y-m-d\TH:i'); ?>">

            <label for="action">Action</label>
            <select id="action" name="action" required>
                <option value="IN">IN</option>
                <option value="OUT">OUT</option>
            </select>

            <label for="violation">Violation</label>
            <input type="text" id="violation" name="violation" placeholder="Optional">

            <button type="submit">Add</button>
        </form>
    </div>

    <script>
        function fillStudentData() {
            const select = document.getElementById('student_id');
            const selected = select.options[select.selectedIndex];
            document.getElementById('student_name').value = selected.getAttribute('data-name');
            document.getElementById('role').value = selected.getAttribute('data-role');
        }
    </script>

</body>

</html>