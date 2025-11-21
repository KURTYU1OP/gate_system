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

        /* Mode Toggle Buttons */
        .mode-toggle {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }

        .mode-btn {
            flex: 1;
            padding: 14px;
            border: 2px solid #A60212;
            background: white;
            color: #A60212;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            font-size: 15px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .mode-btn.active {
            background: linear-gradient(135deg, #A60212 0%, #8B0010 100%);
            color: white;
        }

        .mode-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(166, 2, 18, 0.3);
        }

        /* QR Scanner Styles */
        .scanner-container {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        #qr-reader {
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 20px;
            border: 3px solid #e0e0e0;
        }

        #qr-reader video {
            width: 100% !important;
            border-radius: 8px;
        }

        .scan-status {
            text-align: center;
            padding: 15px;
            background: #cfe2ff;
            border-radius: 8px;
            margin-bottom: 15px;
            font-weight: 600;
            color: #084298;
            border: 2px solid #b6d4fe;
        }

        .scan-status i {
            margin-right: 8px;
        }

        /* Manual Entry Styles */
        .manual-entry {
            display: none;
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

        .submit-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
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
        <!-- Time Display -->
        <div class="time-display">
            <i class="fas fa-clock"></i>
            <span id="currentDateTime">Loading...</span>
        </div>

        <!-- Mode Toggle Buttons -->
        <div class="mode-toggle">
            <button class="mode-btn active" onclick="switchMode('scanner')">
                <i class="fas fa-qrcode"></i> QR Scanner
            </button>
            <button class="mode-btn" onclick="switchMode('manual')">
                <i class="fas fa-keyboard"></i> Manual Entry
            </button>
        </div>
        <div id="responseCard" class="response-card"></div>

        <!-- QR Scanner Mode -->
        <div id="scanner-mode" class="scanner-container">
            <div class="scan-status">
                <i class="fas fa-camera"></i> Ready to scan QR code
            </div>
            <div id="qr-reader"></div>
            
            <div class="form-group">
                <label for="violation_scanner">Violation</label>
                <select id="violation_scanner" name="violation">
                    <option value="None">None</option>
                    <option value="Improper Uniform">Improper Uniform</option>
                    <option value="Late Entry">Late Entry</option>
                    <option value="Prohibited Items">Prohibited Items</option>
                </select>
            </div>
        </div>

        <!-- Manual Entry Mode -->
        <div id="manual-mode" class="manual-entry">
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

        <!-- Response Card -->
        
    </div>

    <script>
        let html5QrCode;
        let isProcessing = false;

        // ==================== TIME DISPLAY ====================
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

        // ==================== QR SCANNER ====================
        function startScanner() {
            html5QrCode = new Html5Qrcode("qr-reader");
            
            html5QrCode.start(
                { facingMode: "environment" }, // Use back camera
                {
                    fps: 10,
                    qrbox: { width: 250, height: 250 }
                },
                onScanSuccess,
                onScanFailure
            ).catch(err => {
                console.error("Error starting scanner:", err);
                document.querySelector('.scan-status').innerHTML = 
                    '<i class="fas fa-exclamation-triangle"></i> Camera access denied. Please enable camera or use manual entry.';
            });
        }

        function onScanSuccess(decodedText, decodedResult) {
            if (isProcessing) return; // Prevent multiple scans
            isProcessing = true;

            // Parse student ID from QR code
            let studentId = extractStudentId(decodedText);
            
            if (!studentId) {
                showErrorMessage('Invalid QR code format');
                setTimeout(() => { isProcessing = false; }, 2000);
                return;
            }

            // Get violation selection
            const violation = document.getElementById('violation_scanner').value;
            
            // Submit entry
            submitEntry(studentId, violation);
        }

        function onScanFailure(error) {
            // Silently handle scan failures (continuous scanning)
        }

        function extractStudentId(qrText) {
            console.log("QR Code scanned:", qrText); // Debug log
            
            // Primary method: Extract from "StudentID: 01" format
            const match = qrText.match(/StudentID:\s*(.+)/i);
            if (match) {
                // Extract just the ID part (before newline if exists)
                const studentId = match[1].split('\n')[0].trim();
                console.log("Extracted Student ID:", studentId);
                return studentId;
            }

            // Fallback: If QR is just the student ID
            if (/^\d+$/.test(qrText.trim())) {
                return qrText.trim();
            }

            // If no match found
            console.error("Could not extract student ID from QR code");
            return null;
        }

        // ==================== MODE SWITCHING ====================
        function switchMode(mode) {
            const scannerMode = document.getElementById('scanner-mode');
            const manualMode = document.getElementById('manual-mode');
            const buttons = document.querySelectorAll('.mode-btn');

            if (mode === 'scanner') {
                // Show scanner, hide manual
                scannerMode.style.display = 'block';
                manualMode.style.display = 'none';
                buttons[0].classList.add('active');
                buttons[1].classList.remove('active');
                
                // Start scanner if not already running
                if (!html5QrCode) {
                    startScanner();
                }
            } else {
                // Show manual, hide scanner
                scannerMode.style.display = 'none';
                manualMode.style.display = 'block';
                buttons[0].classList.remove('active');
                buttons[1].classList.add('active');
                
                // Stop scanner
                if (html5QrCode) {
                    html5QrCode.stop().then(() => {
                        html5QrCode.clear();
                    }).catch(err => console.error(err));
                }
                
                // Focus on input
                setTimeout(() => {
                    document.getElementById('student_id').focus();
                }, 100);
            }
        }

        // ==================== SUBMIT ENTRY ====================
        function submitEntry(studentId, violation) {
            const responseCard = document.getElementById('responseCard');
            
            // Show loading
            responseCard.className = 'response-card';
            responseCard.style.display = 'block';
            responseCard.innerHTML = '<div style="text-align:center;"><i class="fas fa-spinner fa-spin"></i> Processing...</div>';

            // Prepare form data
            const formData = new FormData();
            formData.append('student_id', studentId);
            formData.append('violation', violation);

            // Send to server
            fetch('../php/action_add__gate_entries.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showSuccessMessage(data);
                    // Reset violation dropdown
                    document.getElementById('violation_scanner').value = 'None';
                } else {
                    showErrorMessage(data.message);
                }
                
                // Allow scanning again after 3 seconds
                setTimeout(() => {
                    isProcessing = false;
                    responseCard.style.display = 'none';
                }, 10000);
            })
            .catch(error => {
                console.error('Error:', error);
                showErrorMessage('Connection error. Please try again.');
                setTimeout(() => {
                    isProcessing = false;
                    responseCard.style.display = 'none';
                }, 10000);
            });
        }

        // ==================== MANUAL FORM SUBMISSION ====================
        document.getElementById('entryForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const studentId = document.getElementById('student_id').value;
            const violation = document.getElementById('violation').value;
            
            submitEntry(studentId, violation);
            
            // Clear form
            document.getElementById('student_id').value = '';
            document.getElementById('violation').value = 'None';
            document.getElementById('student_id').focus();
        });

        // ==================== DISPLAY MESSAGES ====================
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
                        <span class="detail-label">Campus:</span>
                        <span class="detail-value">${data.campus}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Entry Mode:</span>
                        <span class="detail-value">${data.is_visitor ? 'Student Visitor' : 'Regular Schedule'}</span>
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

        // ==================== INITIALIZE ON PAGE LOAD ====================
        window.addEventListener('load', () => {
            startScanner();
        });
    </script>
