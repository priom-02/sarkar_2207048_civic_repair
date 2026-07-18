<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Civic Reporting - Empower Your Neighborhood</title>
    <link rel="stylesheet" href="{{ asset('css/citizen.css') }}?v={{ time() }}">
    <!-- Leaflet Mapping Library CDN -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
</head>
<body>
    <!-- Hamburger Toggle Button -->
    <button id="citizenSidebarToggle" onclick="toggleCitizenSidebar()" aria-label="Toggle navigation" style="
        position: fixed;
        top: 1rem;
        left: 1rem;
        z-index: 98;
        background: linear-gradient(135deg, #1e3a5f, #0f2d59);
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
        box-shadow: 0 4px 15px rgba(30,58,95,0.35);
        transition: all 0.25s ease;
    "
    onmouseover="this.style.transform='scale(1.08)'; this.style.boxShadow='0 6px 20px rgba(30,58,95,0.5)';"
    onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='0 4px 15px rgba(30,58,95,0.35)';">
        <span id="cham1" style="display:block;width:22px;height:2.5px;background:white;border-radius:2px;transition:all 0.3s ease;"></span>
        <span id="cham2" style="display:block;width:22px;height:2.5px;background:white;border-radius:2px;transition:all 0.3s ease;"></span>
        <span id="cham3" style="display:block;width:22px;height:2.5px;background:white;border-radius:2px;transition:all 0.3s ease;"></span>
    </button>

    <!-- Sidebar Backdrop -->
    <div id="citizenSidebarBackdrop" onclick="closeCitizenSidebar()" style="
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(15,23,42,0.45);
        z-index: 199;
        backdrop-filter: blur(3px);
    "></div>

    <!-- Left Sidebar Navigation -->
    <aside id="citizenSidebar" style="width: 260px; background: linear-gradient(180deg, #1e3a5f 0%, #0f2d59 100%); color: white; display: flex; flex-direction: column; padding: 1.75rem 1.25rem; position: fixed; top: 0; bottom: 0; left: -270px; z-index: 200; box-shadow: 4px 0 15px rgba(15, 23, 42, 0.12); border-right: 3px solid #2563eb; transition: left 0.35s cubic-bezier(0.4, 0, 0.2, 1);">

        <!-- Logo Section -->
        <div style="display: flex; align-items: center; gap: 0.65rem; margin-bottom: 2rem; padding-bottom: 1.25rem; border-bottom: 1px solid rgba(255,255,255,0.12); cursor: pointer;" onclick="window.location.reload()">
            <div style="width: 34px; height: 34px; flex-shrink: 0;">
                <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg" style="width: 100%; height: 100%;">
                    <path d="M 52,32 L 72,32 C 74,32 76,34 77,35 L 85,43 C 86,44 87,46 87,48 L 87,78 C 87,81 84,84 81,84 L 52,84" fill="none" stroke="#38bdf8" stroke-width="7" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M 72,32 L 72,44 C 72,46 74,48 76,48 L 87,48" fill="none" stroke="#38bdf8" stroke-width="7" stroke-linecap="round" />
                    <line x1="61" y1="58" x2="76" y2="58" stroke="#ffffff" stroke-width="6" stroke-linecap="round" />
                    <line x1="61" y1="66" x2="76" y2="66" stroke="#ffffff" stroke-width="6" stroke-linecap="round" />
                    <line x1="61" y1="74" x2="76" y2="74" stroke="#ffffff" stroke-width="6" stroke-linecap="round" />
                    <path d="M 45,16 C 24,16 8,30 8,48 C 8,58 13,67 21,73 L 18,85 L 30,79 C 35,81 40,82 45,82 C 66,82 82,68 82,48 C 82,30 66,16 45,16 Z" fill="none" stroke="#ffffff" stroke-width="8" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M 21,72 L 21,53 L 29,43 L 29,72 Z" fill="#38bdf8" />
                    <path d="M 31,72 L 31,37 L 41,26 L 41,72 Z" fill="#0ea5e9" />
                    <path d="M 43,72 L 43,47 L 51,37 L 51,72 Z" fill="#0284c7" />
                </svg>
            </div>
            <div>
                <div style="font-size: 1.2rem; font-weight: 800; line-height: 1.1;">civic <span style="color: #f59e0b;">report</span></div>
                <span style="font-size: 0.68rem; opacity: 0.8; font-weight: 500; letter-spacing: 0.07em; text-transform: uppercase;">Citizen Portal</span>
            </div>
            <span style="margin-left: auto; background: #ef4444; color: white; font-size: 0.6rem; font-weight: 800; padding: 2px 6px; border-radius: 4px; letter-spacing: 0.05em;">LIVE</span>
        </div>

        <!-- Navigation Links -->
        <nav style="display: flex; flex-direction: column; gap: 0.35rem; flex: 1;">
            <a href="#home" style="display: flex; align-items: center; gap: 0.75rem; padding: 0.7rem 0.9rem; border-radius: 10px; color: white; text-decoration: none; font-weight: 600; font-size: 0.9rem; background: rgba(255,255,255,0.12); transition: all 0.2s;"
               onmouseover="this.style.background='rgba(255,255,255,0.2)'" onmouseout="this.style.background='rgba(255,255,255,0.12)'">
                <span style="font-size: 1rem;">🏠</span> Home
            </a>
            <a href="#issues-feed" style="display: flex; align-items: center; gap: 0.75rem; padding: 0.7rem 0.9rem; border-radius: 10px; color: rgba(255,255,255,0.75); text-decoration: none; font-weight: 600; font-size: 0.9rem; transition: all 0.2s;"
               onmouseover="this.style.background='rgba(255,255,255,0.12)'; this.style.color='white';" onmouseout="this.style.background='transparent'; this.style.color='rgba(255,255,255,0.75)';">
                <span style="font-size: 1rem;">📡</span> Feed
            </a>
            <a href="#my-reports" style="display: flex; align-items: center; gap: 0.75rem; padding: 0.7rem 0.9rem; border-radius: 10px; color: rgba(255,255,255,0.75); text-decoration: none; font-weight: 600; font-size: 0.9rem; transition: all 0.2s;"
               onmouseover="this.style.background='rgba(255,255,255,0.12)'; this.style.color='white';" onmouseout="this.style.background='transparent'; this.style.color='rgba(255,255,255,0.75)';">
                <span style="font-size: 1rem;">📋</span> My Reports
            </a>
            <button class="btn-report-trigger" style="display: flex; align-items: center; gap: 0.75rem; padding: 0.7rem 0.9rem; border-radius: 10px; color: white; background: linear-gradient(135deg, #0d9488, #0891b2); border: none; font-weight: 700; font-size: 0.9rem; cursor: pointer; transition: all 0.2s; margin-top: 0.5rem; width: 100%; text-align: left;"
                    onmouseover="this.style.opacity='0.85'" onmouseout="this.style.opacity='1'">
                <span style="font-size: 1rem;">✍️</span> Report Issue
            </button>
        </nav>

        <!-- Bottom Profile & Logout -->
        <div style="padding-top: 1.25rem; border-top: 1px solid rgba(255,255,255,0.12); display: flex; flex-direction: column; gap: 0.75rem;">
            <div style="display: flex; align-items: center; gap: 0.65rem; background: rgba(255,255,255,0.07); padding: 0.65rem; border-radius: 10px;">
                <div style="width: 34px; height: 34px; border-radius: 50%; background: linear-gradient(135deg, #0d9488, #0891b2); display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.95rem; flex-shrink: 0;">
                    {{ strtoupper(substr(auth()->user()->full_name, 0, 1)) }}
                </div>
                <div style="overflow: hidden;">
                    <div style="font-weight: 700; font-size: 0.85rem; color: white; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ auth()->user()->full_name }}</div>
                    <div style="font-size: 0.7rem; color: rgba(255,255,255,0.6);">Citizen</div>
                </div>
            </div>
            <form id="citizenLogoutForm" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
            <button type="button"
                    onclick="document.getElementById('citizenLogoutForm').submit();"
                    style="width: 100%; display: flex; align-items: center; justify-content: center; gap: 0.5rem; background: rgba(255,255,255,0.1); color: rgba(255,255,255,0.85); border: 1px solid rgba(255,255,255,0.2); padding: 0.65rem; border-radius: 10px; font-size: 0.85rem; font-weight: 700; cursor: pointer; transition: all 0.2s;"
                    onmouseover="this.style.background='#ef4444'; this.style.color='white'; this.style.borderColor='#ef4444';"
                    onmouseout="this.style.background='rgba(255,255,255,0.1)'; this.style.color='rgba(255,255,255,0.85)'; this.style.borderColor='rgba(255,255,255,0.2)';">
                <span>🚪</span> Logout
            </button>
        </div>
    </aside>

    <!-- Page content offset wrapper -->
    <div id="citizenMain" style="margin-left: 0; min-height: 100vh; padding-top: 4rem; transition: margin-left 0.35s cubic-bezier(0.4, 0, 0.2, 1);">

    <!-- Hero Section -->
    <section class="hero" id="home">
        <div class="hero-content">
            <h1 class="hero-title">Empowering Our Neighborhood Together</h1>
            <p class="hero-subtitle">Report civic issues, track progress, and build a better community</p>
            <div class="hero-actions">
                <button class="btn btn-primary btn-report-trigger">
                    Report an Issue
                </button>
                <button class="btn btn-secondary" onclick="scrollToSection('issues-feed')">
                    View Recent Reports
                </button>
            </div>
        </div>
        <div class="hero-image">
            <div class="hero-monitor-card">
                <div class="monitor-header">
                    <span class="pulse-dot"></span>
                    <span class="monitor-title">Live City Monitor</span>
                    <span class="monitor-badge">Active</span>
                </div>
                <div class="monitor-body">
                    <div class="monitor-stat-row">
                        <div class="monitor-stat-item">
                            <span class="stat-icon" style="font-size: 1.5rem; display: inline-block; vertical-align: middle;">📈</span>
                            <div class="stat-details">
                                <div class="stat-val">{{ $resolutionRate }}%</div>
                                <div class="stat-lbl">Resolution Rate</div>
                            </div>
                        </div>
                        <div class="monitor-stat-item">
                            <span class="stat-icon" style="font-size: 1.5rem; display: inline-block; vertical-align: middle;">⚡</span>
                            <div class="stat-details">
                                <div class="stat-val">{{ $avgResponseTime }}</div>
                                <div class="stat-lbl">Avg Response Time</div>
                            </div>
                        </div>
                    </div>
                    <div class="monitor-activity">
                        <div class="activity-title">Recent Activity Log</div>
                        <div class="activity-list">
                            @forelse($recentActivities as $act)
                                <div class="activity-item">
                                    <span class="activity-time">{{ $act['time'] }}</span>
                                    <span class="activity-text">{{ $act['text'] }}</span>
                                </div>
                            @empty
                                <div class="activity-item">
                                    <span class="activity-text">No recent platform activities recorded.</span>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Search & Filter Bar -->
    <section class="search-filter-bar">
        <div class="container">
            <div class="search-box">
                <input type="text" id="searchInput" placeholder="Search issues..." class="search-input">
                <button class="search-btn">Search</button>
            </div>
            <div class="category-filters">
                <button class="filter-chip active" data-category-id="all">All</button>
                @foreach ($categories as $cat)
                    <button class="filter-chip" data-category-id="{{ $cat->id }}">{{ $cat->category_name }}</button>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Interactive Issues Feed -->
    <section class="issues-feed" id="issues-feed">
        <div class="container">
            <h2 class="section-title">Recent Community Reports</h2>
            
            <div class="feed-stats">
                <div class="stat-card">
                    <div class="stat-number" id="totalIssues">127</div>
                    <div class="stat-label">Total Issues</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number" id="resolvedIssues">45</div>
                    <div class="stat-label">Resolved</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number" id="inProgressIssues">32</div>
                    <div class="stat-label">In Progress</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number" id="totalVotes">1.2K</div>
                    <div class="stat-label">Community Votes</div>
                </div>
            </div>

            <div class="issues-list" id="issuesList">
                <!-- Issues will be loaded here by JavaScript -->
            </div>
        </div>
    </section>


    <!-- My Space (Profile & Reports) Section -->
    <section class="leaderboard" id="my-reports" style="background: #f8fafc; border-top: 1px solid #e2e8f0; border-bottom: 1px solid #e2e8f0; padding-top: 4rem; padding-bottom: 4rem;">
        <div class="container">
            <h2 class="section-title">My Space</h2>
            
            <div class="myspace-grid" style="display: grid; grid-template-columns: 1fr 2.2fr; gap: 2.5rem; margin-top: 2rem;">
                <!-- Profile details -->
                <div style="background: white; border-radius: 16px; padding: 2rem; box-shadow: 0 4px 20px rgba(0,0,0,0.05); border: 1px solid #edf2f7; display: flex; flex-direction: column; gap: 1.25rem;">
                    <h3 style="font-size: 1.25rem; font-weight: 700; color: #0f172a; border-bottom: 2px solid #edf2f7; padding-bottom: 0.75rem; margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.5rem;">My Profile Details</h3>
                    <div>
                        <span style="font-size: 0.8rem; color: #718096; font-weight: 600; text-transform: uppercase; display: block; letter-spacing: 0.05em;">Full Name</span>
                        <span style="font-size: 1.1rem; font-weight: 700; color: #2d3748;">{{ auth()->user()->full_name }}</span>
                    </div>
                    <div>
                        <span style="font-size: 0.8rem; color: #718096; font-weight: 600; text-transform: uppercase; display: block; letter-spacing: 0.05em;">Email Address</span>
                        <span style="font-size: 1.1rem; font-weight: 700; color: #2d3748;">{{ auth()->user()->email }}</span>
                    </div>
                    <div>
                        <span style="font-size: 0.8rem; color: #718096; font-weight: 600; text-transform: uppercase; display: block; letter-spacing: 0.05em;">Phone Number</span>
                        <span style="font-size: 1.1rem; font-weight: 700; color: #2d3748;">{{ auth()->user()->phone ?? 'N/A' }}</span>
                    </div>
                    <div>
                        <span style="font-size: 0.8rem; color: #718096; font-weight: 600; text-transform: uppercase; display: block; letter-spacing: 0.05em;">Role Type</span>
                        <span style="font-size: 1.1rem; font-weight: 700; color: #2d3748;">Citizen Member</span>
                    </div>
                    <div>
                        <span style="font-size: 0.8rem; color: #718096; font-weight: 600; text-transform: uppercase; display: block; letter-spacing: 0.05em;">Account Status</span>
                        <span style="background: #c6f6d5; color: #22543d; font-size: 0.85rem; font-weight: 700; padding: 0.35rem 0.85rem; border-radius: 9999px; display: inline-block; margin-top: 0.25rem;">Active Account</span>
                    </div>
                </div>

                <!-- My Reports Table -->
                <div style="background: white; border-radius: 16px; padding: 2rem; box-shadow: 0 4px 20px rgba(0,0,0,0.05); border: 1px solid #edf2f7; display: flex; flex-direction: column; gap: 1.5rem;">
                    <h3 style="font-size: 1.25rem; font-weight: 700; color: #0f172a; border-bottom: 2px solid #edf2f7; padding-bottom: 0.75rem; margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.5rem;">My Reported Issues</h3>
                    
                    <div style="overflow-x: auto;">
                        <table style="width: 100%; border-collapse: collapse; text-align: left;">
                            <thead>
                                <tr style="border-bottom: 2px solid #edf2f7; color: #718096; font-weight: 700; font-size: 0.85rem; text-transform: uppercase;">
                                    <th style="padding: 0.75rem 0.5rem;">Issue Title</th>
                                    <th style="padding: 0.75rem 0.5rem;">Category</th>
                                    <th style="padding: 0.75rem 0.5rem;">Current Status</th>
                                    <th style="padding: 0.75rem 0.5rem;">Upvotes</th>
                                    <th style="padding: 0.75rem 0.5rem; text-align: right;">Action</th>
                                </tr>
                            </thead>
                            <tbody id="myReportsTableBody">
                                <tr>
                                    <td colspan="5" style="text-align: center; color: #a0aec0; padding: 3rem 0; font-style: italic;">Loading your reports...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Report Issue Modal (Hidden by default) -->
    <div class="modal" id="reportModal">
        <div class="modal-content">
            <button class="modal-close" onclick="closeModal('reportModal')">&times;</button>
            <h2>Report a New Issue</h2>
            
            <form id="reportForm">
                <div class="form-group">
                    <label for="issueTitle">Issue Title</label>
                    <input type="text" id="issueTitle" placeholder="Brief description of the issue" required>
                </div>

                <div class="form-group">
                    <label for="issueDescription">Detailed Description</label>
                    <textarea id="issueDescription" placeholder="Provide more details..." rows="4" required></textarea>
                </div>

                <div class="form-group" style="margin-bottom: 1.25rem;">
                    <label for="issueCategory" style="font-weight: 600; color: #1e293b; display: block; margin-bottom: 0.5rem;">Category *</label>
                    <select id="issueCategory" required style="width: 100%; padding: 0.75rem; border: 1.5px solid #cbd5e1; border-radius: 10px; font-family: inherit; font-size: 0.95rem; color: #1e293b;">
                        <option value="">Select a category</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->category_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group" style="margin-bottom: 1.25rem;">
                    <label for="issueArea" style="font-weight: 600; color: #1e293b; display: block; margin-bottom: 0.5rem;">Area Address *</label>
                    <input type="text" id="issueArea" name="area_name" placeholder="e.g. Road 4, Sector 7, Uttara" required style="width: 100%; padding: 0.75rem; border: 1.5px solid #cbd5e1; border-radius: 10px; font-family: inherit; font-size: 0.95rem; color: #1e293b;">
                </div>

                <input type="hidden" id="issueLat" name="latitude">
                <input type="hidden" id="issueLng" name="longitude">

                <div class="form-group">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                        <label style="font-weight: 600; color: #1e293b;">Select Location on Map *</label>
                        <button type="button" onclick="locateUser()" style="background: #3182ce; color: white; border: none; border-radius: 6px; padding: 0.25rem 0.5rem; font-size: 0.75rem; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 0.25rem;">
                            <span>📍</span> Locate Me
                        </button>
                    </div>
                    <div id="locationSelectorMap" style="height: 240px; border-radius: 12px; border: 1px solid #cbd5e1; margin-bottom: 0.5rem; z-index: 1;"></div>
                    <small style="color: #64748b; font-size: 0.8rem; display: block; margin-bottom: 1rem;">Click on the map or drag the pin to mark the exact coordinates of the issue.</small>
                </div>

                <div class="form-group">
                    <label for="issuePhoto">Upload Photo</label>
                    <input type="file" id="issuePhoto" accept="image/*" multiple>
                </div>

                <button type="submit" class="btn btn-primary btn-full">Report Issue</button>
            </form>
        </div>
    </div>

    <!-- Issue Details Modal (Hidden by default) -->
    <div class="modal details-modal" id="detailsModal">
        <div class="modal-content details-modal-content">
            <button class="modal-close" onclick="closeModal('detailsModal')">&times;</button>
            <div class="details-header">
                <span class="issue-status" id="detailStatus">PENDING</span>
                <h2 id="detailTitle" class="detail-title">Loading Issue Details...</h2>
                <div class="issue-meta" style="margin-top: 0.5rem;">
                    <span id="detailCategory">🛣️ Category</span>
                    <span id="detailArea">📍 Area</span>
                    <span id="detailTime">🕐 Time</span>
                </div>
            </div>
            
            <div class="details-tabs">
                <button class="tab-btn active" onclick="switchTab('overview')">Overview & Progress</button>
                <button class="tab-btn" onclick="switchTab('comments')">Discussion Feed (<span id="detailCommentCount">0</span>)</button>
            </div>
            
            <div class="tab-content active" id="tab-overview">
                <div class="overview-grid">
                    <div class="overview-left">
                        <label class="modal-label">Description</label>
                        <p id="detailDescription" class="detail-description"></p>
                        
                        <label class="modal-label" style="margin-top: 1.5rem;">Location Details</label>
                        <p id="detailCoordinates" class="detail-coordinates"></p>
                        
                        <label class="modal-label" style="margin-top: 1.5rem;">Photos & Evidence</label>
                        <div id="detailMediaGallery" class="issue-media-gallery">
                            <!-- Photos loaded dynamically -->
                        </div>

                        <!-- Feedback Panel (Only shows if own report is resolved) -->
                        <div id="feedbackSection" style="display: none; margin-top: 2rem; background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 12px; padding: 1.25rem;">
                            <h3 style="font-size: 1.1rem; color: #166534; font-weight: 700; margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.35rem;">🌟 Review Ticket Resolution</h3>
                            <p style="font-size: 0.88rem; color: #1e3a1e; margin-bottom: 1rem; line-height: 1.4;">The worker has marked this ticket as resolved. Please review and let us know if you are satisfied, or if you need to reopen the issue.</p>
                            
                            <form id="feedbackForm" onsubmit="handleFeedbackSubmit(event)">
                                <div style="margin-bottom: 1rem;">
                                    <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #166534; margin-bottom: 0.25rem;">Rating:</label>
                                    <select id="feedbackRating" style="width: 100%; padding: 0.5rem; border: 1px solid #86efac; border-radius: 6px; font-weight: 600;">
                                        <option value="5">⭐⭐⭐⭐⭐ (Excellent)</option>
                                        <option value="4">⭐⭐⭐⭐ (Good)</option>
                                        <option value="3">⭐⭐⭐ (Average)</option>
                                        <option value="2">⭐⭐ (Poor)</option>
                                        <option value="1">⭐ (Very Unsatisfied)</option>
                                    </select>
                                </div>
                                <div style="margin-bottom: 1rem;">
                                    <label style="display: block; font-size: 0.85rem; font-weight: 700; color: #166534; margin-bottom: 0.25rem;">Comment / Reopen Reason:</label>
                                    <textarea id="feedbackComment" placeholder="Explain your experience or why you are reopening..." rows="3" style="width: 100%; padding: 0.5rem; border: 1px solid #86efac; border-radius: 6px; resize: vertical;"></textarea>
                                </div>
                                <div style="display: flex; gap: 0.75rem;">
                                    <button type="submit" onclick="submitFeedbackAction('satisfied')" class="btn btn-primary" style="flex: 1; padding: 0.6rem; font-size: 0.85rem; background: #16a34a; border-color: #16a34a;">I am Satisfied (Close Ticket)</button>
                                    <button type="submit" onclick="submitFeedbackAction('reopen')" class="btn btn-secondary" style="flex: 1; padding: 0.6rem; font-size: 0.85rem; background: #dc2626; border-color: #dc2626; color: white;">Re-open Ticket</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="overview-right">
                        <label class="modal-label">Status & Progress Timeline</label>
                        <div id="detailTimeline" class="vertical-timeline">
                            <!-- Timeline steps loaded dynamically -->
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="tab-content" id="tab-comments">
                <div class="comments-container">
                    <div class="comments-list" id="detailCommentsList">
                        <!-- Comments loaded dynamically -->
                    </div>
                    
                    <form id="commentSubmitForm" class="comment-submit-form" onsubmit="submitComment(event)">
                        @csrf
                        <div class="comment-input-wrapper">
                            <textarea id="newCommentBody" placeholder="Write a comment, share feedback, or ask for an update..." rows="3" required></textarea>
                            <button type="submit" class="btn btn-primary" id="btnSubmitComment">Post Comment</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Overlay for modals -->
    <div class="modal-overlay" id="modalOverlay" onclick="closeCurrentModal()"></div>

    <script>
        window.AppConfig = {
            apiBaseUrl: "{{ request()->getBaseUrl() }}"
        };
        window.allAreas = @json($areas);

        // ── Citizen Sidebar Toggle ───────────────────────────────────────
        let citizenSidebarOpen = false;

        function openCitizenSidebar() {
            citizenSidebarOpen = true;
            document.getElementById('citizenSidebar').style.left = '0';
            document.getElementById('citizenMain').style.marginLeft = '260px';
            document.getElementById('citizenSidebarBackdrop').style.display = 'block';
            document.getElementById('cham1').style.transform = 'translateY(7.5px) rotate(45deg)';
            document.getElementById('cham2').style.opacity  = '0';
            document.getElementById('cham3').style.transform = 'translateY(-7.5px) rotate(-45deg)';
        }

        function closeCitizenSidebar() {
            citizenSidebarOpen = false;
            document.getElementById('citizenSidebar').style.left = '-270px';
            document.getElementById('citizenMain').style.marginLeft = '0';
            document.getElementById('citizenSidebarBackdrop').style.display = 'none';
            document.getElementById('cham1').style.transform = 'none';
            document.getElementById('cham2').style.opacity  = '1';
            document.getElementById('cham3').style.transform = 'none';
        }

        function toggleCitizenSidebar() {
            if (citizenSidebarOpen) { closeCitizenSidebar(); } else { openCitizenSidebar(); }
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && citizenSidebarOpen) closeCitizenSidebar();
        });
    </script>
    <script src="{{ asset('js/citizen.js') }}"></script>
</body>
</html>
