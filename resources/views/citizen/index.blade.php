<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Civic Reporting - Empower Your Neighborhood</title>
    <link rel="stylesheet" href="{{ asset('css/citizen.css') }}">
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar">
        <div class="navbar-container">
            <div class="navbar-logo" onclick="window.location.reload()">
                <div class="logo-icon-container">
                    <svg class="custom-logo-svg" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                        <!-- Document sheet -->
                        <path d="M 52,32 L 72,32 C 74,32 76,34 77,35 L 85,43 C 86,44 87,46 87,48 L 87,78 C 87,81 84,84 81,84 L 52,84" fill="none" stroke="#3182ce" stroke-width="7" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M 72,32 L 72,44 C 72,46 74,48 76,48 L 87,48" fill="none" stroke="#3182ce" stroke-width="7" stroke-linecap="round" />
                        <!-- Lines on document -->
                        <line x1="61" y1="58" x2="76" y2="58" stroke="#0f2d59" stroke-width="6" stroke-linecap="round" />
                        <line x1="61" y1="66" x2="76" y2="66" stroke="#0f2d59" stroke-width="6" stroke-linecap="round" />
                        <line x1="61" y1="74" x2="76" y2="74" stroke="#0f2d59" stroke-width="6" stroke-linecap="round" />
                        <!-- Speech bubble -->
                        <path d="M 45,16 C 24,16 8,30 8,48 C 8,58 13,67 21,73 L 18,85 L 30,79 C 35,81 40,82 45,82 C 66,82 82,68 82,48 C 82,30 66,16 45,16 Z" fill="none" stroke="#0f2d59" stroke-width="8" stroke-linecap="round" stroke-linejoin="round" />
                        <!-- Buildings inside speech bubble -->
                        <path d="M 21,72 L 21,53 L 29,43 L 29,72 Z" fill="#4299e1" />
                        <path d="M 31,72 L 31,37 L 41,26 L 41,72 Z" fill="#3182ce" />
                        <path d="M 43,72 L 43,47 L 51,37 L 51,72 Z" fill="#2b6cb0" />
                    </svg>
                </div>
                <span class="logo-text">civic <span class="logo-text-accent">report</span></span>
                <span class="logo-live-tag">LIVE</span>
            </div>
            <ul class="nav-menu">
                <li><a href="#home" class="nav-link">Home</a></li>
                <li><a href="#issues-feed" class="nav-link">Feed</a></li>
                <li><a href="#leaderboard" class="nav-link">Leaderboard</a></li>
                <li><a href="#" class="nav-link btn-report">Report Issue</a></li>
                <li class="navbar-user-profile">
                    <span class="navbar-user-name">👤 {{ auth()->user()->full_name }}</span>
                    <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn-logout">Logout</button>
                    </form>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero" id="home">
        <div class="hero-content">
            <h1 class="hero-title">Empowering Our Neighborhood Together</h1>
            <p class="hero-subtitle">Report civic issues, track progress, and build a better community</p>
            <div class="hero-actions">
                <button class="btn btn-primary btn-report-trigger">
                    📍 Report an Issue
                </button>
                <button class="btn btn-secondary" onclick="scrollToSection('issues-feed')">
                    👀 View Recent Reports
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
                            <span class="stat-icon">📈</span>
                            <div class="stat-details">
                                <div class="stat-val">86.4%</div>
                                <div class="stat-lbl">Resolution Rate</div>
                            </div>
                        </div>
                        <div class="monitor-stat-item">
                            <span class="stat-icon">⚡</span>
                            <div class="stat-details">
                                <div class="stat-val">&lt; 24h</div>
                                <div class="stat-lbl">Avg Response Time</div>
                            </div>
                        </div>
                    </div>
                    <div class="monitor-activity">
                        <div class="activity-title">Recent Activity Log</div>
                        <div class="activity-list">
                            <div class="activity-item">
                                <span class="activity-time">Just now</span>
                                <span class="activity-text">🚨 Street Light issue reported in Dhanmondi</span>
                            </div>
                            <div class="activity-item">
                                <span class="activity-time">10m ago</span>
                                <span class="activity-text">✅ Pothole repair completed in Mirpur</span>
                            </div>
                            <div class="activity-item">
                                <span class="activity-time">45m ago</span>
                                <span class="activity-text">👷 Worker assigned to water leakage in Gulshan</span>
                            </div>
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
                <button class="search-btn">🔍</button>
            </div>
            <div class="category-filters">
                <button class="filter-chip active" data-category-id="all">All</button>
                @foreach ($categories as $cat)
                    @php
                        $emoji = '📋';
                        $lowerName = strtolower($cat->category_name);
                        if (str_contains($lowerName, 'road') || str_contains($lowerName, 'pothole')) $emoji = '🛣️';
                        elseif (str_contains($lowerName, 'garbage') || str_contains($lowerName, 'waste')) $emoji = '♻️';
                        elseif (str_contains($lowerName, 'light') || str_contains($lowerName, 'safety')) $emoji = '🚨';
                        elseif (str_contains($lowerName, 'water')) $emoji = '💧';
                        elseif (str_contains($lowerName, 'sewer') || str_contains($lowerName, 'drain')) $emoji = '🚽';
                        elseif (str_contains($lowerName, 'power') || str_contains($lowerName, 'electric')) $emoji = '⚡';
                        elseif (str_contains($lowerName, 'traffic')) $emoji = '🚦';
                        elseif (str_contains($lowerName, 'tree') || str_contains($lowerName, 'vegetation')) $emoji = '🌳';
                    @endphp
                    <button class="filter-chip" data-category-id="{{ $cat->id }}">{{ $emoji }} {{ $cat->category_name }}</button>
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

    <!-- Community Leaderboard -->
    <section class="leaderboard" id="leaderboard">
        <div class="container">
            <h2 class="section-title">Community Leaders</h2>
            <div class="leaderboard-list" id="leaderboardList">
                <!-- Leaderboard will be loaded here by JavaScript -->
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

                <div class="form-row">
                    <div class="form-group">
                        <label for="issueCategory">Category</label>
                        <select id="issueCategory" required>
                            <option value="">Select a category</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->category_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="issueArea">Area</label>
                        <select id="issueArea" required>
                            <option value="">Select an area</option>
                            @foreach ($areas as $area)
                                <option value="{{ $area->id }}">{{ $area->area_name }}</option>
                            @endforeach
                        </select>
                    </div>
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
    </script>
    <script src="{{ asset('js/citizen.js') }}"></script>
</body>
</html>
