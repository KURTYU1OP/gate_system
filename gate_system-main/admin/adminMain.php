<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Side</title>

    <link rel="stylesheet" href="../assets/css/global.css?v=1">
    <link rel="stylesheet" href="../assets/css/admin.css?v=1">

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
            <div class="nav-link active" data-tab="dashboard">
                <img src="../assets/images/dashboard icon.png" alt="">
                <span class="nav-text">Dashboard</span>
            </div>
            <div class="nav-link" data-tab="logs">
                <img src="../assets/images/history icon.png" alt="">
                <span class="nav-text">Logs</span>
            </div>
            <div class="nav-link" data-tab="reports">
                <img src="../assets/images/reports icon.png" alt="">
                <span class="nav-text">Reports</span>
            </div>
            <div class="nav-link" data-tab="alerts">
                <img src="../assets/images/alert icon.png" alt="">
                <span class="nav-text">Alerts</span>
            </div>
            <div class="nav-link" data-tab="staff">
                <img src="../assets/images/user icon.png" alt="">
                <span class="nav-text">Staff Management</span>
            </div>
        </div>
    </div>

    <!-- Main -->
    <div class="main">
        <div class="header">
            <button class="menu-toggle" id="menuToggle">&#9776;</button>
            <h2 id="current-tab-title">Bulacan State University Gate System</h2>

            <div class="header-right">
                <!-- Campus Select -->
                <div class="campus-select-wrapper" style="display:flex; align-items:center; gap:8px;">
                    <label for="campusSelect" class="campus-label" style="display:flex; align-items:center; gap:5px; font-weight:500; color:var(--primary); cursor:pointer;">
                        <span class="campus-icon">🏫</span>
                        Campus:
                    </label>
                    <select id="campusSelect">
                        <option value="main">Main</option>
                        <option value="hagonoy">Hagonoy</option>
                        <option value="sarmiento">Sarmiento</option>
                        <option value="bustos">Bustos</option>
                        <option value="sanrafael">San Rafael</option>
                        <option value="meneses">Meneses</option>
                    </select>
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
                <!-- Content will be loaded here via AJAX -->
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
        const campusSelect = document.getElementById('campusSelect');
        const profileBtn = document.getElementById('profileBtn');
        const profileDropdown = document.getElementById('profileDropdown');
        const contentArea = document.getElementById('contentArea');
        const loadingSpinner = document.getElementById('loadingSpinner');

        let currentTab = 'dashboard';

        // Save campus selection
        function updateCampusStorage() {
            const selectedCampus = campusSelect.options[campusSelect.selectedIndex].text;
            sessionStorage.setItem("selectedCampus", selectedCampus);

            // Reload current tab content with new campus
            if (currentTab === 'staff') {
                loadContent(currentTab);
            }
        }

        // Initialize campus
        const savedCampus = sessionStorage.getItem("selectedCampus");
        if (savedCampus) {
            for (let i = 0; i < campusSelect.options.length; i++) {
                if (campusSelect.options[i].text === savedCampus) {
                    campusSelect.selectedIndex = i;
                    break;
                }
            }
        }

        campusSelect.addEventListener('change', updateCampusStorage);

        // Toggle sidebar
        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
            if (window.innerWidth <= 768) sidebar.classList.toggle('mobile-open');
        });

        // Load content via AJAX
        function loadContent(tab) {
            currentTab = tab;

            // Show loading spinner
            loadingSpinner.style.display = 'block';
            contentArea.innerHTML = '';
            contentArea.appendChild(loadingSpinner);

            // Map tab names to actual file names
            const fileMap = {
                'dashboard': 'dashboard.php',
                'logs': 'logs.php',
                'reports': 'reports.php',
                'alerts': 'alerts.php',
                'staff': 'staff_management.php'
            };

            // Prepare URL with campus parameter for staff
            let url = fileMap[tab] || `${tab}.php`;
            if (tab === 'staff') {
                const selectedCampus = campusSelect.options[campusSelect.selectedIndex].text;
                url += `?campus=${encodeURIComponent(selectedCampus)}`;
            }

            // Fetch content
            fetch(url)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.text();
                })
                .then(html => {
                    loadingSpinner.style.display = 'none';
                    contentArea.innerHTML = html;

                    // Execute any scripts in the loaded content
                    const scripts = contentArea.querySelectorAll('script');
                    scripts.forEach(script => {
                        const newScript = document.createElement('script');
                        if (script.src) {
                            newScript.src = script.src;
                        } else {
                            newScript.textContent = script.textContent;
                        }
                        document.body.appendChild(newScript);
                        document.body.removeChild(newScript);
                    });
                })
                .catch(error => {
                    loadingSpinner.style.display = 'none';
                    contentArea.innerHTML = `
                        <div style="padding: 20px; text-align: center; color: #a60212;">
                            <i class="fas fa-exclamation-triangle" style="font-size: 48px; margin-bottom: 10px;"></i>
                            <h3>Error Loading Content</h3>
                            <p>${error.message}</p>
                        </div>
                    `;
                });
        }

        // Navigation click handlers
        navLinks.forEach(link => {
            link.addEventListener('click', () => {
                navLinks.forEach(l => l.classList.remove('active'));
                link.classList.add('active');

                const target = link.dataset.tab;
                const tabText = link.querySelector('.nav-text').textContent;

                tabTitle.textContent = tabText;
                loadContent(target);

                if (window.innerWidth <= 768) sidebar.classList.remove('mobile-open');
            });
        });

        // Load dashboard on page load
        loadContent('dashboard');

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
    </script>
</body>

</html>