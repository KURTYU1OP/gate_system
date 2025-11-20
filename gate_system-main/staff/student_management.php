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

// Get search query if any
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Retrieve students from Firebase filtered by campus
$students = json_decode($db->retrieve("Student"), true);

$filteredStudents = [];
if ($students) {
    foreach ($students as $id => $student) {
        // Match campus
        if (strtolower($student['campus']) !== strtolower($staffCampus)) continue;

        // Filter by search query (name or ID)
        if ($search) {
            $fullName = strtolower($student['firstname'] . ' ' . $student['middlename'] . ' ' . $student['lastname']);
            if (strpos($fullName, strtolower($search)) === false && strpos(strtolower($student['id_no']), strtolower($search)) === false) {
                continue;
            }
        }

        $student['id'] = $id; // keep firebase key for actions
        $filteredStudents[] = $student;
    }
}
?>

<div class="student-management-header" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:15px;">
    <a href="../modal/add_student.php" class="btn" style="padding:8px 12px; background:#A60212; color:#fff; border-radius:4px; text-decoration:none;">+ Add Student</a>

    <form id="searchForm" style="display:flex; gap:8px;">
        <input type="text" id="searchInput" name="search" placeholder="Search student here...." value="<?= htmlspecialchars($search) ?>" style="padding:6px;">
        <button type="submit" style="padding:6px 10px;">Search</button>
        <?php if ($search): ?>
            <button type="button" onclick="clearSearch()" style="padding:6px 10px; background:#dc3545; color:#fff; border:none; border-radius:4px; cursor:pointer;">Clear</button>
        <?php endif; ?>
    </form>
</div>

<div class="student_table_container">
    <h1>Student List - <span id="currentCampus"><?= htmlspecialchars($staffCampus) ?> Campus</span></h1>
    <hr>
    <table class="student_list_table" border="1">
        <thead>
            <tr>
                <th>Full Name</th>
                <th>Course</th>
                <th>Section</th>
                <th>Email</th>
                <th>Campus</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($filteredStudents): ?>
                <?php foreach ($filteredStudents as $student): ?>
                    <?php
                    $fullName = trim($student['firstname'] . ' ' . $student['middlename'] . ' ' . $student['lastname']);
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($fullName) ?></td>
                        <td><?= htmlspecialchars($student['course']) ?></td>
                        <td><?= htmlspecialchars($student['section']) ?></td>
                        <td><?= htmlspecialchars($student['email']) ?></td>
                        <td><?= htmlspecialchars($student['campus']) ?></td>
                        <td>
                            <a href="../modal/edit_student.php?id=<?= $student['id'] ?>">Edit</a>
                            <a href="../php/action_delete_student.php?id=<?= $student['id'] ?>">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" style="text-align:center;">No students found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<style>
    /* Student Table Container */
    .student_table_container {
        background: #fff;
        padding: 25px;
        margin: 30px auto 0;
        border-radius: 15px;
        max-width: 1200px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
    }

    .student_table_container h1 {
        margin-bottom: 15px;
        font-size: 24px;
        color: #333;
    }

    .student_table_container hr {
        margin-bottom: 20px;
        border: none;
        border-bottom: 2px solid #eee;
    }

    /* Student Table */
    table.student_list_table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
    }

    table.student_list_table th,
    table.student_list_table td {
        padding: 12px 15px;
        text-align: center;
    }

    table.student_list_table th {
        background-color: #a60212;
        color: #fff;
        font-weight: 600;
        text-align: center;
    }

    table.student_list_table tr:nth-child(even) {
        background-color: #fdf2f2;
    }

    table.student_list_table tr:hover {
        background-color: #ffe5e5;
    }

    table.student_list_table td a {
        margin-right: 8px;
        text-decoration: none;
        color: #a60212;
        font-weight: 500;
        padding: 5px 10px;
        border-radius: 12px;
        background: #ffe5e5;
        transition: all 0.2s ease;
    }

    table.student_list_table td a:hover {
        background: #a60212;
        color: #fff;
    }

    #searchInput {
        width: 300px;
        padding: 8px 12px;
        border: 2px solid #a60212;
        border-radius: 8px;
        outline: none;
        font-size: 14px;
        transition: all 0.2s ease;
    }

    #searchInput:focus {
        border-color: #f5ab29;
        box-shadow: 0 0 5px rgba(245, 171, 41, 0.5);
    }

    #searchForm button[type="submit"] {
        padding: 8px 14px;
        background-color: #f5ab29;
        border: none;
        border-radius: 8px;
        color: #fff;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    #searchForm button[type="submit"]:hover {
        background-color: #d18b1f;
    }

    button[type="submit"] {
        background-color: #F5AB29;
        border: none;
        color: white;
        font-weight: 500;
    }
</style>


<script>
    // Handle search form submission with AJAX
    document.getElementById('searchForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const searchValue = document.getElementById('searchInput').value;

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
        fetch(`student_management.php?search=${encodeURIComponent(searchValue)}`)
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
            });
    });

    function clearSearch() {
        document.getElementById('searchInput').value = '';
        document.getElementById('searchForm').dispatchEvent(new Event('submit'));
    }
</script>