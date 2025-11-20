<?php
session_start();

if (!isset($_SESSION['staff_logged_in'])) {
    header("Location: ../index.php");
    exit;
}

$staffCampus = ucfirst($_SESSION['staff_campus']);
$staffName   = $_SESSION['staff_name'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BSU Gate System</title>
    <link rel="stylesheet" href="../assets/css/staff.css?v=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .campus-display {
            font-weight: 700;
            color: #F5AB29;
            background: #A60212;
            padding: 6px 12px;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 1.15rem;
            transition: transform 0.2s, box-shadow 0.2s;
            cursor: default;
            height: 40px;
        }

        .campus-display:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.4);
        }

        .loading-spinner {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            font-size: 18px;
            color: #A60212;
            gap: 10px;
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <img src="../assets/images/BULSU WATERMARK.png" alt="BULSU Logo">
            <span class="sidebar-title">Gate System</span>
        </div>
        <div class="nav-menu">
            <div class="nav-link" data-tab="student">
                <img src="../assets/images/student icon.png" alt="">
                <span class="nav-text">Student Management</span>
            </div>
            <div class="nav-link" data-tab="guard">
                <img src="../assets/images/icon guard.png" alt="" style="filter: invert(100%) brightness(100%) saturate(0);">
                <span class="nav-text">Guard Management</span>
            </div>
            <div class="nav-link" data-tab="schedule">
                <img src="../assets/images/schedule icon.png" alt="">
                <span class="nav-text">Schedule Management</span>
            </div>
        </div>
    </div>

    <!-- Main -->
    <div class="main">
        <div class="header">
            <button class="menu-toggle" id="menuToggle">&#9776;</button>
            <h2 id="current-tab-title">Bulacan State University Gate System</h2>
            <div class="header-right">
                <!-- Campus Display -->
                <div class="campus-display" style="font-weight:600; display:flex; align-items:center; gap:6px;">
                    <span class="fas fa-school"></span>
                    <span><?= $staffCampus ?></span>
                    <span> Campus</span>
                </div>

                <!-- Profile -->
                <div class="profile-wrapper">
                    <button id="profileBtn" class="profile-btn">👤</button>
                    <div id="profileDropdown" class="profile-dropdown">
                        <div class="profile-item" onclick="logout()">Logout</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="card" id="contentArea">
                <div class="loading-spinner" id="loadingSpinner" style="display:none;">
                    <i class="fas fa-spinner fa-spin"></i> Loading...
                </div>
            </div>
        </div>
    </div>

    <script>
        const sidebar = document.getElementById('sidebar');
        const toggleBtn = document.getElementById('menuToggle');
        const navLinks = document.querySelectorAll('.nav-link');
        const tabTitle = document.getElementById('current-tab-title');
        const profileBtn = document.getElementById('profileBtn');
        const profileDropdown = document.getElementById('profileDropdown');
        const contentArea = document.getElementById('contentArea');
        const loadingSpinner = document.getElementById('loadingSpinner');

        let currentTab = 'student';

        // Toggle sidebar
        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
            if (window.innerWidth <= 768) sidebar.classList.toggle('mobile-open');
        });

        // Load content via AJAX
        function loadContent(tab) {
            currentTab = tab;

            // Highlight active link
            navLinks.forEach(l => l.classList.remove('active'));
            document.querySelector(`.nav-link[data-tab="${tab}"]`)?.classList.add('active');

            // Update tab title
            const tabText = document.querySelector(`.nav-link[data-tab="${tab}"] .nav-text`)?.textContent;
            if (tabText) tabTitle.textContent = tabText;

            // Show loading spinner
            loadingSpinner.style.display = 'flex';
            contentArea.innerHTML = '';
            contentArea.appendChild(loadingSpinner);

            const fileMap = {
                'student': 'student_management.php',
                'guard': 'guard_management.php',
                'schedule': 'schedule_management.php'
            };

            const url = fileMap[tab] || `${tab}.php`;

            fetch(url)
                .then(res => res.ok ? res.text() : Promise.reject('Network response was not ok'))
                .then(html => {
                    loadingSpinner.style.display = 'none';
                    contentArea.innerHTML = html;

                    // Execute scripts in loaded content
                    const scripts = contentArea.querySelectorAll('script');
                    scripts.forEach(script => {
                        const newScript = document.createElement('script');
                        if (script.src) newScript.src = script.src;
                        else newScript.textContent = script.textContent;
                        document.body.appendChild(newScript);
                        document.body.removeChild(newScript);
                    });
                })
                .catch(err => {
                    loadingSpinner.style.display = 'none';
                    contentArea.innerHTML = `<div style="padding:20px; text-align:center; color:#A60212;">
                        <i class="fas fa-exclamation-triangle" style="font-size:48px; margin-bottom:10px;"></i>
                        <h3>Error Loading Content</h3>
                        <p>${err}</p>
                    </div>`;
                });

            // Update URL hash
            window.location.hash = tab;
        }

        // Nav link clicks
        navLinks.forEach(link => {
            link.addEventListener('click', () => {
                const target = link.dataset.tab;
                loadContent(target);
                if (window.innerWidth <= 768) sidebar.classList.remove('mobile-open');
            });
        });

        // Profile dropdown
        profileBtn.addEventListener('click', () => {
            profileDropdown.style.display = profileDropdown.style.display === 'block' ? 'none' : 'block';
        });

        window.addEventListener('click', (e) => {
            if (!profileBtn.contains(e.target) && !profileDropdown.contains(e.target)) {
                profileDropdown.style.display = 'none';
            }
        });

        function logout() {
            window.location.href = "../index.php";
        }

        // Load tab based on URL hash
        function loadTabFromHash() {
            const hash = window.location.hash.replace('#', '');
            loadContent(hash || 'student');
        }

        window.addEventListener('hashchange', loadTabFromHash);

        // Initial load
        loadTabFromHash();
    </script>
</body>

</html>