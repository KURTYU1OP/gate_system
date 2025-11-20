<?php
// Sample data
$studentInOut = 487;
$totalViolations = 15;
$activeGuards = 12;

$recentAccess = [
    "Student #20245523 entered Main Campus – 8:12 AM",
    "Guard #G-112 logged in – 7:59 AM",
    "Student #20245524 exited Main Campus – 7:50 AM",
    "Suspicious alert triggered at Gate 3 – 7:45 AM",
    "Student #20245525 entered Main Campus – 7:40 AM",
    "Guard #G-113 logged in – 7:30 AM",
    "Student #20245526 exited Main Campus – 7:25 AM",
    "Student #20245527 entered Main Campus – 7:20 AM",
    "Guard #G-114 logged in – 7:15 AM",
    "Student #20245528 entered Main Campus – 7:10 AM"
];
?>

<div class="dashboard-container">
    <h2>Dashboard Overview</h2>

    <!-- Stats Cards -->
    <div class="stats-cards">
        <div class="card card-orange">
            <h3>Student In/Out</h3>
            <p><?= $studentInOut ?></p>
        </div>
        <div class="card card-red">
            <h3>Total Violations</h3>
            <p><?= $totalViolations ?></p>
        </div>
        <div class="card card-orange">
            <h3>Active Guards</h3>
            <p><?= $activeGuards ?></p>
        </div>
    </div>

    <!-- Charts & Recent Section -->
    <div class="charts-section">
        <div class="chart-card">
            <h3>Daily/Weekly Entry</h3>
            <canvas id="entryChart"></canvas>
        </div>

        <div class="recent-activity">
            <h3>Recent Access (Last 10)</h3>
            <ul>
                <?php foreach ($recentAccess as $activity): ?>
                    <li><?= $activity ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</div>

<style>
    .dashboard-container {
        max-width: 1200px;
        margin: 20px auto;
        font-family: 'Segoe UI', sans-serif;
        color: #333;
    }

    .dashboard-container h2 {
        margin-bottom: 25px;
        font-size: 28px;
        color: #A60212;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    /* Stats Cards */
    .stats-cards {
        display: flex;
        gap: 20px;
        flex-wrap: wrap;
        margin-bottom: 35px;
    }

    .stats-cards .card {
        flex: 1;
        min-width: 220px;
        border-radius: 15px;
        padding: 25px 20px;
        text-align: center;
        color: #fff;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
    }

    .stats-cards .card:hover {
        transform: translateY(-7px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
    }

    .stats-cards .card h3 {
        margin-bottom: 15px;
        font-size: 18px;
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    .stats-cards .card p {
        font-size: 32px;
        font-weight: bold;
        margin: 0;
    }

    /* Card Colors */
    .card-orange {
        background: linear-gradient(135deg, #F5AB29, #A60212);
    }

    .card-red {
        background: #A60212;
    }

    /* Charts & Recent Section */
    .charts-section {
        display: flex;
        gap: 20px;
        flex-wrap: wrap;
    }

    .chart-card,
    .recent-activity {
        background: #fff;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
    }

    .chart-card {
        flex: 2;
        min-width: 400px;
    }

    .recent-activity {
        flex: 1;
        min-width: 250px;
    }

    .recent-activity ul {
        margin-top: 15px;
        padding-left: 20px;
        line-height: 1.8;
    }

    .recent-activity li {
        padding: 8px 0;
        border-bottom: 1px solid #eee;
        font-size: 14px;
        color: #555;
    }

    .recent-activity li:last-child {
        border-bottom: none;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('entryChart').getContext('2d');
    const gradient = ctx.createLinearGradient(0, 0, 0, 300);
    gradient.addColorStop(0, 'rgba(245,171,41,0.4)');
    gradient.addColorStop(1, 'rgba(166,2,18,0)');

    const entryChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
            datasets: [{
                label: 'Entries',
                data: [120, 150, 170, 130, 180, 200, 160],
                backgroundColor: gradient,
                borderColor: '#F5AB29',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#F5AB29',
                pointRadius: 6
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    mode: 'index',
                    intersect: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
</script>