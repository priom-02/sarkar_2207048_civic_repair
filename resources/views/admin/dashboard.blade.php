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
    <!-- Hamburger Toggle Button -->
    <button id="sidebarToggle" onclick="toggleSidebar()" aria-label="Toggle navigation" style="
        position: fixed;
        top: 1rem;
        left: 1rem;
        z-index: 98;
        background: linear-gradient(135deg, #0d9488, #0f766e);
        border: none;
        border-radius: 10px;
        width: 44px;
        height: 44px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 5px;
        cursor: pointer;
        box-shadow: 0 4px 15px rgba(13,148,136,0.35);
        transition: all 0.25s ease;
    "
    onmouseover="this.style.transform='scale(1.08)'; this.style.boxShadow='0 6px 20px rgba(13,148,136,0.5)';"
    onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='0 4px 15px rgba(13,148,136,0.35)';">
        <span class="ham-line" id="ham1" style="display:block;width:22px;height:2.5px;background:white;border-radius:2px;transition:all 0.3s ease;"></span>
        <span class="ham-line" id="ham2" style="display:block;width:22px;height:2.5px;background:white;border-radius:2px;transition:all 0.3s ease;"></span>
        <span class="ham-line" id="ham3" style="display:block;width:22px;height:2.5px;background:white;border-radius:2px;transition:all 0.3s ease;"></span>
    </button>

    <!-- Sidebar Backdrop (mobile overlay) -->
    <div id="sidebarBackdrop" onclick="closeSidebar()" style="
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(15,23,42,0.45);
        z-index: 99;
        backdrop-filter: blur(3px);
        transition: opacity 0.3s;
    "></div>

    <div class="admin-container" style="display: flex; min-height: 100vh; background: #f8fafc;">
        <!-- Left Sidebar Navigation -->
        <aside class="admin-sidebar" id="adminSidebar" style="width: 280px; background: linear-gradient(180deg, #0d9488 0%, #0f766e 100%); color: white; display: flex; flex-direction: column; padding: 2rem 1.5rem; position: fixed; top: 0; bottom: 0; left: -290px; z-index: 100; box-shadow: 4px 0 15px rgba(15, 23, 42, 0.08); border-right: 4px solid #0f766e; transition: left 0.35s cubic-bezier(0.4, 0, 0.2, 1);">
            <!-- Logo Section -->
            <div class="sidebar-logo" style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 2.5rem; padding-bottom: 1.5rem; border-bottom: 1px solid rgba(255,255,255,0.15);">
                <div style="width: 35px; height: 35px;">
                    <svg class="custom-logo-svg" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg" style="width: 100%; height: 100%;">
                        <!-- Document sheet -->
                        <path d="M 52,32 L 72,32 C 74,32 76,34 77,35 L 85,43 C 86,44 87,46 87,48 L 87,78 C 87,81 84,84 81,84 L 52,84" fill="none" stroke="#38bdf8" stroke-width="7" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M 72,32 L 72,44 C 72,46 74,48 76,48 L 87,48" fill="none" stroke="#38bdf8" stroke-width="7" stroke-linecap="round" />
                        <!-- Lines on document -->
                        <line x1="61" y1="58" x2="76" y2="58" stroke="#ffffff" stroke-width="6" stroke-linecap="round" />
                        <line x1="61" y1="66" x2="76" y2="66" stroke="#ffffff" stroke-width="6" stroke-linecap="round" />
                        <line x1="61" y1="74" x2="76" y2="74" stroke="#ffffff" stroke-width="6" stroke-linecap="round" />
                        <!-- Speech bubble -->
                        <path d="M 45,16 C 24,16 8,30 8,48 C 8,58 13,67 21,73 L 18,85 L 30,79 C 35,81 40,82 45,82 C 66,82 82,68 82,48 C 82,30 66,16 45,16 Z" fill="none" stroke="#ffffff" stroke-width="8" stroke-linecap="round" stroke-linejoin="round" />
                        <!-- Buildings inside speech bubble -->
                        <path d="M 21,72 L 21,53 L 29,43 L 29,72 Z" fill="#38bdf8" />
                        <path d="M 31,72 L 31,37 L 41,26 L 41,72 Z" fill="#0ea5e9" />
                        <path d="M 43,72 L 43,47 L 51,37 L 51,72 Z" fill="#0284c7" />
                    </svg>
                </div>
                <div>
                    <h1 style="font-size: 1.3rem; font-weight: 800; margin: 0; line-height: 1.2;">civic <span style="color: #f59e0b;">report</span></h1>
                    <span style="font-size: 0.75rem; opacity: 0.85; font-weight: 500; letter-spacing: 0.05em; text-transform: uppercase;">Admin Console</span>
                </div>
            </div>

            <!-- Menu Navigation Links -->
            <nav class="sidebar-nav" style="display: flex; flex-direction: column; gap: 0.5rem; flex: 1;">
                <button class="tab-btn active" onclick="switchTab('analytics', this)" style="background: rgba(255,255,255,0.15); border: none; border-radius: 10px; font-family: inherit; font-size: 0.95rem; font-weight: 700; padding: 0.75rem 1rem; cursor: pointer; color: white; display: flex; align-items: center; gap: 0.75rem; transition: all 0.2s; text-align: left; width: 100%;">
                    <span style="font-size: 1.1rem;">📊</span> Analytics
                </button>
                <button class="tab-btn" onclick="switchTab('complaints', this)" style="background: none; border: none; border-radius: 10px; font-family: inherit; font-size: 0.95rem; font-weight: 700; padding: 0.75rem 1rem; cursor: pointer; color: rgba(255,255,255,0.75); display: flex; align-items: center; gap: 0.75rem; transition: all 0.2s; text-align: left; width: 100%;">
                    <span style="font-size: 1.1rem;">📋</span> Complaints
                </button>
                <button class="tab-btn" onclick="switchTab('categories', this)" style="background: none; border: none; border-radius: 10px; font-family: inherit; font-size: 0.95rem; font-weight: 700; padding: 0.75rem 1rem; cursor: pointer; color: rgba(255,255,255,0.75); display: flex; align-items: center; gap: 0.75rem; transition: all 0.2s; text-align: left; width: 100%;">
                    <span style="font-size: 1.1rem;">🗂️</span> Categories
                </button>
                <button class="tab-btn" onclick="switchTab('users', this)" style="background: none; border: none; border-radius: 10px; font-family: inherit; font-size: 0.95rem; font-weight: 700; padding: 0.75rem 1rem; cursor: pointer; color: rgba(255,255,255,0.75); display: flex; align-items: center; gap: 0.75rem; transition: all 0.2s; text-align: left; width: 100%;">
                    <span style="font-size: 1.1rem;">👥</span> Users
                </button>
            </nav>

            <!-- Bottom Profile & Logout -->
            <div class="sidebar-footer" style="padding-top: 1.5rem; border-top: 1px solid rgba(255,255,255,0.15); display: flex; flex-direction: column; gap: 1rem;">
                <div style="display: flex; align-items: center; gap: 0.75rem; background: rgba(255, 255, 255, 0.08); padding: 0.75rem; border-radius: 12px;">
                    <div style="font-size: 1.5rem;">👤</div>
                    <div style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                        <div style="font-weight: 700; font-size: 0.9rem; color: white;">{{ auth()->user()->full_name }}</div>
                        <div style="font-size: 0.75rem; color: rgba(255, 255, 255, 0.7);">System Admin</div>
                    </div>
                </div>
                <form id="logoutForm" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
                <button type="button" 
                        onclick="document.getElementById('logoutForm').submit();"
                        style="width: 100%; display: flex; align-items: center; justify-content: center; gap: 0.5rem; background: rgba(255, 255, 255, 0.1); color: rgba(255, 255, 255, 0.85); border: 1px solid rgba(255, 255, 255, 0.25); padding: 0.75rem; border-radius: 10px; font-size: 0.9rem; font-weight: 700; cursor: pointer; transition: all 0.2s;"
                        onmouseover="this.style.background='#ef4444'; this.style.color='white'; this.style.borderColor='#ef4444';"
                        onmouseout="this.style.background='rgba(255, 255, 255, 0.1)'; this.style.color='rgba(255, 255, 255, 0.85)'; this.style.borderColor='rgba(255, 255, 255, 0.25)';">
                    <span>🚪</span> Logout
                </button>
            </div>
        </aside>

        <!-- Main Content Area -->
        <main class="admin-main" id="adminMain" style="flex: 1; margin-left: 0; padding: 4.5rem 2.5rem 2.5rem; width: 100%; min-height: 100vh; transition: margin-left 0.35s cubic-bezier(0.4, 0, 0.2, 1);">
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
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
                    <h2 style="margin: 0; font-size: 1.5rem;">Complaint Assignment & Dispatch Board</h2>
                    
                    <!-- Priority Filter Dropdown -->
                    <div style="display: flex; align-items: center; gap: 0.5rem; background: white; padding: 0.5rem 1rem; border-radius: 8px; border: 1px solid #cbd5e1; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                        <label for="priorityFilter" style="font-weight: 700; color: #475569; font-size: 0.9rem;">Filter Priority:</label>
                        <select id="priorityFilter" onchange="filterIssuesByPriority()" style="border: none; outline: none; font-weight: 600; color: #0f172a; font-size: 0.9rem; cursor: pointer;">
                            <option value="all">All Priorities</option>
                            <option value="High">🔴 High Priority (40+ votes)</option>
                            <option value="Medium">🟡 Medium Priority (20+ votes)</option>
                            <option value="Low">🟢 Low Priority (&lt;20 votes)</option>
                        </select>
                    </div>
                </div>

                <div class="table-container">
                    <table class="assignments-table">
                        <thead>
                            <tr>
                                <th>Issue Details</th>
                                <th>Category & Area</th>
                                <th>Reported By</th>
                                <th>Priority & Upvotes</th>
                                <th>Current Status</th>
                                <th>Details Action</th>
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
                                <th>NID Status</th>
                                <th>Account Status</th>
                                <th>Details</th>
                                <th>Control Action</th>
                            </tr>
                        </thead>
                        <tbody id="usersTableBody">
                            <tr>
                                <td colspan="8" class="loading-text">Loading platform users...</td>
                            </tr>
                        </tbody>
                    </table>
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

        // ── Sidebar Toggle ──────────────────────────────────────────────
        let sidebarOpen = false;

        function openSidebar() {
            sidebarOpen = true;
            const sidebar = document.getElementById('adminSidebar');
            const main    = document.getElementById('adminMain');
            const backdrop = document.getElementById('sidebarBackdrop');
            sidebar.style.left = '0';
            main.style.marginLeft = '280px';
            main.style.width = 'calc(100% - 280px)';
            backdrop.style.display = 'block';
            // Animate hamburger to X
            document.getElementById('ham1').style.transform = 'translateY(7.5px) rotate(45deg)';
            document.getElementById('ham2').style.opacity  = '0';
            document.getElementById('ham3').style.transform = 'translateY(-7.5px) rotate(-45deg)';
        }

        function closeSidebar() {
            sidebarOpen = false;
            const sidebar = document.getElementById('adminSidebar');
            const main    = document.getElementById('adminMain');
            const backdrop = document.getElementById('sidebarBackdrop');
            sidebar.style.left = '-290px';
            main.style.marginLeft = '0';
            main.style.width = '100%';
            backdrop.style.display = 'none';
            // Reset hamburger lines
            document.getElementById('ham1').style.transform = 'none';
            document.getElementById('ham2').style.opacity  = '1';
            document.getElementById('ham3').style.transform = 'none';
        }

        function toggleSidebar() {
            if (sidebarOpen) { closeSidebar(); } else { openSidebar(); }
        }

        // Close sidebar with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && sidebarOpen) closeSidebar();
        });

        function switchTab(tabId, btn) {
            // Hide all sections
            document.getElementById('analyticsSection').style.display = 'none';
            document.getElementById('complaintsSection').style.display = 'none';
            document.getElementById('categoriesSection').style.display = 'none';
            document.getElementById('usersSection').style.display = 'none';

            // Show selected section
            document.getElementById(tabId + 'Section').style.display = 'block';

            // Update tab button styles
            document.querySelectorAll('.tab-btn').forEach(button => {
                button.classList.remove('active');
                button.style.color = 'rgba(255,255,255,0.8)';
                button.style.background = 'none';
            });

            btn.classList.add('active');
            btn.style.color = 'white';
            btn.style.background = 'rgba(255,255,255,0.15)';

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
