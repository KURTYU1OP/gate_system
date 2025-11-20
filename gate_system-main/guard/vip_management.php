<?php
session_start();
if (!isset($_SESSION['guard_logged_in'])) {
    header("Location: ../index.php");
    exit;
}
?>
<style>
.vip-container {
    max-width: 1000px;
    margin: 0 auto;
}

.entry-form {
    background: white;
    border-radius: 12px;
    padding: 30px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    margin-bottom: 30px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    font-weight: 600;
    color: #333;
    margin-bottom: 8px;
    font-size: 14px;
    text-transform: uppercase;
}

.form-group input,
.form-group textarea {
    width: 100%;
    padding: 14px 16px;
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    font-size: 16px;
    background: #f8f9fa;
    box-sizing: border-box;
    transition: all 0.3s ease;
    font-family: inherit;
}

.form-group textarea {
    min-height: 100px;
    resize: vertical;
}

.form-group input:focus,
.form-group textarea:focus {
    outline: none;
    border-color: #A60212;
    background: white;
    box-shadow: 0 0 0 3px rgba(166, 2, 18, 0.1);
}

.submit-btn {
    width: 100%;
    padding: 16px;
    background: linear-gradient(135deg, #A60212 0%, #8B0010 100%);
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    font-weight: 600;
    text-transform: uppercase;
    cursor: pointer;
    transition: all 0.3s ease;
}

.submit-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(166, 2, 18, 0.4);
}

.response-card {
    display: none;
    border-radius: 12px;
    padding: 25px;
    margin-bottom: 20px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.response-card.success {
    background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
    border-left: 5px solid #28a745;
}

.response-card.error {
    background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
    border-left: 5px solid #dc3545;
}

.response-header {
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 20px;
    font-weight: 700;
    margin-bottom: 15px;
}

.response-card.success .response-header {
    color: #155724;
}

.response-card.error .response-header {
    color: #721c24;
}

.student-details {
    margin-top: 15px;
    padding-top: 15px;
    border-top: 2px solid rgba(0, 0, 0, 0.1);
}

.detail-row {
    display: flex;
    justify-content: space-between;
    padding: 8px 0;
}

.detail-label {
    font-weight: 600;
    color: #555;
}

.detail-value {
    font-weight: 500;
    color: #333;
}

.table-card {
    background: white;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.table-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 2px solid #e0e0e0;
}

.table-header strong {
    font-size: 18px;
    color: #333;
}

.refresh-btn {
    padding: 8px 16px;
    background: #A60212;
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px;
    transition: all 0.3s ease;
}

.refresh-btn:hover {
    background: #8B0010;
}

.visitor-table {
    width: 100%;
    border-collapse: collapse;
}

.visitor-table thead {
    background: #f8f9fa;
}

.visitor-table th {
    padding: 12px;
    text-align: left;
    font-weight: 600;
    color: #333;
    border-bottom: 2px solid #e0e0e0;
}

.visitor-table td {
    padding: 12px;
    border-bottom: 1px solid #f0f0f0;
}

.visitor-table tbody tr:hover {
    background: #f8f9fa;
}

.logout-btn {
    padding: 6px 12px;
    background: #dc3545;
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 13px;
    transition: all 0.3s ease;
}

.logout-btn:hover {
    background: #c82333;
}
</style>

<div class="vip-container">
    <form id="vipForm" class="entry-form">
        <div class="form-group">
            <label for="name">VIP Name</label>
            <input type="text" id="name" name="name" placeholder="Full name" required>
        </div>
        <div class="form-group">
            <label for="reason">Reason for Entry</label>
            <textarea id="reason" name="reason" placeholder="Reason" required></textarea>
        </div>
        <button type="submit" class="submit-btn">
            <i class="fas fa-check-circle"></i> Submit VIP Entry
        </button>
    </form>

    <div id="responseCard" class="response-card"></div>

    <div class="table-card">
        <div class="table-header">
            <strong>Active VIPs</strong>
            <button id="refreshList" class="refresh-btn">
                <i class="fas fa-sync"></i> Refresh
            </button>
        </div>
        <table class="visitor-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Reason</th>
                    <th>Time In</th>
                    <th>Gate In</th>
                    <th>Guard In</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="vipRows">
                <tr>
                    <td colspan="6" style="text-align:center; color:#666;">Loading...</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script>
document.getElementById('vipForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const responseCard = document.getElementById('responseCard');

    responseCard.className = 'response-card';
    responseCard.style.display = 'block';
    responseCard.innerHTML = '<div style="text-align:center;"><i class="fas fa-spinner fa-spin"></i> Processing...</div>';

    fetch('../php/action_add__vip_entries.php', { method: 'POST', body: formData })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                showSuccessMessage(data);
                if (data.action === 'time_in') {
                    this.reset();
                }
                loadActiveVIPs();
            } else {
                showErrorMessage(data.message || 'Request failed.');
            }
        })
        .catch(() => showErrorMessage('Connection error. Please try again.'));
});

