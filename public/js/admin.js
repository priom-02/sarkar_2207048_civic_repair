/* ============================================
   ADMIN DASHBOARD - EXECUTIVE ANALYTICS
   JavaScript Functionality
   ============================================ */

// ============================================
// STATE MANAGEMENT
// ============================================

const apiBase = window.AppConfig?.apiBaseUrl || '';

let currentCategories = [];
let currentWorkers = [];
let currentIssues = [];

// ============================================
// INITIALIZATION
// ============================================

document.addEventListener('DOMContentLoaded', function() {
    fetchAnalytics();
    fetchCategories();
    fetchWorkers().then(() => {
        fetchIssues();
    });
    fetchUsers();
    fetchAreas();
    setupEventListeners();
    setupIconPreview();
});

// ============================================
// API COMMUNICATION
// ============================================

async function fetchAnalytics() {
    try {
        const response = await fetch(`${apiBase}/admin/api/analytics`);
        if (!response.ok) throw new Error('Failed to fetch analytics');
        const data = await response.json();
        
        // 1. Update Metrics Counts
        updateMetrics(data.stats);
        
        // 2. Render Heatmap Pins
        plotHeatmapPins(data.locations);
        
        // 3. Draw Trends Graph
        renderTrendsChart(data.trends);
        
    } catch (error) {
        console.error('Error fetching admin analytics:', error);
    }
}

async function fetchCategories() {
    try {
        const response = await fetch(`${apiBase}/admin/api/categories`);
        if (!response.ok) throw new Error('Failed to fetch categories');
        currentCategories = await response.json();
        renderCategories();
    } catch (error) {
        console.error('Error fetching categories:', error);
    }
}

async function fetchWorkers() {
    try {
        const response = await fetch(`${apiBase}/admin/api/workers`);
        if (!response.ok) throw new Error('Failed to fetch workers');
        currentWorkers = await response.json();
    } catch (error) {
        console.error('Error fetching workers:', error);
    }
}

async function fetchIssues() {
    try {
        const response = await fetch(`${apiBase}/admin/api/issues`);
        if (!response.ok) throw new Error('Failed to fetch issues');
        currentIssues = await response.json();
        renderIssuesTable();
    } catch (error) {
        console.error('Error fetching issues:', error);
        const tbody = document.getElementById('complaintsTableBody');
        if (tbody) {
            tbody.innerHTML = `<tr><td colspan="6" class="loading-text" style="color: #ef4444;">Error loading complaints.</td></tr>`;
        }
    }
}

function updateMetrics(stats) {
    document.getElementById('totalReportsCount').textContent = stats.total;
    document.getElementById('resolvedReportsCount').textContent = stats.resolved;
    document.getElementById('inProgressReportsCount').textContent = stats.inprogress;
    document.getElementById('activeWorkersCount').textContent = stats.workers;
}

// ============================================
// MAP PLOTTING
// ============================================

let adminMap = null;
let adminMarkerGroup = null;

function plotHeatmapPins(locations) {
    const defaultLat = 23.8103;
    const defaultLng = 90.4125;

    if (!adminMap) {
        adminMap = L.map('liveAdminMap').setView([defaultLat, defaultLng], 12);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(adminMap);

        adminMarkerGroup = L.layerGroup().addTo(adminMap);
    } else {
        adminMarkerGroup.clearLayers();
    }

    if (locations.length === 0) return;

    const bounds = [];

    locations.forEach(loc => {
        if (!loc.latitude || !loc.longitude) return;

        const color = loc.upvotes >= 40 ? '#ef4444' : '#f59e0b';
        
        const circleMarker = L.circleMarker([loc.latitude, loc.longitude], {
            radius: loc.upvotes >= 40 ? 12 : 8,
            fillColor: color,
            color: '#fff',
            weight: 2,
            opacity: 1,
            fillOpacity: 0.8
        }).bindPopup(`
            <div style="font-family: inherit; font-size: 0.9rem; color: #1e293b;">
                <strong>${escapeHtml(loc.title)}</strong>
                <p style="margin: 0.25rem 0; color:#6b7280;">Status: ${escapeHtml(loc.status)}</p>
                <span style="font-weight: 700; color:#3182ce;">👍 ${loc.upvotes} upvotes</span>
            </div>
        `);

        adminMarkerGroup.addLayer(circleMarker);
        bounds.push([loc.latitude, loc.longitude]);
    });

    setTimeout(() => {
        adminMap.invalidateSize();
        if (bounds.length > 0) {
            adminMap.fitBounds(bounds, { padding: [40, 40] });
        }
    }, 200);
}

