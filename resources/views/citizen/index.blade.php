<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Civic Reporting - Empower Your Neighborhood</title>
    <link rel="stylesheet" href="{{ asset('css/citizen.css') }}">
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar">
        <div class="navbar-container">
            <div class="navbar-logo">
                <span class="logo-icon">🏛️</span>
                <span class="logo-text">CivicReport</span>
            </div>
            <ul class="nav-menu">
                <li><a href="#home" class="nav-link">Home</a></li>
                <li><a href="#feed" class="nav-link">Feed</a></li>
                <li><a href="#dashboard" class="nav-link">Dashboard</a></li>
                <li><a href="#" class="nav-link">My Issues</a></li>
                <li><a href="#" class="nav-link btn-report">Report Issue</a></li>
            </ul>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero" id="home">
        <div class="hero-content">
            <h1 class="hero-title">Empowering Our Neighborhood Together</h1>
            <p class="hero-subtitle">Report civic issues, track progress, and build a better community</p>
            <div class="hero-actions">
                <button class="btn btn-primary" onclick="scrollToSection('report-issue')">
                    📍 Report an Issue
                </button>
                <button class="btn btn-secondary" onclick="scrollToSection('issues-feed')">
                    👀 View Recent Reports
                </button>
            </div>
        </div>
        <div class="hero-image">
            <div class="hero-placeholder">
                <span>🚨 Community Issues</span>
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
                <button class="filter-chip active" data-category="all">All</button>
                <button class="filter-chip" data-category="road">🛣️ Road Maintenance</button>
                <button class="filter-chip" data-category="waste">♻️ Waste Management</button>
                <button class="filter-chip" data-category="safety">🚨 Public Safety</button>
                <button class="filter-chip" data-category="water">💧 Water/Utilities</button>
                <button class="filter-chip" data-category="other">📋 Other</button>
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
    <section class="leaderboard">
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
                            <option value="road">Road Maintenance</option>
                            <option value="waste">Waste Management</option>
                            <option value="safety">Public Safety</option>
                            <option value="water">Water/Utilities</option>
                            <option value="other">Other</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="issueArea">Area</label>
                        <select id="issueArea" required>
                            <option value="">Select an area</option>
                            <option value="downtown">Downtown</option>
                            <option value="westside">West Side</option>
                            <option value="eastside">East Side</option>
                            <option value="northside">North Side</option>
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

    <!-- Overlay for modals -->
    <div class="modal-overlay" id="modalOverlay" onclick="closeCurrentModal()"></div>

    <script src="{{ asset('js/citizen.js') }}"></script>
</body>
</html>
