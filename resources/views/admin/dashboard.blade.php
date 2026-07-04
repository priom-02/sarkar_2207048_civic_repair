<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Dashboard - Executive Analytics & Management</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body>
    <div class="admin-container">
        <!-- Header -->
        <header class="admin-header">
            <div class="header-content">
                <div class="header-left">
                    <h1 class="admin-title">👨‍💼 Admin Control Center</h1>
                    <p class="admin-subtitle">Executive Analytics & Platform Management</p>
                </div>
                <div class="header-right">
                    <div class="admin-user">
                        {{ auth()->user()->full_name }}
                    </div>
                    <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="logout-btn">Logout</button>
                    </form>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="admin-main">
            <!-- Executive Analytics Section -->
            <section class="analytics-section">
                <h2>📊 Executive Analytics</h2>
                
                <!-- Key Metrics -->
                <div class="metrics-grid">
                    <div class="metric-card">
                        <div class="metric-icon">📍</div>
                        <div class="metric-number" id="totalReportsCount">0</div>
                        <div class="metric-label">Total Reports</div>
                    </div>
                    <div class="metric-card">
                        <div class="metric-icon">✅</div>
                        <div class="metric-number" id="resolvedReportsCount">0</div>
                        <div class="metric-label">Resolved</div>
                    </div>
                    <div class="metric-card">
                        <div class="metric-icon">⏳</div>
                        <div class="metric-number" id="inProgressReportsCount">0</div>
                        <div class="metric-label">In Progress</div>
                    </div>
                    <div class="metric-card">
                        <div class="metric-icon">👷</div>
                        <div class="metric-number" id="activeWorkersCount">0</div>
                        <div class="metric-label">Active Workers</div>
                    </div>
                </div>

                <!-- Hotspots Heatmap -->
                <div class="analytics-grid">
                    <div class="heatmap-container">
                        <h3>🗺️ Hotspots Heatmap - Report Density</h3>
                        <div class="map-wrapper">
                            <svg class="heatmap-svg" viewBox="0 0 800 500" xmlns="http://www.w3.org/2000/svg">
                                <!-- City Map Background -->
                                <defs>
                                    <radialGradient id="heatspot1" cx="50%" cy="50%">
                                        <stop offset="0%" style="stop-color:#ff0000;stop-opacity:0.7" />
                                        <stop offset="100%" style="stop-color:#ff0000;stop-opacity:0" />
                                    </radialGradient>
                                    <radialGradient id="heatspot2" cx="50%" cy="50%">
                                        <stop offset="0%" style="stop-color:#ff9900;stop-opacity:0.6" />
                                        <stop offset="100%" style="stop-color:#ff9900;stop-opacity:0" />
                                    </radialGradient>
                                    <radialGradient id="heatspot3" cx="50%" cy="50%">
                                        <stop offset="0%" style="stop-color:#ffff00;stop-opacity:0.5" />
                                        <stop offset="100%" style="stop-color:#ffff00;stop-opacity:0" />
                                    </radialGradient>
                                </defs>

                                <!-- Base Map -->
                                <rect width="800" height="500" fill="#e8eef7" stroke="#999" stroke-width="2"/>
                                
                                <!-- City Grid -->
                                <line x1="0" y1="125" x2="800" y2="125" stroke="#ddd" stroke-width="1" stroke-dasharray="5,5"/>
                                <line x1="0" y1="250" x2="800" y2="250" stroke="#ddd" stroke-width="1" stroke-dasharray="5,5"/>
                                <line x1="0" y1="375" x2="800" y2="375" stroke="#ddd" stroke-width="1" stroke-dasharray="5,5"/>
                                <line x1="200" y1="0" x2="200" y2="500" stroke="#ddd" stroke-width="1" stroke-dasharray="5,5"/>
                                <line x1="400" y1="0" x2="400" y2="500" stroke="#ddd" stroke-width="1" stroke-dasharray="5,5"/>
                                <line x1="600" y1="0" x2="600" y2="500" stroke="#ddd" stroke-width="1" stroke-dasharray="5,5"/>

                                <!-- Zone Labels -->
                                <text x="100" y="30" font-size="14" font-weight="bold" fill="#666">Downtown</text>
                                <text x="300" y="30" font-size="14" font-weight="bold" fill="#666">Central</text>
                                <text x="550" y="30" font-size="14" font-weight="bold" fill="#666">East Zone</text>
                                <text x="50" y="450" font-size="14" font-weight="bold" fill="#666">North</text>
                                <text x="450" y="450" font-size="14" font-weight="bold" fill="#666">South</text>

                                <!-- High Density Heatspots (Red) -->
                                <circle cx="150" cy="120" r="80" fill="url(#heatspot1)" class="heatspot-critical"/>
                                <circle cx="650" cy="200" r="70" fill="url(#heatspot1)" class="heatspot-critical"/>

                                <!-- Medium Density Heatspots (Orange) -->
                                <circle cx="400" cy="150" r="60" fill="url(#heatspot2)" class="heatspot-warning"/>
                                <circle cx="300" cy="350" r="55" fill="url(#heatspot2)" class="heatspot-warning"/>

                                <!-- Low Density Heatspots (Yellow) -->
                                <circle cx="600" cy="350" r="50" fill="url(#heatspot3)" class="heatspot-info"/>
                                <circle cx="100" cy="300" r="45" fill="url(#heatspot3)" class="heatspot-info"/>

                                <!-- Report Pins -->
                                <g id="report-pins" opacity="0.8">
                                    <!-- Dynamic pins will be plotted here by JavaScript -->
                                </g>
                            </svg>
                        </div>
                        <div class="heatmap-legend">
                            <div class="legend-row">
                                <span class="legend-swatch critical"></span>
                                <span>Critical (50+ reports)</span>
                            </div>
                            <div class="legend-row">
                                <span class="legend-swatch warning"></span>
                                <span>Warning (20-49 reports)</span>
                            </div>
                            <div class="legend-row">
                                <span class="legend-swatch info"></span>
                                <span>Info (1-19 reports)</span>
                            </div>
                        </div>
                    </div>

                    <!-- Trends Bar Graph -->
                    <div class="trends-container">
                        <h3>📈 30-Day Trends Analysis</h3>
                        <div class="chart-wrapper">
                            <svg class="trends-chart" viewBox="0 0 400 300" xmlns="http://www.w3.org/2000/svg">
                                <!-- Axes -->
                                <line x1="40" y1="250" x2="380" y2="250" stroke="#333" stroke-width="2"/>
                                <line x1="40" y1="20" x2="40" y2="250" stroke="#333" stroke-width="2"/>

                                <!-- Grid Lines -->
                                <line x1="40" y1="200" x2="380" y2="200" stroke="#e0e0e0" stroke-width="1"/>
                                <line x1="40" y1="150" x2="380" y2="150" stroke="#e0e0e0" stroke-width="1"/>
                                <line x1="40" y1="100" x2="380" y2="100" stroke="#e0e0e0" stroke-width="1"/>
                                <line x1="40" y1="50" x2="380" y2="50" stroke="#e0e0e0" stroke-width="1"/>

                                <!-- Y-axis Labels -->
                                <text x="5" y="255" font-size="12" fill="#666">0</text>
                                <text x="5" y="205" font-size="12" fill="#666">25</text>
                                <text x="5" y="155" font-size="12" fill="#666">50</text>
                                <text x="5" y="105" font-size="12" fill="#666">75</text>
                                <text x="5" y="55" font-size="12" fill="#666">100</text>

                                <!-- Infrastructure Reports (Blue Bars) -->
                                <g id="infrastructure-bars">
                                    <!-- Dynamic bars will be plotted here by JavaScript -->
                                </g>

                                <!-- Sanitation Reports (Orange Bars) -->
                                <g id="sanitation-bars">
                                    <!-- Dynamic bars will be plotted here by JavaScript -->
                                </g>

                                <!-- X-axis Labels -->
                                <text x="50" y="270" font-size="11" fill="#666">Week 1</text>
                                <text x="110" y="270" font-size="11" fill="#666">Week 2</text>
                                <text x="170" y="270" font-size="11" fill="#666">Week 3</text>
                                <text x="230" y="270" font-size="11" fill="#666">Week 4</text>

                                <!-- Title -->
                                <text x="150" y="15" font-size="14" font-weight="bold" fill="#333">Report Categories Trend</text>
                            </svg>
                        </div>
                        <div class="chart-legend">
                            <div class="legend-item">
                                <span class="legend-box" style="background: #4db8ff;"></span>
                                <span>Infrastructure Reports</span>
                            </div>
                            <div class="legend-item">
                                <span class="legend-box" style="background: #ff9900;"></span>
                                <span>Sanitation Alerts</span>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Category Management Section -->
            <section class="category-section">
                <h2>🏷️ Category Management Board</h2>
                
                <div class="category-layout">
                    <!-- Existing Categories -->
                    <div class="categories-list">
                        <h3>Current Categories</h3>
                        <div id="categoriesList" class="categories-grid">
                            <!-- Categories will be populated by JavaScript -->
                        </div>
                    </div>

                    <!-- Create New Category Form -->
                    <div class="create-category-box">
                        <h3>➕ Add New Category</h3>
                        <form id="categoryForm" class="category-form">
                            <div class="form-group">
                                <label for="categoryName">Category Name</label>
                                <input 
                                    type="text" 
                                    id="categoryName" 
                                    name="categoryName" 
                                    placeholder="e.g., Pothole Repair"
                                    required
                                />
                            </div>

                            <div class="form-group">
                                <label for="categoryDescription">Description</label>
                                <textarea 
                                    id="categoryDescription" 
                                    name="categoryDescription" 
                                    placeholder="Describe this category..."
                                    rows="3"
                                    required
                                ></textarea>
                            </div>

                            <div class="form-group">
                                <label for="categoryIcon">Icon (Emoji)</label>
                                <div class="icon-selector">
                                    <input 
                                        type="text" 
                                        id="categoryIcon" 
                                        name="categoryIcon" 
                                        placeholder="🛣️"
                                        maxlength="2"
                                        class="icon-input"
                                        required
                                    />
                                    <div class="icon-preview" id="iconPreview">🛣️</div>
                                </div>
                                <div class="quick-icons">
                                    <span class="quick-icon" data-icon="🛣️">🛣️</span>
                                    <span class="quick-icon" data-icon="♻️">♻️</span>
                                    <span class="quick-icon" data-icon="🚨">🚨</span>
                                    <span class="quick-icon" data-icon="💧">💧</span>
                                    <span class="quick-icon" data-icon="🌳">🌳</span>
                                    <span class="quick-icon" data-icon="🔧">🔧</span>
                                </div>
                            </div>

                            <button type="submit" class="btn-create">Create Category</button>
                        </form>
                    </div>
                </div>
            </section>

            <!-- Complaint Assignment & Dispatch Board Section -->
            <section class="assignments-section">
                <h2>📋 Complaint Assignment & Dispatch Board</h2>
                <div class="table-container">
                    <table class="assignments-table">
                        <thead>
                            <tr>
                                <th>Issue Details</th>
                                <th>Category & Area</th>
                                <th>Reported By</th>
                                <th>Priority & Upvotes</th>
                                <th>Current Status</th>
                                <th>Assign Work Order</th>
                            </tr>
                        </thead>
                        <tbody id="complaintsTableBody">
                            <tr>
                                <td colspan="6" class="loading-text">Loading reported complaints...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>

    <!-- Success Notification Banner -->
    <div class="notification-banner" id="notificationBanner">
        <div class="banner-content">
            <span class="banner-icon">✓</span>
            <span class="banner-message" id="bannerMessage">New category created successfully!</span>
            <button class="banner-close" onclick="closeBanner()">&times;</button>
        </div>
    </div>

    <script>
        window.AppConfig = {
            apiBaseUrl: "{{ request()->getBaseUrl() }}"
        };
    </script>
    <script src="{{ asset('js/admin.js') }}"></script>
</body>
</html>