// ============================================
// TRENDS CHART PLOTTING
// ============================================

function renderTrendsChart(trends) {
    const infraContainer = document.getElementById('infrastructure-bars');
    const sanitationContainer = document.getElementById('sanitation-bars');
    if (!infraContainer || !sanitationContainer) return;
    
    // Max scale calculation
    let maxCount = 1;
    trends.forEach(t => {
        maxCount = Math.max(maxCount, t.infrastructure, t.sanitation);
    });
    
    // Map week rects (Week 1 = X:50/65, Week 2 = X:110/125, etc.)
    infraContainer.innerHTML = trends.map((t, idx) => {
        const x = 50 + idx * 60;
        const height = (t.infrastructure / maxCount) * 180 + 8; // min size 8px
        const y = 250 - height;
        return `
            <rect x="${x}" y="${y}" width="12" height="${height}" fill="#4db8ff" class="trend-bar" 
                data-week="${t.week}" data-value="${t.infrastructure}" style="cursor: pointer; opacity: 0.85; transition: opacity 0.2s;" />
        `;
    }).join('');
    
    sanitationContainer.innerHTML = trends.map((t, idx) => {
        const x = 65 + idx * 60;
        const height = (t.sanitation / maxCount) * 180 + 8;
        const y = 250 - height;
        return `
            <rect x="${x}" y="${y}" width="12" height="${height}" fill="#ff9900" class="trend-bar" 
                data-week="${t.week}" data-value="${t.sanitation}" style="cursor: pointer; opacity: 0.85; transition: opacity 0.2s;" />
        `;
    }).join('');
    
    // Attach details notifications to bars
    document.querySelectorAll('.trend-bar').forEach(bar => {
        bar.addEventListener('mouseenter', function() {
            this.style.opacity = '1';
            const week = this.getAttribute('data-week');
            const val = this.getAttribute('data-value');
            const category = this.getAttribute('fill') === '#4db8ff' ? 'Infrastructure' : 'Sanitation';
            showNotification(`📊 ${week} - ${category}: ${val} reports`, 'info');
        });
        
        bar.addEventListener('mouseleave', function() {
            this.style.opacity = '0.85';
        });
    });
}

// ============================================
// EVENT LISTENERS
// ============================================

function setupEventListeners() {
    const categoryForm = document.getElementById('categoryForm');
    if (categoryForm) {
        categoryForm.addEventListener('submit', handleCategorySubmit);
    }

    // Quick icon selection
    document.querySelectorAll('.quick-icon').forEach(icon => {
        icon.addEventListener('click', function() {
            const iconValue = this.getAttribute('data-icon');
            const input = document.getElementById('categoryIcon');
            if (input) {
                input.value = iconValue;
                updateIconPreview(iconValue);
            }
        });
    });
}

// ============================================
// ICON PREVIEW
// ============================================

function setupIconPreview() {
    const iconInput = document.getElementById('categoryIcon');
    if (iconInput) {
        iconInput.addEventListener('input', function() {
            updateIconPreview(this.value);
        });
    }
}

function updateIconPreview(iconValue) {
    const preview = document.getElementById('iconPreview');
    if (preview && iconValue.trim()) {
        preview.textContent = iconValue;
        preview.style.animation = 'none';
        setTimeout(() => {
            preview.style.animation = 'scaleIcon 0.4s ease';
        }, 10);
    }
}

// ============================================
// CATEGORY MANAGEMENT
// ============================================

