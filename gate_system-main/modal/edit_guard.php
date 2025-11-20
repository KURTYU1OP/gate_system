<?php
include("../config.php");
include("../firebaseRDB.php");

$db = new firebaseRDB($databaseURL);

// Get guard ID from GET
$id = isset($_GET['id']) ? $_GET['id'] : '';
if (!$id) {
    echo "<script>alert('No guard selected'); window.history.back();</script>";
    exit;
}

// Get guard data
$guardData = json_decode($db->retrieve("Guard/{$id}"), true);
if (!$guardData) {
    echo "<script>alert('Guard not found'); window.history.back();</script>";
    exit;
}

$campus = $guardData['campus'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Guard</title>
    <link rel="stylesheet" href="../assets/css/global.css?v=1">
    <link rel="stylesheet" href="../assets/css/modal.css?v=1">
</head>

<body>

    <div class="form_container">
        <a href="javascript:window.history.back();" class="back_btn">← Back</a>
        <h2>Edit Guard</h2>

        <form action="../php/action_edit_guard.php" method="POST">
            <input type="hidden" name="id" value="<?php echo $id; ?>">

            <label for="firstname">First Name</label>
            <input type="text" name="firstname" id="firstname" value="<?php echo htmlspecialchars($guardData['firstname']); ?>" required>

            <label for="middlename">Middle Name</label>
            <input type="text" name="middlename" id="middlename" value="<?php echo htmlspecialchars($guardData['middlename']); ?>">

            <label for="lastname">Last Name</label>
            <input type="text" name="lastname" id="lastname" value="<?php echo htmlspecialchars($guardData['lastname']); ?>" required>

            <label for="email">Email</label>
            <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($guardData['email']); ?>" required>

            <label for="password">Password</label>
            <input type="password" name="password" id="password" value="<?php echo htmlspecialchars($guardData['password']); ?>" required>

            <label for="campus">Campus</label>
            <input type="text" name="campus" id="campus" value="<?php echo htmlspecialchars($campus); ?>" readonly style="background:#e9e9e9; cursor:not-allowed;">

            <label for="gate_designation">Gate Designation</label>
            <input type="text" name="gate_designation" id="gate_designation" value="<?php echo htmlspecialchars($guardData['gate_designation']); ?>" required>

            <button type="submit">Update</button>
        </form>
    </div>

</body>

</html>