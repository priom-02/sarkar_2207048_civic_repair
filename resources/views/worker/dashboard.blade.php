<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Worker Dashboard - Field Operations Portal</title>
    <link rel="stylesheet" href="{{ asset('css/worker.css') }}">
    <!-- Leaflet Mapping Library CDN -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
</head>
<body>
    <div class="worker-container">
        <!-- Header -->
        <header class="worker-header">
            <div class="header-content">
                <div class="header-left">
                    <h1 class="greeting">Good Morning, {{ auth()->user()->full_name }}</h1>
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
                    <h2>Active Work Orders</h2>
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
                    <h2>Assigned Incidents Map</h2>
                    <div id="liveWorkerMap" style="height: 400px; border-radius: 16px; border: 1px solid #cbd5e1; z-index: 1;"></div>
                </section>

                <!-- Today's Summary -->
                <section class="summary-section">
                    <h3>Summary</h3>
                    <div class="summary-item">
                        <span>Resolved Tasks</span>
                        <strong id="summaryCompleted">7 of 12</strong>
                    </div>

                    <div class="summary-item">
                        <span>Next Priority</span>
                        <strong id="summaryNextPriority">Pothole Repair</strong>
                    </div>
                    <div class="summary-item">
                        <span>Your Rating</span>
                        <strong id="summaryRating">4.9 / 5.0</strong>
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
                            <span class="status-name">Assigned</span>
                        </button>
                        <button class="status-option" data-status="working" onclick="selectStatus('working')">
                            <span class="status-name">Working</span>
                        </button>
                        <button class="status-option" data-status="completed" onclick="selectStatus('completed')">
                            <span class="status-name">Completed</span>
                        </button>
                    </div>
                </div>

                <div class="modal-section" id="workerDetailMapSection" style="margin-bottom: 1.25rem;">
                    <label class="modal-label">Incident Location Map:</label>
                    <div id="workerDetailMap" style="height: 180px; border-radius: 12px; border: 1px solid #cbd5e1; background: #f8fafc; z-index: 1;"></div>
                </div>

                <div class="modal-section">
                    <label class="modal-label">Progress Notes:</label>
                    <textarea id="progressNotes" class="progress-textarea" placeholder="Add notes about the current work status, issues, or next steps..." rows="5"></textarea>
                </div>

                <div class="modal-section">
                    <label class="modal-label">Photos/Evidence:</label>
                    <div class="upload-area" onclick="document.getElementById('photoUpload').click()">
                        <p>Click to upload photo evidence</p>
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