async function handleCategorySubmit(e) {
    e.preventDefault();

    const name = document.getElementById('categoryName').value.trim();
    const description = document.getElementById('categoryDescription').value.trim();
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    if (!name || !description) {
        showNotification('Please fill in all fields', 'error');
        return;
    }

    try {
        const response = await fetch(`${apiBase}/admin/api/categories`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                name: name,
                description: description
            })
        });

        if (!response.ok) {
            throw new Error('Failed to create category');
        }

        const data = await response.json();
        if (data.success) {
            document.getElementById('categoryForm').reset();
            document.getElementById('iconPreview').textContent = '🛣️';
            
            showNotification(`✓ Category "${name}" created successfully!`, 'success');
            
            // Reload list and charts
            fetchCategories();
            fetchAnalytics();
        } else {
            showNotification(data.message || 'Error creating category', 'error');
        }
    } catch (error) {
        console.error('Category creation error:', error);
        showNotification('Category name already exists or server error', 'error');
    }
}

function renderCategories() {
    const container = document.getElementById('categoriesList');
    if (!container) return;

    container.innerHTML = '';

    const categoryEmoji = {
        'Broken Road / Pothole': '🛣️',
        'Garbage & Waste': '♻️',
        'Water Leakage / Supply': '💧',
        'Sewerage & Drainage': '🚽',
        'Street Lighting': '💡',
        'Electricity / Power': '⚡',
        'Traffic & Signals': '🚦',
        'Tree / Vegetation': '🌳',
        'Public Property Damage': '🏛️',
        'Noise & Air Pollution': '💨',
        'Footpath / Pavement': '🚶',
        'Other': '📋'
    };

    currentCategories.forEach(category => {
        const emoji = categoryEmoji[category.category_name] || '📋';
        const categoryItem = document.createElement('div');
        categoryItem.className = 'category-item default-item';
        categoryItem.setAttribute('data-category-id', category.id);
        categoryItem.innerHTML = `
            <div class="category-icon">${emoji}</div>
            <div class="category-name">${escapeHtml(category.category_name)}</div>
            <div class="category-description">${escapeHtml(category.description)}</div>
        `;

        container.appendChild(categoryItem);
    });
}

function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// ============================================
// NOTIFICATIONS
// ============================================

function showNotification(message, type = 'success') {
    const banner = document.getElementById('notificationBanner');
    const bannerMessage = document.getElementById('bannerMessage');

    if (!banner || !bannerMessage) return;

    bannerMessage.textContent = message;
    banner.classList.add('show');

    const icon = banner.querySelector('.banner-icon');
    if (type === 'success') {
        icon.textContent = '✓';
        banner.style.background = 'linear-gradient(135deg, #10b981 0%, #059669 100%)';
        banner.style.boxShadow = '0 8px 25px rgba(16, 185, 129, 0.3)';
    } else if (type === 'error') {
        icon.textContent = '✕';
        banner.style.background = 'linear-gradient(135deg, #ef4444 0%, #dc2626 100%)';
        banner.style.boxShadow = '0 8px 25px rgba(239, 68, 68, 0.3)';
    } else if (type === 'info') {
        icon.textContent = 'ℹ';
        banner.style.background = 'linear-gradient(135deg, #3b82f6 0%, #2563eb 100%)';
        banner.style.boxShadow = '0 8px 25px rgba(59, 130, 246, 0.3)';
    }

    if (banner.currentTimeout) {
        clearTimeout(banner.currentTimeout);
    }

    banner.currentTimeout = setTimeout(() => {
        closeBanner();
    }, 4000);
}

function closeBanner() {
    const banner = document.getElementById('notificationBanner');
    if (!banner) return;
    banner.classList.remove('show');
}

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeBanner();
    }
});

// ============================================
// ASSIGNMENT RENDERING & DISPATCH HANDLERS
// ============================================