function showSuccessMessage(data) {
    const responseCard = document.getElementById('responseCard');
    const isTimeIn = data.action === 'time_in';

    responseCard.className = 'response-card success';
    responseCard.style.display = 'block';
    responseCard.innerHTML = `
        <div class="response-header">
            <i class="fas fa-check-circle"></i>
            <span>${isTimeIn ? 'VIP TIME IN RECORDED' : 'VIP TIME OUT RECORDED'}</span>
        </div>
        <div class="student-details">
            <div class="detail-row">
                <span class="detail-label">Name:</span>
                <span class="detail-value">${data.name}</span>
            </div>
            ${isTimeIn ? `
            <div class="detail-row">
                <span class="detail-label">Reason:</span>
                <span class="detail-value">${data.reason}</span>
            </div>
            ` : ''}
            <div class="detail-row">
                <span class="detail-label">${isTimeIn ? 'Time In:' : 'Time Out:'}</span>
                <span class="detail-value">${data.time}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Gate In:</span>
                <span class="detail-value">${data.gate_in || '-'}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Guard In:</span>
                <span class="detail-value">${data.guard_in || '-'}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Gate Out:</span>
                <span class="detail-value">${data.gate_out || '-'}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Guard Out:</span>
                <span class="detail-value">${data.guard_out || '-'}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Campus:</span>
                <span class="detail-value">${data.campus || '-'}</span>
            </div>
        </div>
    `;
    setTimeout(() => { responseCard.style.display = 'none'; }, 8000);
}

function showErrorMessage(message) {
    const responseCard = document.getElementById('responseCard');
    responseCard.className = 'response-card error';
    responseCard.style.display = 'block';
    responseCard.innerHTML = `
        <div class="response-header">
            <i class="fas fa-times-circle"></i>
            <span>REQUEST FAILED</span>
        </div>
        <div class="student-details">
            <p style="margin:10px 0;">${message}</p>
        </div>
    `;
}

function loadActiveVIPs() {
    const tbody = document.getElementById('vipRows');
    tbody.innerHTML = '<tr><td colspan="6" style="text-align:center; color:#666;"><i class="fas fa-spinner fa-spin"></i> Loading...</td></tr>';

    fetch('../php/action_list__vip_active.php')
        .then(r => r.json())
        .then(data => {
            if (!data.success) {
                tbody.innerHTML = `<tr><td colspan="6" style="text-align:center; color:#c00;">${data.message || 'Failed to load.'}</td></tr>`;
                return;
            }
            if (!data.active || data.active.length === 0) {
                tbody.innerHTML = '<tr><td colspan="6" style="text-align:center; color:#666;">No active VIPs.</td></tr>';
                return;
            }
            tbody.innerHTML = data.active.map(v => `
                <tr>
                    <td>${v.name}</td>
                    <td>${v.reason || '-'}</td>
                    <td>${v.time_in || '-'}</td>
                    <td>${v.gate_in || '-'}</td>
                    <td>${v.guard_in || '-'}</td>
                    <td>
                        <button class="logout-btn" onclick="logoutVIP('${v.entry_key}')">
                            <i class="fas fa-sign-out-alt"></i> Log Out
                        </button>
                    </td>
                </tr>
            `).join('');
        })
        .catch(() => {
            tbody.innerHTML = '<tr><td colspan="6" style="text-align:center; color:#c00;">Connection error.</td></tr>';
        });
}

function logoutVIP(entryKey) {
    const responseCard = document.getElementById('responseCard');
    responseCard.className = 'response-card';
    responseCard.style.display = 'block';
    responseCard.innerHTML = '<div style="text-align:center;"><i class="fas fa-spinner fa-spin"></i> Processing logout...</div>';

    const formData = new FormData();
    formData.append('entry_key', entryKey);

    fetch('../php/action_logout__vip.php', { method: 'POST', body: formData })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                showSuccessMessage(data);
                loadActiveVIPs();
            } else {
                showErrorMessage(data.message || 'Logout failed.');
            }
        })
        .catch(() => showErrorMessage('Connection error. Please try again.'));
}

loadActiveVIPs();
document.getElementById('refreshList').addEventListener('click', loadActiveVIPs);
</script>