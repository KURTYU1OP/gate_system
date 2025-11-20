<?php
session_start();

if (!isset($_SESSION['staff_logged_in'])) {
    header("Location: ../index.php");
    exit;
}

include("../config.php");
include("../firebaseRDB.php");

$db = new firebaseRDB($databaseURL);

// Get student ID from GET
$id = isset($_GET['id']) ? $_GET['id'] : '';
if (!$id) {
    echo "<script>alert('No student selected'); window.history.back();</script>";
    exit;
}

// Get student data
$studentData = json_decode($db->retrieve("Student/{$id}"), true);
if (!$studentData) {
    echo "<script>alert('Student not found'); window.history.back();</script>";
    exit;
}

$staffCampus = ucfirst($_SESSION['staff_campus']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student</title>
    <link rel="stylesheet" href="../assets/css/global.css?v=1">
    <link rel="stylesheet" href="../assets/css/modal.css?v=1">
</head>

<body>

    <div class="form_container">
        <a href="javascript:window.history.back();" class="back_btn">← Back</a>
        <h2>Edit Student</h2>

        <form action="../php/action_edit_student.php" method="POST">
            <input type="hidden" name="id" value="<?php echo $id; ?>">

            <label for="student_id">Student ID</label>
            <input type="text" id="student_id" name="student_id" value="<?php echo htmlspecialchars($studentData['student_id']); ?>" required>

            <label for="firstname">First Name</label>
            <input type="text" id="firstname" name="firstname" value="<?php echo htmlspecialchars($studentData['firstname']); ?>" required>

            <label for="middlename">Middle Name</label>
            <input type="text" id="middlename" name="middlename" value="<?php echo htmlspecialchars($studentData['middlename']); ?>">

            <label for="lastname">Last Name</label>
            <input type="text" id="lastname" name="lastname" value="<?php echo htmlspecialchars($studentData['lastname']); ?>" required>

            <label for="gender">Gender</label>
            <select id="gender" name="gender" required>
                <option value="Male" <?php if (($studentData['gender'] ?? '') === 'Male') echo 'selected'; ?>>Male</option>
                <option value="Female" <?php if (($studentData['gender'] ?? '') === 'Female') echo 'selected'; ?>>Female</option>
            </select>

            <label for="year_level">Year Level</label>
            <select id="year_level" name="year_level" required>
                <?php
                $levels = ['1st Year', '2nd Year', '3rd Year', '4th Year'];
                foreach ($levels as $level) {
                    $selected = ($studentData['year_level'] ?? '') === $level ? 'selected' : '';
                    echo "<option value=\"$level\" $selected>$level</option>";
                }
                ?>
            </select>

            <label for="course">Course</label>
            <input type="text" id="course" name="course" value="<?php echo htmlspecialchars($studentData['course']); ?>" required>

            <label for="section">Section</label>
            <input type="text" id="section" name="section" value="<?php echo htmlspecialchars($studentData['section']); ?>" required>

            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($studentData['email']); ?>" required>

            <label for="contact">Contact Number</label>
            <input type="text" id="contact" name="contact" value="<?php echo htmlspecialchars($studentData['contact']); ?>" required>

            <label for="campus">Campus</label>
            <input type="text" id="campus" name="campus" value="<?php echo htmlspecialchars($staffCampus); ?>" readonly style="background:#e9e9e9; cursor:not-allowed;">

            <button type="submit">Update</button>
        </form>
    </div>

</body>

</html>