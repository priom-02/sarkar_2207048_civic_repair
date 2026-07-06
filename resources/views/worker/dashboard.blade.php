<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Worker Dashboard - Field Operations Portal</title>
    <link rel="stylesheet" href="{{ asset('css/worker.css') }}">
</head>
<body>
    <div class="worker-container">
        <!-- Header -->
        <header class="worker-header">
            <div class="header-content">
                <div class="header-left">
                    <h1 class="greeting">👷 Good Morning, {{ auth()->user()->full_name }}</h1>
                    <p class="subtitle">Field Operations Portal</p>
                </div>
                <div class="header-right">
                    <div class="time" id="currentTime">12:00 PM</div>
                    <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="logout-btn">Logout</button>
                    </form>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="worker-main">
            <!-- Left Panel - Progress Tracker -->
            <div class="left-panel">
                <section class="progress-section">
                    <h2>📊 Active Work Orders</h2>
                    <div class="stats-overview">
                        <div class="stat-card">
                            <div class="stat-number">12</div>
                            <div class="stat-label">Total Assigned</div>
                        </div>
                        <div class="stat-card accent">
                            <div class="stat-number">3</div>
                            <div class="stat-label">High Priority</div>
                        </div>
                        <div class="stat-card success">
                            <div class="stat-number">7</div>
                            <div class="stat-label">Completed</div>
                        </div>
                    </div>

                    <!-- Progress Bento Grid -->
                    <div class="bento-grid" id="workOrdersGrid">
                        <!-- Dynamic work orders will be rendered here by JavaScript -->
                    </div>
                </section>
            </div>

            <!-- Right Panel - Map and Details -->
            <div class="right-panel">
                <!-- Interactive Map Section -->
                <section class="map-section">
                    <h2>🗺️ Assigned Incidents Map</h2>
                    <div class="map-container" id="incidentsMap">
                        <svg class="map-svg" viewBox="0 0 600 400" xmlns="http://www.w3.org/2000/svg">
                            <!-- Map background -->
                            <rect width="600" height="400" fill="#f0f4f8" stroke="#ccc" stroke-width="2"/>
                            
                            <!-- Grid lines -->
                            <line x1="0" y1="100" x2="600" y2="100" stroke="#ddd" stroke-width="1" stroke-dasharray="5,5"/>
                            <line x1="0" y1="200" x2="600" y2="200" stroke="#ddd" stroke-width="1" stroke-dasharray="5,5"/>
                            <line x1="0" y1="300" x2="600" y2="300" stroke="#ddd" stroke-width="1" stroke-dasharray="5,5"/>
                            <line x1="150" y1="0" x2="150" y2="400" stroke="#ddd" stroke-width="1" stroke-dasharray="5,5"/>
                            <line x1="300" y1="0" x2="300" y2="400" stroke="#ddd" stroke-width="1" stroke-dasharray="5,5"/>
                            <line x1="450" y1="0" x2="450" y2="400" stroke="#ddd" stroke-width="1" stroke-dasharray="5,5"/>

                            <!-- Zones/Areas -->
                            <g id="zones">
                                <text x="50" y="30" font-size="12" font-weight="bold" fill="#666">Downtown</text>
                                <text x="200" y="30" font-size="12" font-weight="bold" fill="#666">Central</text>
                                <text x="400" y="30" font-size="12" font-weight="bold" fill="#666">West Side</text>
                                <text x="50" y="350" font-size="12" font-weight="bold" fill="#666">North Side</text>
                                <text x="350" y="350" font-size="12" font-weight="bold" fill="#666">East Side</text>
                            </g>

                            <!-- Incident Pins -->
                            <g id="pins">
                                <!-- Dynamic pins will be plotted here by JavaScript -->
                            </g>
                        </svg>
                        
                        <div class="map-legend">
                            <div class="legend-item">
                                <span class="legend-dot urgent"></span>
                                <span>High Priority</span>
                            </div>
                            <div class="legend-item">
                                <span class="legend-dot medium"></span>
                                <span>Medium Priority</span>
                            </div>
                            <div class="legend-item">
                                <span class="legend-dot completed"></span>
                                <span>Completed</span>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Today's Summary -->
                <section class="summary-section">
                    <h3>📋 Today's Summary</h3>
                    <div class="summary-item">
                        <span>Tasks Completed</span>
                        <strong>7 of 12</strong>
                    </div>
                    <div class="summary-item">
                        <span>Avg. Completion Time</span>
                        <strong>1h 45m</strong>
                    </div>
                    <div class="summary-item">
                        <span>Next Priority</span>
                        <strong>Pothole Repair</strong>
                    </div>
                    <div class="summary-item">
                        <span>Your Rating</span>
                        <strong>⭐⭐⭐⭐⭐ 4.9</strong>
                    </div>
                </section>
            </div>
        </main>
    </div>

    <!-- Status Action Modal -->
    <div class="modal" id="statusModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="modalTitle">Update Work Order Status</h2>
                <button class="modal-close" onclick="closeStatusModal()">×</button>
            </div>
            
            <div class="modal-body">
                <div class="modal-section">
                    <label class="modal-label">Current Status: <span id="currentStatusDisplay" class="badge"></span></label>
                </div>

                <div class="modal-section">
                    <label class="modal-label">Update to New Status:</label>
                    <div class="status-options">
                        <button class="status-option" data-status="assigned" onclick="selectStatus('assigned')">
                            <span class="status-icon">📋</span>
                            <span class="status-name">Assigned</span>
                        </button>
                        <button class="status-option" data-status="working" onclick="selectStatus('working')">
                            <span class="status-icon">🔧</span>
                            <span class="status-name">Working</span>
                        </button>
                        <button class="status-option" data-status="completed" onclick="selectStatus('completed')">
                            <span class="status-icon">✅</span>
                            <span class="status-name">Completed</span>
                        </button>
                    </div>
                </div>

                <div class="modal-section">
                    <label class="modal-label">Progress Notes:</label>
                    <textarea id="progressNotes" class="progress-textarea" placeholder="Add notes about the current work status, issues, or next steps..." rows="5"></textarea>
                </div>

                <div class="modal-section">
                    <label class="modal-label">Photos/Evidence:</label>
                    <div class="upload-area" onclick="document.getElementById('photoUpload').click()">
                        <div class="upload-icon">📷</div>
                        <p>Click to upload photos</p>
                        <input type="file" id="photoUpload" accept="image/*" style="display: none;">
                    </div>
                </div>

                <div class="modal-section updates-feed">
                    <label class="modal-label">Recent Updates:</label>
                    <div class="update-item">
                        <span class="update-time">Today 10:30 AM</span>
                        <span class="update-status">Status changed to Working</span>
                    </div>
                    <div class="update-item">
                        <span class="update-time">Today 09:15 AM</span>
                        <span class="update-status">Assigned to Marcus</span>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="closeStatusModal()">Cancel</button>
                <button class="btn btn-primary" onclick="submitStatusUpdate()">Save & Update</button>
            </div>
        </div>
    </div>

    <!-- Notification Toast -->
    <div class="notification" id="notification">
        <span id="notificationText">Status updated successfully!</span>
    </div>

    <script>
        window.AppConfig = {
            apiBaseUrl: "{{ request()->getBaseUrl() }}"
        };
    </script>
    <script src="{{ asset('js/worker.js') }}"></script>
</body>
</html>