function renderIssuesTable() {
    const tbody = document.getElementById('complaintsTableBody');
    if (!tbody) return;

    if (currentIssues.length === 0) {
        tbody.innerHTML = `<tr><td colspan="6" class="loading-text">No reports submitted yet.</td></tr>`;
        return;
    }

    tbody.innerHTML = currentIssues.map(issue => {
        // Priority calculation based on upvotes
        let priority = 'Low';
        let priorityClass = 'priority-low';
        let priorityEmoji = '🟢';
        if (issue.upvote_count >= 40) {
            priority = 'High';
            priorityClass = 'priority-high';
            priorityEmoji = '🔴';
        } else if (issue.upvote_count >= 20) {
            priority = 'Medium';
            priorityClass = 'priority-medium';
            priorityEmoji = '🟡';
        }

        // Status badge configuration
        const statusClean = issue.status_name.toLowerCase().replace(/\s+/g, '');
        let statusBadgeClass = `status-${statusClean}`;
        
        // Category emoji mapping
        const categoryEmoji = {
            'Broken Road / Pothole': '🛣️',
            'Garbage & Waste': '♻️',
            'Water Leakage / Supply': '💧',
            'Sewerage & Drainage': '🚽',
            'Street Lighting': '💡',
            'Electricity / Power': '⚡',
            'Traffic & Signals': '🚦',
            'Tree / Vegetation': '🌳',
            'Public Property Damage': '🏛️',
            'Noise & Air Pollution': '💨',
            'Footpath / Pavement': '🚶',
            'Other': '📋'
        };
        const catEmoji = categoryEmoji[issue.category_name] || '📋';

        // Worker options
        const workerOptionsHTML = currentWorkers.map(w => {
            const isSelected = w.id === issue.assigned_worker_id ? 'selected' : '';
            const caseload = w.active_caseload !== undefined ? ` (${w.active_caseload} active)` : '';
            return `<option value="${w.id}" ${isSelected}>${escapeHtml(w.full_name)}${caseload}</option>`;
        }).join('');

        // Action controls based on status
        let assignmentControlHTML = '';
        if (issue.status_id >= 5) {
            // Completed, resolved, closed, rejected
            assignmentControlHTML = `
                <div class="assigned-worker-info" style="font-weight: 600; color: #16a34a; font-size: 0.9rem;">
                    Completed by:<br>${escapeHtml(issue.assigned_worker_name)}
                </div>
            `;
        } else {
            assignmentControlHTML = `
                <form class="assign-form" data-issue-id="${issue.id}">
                    <select class="assign-select" name="worker_id" required>
                        <option value="">-- Choose Field Worker --</option>
                        ${workerOptionsHTML}
                    </select>
                    <textarea class="assign-notes" name="notes" placeholder="Dispatch instructions / notes..." maxlength="255"></textarea>
                    <button type="submit" class="btn-assign">
                        <span>🚀</span> Dispatch Worker
                    </button>
                </form>
            `;
        }

        return `
            <tr id="issue-row-${issue.id}">
                <td>
                    <div class="issue-cell-title">${escapeHtml(issue.title)}</div>
                    <div class="issue-cell-desc" title="${escapeHtml(issue.description)}">${escapeHtml(issue.description)}</div>
                </td>
                <td>
                    <div style="font-weight: 600; font-size: 0.95rem; display: flex; align-items: center; gap: 0.25rem;">
                        <span>${catEmoji}</span> ${escapeHtml(issue.category_name)}
                    </div>
                    <div style="font-size: 0.85rem; color: #64748b; margin-top: 0.25rem;">📍 ${escapeHtml(issue.area_name)}</div>
                </td>
                <td>
                    <div style="font-weight: 500;">${escapeHtml(issue.reported_by)}</div>
                    <div style="font-size: 0.8rem; color: #94a3b8; margin-top: 0.25rem;">🕒 ${issue.time_ago}</div>
                </td>
                <td>
                    <span class="priority-badge ${priorityClass}">${priorityEmoji} ${priority}</span>
                    <div style="font-size: 0.85rem; color: #64748b; margin-top: 0.25rem;">👍 ${issue.upvote_count} upvotes</div>
                </td>
                <td>
                    <span class="status-badge ${statusBadgeClass}">${escapeHtml(issue.status_name)}</span>
                    ${issue.assigned_worker_id ? `<div style="font-size: 0.8rem; color: #64748b; margin-top: 0.25rem; font-style: italic;">Assigned to: ${escapeHtml(issue.assigned_worker_name)}</div>` : ''}
                    <button onclick="showIssueDetails(${issue.id})" class="btn-assign" style="margin-top: 0.5rem; padding: 0.35rem 0.6rem; font-size: 0.78rem; background: #64748b; border: 1px solid #64748b; width: 100%; justify-content: center; display: inline-flex;">👁️ Details</button>
                </td>
                <td>
                    ${assignmentControlHTML}
                </td>
            </tr>
        `;
    }).join('');

    // Attach event listeners to assignment forms
    tbody.querySelectorAll('.assign-form').forEach(form => {
        form.addEventListener('submit', handleAssignmentSubmit);
    });
}

