<?php
$campus = isset($_GET['campus']) ? $_GET['campus'] : 'Main';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Staff</title>

    <link rel="stylesheet" href="../assets/css/global.css?v=1">
    <link rel="stylesheet" href="../assets/css/modal.css?v=1">

</head>

<body>

    <div class="form_container">
        <a href="javascript:window.history.back();" class="back_btn">← Back</a>
        <h2>Add Staff</h2>

        <form action="../php/action_add_staff.php" method="POST">

            <input type="text" name="firstname" placeholder="First Name" required>
            <input type="text" name="middlename" placeholder="Middle Name">
            <input type="text" name="lastname" placeholder="Last Name" required>

            <input type="text" name="college" placeholder="College" required>

            <input type="email" name="email" placeholder="Email Address" required>
            <input type="password" name="password" placeholder="Password" required>

            <input type="text" name="campus" value="<?php echo htmlspecialchars($campus); ?>" readonly style="background:#e9e9e9; cursor:not-allowed;">


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