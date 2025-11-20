<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (!isset($_SESSION['staff_logged_in'])) {
    echo '<div style="padding:20px; text-align:center; color:#A60212;">
            <p>Session expired. Please <a href="../index.php">login again</a>.</p>
          </div>';
    exit;
}

include("../config.php");
include("../firebaseRDB.php");

$db = new firebaseRDB($databaseURL);

$staffCampus = ucfirst($_SESSION['staff_campus']);
$search = isset($_GET['search_schedule']) ? $_GET['search_schedule'] : '';

$schedules = json_decode($db->retrieve("Schedule"), true);
$filteredSchedules = [];

if ($schedules) {
    foreach ($schedules as $id => $schedule) {
        if (strtolower($schedule['campus']) === strtolower($staffCampus)) {
            if (
                $search === '' ||
                stripos($schedule['course'], $search) !== false ||
                stripos($schedule['section'], $search) !== false ||
                stripos($schedule['subject'], $search) !== false ||
                stripos($schedule['day'], $search) !== false
            ) {
                $filteredSchedules[$id] = $schedule;
            }
        }
    }
}
?>

<div class="management-header" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:15px;">
    <a href="../modal/add_schedule.php" class="btn-add" style="padding:8px 12px; background:#A60212; color:#fff; border-radius:4px; text-decoration:none;">+ Add Schedule</a>

    <form id="searchScheduleForm" style="display:flex; gap:8px;">
        <input type="text" id="searchScheduleInput" name="search_schedule" placeholder="Search by course, section, subject..." value="<?= htmlspecialchars($search) ?>" style="padding:6px;">
        <button type="submit" style="padding:6px 10px;">Search</button>
        <?php if ($search): ?>
            <button type="button" onclick="clearScheduleSearch()" style="padding:6px 10px; background:#dc3545; color:#fff; border:none; border-radius:4px; cursor:pointer;">Clear</button>
        <?php endif; ?>
    </form>
</div>

<div class="schedule_table_container">
    <h1>Schedule List - <span id="currentCampus"><?= htmlspecialchars($staffCampus) ?> Campus</span></h1>
    <hr>
    <table class="schedule_list_table" border="1">
        <thead>
            <tr>
                <th>Course</th>
                <th>Section</th>
                <th>Subject</th>
                <th>Day</th>
                <th>Time From</th>
                <th>Time To</th>
                <th>Campus</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($filteredSchedules): ?>
                <?php foreach ($filteredSchedules as $id => $schedule): ?>
                    <tr>
                        <td><?= htmlspecialchars($schedule['course'] ?? 'N/A') ?></td>
                        <td><?= htmlspecialchars($schedule['section'] ?? 'N/A') ?></td>
                        <td><?= htmlspecialchars($schedule['subject'] ?? 'N/A') ?></td>
                        <td><?= htmlspecialchars($schedule['day'] ?? 'N/A') ?></td>
                        <td><?= htmlspecialchars($schedule['time_from'] ?? 'N/A') ?></td>
                        <td><?= htmlspecialchars($schedule['time_to'] ?? 'N/A') ?></td>
                        <td><?= htmlspecialchars($schedule['campus'] ?? 'N/A') ?></td>
                        <td>
                            <a href="../modal/edit_schedule.php?id=<?= $id ?>">Edit</a>
                            <a href="../php/action_delete_schedule.php?id=<?= $id ?>" onclick="return confirm('Delete this schedule?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8" style="text-align:center; padding:20px; color:#666;">
                        <i class="fas fa-calendar-times" style="font-size:48px; margin-bottom:10px; display:block;"></i>
                        No schedule records found for <strong><?= htmlspecialchars($staffCampus) ?> Campus</strong>.
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<style>
    /* Schedule Table Container */
    .schedule_table_container {
        background: #fff;
        padding: 25px;
        margin: 30px auto 0;
        border-radius: 15px;
        max-width: 1200px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
    }

    .schedule_table_container h1 {
        margin-bottom: 15px;
        font-size: 24px;
        color: #333;
    }

    .schedule_table_container hr {
        margin-bottom: 20px;
        border: none;
        border-bottom: 2px solid #eee;
    }

    /* Schedule Table */
    table.schedule_list_table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
    }

    table.schedule_list_table th,
    table.schedule_list_table td {
        padding: 12px 15px;
        text-align: center;
    }

    table.schedule_list_table th {
        background-color: #a60212;
        color: #fff;
        font-weight: 600;
        text-align: center;
    }

    table.schedule_list_table tr:nth-child(even) {
        background-color: #fdf2f2;
    }

    table.schedule_list_table tr:hover {
        background-color: #ffe5e5;
    }

    table.schedule_list_table td a {
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

    table.schedule_list_table td a:hover {
        background: #a60212;
        color: #fff;
    }

    #searchScheduleInput {
        width: 300px;
        padding: 8px 12px;
        border: 2px solid #a60212;
        border-radius: 8px;
        outline: none;
        font-size: 14px;
        transition: all 0.2s ease;
    }

    #searchScheduleInput:focus {
        border-color: #f5ab29;
        box-shadow: 0 0 5px rgba(245, 171, 41, 0.5);
    }

    #searchScheduleForm button[type="submit"] {
        padding: 8px 14px;
        background-color: #f5ab29;
        border: none;
        border-radius: 8px;
        color: #fff;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    #searchScheduleForm button[type="submit"]:hover {
        background-color: #d18b1f;
    }

    button[type="submit"] {
        background-color: #F5AB29;
        border: none;
        color: white;
        font-weight: 500;
    }

    .management-header .btn-add {
        font-weight: 600;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .management-header .btn-add:hover {
        background: #8a0210 !important;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }
</style>

<script>
    // Handle search form submission with AJAX
    document.getElementById('searchScheduleForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const searchValue = document.getElementById('searchScheduleInput').value;

        // Show loading
        const contentArea = document.getElementById('contentArea');
        if (contentArea) {
            const loadingSpinner = document.getElementById('loadingSpinner');
            if (loadingSpinner) {
                loadingSpinner.style.display = 'flex';
                contentArea.innerHTML = '';
                contentArea.appendChild(loadingSpinner);
            }
        }

        // Reload content with search parameter
        fetch(`schedule_management.php?search_schedule=${encodeURIComponent(searchValue)}`)
            .then(response => response.text())
            .then(html => {
                if (contentArea) {
                    contentArea.innerHTML = html;

                    // Re-execute scripts
                    const scripts = contentArea.querySelectorAll('script');
                    scripts.forEach(script => {
                        const newScript = document.createElement('script');
                        newScript.textContent = script.textContent;
                        document.body.appendChild(newScript);
                        document.body.removeChild(newScript);
                    });
                }
            })
            .catch(error => {
                console.error('Search error:', error);
                if (contentArea) {
                    contentArea.innerHTML = `
                        <div style="padding: 20px; text-align: center; color: #A60212;">
                            <i class="fas fa-exclamation-triangle" style="font-size: 48px; margin-bottom: 10px;"></i>
                            <h3>Error Loading Content</h3>
                            <p>${error.message}</p>
                        </div>
                    `;
                }
            });
    });

    function clearScheduleSearch() {
        document.getElementById('searchScheduleInput').value = '';
        document.getElementById('searchScheduleForm').dispatchEvent(new Event('submit'));
    }
</script>