async function handleAssignmentSubmit(e) {
    e.preventDefault();
    const form = this;
    const issueId = form.getAttribute('data-issue-id');
    const workerId = form.querySelector('[name="worker_id"]').value;
    const notes = form.querySelector('[name="notes"]').value.trim();
    const btn = form.querySelector('button[type="submit"]');

    if (!workerId) {
        showNotification('Please select a field worker to assign', 'error');
        return;
    }

    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    btn.disabled = true;
    btn.innerHTML = 'Dispatching...';

    try {
        const response = await fetch(`${apiBase}/admin/api/assignments`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                issue_id: issueId,
                worker_id: workerId,
                notes: notes
            })
        });

        if (!response.ok) {
            throw new Error('Failed to dispatch worker assignment');
        }

        const data = await response.json();
        if (data.success) {
            showNotification('✓ Worker dispatched successfully!', 'success');
            // Refresh dashboard counts and issues list
            fetchAnalytics();
            fetchIssues();
        } else {
            showNotification(data.message || 'Error assigning worker', 'error');
            btn.disabled = false;
            btn.innerHTML = 'Dispatch Worker';
        }
    } catch (error) {
        console.error('Assignment dispatch error:', error);
        showNotification('Could not assign task. Please try again.', 'error');
        btn.disabled = false;
        btn.innerHTML = 'Dispatch Worker';
    }
}

// ============================================
// USER MANAGEMENT CONTROL
// ============================================

let currentUsers = [];

async function fetchUsers() {
    try {
        const response = await fetch(`${apiBase}/admin/api/users`);
        if (!response.ok) throw new Error('Failed to fetch users');
        currentUsers = await response.json();
        renderUsersTable();
    } catch (error) {
        console.error('Error fetching users:', error);
        const tbody = document.getElementById('usersTableBody');
        if (tbody) {
            tbody.innerHTML = `<tr><td colspan="6" class="loading-text" style="color: #ef4444;">Error loading platform users.</td></tr>`;
        }
    }
}

function renderUsersTable() {
    const tbody = document.getElementById('usersTableBody');
    if (!tbody) return;

    if (currentUsers.length === 0) {
        tbody.innerHTML = `<tr><td colspan="6" class="loading-text">No registered users found.</td></tr>`;
        return;
    }

    tbody.innerHTML = currentUsers.map(user => {
        const statusBadge = user.is_active
            ? `<span class="status-badge" style="background-color: #d1fae5; color: #065f46; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.85rem; font-weight: 600; display: inline-block;">Active</span>`
            : `<span class="status-badge" style="background-color: #fee2e2; color: #991b1b; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.85rem; font-weight: 600; display: inline-block;">Deactivated</span>`;

        const actionBtn = user.is_active
            ? `<button onclick="toggleUserStatus(${user.id}, this)" class="logout-btn" style="background-color: #ef4444; border-color: #ef4444; font-size: 0.85rem; padding: 0.4rem 0.8rem; cursor: pointer; border-radius: 6px; color: white;">Deactivate</button>`
            : `<button onclick="toggleUserStatus(${user.id}, this)" class="logout-btn" style="background-color: #10b981; border-color: #10b981; font-size: 0.85rem; padding: 0.4rem 0.8rem; cursor: pointer; border-radius: 6px; color: white;">Activate</button>`;

        return `
            <tr>
                <td>
                    <div style="font-weight: 600; color: #1e293b; font-size: 0.95rem;">${escapeHtml(user.full_name)}</div>
                    <small style="color: #64748b; font-size: 0.75rem;">ID: #${user.id}</small>
                </td>
                <td>${escapeHtml(user.email)}</td>
                <td>${escapeHtml(user.phone)}</td>
                <td style="font-weight: 500; color: #475569;">${escapeHtml(user.role_name)}</td>
                <td>${statusBadge}</td>
                <td>${actionBtn}</td>
            </tr>
        `;
    }).join('');
}

