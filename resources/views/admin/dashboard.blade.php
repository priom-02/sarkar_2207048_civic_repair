<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Civic Reporting</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="navbar-container">
            <div class="navbar-logo">
                <span class="logo-icon">👨‍💼</span>
                <span class="logo-text">Admin Dashboard</span>
            </div>
            <div class="navbar-menu">
                <a href="#" class="nav-link">Issues</a>
                <a href="#" class="nav-link">Workers</a>
                <a href="#" class="nav-link">Reports</a>
                <a href="#" class="nav-link">Profile</a>
                <form action="{{ route('logout') }}" method="POST" class="logout-form">
                    @csrf
                    <button type="submit" class="nav-link logout-btn">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container">
        <header class="dashboard-header">
            <h1>Welcome, {{ auth()->user()->full_name }}! 👨‍💼</h1>
            <p>Manage civic issues, workers, and platform operations</p>
        </header>

        <div class="dashboard-grid">
            <!-- Stats -->
            <div class="stats-section">
                <div class="stat-card">
                    <div class="stat-number">127</div>
                    <div class="stat-label">Total Issues</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">45</div>
                    <div class="stat-label">Resolved</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">32</div>
                    <div class="stat-label">In Progress</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">12</div>
                    <div class="stat-label">Workers</div>
                </div>
            </div>

            <!-- Issues Management -->
            <div class="tasks-section">
                <h2>Issue Management</h2>
                <div class="placeholder">
                    <p>Issue management interface coming soon...</p>
                </div>
            </div>

            <!-- Workers Management -->
            <div class="activity-section">
                <h2>Workers Management</h2>
                <div class="placeholder">
                    <p>Worker management interface coming soon...</p>
                </div>
            </div>

            <!-- System Reports -->
            <div class="activity-section">
                <h2>System Reports</h2>
                <div class="placeholder">
                    <p>System reports coming soon...</p>
                </div>
            </div>
        </div>
    </div>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f9fafb;
            color: #1f2937;
        }

        .navbar {
            background: white;
            border-bottom: 1px solid #e5e7eb;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .navbar-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 1rem 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar-logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: bold;
            font-size: 1.3rem;
            color: #2563eb;
        }

        .logo-icon {
            font-size: 1.5rem;
        }

        .navbar-menu {
            display: flex;
            gap: 2rem;
            align-items: center;
        }

        .nav-link {
            text-decoration: none;
            color: #1f2937;
            font-weight: 500;
            transition: color 0.3s;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 0.95rem;
        }

        .nav-link:hover {
            color: #2563eb;
        }

        .logout-btn {
            background: #ef4444;
            color: white !important;
            padding: 0.5rem 1rem;
            border-radius: 6px;
        }

        .logout-btn:hover {
            background: #dc2626;
        }

        .logout-form {
            margin: 0;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem 20px;
        }

        .dashboard-header {
            margin-bottom: 2rem;
        }

        .dashboard-header h1 {
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }

        .dashboard-header p {
            color: #6b7280;
            font-size: 1rem;
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
        }

        .stats-section {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            grid-column: 1 / -1;
        }

        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            text-align: center;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            color: #2563eb;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            color: #6b7280;
            font-size: 0.9rem;
        }

        .tasks-section,
        .activity-section {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .tasks-section h2,
        .activity-section h2 {
            margin-bottom: 1rem;
            font-size: 1.25rem;
        }

        .placeholder {
            text-align: center;
            padding: 3rem 1rem;
            background: #f9fafb;
            border-radius: 6px;
            color: #9ca3af;
        }

        @media (max-width: 768px) {
            .stats-section {
                grid-template-columns: 1fr;
            }

            .navbar-menu {
                gap: 0.5rem;
                flex-wrap: wrap;
            }

            .dashboard-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</body>
</html>
