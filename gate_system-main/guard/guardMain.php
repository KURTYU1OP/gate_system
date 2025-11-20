<?php
session_start();

if (!isset($_SESSION['guard_logged_in'])) {
    header("Location: ../index.php");
    exit;
}

$guardCampus = ucfirst($_SESSION['guard_campus']);
$guardName   = $_SESSION['guard_name'];
$guardGate   = $_SESSION['guard_gate'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guard Dashboard</title>
    <link rel="stylesheet" href="../assets/css/global.css?v=1">
    <link rel="stylesheet" href="../assets/css/guard.css?v=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <img src="../assets/images/BULSU WATERMARK.png" alt="BULSU Logo">
            <span class="sidebar-title">Gate System</span>
        </div>

        <div class="nav-menu">
            <div class="nav-link active" data-tab="students">
                <i class="fas fa-list"></i>
                <span class="nav-text">Students</span>
            </div>

            <div class="nav-link" data-tab="visitors">
                <i class="fas fa-user"></i>
                <span class="nav-text">Visitors</span>
            </div>

            <div class="nav-link" data-tab="vip">
                <i class="fas fa-user-tie"></i>
                <span class="nav-text">VIP</span>
            </div>

        </div>
    </div>

    <!-- Main -->
    <div class="main">
        <div class="header">
            <button class="menu-toggle" id="menuToggle">&#9776;</button>
            <h2 id="current-tab-title">Bulacan State University Gate System</h2>

            <div class="header-right">
                <!-- Guard Info -->
                <div class="" id="guardDesignation">
                    <span class="fas fa-school"></span>
                    <span><?= $guardCampus ?> Campus</span>
                    <span class="fas fa-door-open" style="margin-left:12px;"></span>
                    <span><?= $guardGate ?></span>
                </div>

                <!-- Profile -->
                <div class="profile-wrapper">
                    <button id="profileBtn" class="profile-btn">👤</button>
                    <div id="profileDropdown" class="profile-dropdown">
                        <div class="profile-item" style="padding:10px; border-bottom:1px solid #eee; font-weight:600; color:#A60212;">
                            <?= htmlspecialchars($guardName) ?>
                        </div>
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

        let currentTab = 'students';

        // Toggle sidebar
        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
            if (window.innerWidth <= 768) sidebar.classList.toggle('mobile-open');
        });

        // Load content via AJAX
        function loadContent(tab) {
            currentTab = tab;
            loadingSpinner.style.display = 'block';
            contentArea.innerHTML = '';
            contentArea.appendChild(loadingSpinner);

            const fileMap = {
                'students': 'log_management.php',
                'visitors': 'visitors_vip_management.php',
                'vip': 'vip_management.php'
            };
            let url = fileMap[tab] || `${tab}.php`;

            fetch(url)
                .then(res => res.ok ? res.text() : Promise.reject('Network error'))
                .then(html => {
                    loadingSpinner.style.display = 'none';
                    contentArea.innerHTML = html;

                    const scripts = contentArea.querySelectorAll('script');
                    scripts.forEach(script => {
                        const newScript = document.createElement('script');
                        script.src ? newScript.src = script.src : newScript.textContent = script.textContent;
                        document.body.appendChild(newScript);
                        document.body.removeChild(newScript);
                    });
                })
                .catch(err => {
                    loadingSpinner.style.display = 'none';
                    contentArea.innerHTML = `<div style="padding:20px;text-align:center;color:#A60212;">
                        <i class="fas fa-exclamation-triangle" style="font-size:48px;margin-bottom:10px;"></i>
                        <h3>Error Loading Content</h3>
                        <p>${err}</p>
                    </div>`;
                });
        }

        // Nav click
        navLinks.forEach(link => {
            link.addEventListener('click', () => {
                navLinks.forEach(l => l.classList.remove('active'));
                link.classList.add('active');

                const target = link.dataset.tab;
                tabTitle.textContent = link.querySelector('.nav-text').textContent;
                loadContent(target);

                if (window.innerWidth <= 768) sidebar.classList.remove('mobile-open');
            });
        });

        loadContent('students');

        // Profile dropdown
        profileBtn.addEventListener('click', () => {
            profileDropdown.style.display = profileDropdown.style.display === 'block' ? 'none' : 'block';
        });

        window.addEventListener('click', e => {
            if (!profileBtn.contains(e.target) && !profileDropdown.contains(e.target)) {
                profileDropdown.style.display = 'none';
            }
        });

        function logout() {
            window.location.href = "../index.php";
        }
    </script>
</body>

</html>