async function toggleUserStatus(userId, button) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const originalText = button.textContent;
    button.disabled = true;
    button.textContent = 'Updating...';

    try {
        const response = await fetch(`${apiBase}/admin/api/users/${userId}/toggle-active`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        });

        if (!response.ok) {
            throw new Error('Failed to toggle user status');
        }

        const data = await response.json();
        if (data.success) {
            showNotification(data.message || '✓ User status updated!', 'success');
            fetchUsers(); // Refresh the users table
        } else {
            showNotification(data.message || 'Error updating user status', 'error');
            button.disabled = false;
            button.textContent = originalText;
        }
    } catch (error) {
        console.error('Toggle status error:', error);
        showNotification('Could not update user status. Please try again.', 'error');
        button.disabled = false;
        button.textContent = originalText;
    }
}

// ============================================
// GEOGRAPHIC AREAS MANAGEMENT
// ============================================

let currentAreas = [];

async function fetchAreas() {
    try {
        const response = await fetch(`${apiBase}/admin/api/areas`);
        if (!response.ok) throw new Error('Failed to fetch areas');
        const data = await response.json();
        currentAreas = data.areas;
        renderAreasTable();
    } catch (error) {
        console.error('Error fetching areas:', error);
        const tbody = document.getElementById('areasTableBody');
        if (tbody) {
            tbody.innerHTML = `<tr><td colspan="3" class="loading-text" style="color: #ef4444; text-align: center; padding: 2rem;">Error loading geographic catalog.</td></tr>`;
        }
    }
}

function renderAreasTable() {
    const tbody = document.getElementById('areasTableBody');
    if (!tbody) return;

    if (currentAreas.length === 0) {
        tbody.innerHTML = `<tr><td colspan="3" class="loading-text" style="text-align: center; padding: 2rem;">No registered geographic areas found.</td></tr>`;
        return;
    }

    tbody.innerHTML = currentAreas.map(area => {
        return `
            <tr style="border-bottom: 1px solid #edf2f7; font-size: 0.95rem;">
                <td style="padding: 1rem 0.5rem;">
                    <div style="font-weight: 700; color: #1e293b;">${escapeHtml(area.division)}</div>
                    <small style="color: #64748b; font-size: 0.8rem;">${escapeHtml(area.district)} &gt; ${escapeHtml(area.upazila)}</small>
                </td>
                <td style="padding: 1rem 0.5rem; font-weight: 600; color: #475569;">
                    ${escapeHtml(area.union_parishad || 'N/A')}
                </td>
                <td style="padding: 1rem 0.5rem; text-align: right;">
                    <button onclick="deleteGeographicArea(${area.id}, this)" style="background-color: #ef4444; border: none; font-size: 0.8rem; padding: 0.4rem 0.8rem; cursor: pointer; border-radius: 6px; color: white; font-weight: 600; transition: all 0.2s;">Delete</button>
                </td>
            </tr>
        `;
    }).join('');
}

