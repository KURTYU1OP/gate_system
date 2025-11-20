<style>
.log-container {
    max-width: 800px;
    margin: 0 auto;
}

.time-display {
    background: linear-gradient(135deg, #A60212 0%, #8B0010 100%);
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 30px;
    box-shadow: 0 4px 15px rgba(166, 2, 18, 0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 15px;
}

.time-display i {
    font-size: 32px;
    color: white;
}

.time-display span {
    font-size: 24px;
    font-weight: 600;
    color: white;
    letter-spacing: 1px;
}

.entry-form {
    background: white;
    border-radius: 12px;
    padding: 30px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    margin-bottom: 30px;
}

.form-group {
    margin-bottom: 25px;
}

.form-group label {
    display: block;
    font-weight: 600;
    color: #333;
    margin-bottom: 8px;
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.form-group input,
.form-group select {
    width: 100%;
    padding: 14px 16px;
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    font-size: 16px;
    background: #f8f9fa;
    box-sizing: border-box;
    transition: all 0.3s ease;
}

.form-group input:focus,
.form-group select:focus {
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
    letter-spacing: 1px;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(166, 2, 18, 0.3);
}

.submit-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(166, 2, 18, 0.4);
}

.submit-btn:active {
    transform: translateY(0);
}

.response-card {
    display: none;
    border-radius: 12px;
    padding: 25px;
    margin-top: 20px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    animation: slideIn 0.3s ease;
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
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
    margin-bottom: 15px;
    font-size: 20px;
    font-weight: 700;
}

.response-header i {
    font-size: 28px;
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
    font-size: 14px;
}

.detail-label {
    font-weight: 600;
    color: #555;
}

.detail-value {
    font-weight: 500;
    color: #333;
}

.schedule-info {
    margin-top: 12px;
    padding: 12px;
    background: rgba(255, 255, 255, 0.5);
    border-radius: 6px;
    font-size: 13px;
}

.schedule-info strong {
    color: #A60212;
}
</style>

<div class="log-container">
    <div class="time-display">
        <i class="fas fa-clock"></i>
        <span id="currentDateTime">Loading...</span>
    </div>
    <div id="responseCard" class="response-card"></div>
    <form id="entryForm" class="entry-form">
        <div class="form-group">
            <label for="student_id">Student ID</label>
            <input type="text" id="student_id" name="student_id" placeholder="Enter Student ID" required autofocus>
        </div>

        <div class="form-group">
            <label for="violation">Violation</label>
            <select id="violation" name="violation">
                <option value="None">None</option>
                <option value="Improper Uniform">Improper Uniform</option>
                <option value="Late Entry">Late Entry</option>
                <option value="Prohibited Items">Prohibited Items</option>
            </select>
        </div>

        <button type="submit" class="submit-btn">
            <i class="fas fa-check-circle"></i> Submit Entry
        </button>
    </form>

    
</div>

<script>
function updateTime() {
    const now = new Date();
    const timeString = now.toLocaleTimeString('en-US', { 
        hour: '2-digit', 
        minute: '2-digit', 
        second: '2-digit' 
    });
    const dateString = now.toLocaleDateString('en-US', { 
        weekday: 'long', 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric' 
    });
    document.getElementById('currentDateTime').textContent = `${timeString} | ${dateString}`;
}

updateTime();
setInterval(updateTime, 1000);

document.getElementById('entryForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const responseCard = document.getElementById('responseCard');

    responseCard.className = 'response-card';
    responseCard.style.display = 'block';
    responseCard.innerHTML = '<div style="text-align:center;"><i class="fas fa-spinner fa-spin"></i> Processing...</div>';

    fetch('../php/action_add__gate_entries.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showSuccessMessage(data);
            document.getElementById('student_id').value = '';
            document.getElementById('violation').value = 'None';
            document.getElementById('student_id').focus();
        } else {
            showErrorMessage(data.message);
        }
    })
    .catch(() => showErrorMessage('Connection error. Please try again.'));
});

function showSuccessMessage(data) {
    const responseCard = document.getElementById('responseCard');
    const isTimeIn = data.action === 'time_in';
    
    let scheduleHTML = '';
    if (data.schedules && data.schedules.length > 0) {
        scheduleHTML = '<div class="schedule-info"><strong>Today\'s Schedule:</strong><br>';
        data.schedules.forEach(sched => {
            scheduleHTML += `${sched.subject} (${sched.time_from} - ${sched.time_to})<br>`;
        });
        scheduleHTML += '</div>';
    }

    responseCard.className = 'response-card success';
    responseCard.style.display = 'block';
    responseCard.innerHTML = `
        <div class="response-header">
            <i class="fas fa-check-circle"></i>
            <span>${isTimeIn ? 'TIME IN RECORDED' : 'TIME OUT RECORDED'}</span>
        </div>
        <div class="student-details">
            <div class="detail-row">
                <span class="detail-label">Student ID:</span>
                <span class="detail-value">${data.student_id}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Name:</span>
                <span class="detail-value">${data.full_name}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Course:</span>
                <span class="detail-value">${data.course}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Section:</span>
                <span class="detail-value">${data.section}</span>
            </div>
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
            ${isTimeIn && data.violation !== 'None' ? `
            <div class="detail-row">
                <span class="detail-label">Violation:</span>
                <span class="detail-value" style="color:#dc3545;">${data.violation}</span>
            </div>
            ` : ''}
            ${scheduleHTML}
        </div>
    `;

    setTimeout(() => {
        responseCard.style.display = 'none';
    }, 8000);
}

function showErrorMessage(message) {
    const responseCard = document.getElementById('responseCard');
    responseCard.className = 'response-card error';
    responseCard.style.display = 'block';
    responseCard.innerHTML = `
        <div class="response-header">
            <i class="fas fa-times-circle"></i>
            <span>ENTRY NOT ALLOWED</span>
        </div>
        <div class="student-details">
            <p style="margin:10px 0; font-size:15px;">${message}</p>
        </div>
    `;
}
</script>