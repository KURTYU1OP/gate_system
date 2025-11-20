<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in (for admin)
if (!isset($_SESSION['admin_logged_in']) && !isset($_SESSION['staff_logged_in'])) {
    echo '<div style="padding:20px; text-align:center; color:#A60212;">
            <p>Session expired. Please <a href="../index.php">login again</a>.</p>
          </div>';
    exit;
}

include("../config.php");
include("../firebaseRDB.php");

$db = new firebaseRDB($databaseURL);

// Get campus from GET parameter or sessionStorage (passed via AJAX)
$selectedCampus = isset($_GET['campus']) ? $_GET['campus'] : 'Main';

// Retrieve all staff records
$Staff = json_decode($db->retrieve("Staff"), true);

// Filter staff by selected campus
$filteredStaff = [];
if (is_array($Staff) && count($Staff) > 0) {
    foreach ($Staff as $key => $row) {
        $staffCampus = $row['campus'] ?? '';

        // Only include staff from the selected campus
        if (strtolower($staffCampus) === strtolower($selectedCampus)) {
            $filteredStaff[$key] = $row;
        }
    }
}
?>

<style>
    /* Add Staff Card */
    #add_staff_modal {
        display: flex;
        align-items: center;
        gap: 20px;
        background: linear-gradient(135deg, #F5AB29, #A60212);
        border-radius: 15px;
        padding: 20px;
        cursor: pointer;
        transition: all 0.2s ease;
        max-width: 1200px;
        margin: auto;
        color: #fff;
    }

    #add_staff_modal:hover {
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        transform: translateY(-3px);
    }

    #add_staff_modal img {
        height: 100px;
        border-radius: 10px;
        border: 2px solid rgba(255, 255, 255, 0.5);
    }

    #add_staff_modal h1 {
        margin: 0 0 10px 0;
        font-size: 22px;
        color: #fff;
    }

    #add_staff_modal p {
        margin: 0;
        color: #fff;
        font-size: 14px;
    }

    /* Table Container */
    .staff_table_container {
        background: #fff;
        padding: 25px;
        margin: 30px auto 0;
        border-radius: 15px;
        max-width: 1200px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
    }

    .staff_table_container h1 {
        margin-bottom: 15px;
        font-size: 24px;
        color: #333;
    }

    .staff_table_container hr {
        margin-bottom: 20px;
        border: none;
        border-bottom: 2px solid #eee;
    }

    /* Staff Table */
    table.staff_list_table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
    }

    table.staff_list_table th,
    table.staff_list_table td {
        padding: 12px 15px;
        text-align: center;
    }

    table.staff_list_table th {
        background-color: #a60212;
        color: #fff;
        font-weight: 600;
        text-align: center;
    }

    table.staff_list_table tr:nth-child(even) {
        background-color: #fdf2f2;
    }

    table.staff_list_table tr:hover {
        background-color: #ffe5e5;
    }

    table.staff_list_table td a {
        margin-right: 8px;
        text-decoration: none;
        color: #a60212;
        font-weight: 500;
        padding: 5px 10px;
        border-radius: 12px;
        background: #ffe5e5;
        transition: all 0.2s ease;
        display: inline-block;
    }

    table.staff_list_table td a:hover {
        background: #a60212;
        color: #fff;
        text-decoration: none;
    }

    .campus-badge {
        display: inline-block;
        padding: 4px 10px;
        background: #A60212;
        color: #F5AB29;
        border-radius: 12px;
        font-weight: 600;
        font-size: 0.9em;
    }
</style>

<div id="add_staff_modal" onclick="openAddStaff()">
    <img src="../assets/images/icon staff.png" alt="Add Staff">
    <div>
        <h1>Add Staff</h1>
        <p>Oversaw and executed all CRUD operations for Guards, Students, and Schedules, ensuring accurate and up-to-date records while maintaining effective monitoring of campus activities and security.</p>
    </div>
</div>

<div class="staff_table_container">
    <h1>
        Staff List - <span class="campus-badge" id="currentCampus"><?php echo htmlspecialchars($selectedCampus); ?> Campus</span>
        <span style="font-size:0.7em; color:#666; font-weight:normal;">(<?php echo count($filteredStaff); ?> records)</span>
    </h1>
    <hr>
    <table class="staff_list_table" border="1">
        <thead>
            <tr>
                <th>Full Name</th>
                <th>Campus</th>
                <th>College</th>
                <th>Email</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (count($filteredStaff) > 0) {
                foreach ($filteredStaff as $key => $row) {
                    $fullName = trim(
                        ($row['firstname'] ?? '') . ' ' .
                            ($row['middlename'] ?? '') . ' ' .
                            ($row['lastname'] ?? '')
                    );
                    $campus = $row['campus'] ?? '';
                    $college = $row['college'] ?? '';
                    $email = $row['email'] ?? '';

                    echo "<tr>
                        <td>{$fullName}</td>
                        <td>{$campus}</td>
                        <td>{$college}</td>
                        <td>{$email}</td>
                        <td>
                            <a href='../modal/edit_staff.php?id={$key}'>Edit</a>
                            <a href='../php/view_staff.php?id={$key}'>View</a>
                            <a href='../php/action_delete_staff.php?id={$key}' onclick='return confirm(\"Delete this staff member?\")'>Delete</a>
                        </td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='5' style='text-align:center; padding:20px; color:#666;'>
                        <i class='fas fa-inbox' style='font-size:48px; margin-bottom:10px; display:block;'></i>
                        No staff members found for <strong>{$selectedCampus} Campus</strong>.
                      </td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<script>
    function openAddStaff() {
        // Get the campus from sessionStorage
        let campus = sessionStorage.getItem("selectedCampus") || "Main";
        window.location.href = `../modal/add_staff.php?campus=${encodeURIComponent(campus)}`;
    }
</script>