async function handleAreaFormSubmit(e) {
    e.preventDefault();
    const form = e.target;
    const division = document.getElementById('areaDivisionInput').value.trim();
    const district = document.getElementById('areaDistrictInput').value.trim();
    const upazila = document.getElementById('areaUpazilaInput').value.trim();
    const union = document.getElementById('areaUnionInput').value.trim();
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    if (!division || !district || !upazila) {
        showNotification('Please fill in all required fields', 'error');
        return;
    }

    const btn = form.querySelector('button[type="submit"]');
    btn.disabled = true;
    btn.textContent = 'Adding...';

    try {
        const response = await fetch(`${apiBase}/admin/api/areas`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                division: division,
                district: district,
                upazila: upazila,
                union_parishad: union
            })
        });

        if (!response.ok) {
            throw new Error('Failed to create area');
        }

        const data = await response.json();
        if (data.success) {
            showNotification('✓ Area registered successfully!', 'success');
            form.reset();
            fetchAreas(); // Refresh list
        } else {
            showNotification(data.message || 'Error creating area', 'error');
        }
    } catch (error) {
        console.error('Create area error:', error);
        showNotification('Could not save area. Please try again.', 'error');
    } finally {
        btn.disabled = false;
        btn.textContent = 'Add Geographic Area';
    }
}

