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
    <title>Add Guard</title>

    <link rel="stylesheet" href="../assets/css/global.css?v=1">
    <link rel="stylesheet" href="../assets/css/modal.css?v=1">

</head>

<body>

    <div class="form_container">
        <a href="javascript:window.history.back();" class="back_btn">← Back</a>
        <h2>Add Guard</h2>

        <form action="../php/action_add_guard.php" method="POST">

            <label for="firstname">First Name</label>
            <input type="text" id="firstname" name="firstname" placeholder="" required>

            <label for="middlename">Middle Name</label>
            <input type="text" id="middlename" name="middlename" placeholder="">

            <label for="lastname">Last Name</label>
            <input type="text" id="lastname" name="lastname" placeholder="" required>

            <label for="campus">Campus</label>
            <input type="text" id="campus" name="campus" value="<?php echo htmlspecialchars($staffCampus); ?>" readonly
                style="background:#e9e9e9; cursor:not-allowed;">

            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" placeholder="" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="" required>

            <label class="checkbox_label">
                <input type="checkbox" id="showPassword"> Show Password
            </label>

            <label for="gate_designation">Gate Designation</label>
            <input type="text" id="gate_designation" name="gate_designation" placeholder="e.g. Gate 1" required>

            <button type="submit">Add</button>
        </form>

    </div>

    <script>
        const passwordInput = document.getElementById('password');
        const showPasswordCheckbox = document.getElementById('showPassword');

        showPasswordCheckbox.addEventListener('change', function() {
            passwordInput.type = this.checked ? 'text' : 'password';
        });

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