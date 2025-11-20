<?php
session_start();

if (!isset($_SESSION['staff_logged_in'])) {
    header("Location: ../index.php");
    exit;
}

$staffCampus = ucfirst($_SESSION['staff_campus']); // get staff campus
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Student</title>

    <link rel="stylesheet" href="../assets/css/global.css?v=1">
    <link rel="stylesheet" href="../assets/css/modal.css?v=1">

</head>

<body>

    <div class="form_container">
        <a href="javascript:window.history.back();" class="back_btn">← Back</a>
        <h2>Add Student</h2>

        <form action="../php/action_add_student.php" method="POST">

            <label for="student_id">Student ID</label>
            <input type="text" id="student_id" name="student_id" placeholder="" required>

            <label for="firstname">First Name</label>
            <input type="text" id="firstname" name="firstname" placeholder="" required>

            <label for="middlename">Middle Name</label>
            <input type="text" id="middlename" name="middlename" placeholder="">

            <label for="lastname">Last Name</label>
            <input type="text" id="lastname" name="lastname" placeholder="" required>

            <label for="gender">Gender</label>
            <select id="gender" name="gender" required>
                <option value="" disabled selected>Gender</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
            </select>

            <label for="year_level">Year Level</label>
            <select id="year_level" name="year_level" required>
                <option value="" disabled selected>Year Level</option>
                <option value="1st Year">1st Year</option>
                <option value="2nd Year">2nd Year</option>
                <option value="3rd Year">3rd Year</option>
                <option value="4th Year">4th Year</option>
            </select>

            <label for="section">Course</label>
            <input type="text" id="course" name="course" placeholder="e.g. BSIT" required>

            <label for="section">Section</label>
            <input type="text" id="section" name="section" placeholder="e.g. 3H G1" required>

            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" placeholder="" required>

            <label for="contact">Contact Number</label>
            <input type="text" id="contact" name="contact" placeholder="" required>

            <label for="campus">Campus</label>
            <input type="text" id="campus" name="campus" value="<?php echo htmlspecialchars($staffCampus); ?>" readonly
                style="background:#e9e9e9; cursor:not-allowed;">

            <button type="submit">Add</button>
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