async function deleteGeographicArea(areaId, button) {
    if (!confirm('Are you sure you want to delete this geographic area?')) return;
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const originalText = button.textContent;
    button.disabled = true;
    button.textContent = 'Deleting...';

    try {
        const response = await fetch(`${apiBase}/admin/api/areas/${areaId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken
            }
        });

        const data = await response.json();
        if (response.ok && data.success) {
            showNotification('✓ Area deleted successfully!', 'success');
            fetchAreas();
        } else {
            showNotification(data.message || 'Error deleting area', 'error');
            button.disabled = false;
            button.textContent = originalText;
        }
    } catch (error) {
        console.error('Delete area error:', error);
        showNotification('Could not delete area. Please try again.', 'error');
        button.disabled = false;
        button.textContent = originalText;
    }
}

async function showIssueDetails(issueId) {
    const modal = document.getElementById('issueDetailsModal');
    const loading = document.getElementById('modalDetailsLoading');
    const body = document.getElementById('modalDetailsBody');

    if (!modal || !loading || !body) return;

    // Show loading
    loading.style.display = 'block';
    body.style.display = 'none';
    modal.classList.add('active');
    document.body.style.overflow = 'hidden';

    // Find the issue
    const issue = currentIssues.find(i => i.id === issueId);
    if (!issue) {
        loading.innerHTML = `<p style="color: #ef4444; font-weight: 600;">Error: Issue not found</p>`;
        return;
    }

    // Separate before and after photos
    const citizenPhotos = (issue.media || []).filter(m => m.uploaded_by_role === 1);
    const workerPhotos = (issue.media || []).filter(m => m.uploaded_by_role === 2);

    let beforePhotosHtml = `<div class="no-proof">📷 No photos uploaded by citizen</div>`;
    if (citizenPhotos.length > 0) {
        beforePhotosHtml = citizenPhotos.map(m => `
            <a href="${m.url}" target="_blank">
                <img src="${m.url}" class="proof-img" alt="Citizen Proof">
            </a>
        `).join('');
    }

    let afterPhotosHtml = `<div class="no-proof">🔧 No resolution photos uploaded yet</div>`;
    if (workerPhotos.length > 0) {
        afterPhotosHtml = workerPhotos.map(m => `
            <a href="${m.url}" target="_blank">
                <img src="${m.url}" class="proof-img" alt="Worker Resolution Proof">
            </a>
        `).join('');
    }

    // Render timeline
    let timelineHtml = '<p style="font-size: 0.85rem; color: #64748b; font-style: italic; padding: 0.5rem 0;">No status changes logged yet.</p>';
    if (issue.history && issue.history.length > 0) {
        timelineHtml = issue.history.map(h => `
            <div class="audit-item">
                <span class="audit-time">${h.time} &bull; by ${escapeHtml(h.user_name)}</span>
                <div class="audit-desc">Changed from "${escapeHtml(h.old_status)}" to "${escapeHtml(h.new_status)}"</div>
                ${h.remark ? `<div class="audit-remark">${escapeHtml(h.remark)}</div>` : ''}
            </div>
        `).join('');
    }

    // Build overall content
    const statusClean = issue.status_name.toLowerCase().replace(/\s+/g, '');
    const statusBadgeClass = `status-${statusClean}`;

    body.innerHTML = `
        <div style="border-bottom: 2px solid #f1f5f9; padding-bottom: 1.25rem; margin-bottom: 1.5rem;">
            <div style="display: flex; align-items: flex-start; justify-content: space-between; gap: 1rem; flex-wrap: wrap;">
                <div>
                    <span style="font-size: 0.8rem; background: #e2e8f0; color: #475569; padding: 0.25rem 0.6rem; border-radius: 6px; font-weight: 700; text-transform: uppercase;">ID: #${issue.id}</span>
                    <h1 style="font-size: 1.6rem; font-weight: 800; color: #0f172a; margin-top: 0.5rem; line-height: 1.2;">${escapeHtml(issue.title)}</h1>
                </div>
                <span class="status-badge ${statusBadgeClass}" style="font-size: 0.9rem; padding: 0.4rem 1rem;">${escapeHtml(issue.status_name)}</span>
            </div>
        </div>

        <div style="margin-bottom: 1.5rem;">
            <h3 style="font-size: 1.05rem; font-weight: 800; color: #1e293b; margin-bottom: 0.5rem;">Detailed Description</h3>
            <p style="color: #475569; line-height: 1.6; font-size: 0.98rem; background: #f8fafc; padding: 1.25rem; border-radius: 12px; border: 1px solid #edf2f7; white-space: pre-wrap;">${escapeHtml(issue.description)}</p>
        </div>

        <!-- Before / After Photo Comparison Grid -->
        <h3 style="font-size: 1.05rem; font-weight: 800; color: #1e293b; margin-bottom: 0.75rem;">Before & After Comparison</h3>
        <div class="comparison-grid">
            <div class="proof-box">
                <div class="proof-title">🚨 Before (Citizen Proof)</div>
                ${beforePhotosHtml}
            </div>
            <div class="proof-box">
                <div class="proof-title">✅ After (Worker Resolution Proof)</div>
                ${afterPhotosHtml}
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-top: 2rem;">
            <div>
                <h3 style="font-size: 1.05rem; font-weight: 800; color: #1e293b; margin-bottom: 0.75rem;">Metadata & Assignment</h3>
                <div style="background: #f8fafc; border-radius: 12px; border: 1px solid #edf2f7; padding: 1rem; display: flex; flex-direction: column; gap: 0.75rem; font-size: 0.95rem;">
                    <div style="display: flex; justify-content: space-between;"><span style="color: #64748b;">Reported By:</span><strong style="color: #1e293b;">${escapeHtml(issue.reported_by)}</strong></div>
                    <div style="display: flex; justify-content: space-between;"><span style="color: #64748b;">Submitted:</span><strong style="color: #1e293b;">${issue.time_ago}</strong></div>
                    <div style="display: flex; justify-content: space-between;"><span style="color: #64748b;">Area/Union:</span><strong style="color: #1e293b;">📍 ${escapeHtml(issue.area_name)}</strong></div>
                    <div style="display: flex; justify-content: space-between;"><span style="color: #64748b;">Upvotes:</span><strong style="color: #1e293b;">👍 ${issue.upvote_count} upvotes</strong></div>
                    <div style="display: flex; justify-content: space-between; border-top: 1px solid #e2e8f0; padding-top: 0.75rem; margin-top: 0.25rem;"><span style="color: #64748b;">Assigned Worker:</span><strong style="color: #3b82f6;">${escapeHtml(issue.assigned_worker_name)}</strong></div>
                </div>
            </div>
            <div>
                <h3 style="font-size: 1.05rem; font-weight: 800; color: #1e293b; margin-bottom: 0.75rem;">Timeline Audit Log</h3>
                <div class="audit-timeline">
                    ${timelineHtml}
                </div>
            </div>
        </div>
    `;

    // Display body
    loading.style.display = 'none';
    body.style.display = 'block';
}

window.handleAreaFormSubmit = handleAreaFormSubmit;
window.deleteGeographicArea = deleteGeographicArea;
window.showIssueDetails = showIssueDetails;

