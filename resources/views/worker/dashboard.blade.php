<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
                    <div class="bento-grid">
                        <!-- Work Order Card 1 -->
                        <div class="bento-card urgent" data-order-id="1">
                            <div class="card-header">
                                <h3>Pothole Repair</h3>
                                <span class="urgency-badge urgent">🔴 High Priority</span>
                            </div>
                            <p class="location">📍 Downtown - Maple Ave</p>
                            <div class="card-details">
                                <span class="status-badge assigned">Assigned</span>
                                <span class="time">2 hours ago</span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: 15%"></div>
                            </div>
                            <p class="completion">15% Complete</p>
                            <button class="update-btn" onclick="openStatusModal(1, 'Pothole Repair', 'assigned')">Update Status</button>
                        </div>

                        <!-- Work Order Card 2 -->
                        <div class="bento-card" data-order-id="2">
                            <div class="card-header">
                                <h3>Trash Bin Service</h3>
                                <span class="urgency-badge">🟡 Medium</span>
                            </div>
                            <p class="location">📍 Downtown - Central Park</p>
                            <div class="card-details">
                                <span class="status-badge working">Working</span>
                                <span class="time">30 min ago</span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: 65%"></div>
                            </div>
                            <p class="completion">65% Complete</p>
                            <button class="update-btn" onclick="openStatusModal(2, 'Trash Bin Service', 'working')">Update Status</button>
                        </div>

                        <!-- Work Order Card 3 -->
                        <div class="bento-card wide" data-order-id="3">
                            <div class="card-header">
                                <h3>Street Light Installation</h3>
                                <span class="urgency-badge">🟢 Low Priority</span>
                            </div>
                            <p class="location">📍 West Side - Oak Street</p>
                            <div class="card-details">
                                <span class="status-badge completed">Completed</span>
                                <span class="time">1 hour ago</span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill success" style="width: 100%"></div>
                            </div>
                            <p class="completion">100% Complete ✓</p>
                            <button class="update-btn completed" onclick="openStatusModal(3, 'Street Light Installation', 'completed')" disabled>Completed</button>
                        </div>

                        <!-- Work Order Card 4 -->
                        <div class="bento-card" data-order-id="4">
                            <div class="card-header">
                                <h3>Water Main Leak</h3>
                                <span class="urgency-badge urgent">🔴 High Priority</span>
                            </div>
                            <p class="location">📍 East Side - 5th Street</p>
                            <div class="card-details">
                                <span class="status-badge assigned">Assigned</span>
                                <span class="time">45 min ago</span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: 25%"></div>
                            </div>
                            <p class="completion">25% Complete</p>
                            <button class="update-btn" onclick="openStatusModal(4, 'Water Main Leak', 'assigned')">Update Status</button>
                        </div>

                        <!-- Work Order Card 5 -->
                        <div class="bento-card" data-order-id="5">
                            <div class="card-header">
                                <h3>Sidewalk Repair</h3>
                                <span class="urgency-badge">🟡 Medium</span>
                            </div>
                            <p class="location">📍 North Side - Park Lane</p>
                            <div class="card-details">
                                <span class="status-badge working">Working</span>
                                <span class="time">1 hour ago</span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: 80%"></div>
                            </div>
                            <p class="completion">80% Complete</p>
                            <button class="update-btn" onclick="openStatusModal(5, 'Sidewalk Repair', 'working')">Update Status</button>
                        </div>
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
                                <!-- High Priority Pin 1 (Downtown) - Pothole -->
                                <circle cx="80" cy="80" r="8" fill="#ff4444" opacity="0.8" class="incident-pin urgent" data-order="1"/>
                                <circle cx="80" cy="80" r="12" fill="#ff4444" opacity="0.3" r="12" class="pulse"/>
                                <text x="75" y="85" font-size="14" fill="white" font-weight="bold">1</text>

                                <!-- Medium Priority Pin 2 (Central) - Trash Bins -->
                                <circle cx="250" cy="120" r="8" fill="#ffaa00" opacity="0.8" class="incident-pin" data-order="2"/>
                                <circle cx="250" cy="120" r="12" fill="#ffaa00" opacity="0.3" class="pulse"/>
                                <text x="245" y="125" font-size="14" fill="white" font-weight="bold">2</text>

                                <!-- Completed Pin 3 (Central) - Street Light -->
                                <circle cx="280" cy="200" r="8" fill="#44aa44" opacity="0.8" class="incident-pin" data-order="3"/>
                                <circle cx="280" cy="200" r="12" fill="#44aa44" opacity="0.3" class="pulse"/>
                                <text x="275" y="205" font-size="14" fill="white" font-weight="bold">3</text>

                                <!-- High Priority Pin 4 (East) - Water Leak -->
                                <circle cx="500" cy="250" r="8" fill="#ff4444" opacity="0.8" class="incident-pin urgent" data-order="4"/>
                                <circle cx="500" cy="250" r="12" fill="#ff4444" opacity="0.3" class="pulse"/>
                                <text x="495" y="255" font-size="14" fill="white" font-weight="bold">4</text>

                                <!-- Medium Priority Pin 5 (North) - Sidewalk -->
                                <circle cx="100" cy="320" r="8" fill="#ffaa00" opacity="0.8" class="incident-pin" data-order="5"/>
                                <circle cx="100" cy="320" r="12" fill="#ffaa00" opacity="0.3" class="pulse"/>
                                <text x="95" y="325" font-size="14" fill="white" font-weight="bold">5</text>
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

    <script src="{{ asset('js/worker.js') }}"></script>
</body>
</html>
