<?php
include("../config.php");
include("../firebaseRDB.php");

$db = new firebaseRDB($databaseURL);

// Get staff ID from GET
$id = isset($_GET['id']) ? $_GET['id'] : '';
if (!$id) {
    echo "<script>alert('No staff selected'); window.history.back();</script>";
    exit;
}

// Get staff data
$staffData = json_decode($db->retrieve("Staff/{$id}"), true);
if (!$staffData) {
    echo "<script>alert('Staff not found'); window.history.back();</script>";
    exit;
}

$campus = $staffData['campus'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Staff</title>

    <link rel="stylesheet" href="../assets/css/global.css?v=1">
    <link rel="stylesheet" href="../assets/css/modal.css?v=1">
</head>

<body>

    <div class="form_container">
        <a href="javascript:window.history.back();" class="back_btn">← Back</a>
        <h2>Edit Staff</h2>

        <form action="../php/action_edit_staff.php" method="POST">
            <input type="hidden" name="id" value="<?php echo $id; ?>">

            <input type="text" name="firstname" placeholder="First Name" value="<?php echo htmlspecialchars($staffData['firstname']); ?>" required>
            <input type="text" name="middlename" placeholder="Middle Name" value="<?php echo htmlspecialchars($staffData['middlename']); ?>">
            <input type="text" name="lastname" placeholder="Last Name" value="<?php echo htmlspecialchars($staffData['lastname']); ?>" required>

            <input type="text" name="college" placeholder="College" value="<?php echo htmlspecialchars($staffData['college']); ?>" required>

            <input type="email" name="email" placeholder="Email Address" value="<?php echo htmlspecialchars($staffData['email']); ?>" required>
            <input type="password" name="password" placeholder="Password" value="<?php echo htmlspecialchars($staffData['password']); ?>" required>

            <input type="text" name="campus" value="<?php echo htmlspecialchars($campus); ?>" readonly style="background:#e9e9e9; cursor:not-allowed;">

            <button type="submit">Update</button>
        </form>
    </div>

</body>

</html>