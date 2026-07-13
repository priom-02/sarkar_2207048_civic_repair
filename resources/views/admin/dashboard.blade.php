<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Dashboard - CivicReport</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <!-- Leaflet Mapping Library CDN -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
</head>
<body>
    <div class="admin-container">
        <!-- Header -->
        <header class="admin-header">
            <div class="header-content">
                <div class="header-left">
                    <h1 class="admin-title">Admin Control Center</h1>
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

        <!-- Tab Navigation Bar -->
        <div style="background: white; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: center; gap: 1rem; padding: 0.75rem 0; box-shadow: 0 4px 6px rgba(0,0,0,0.05); position: sticky; top: 0; z-index: 100;">
            <button class="tab-btn active" onclick="switchTab('analytics', this)" style="background: none; border: none; font-family: inherit; font-size: 1.05rem; font-weight: 700; padding: 0.5rem 1.5rem; cursor: pointer; color: #1e3c72; border-bottom: 3px solid #1e3c72; border-radius: 4px; transition: all 0.2s;">Analytics</button>
            <button class="tab-btn" onclick="switchTab('complaints', this)" style="background: none; border: none; font-family: inherit; font-size: 1.05rem; font-weight: 700; padding: 0.5rem 1.5rem; cursor: pointer; color: #64748b; border-bottom: 3px solid transparent; border-radius: 4px; transition: all 0.2s;">Complaints</button>
            <button class="tab-btn" onclick="switchTab('categories', this)" style="background: none; border: none; font-family: inherit; font-size: 1.05rem; font-weight: 700; padding: 0.5rem 1.5rem; cursor: pointer; color: #64748b; border-bottom: 3px solid transparent; border-radius: 4px; transition: all 0.2s;">Categories</button>
            <button class="tab-btn" onclick="switchTab('users', this)" style="background: none; border: none; font-family: inherit; font-size: 1.05rem; font-weight: 700; padding: 0.5rem 1.5rem; cursor: pointer; color: #64748b; border-bottom: 3px solid transparent; border-radius: 4px; transition: all 0.2s;">Users</button>
            <button class="tab-btn" onclick="switchTab('areas', this)" style="background: none; border: none; font-family: inherit; font-size: 1.05rem; font-weight: 700; padding: 0.5rem 1.5rem; cursor: pointer; color: #64748b; border-bottom: 3px solid transparent; border-radius: 4px; transition: all 0.2s;">Areas</button>
        </div>

        <!-- Main Content -->
        <main class="admin-main">
            <!-- Executive Analytics Section -->
            <section id="analyticsSection" class="analytics-section">
                <h2>Executive Analytics</h2>
                
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
                        <h3>Hotspots Heatmap - Report Density</h3>
                        <div id="liveAdminMap" style="height: 450px; border-radius: 16px; border: 1px solid #cbd5e1; z-index: 1; margin-top: 1.5rem;"></div>
                    </div>

                    <!-- Trends Bar Graph -->
                    <div class="trends-container">
                        <h3>30-Day Trends Analysis</h3>
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
            <section id="categoriesSection" class="category-section" style="display: none;">
                <h2>Category Management Board</h2>
                
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
                        <h3>Add New Category</h3>
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
            <section id="complaintsSection" class="assignments-section" style="display: none;">
                <h2>Complaint Assignment & Dispatch Board</h2>
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

            <!-- User Management Board Section -->
            <section id="usersSection" class="assignments-section" style="display: none;">
                <h2>User Management Board</h2>
                <div class="table-container">
                    <table class="assignments-table">
                        <thead>
                            <tr>
                                <th>User Details</th>
                                <th>Email Address</th>
                                <th>Phone Number</th>
                                <th>Role</th>
                                <th>Account Status</th>
                                <th>Control Action</th>
                            </tr>
                        </thead>
                        <tbody id="usersTableBody">
                            <tr>
                                <td colspan="6" class="loading-text">Loading platform users...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- Areas Management Section -->
            <section id="areasSection" class="category-section" style="display: none;">
                <h2>Geographic Areas Board</h2>
                
                <div class="category-layout">
                    <!-- Existing Areas List -->
                    <div class="categories-list">
                        <h3>Registered Areas Catalog</h3>
                        <div class="table-container" style="background: white; border-radius: 12px; padding: 1rem; border: 1px solid #edf2f7;">
                            <table class="assignments-table" style="width: 100%; border-collapse: collapse;">
                                <thead>
                                    <tr style="border-bottom: 2px solid #edf2f7; text-align: left; font-size: 0.9rem; color: #4a5568;">
                                        <th style="padding: 0.75rem 0.5rem;">Hierarchy (Div > Dist > Upz)</th>
                                        <th style="padding: 0.75rem 0.5rem;">Union / Ward / Village</th>
                                        <th style="padding: 0.75rem 0.5rem; text-align: right;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="areasTableBody">
                                    <tr>
                                        <td colspan="3" class="loading-text" style="text-align: center; padding: 2rem;">Loading geographic catalog...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Create New Area Form -->
                    <div class="create-category-box">
                        <h3>Register New Area</h3>
                        <form id="areaCreateForm" class="category-form" onsubmit="handleAreaFormSubmit(event)">
                            <div class="form-group" style="margin-bottom: 1rem;">
                                <label for="areaDivisionInput" style="font-weight: 600; display: block; margin-bottom: 0.25rem;">Division Name *</label>
                                <input type="text" id="areaDivisionInput" required placeholder="e.g. Dhaka" style="width:100%; padding:0.6rem; border:1px solid #cbd5e1; border-radius:6px;">
                            </div>
                            <div class="form-group" style="margin-bottom: 1rem;">
                                <label for="areaDistrictInput" style="font-weight: 600; display: block; margin-bottom: 0.25rem;">District Name *</label>
                                <input type="text" id="areaDistrictInput" required placeholder="e.g. Dhaka" style="width:100%; padding:0.6rem; border:1px solid #cbd5e1; border-radius:6px;">
                            </div>
                            <div class="form-group" style="margin-bottom: 1rem;">
                                <label for="areaUpazilaInput" style="font-weight: 600; display: block; margin-bottom: 0.25rem;">Upazila / Thana *</label>
                                <input type="text" id="areaUpazilaInput" required placeholder="e.g. Savar" style="width:100%; padding:0.6rem; border:1px solid #cbd5e1; border-radius:6px;">
                            </div>
                            <div class="form-group" style="margin-bottom: 1.5rem;">
                                <label for="areaUnionInput" style="font-weight: 600; display: block; margin-bottom: 0.25rem;">Union / Ward / Village</label>
                                <input type="text" id="areaUnionInput" placeholder="e.g. Aminbazar Union" style="width:100%; padding:0.6rem; border:1px solid #cbd5e1; border-radius:6px;">
                            </div>
                            <button type="submit" class="btn-create-category" style="width: 100%;">Add Geographic Area</button>
                        </form>
                    </div>
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

        function switchTab(tabId, btn) {
            // Hide all sections
            document.getElementById('analyticsSection').style.display = 'none';
            document.getElementById('complaintsSection').style.display = 'none';
            document.getElementById('categoriesSection').style.display = 'none';
            document.getElementById('usersSection').style.display = 'none';
            document.getElementById('areasSection').style.display = 'none';

            // Show selected section
            document.getElementById(tabId + 'Section').style.display = 'block';

            // Update tab button styles
            document.querySelectorAll('.tab-btn').forEach(button => {
                button.classList.remove('active');
                button.style.color = '#64748b';
                button.style.borderBottomColor = 'transparent';
            });

            btn.classList.add('active');
            btn.style.color = '#1e3c72';
            btn.style.borderBottomColor = '#1e3c72';

            // Correct Leaflet display size calculation when tab is loaded
            if (tabId === 'analytics' && typeof adminMap !== 'undefined' && adminMap !== null) {
                setTimeout(() => {
                    adminMap.invalidateSize();
                }, 150);
            }
        }
    </script>
    <script src="{{ asset('js/admin.js') }}"></script>
</body>
</html>
