<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Dashboard - CivicReport</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <style>
        .details-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(15, 23, 42, 0.6);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            backdrop-filter: blur(8px);
            padding: 1rem;
        }
        .details-modal.active {
            display: flex;
        }
        .details-modal-content {
            background: white;
            border-radius: 20px;
            width: 100%;
            max-width: 850px;
            max-height: 85vh;
            overflow-y: auto;
            padding: 2.25rem;
            box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);
            position: relative;
            animation: modalSlideUp 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }
        @keyframes modalSlideUp {
            from { transform: translateY(30px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        .details-modal-close {
            position: absolute;
            top: 1.25rem;
            right: 1.5rem;
            font-size: 2.25rem;
            color: #94a3b8;
            border: none;
            background: none;
            cursor: pointer;
            line-height: 1;
            transition: color 0.2s;
        }
        .details-modal-close:hover {
            color: #ef4444;
        }
        .comparison-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
            margin: 1.5rem 0;
        }
        @media (max-width: 640px) {
            .comparison-grid {
                grid-template-columns: 1fr;
            }
        }
        .proof-box {
            background: #f8fafc;
            border-radius: 14px;
            border: 1px solid #e2e8f0;
            padding: 1.25rem;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }
        .proof-title {
            font-size: 0.85rem;
            font-weight: 800;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            display: flex;
            align-items: center;
            gap: 0.35rem;
        }
        .proof-img {
            width: 100%;
            height: 240px;
            object-fit: cover;
            border-radius: 10px;
            border: 1px solid #cbd5e1;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .proof-img:hover {
            transform: scale(1.015);
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);
        }
        .no-proof {
            height: 240px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            background: #f1f5f9;
            color: #94a3b8;
            font-size: 0.9rem;
            font-style: italic;
            border-radius: 10px;
            border: 2px dashed #cbd5e1;
            padding: 1rem;
        }
        .audit-timeline {
            position: relative;
            padding-left: 1.5rem;
            margin-top: 1.25rem;
            border-left: 2px solid #e2e8f0;
        }
        .audit-item {
            position: relative;
            margin-bottom: 1.25rem;
        }
        .audit-item::before {
            content: '';
            position: absolute;
            left: -1.95rem;
            top: 0.3rem;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #3b82f6;
            border: 3px solid white;
            box-shadow: 0 0 0 1px #3b82f6;
        }
        .audit-time {
            font-size: 0.75rem;
            color: #94a3b8;
            font-weight: 600;
            margin-bottom: 0.15rem;
            display: block;
        }
        .audit-desc {
            font-size: 0.9rem;
            font-weight: 700;
            color: #1e293b;
        }
        .audit-remark {
            font-size: 0.85rem;
            color: #64748b;
            margin-top: 0.35rem;
            background: #f8fafc;
            border-left: 3px solid #94a3b8;
            padding: 0.5rem 0.75rem;
            border-radius: 0 6px 6px 0;
        }
    </style>
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
                        <h3>7-Day Trends Analysis</h3>
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
                                <text x="5" y="255" font-size="12" fill="#666" id="y-label-0">0</text>
                                <text x="5" y="205" font-size="12" fill="#666" id="y-label-1">25</text>
                                <text x="5" y="155" font-size="12" fill="#666" id="y-label-2">50</text>
                                <text x="5" y="105" font-size="12" fill="#666" id="y-label-3">75</text>
                                <text x="5" y="55" font-size="12" fill="#666" id="y-label-4">100</text>

                                <!-- Infrastructure Reports (Blue Bars) -->
                                <g id="infrastructure-bars">
                                    <!-- Dynamic bars will be plotted here by JavaScript -->
                                </g>

                                <!-- Sanitation Reports (Orange Bars) -->
                                <g id="sanitation-bars">
                                    <!-- Dynamic bars will be plotted here by JavaScript -->
                                </g>

                                <!-- X-axis Labels -->
                                <g id="chart-x-labels">
                                    <!-- Dynamic labels will be rendered here by JavaScript -->
                                </g>

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
                                <th>NID Verification</th>
                                <th>Account Status</th>
                                <th>Control Action</th>
                            </tr>
                        </thead>
                        <tbody id="usersTableBody">
                            <tr>
                                <td colspan="7" class="loading-text">Loading platform users...</td>
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
                                        <th style="padding: 0.75rem 0.5rem;">Registered Addresses / Areas</th>
                                        <th style="padding: 0.75rem 0.5rem; text-align: right;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="areasTableBody">
                                    <tr>
                                        <td colspan="2" class="loading-text" style="text-align: center; padding: 2rem;">Loading geographic catalog...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Create New Area Form -->
                    <div class="create-category-box">
                        <h3>Register New Area</h3>
                        <form id="areaCreateForm" class="category-form" onsubmit="handleAreaFormSubmit(event)">
                            <div class="form-group" style="margin-bottom: 1.5rem;">
                                <label for="areaNameInput" style="font-weight: 600; display: block; margin-bottom: 0.25rem;">Area Address / Name *</label>
                                <input type="text" id="areaNameInput" required placeholder="e.g. Road 4, Sector 7, Uttara" style="width:100%; padding:0.6rem; border:1px solid #cbd5e1; border-radius:6px;">
                            </div>
                            <button type="submit" class="btn-create-category" style="width: 100%;">Add Geographic Area</button>
                        </form>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <!-- Issue Details & Before/After Image Comparison Modal -->
    <div class="details-modal" id="issueDetailsModal">
        <div class="details-modal-content">
            <button class="details-modal-close" onclick="closeDetailsModal()">&times;</button>
            <div id="modalDetailsLoading" style="text-align: center; padding: 3rem 0;">
                <p style="color: #64748b; font-weight: 500; font-size: 1.1rem;">Loading ticket details...</p>
            </div>
            <div id="modalDetailsBody" style="display: none;">
                <!-- Content will be populated dynamically by JavaScript -->
            </div>
        </div>
    </div>

    <!-- NID Verification Modal -->
    <div class="details-modal" id="nidVerificationModal">
        <div class="details-modal-content" style="max-width: 750px;">
            <button class="details-modal-close" onclick="closeNidModal()">&times;</button>
            <div id="nidModalBody">
                <!-- Content will be populated dynamically by JavaScript -->
            </div>
        </div>
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

        function closeDetailsModal() {
            const modal = document.getElementById('issueDetailsModal');
            if (modal) {
                modal.classList.remove('active');
                document.body.style.overflow = 'auto';
            }
        }

        function closeNidModal() {
            const modal = document.getElementById('nidVerificationModal');
            if (modal) {
                modal.classList.remove('active');
                document.body.style.overflow = 'auto';
            }
        }
    </script>
    <script src="{{ asset('js/admin.js') }}"></script>
</body>
</html>
