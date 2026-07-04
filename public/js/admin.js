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

function plotHeatmapPins(locations) {
    const container = document.getElementById('report-pins');
    if (!container) return;
    
    if (locations.length === 0) {
        container.innerHTML = '';
        return;
    }
    
    container.innerHTML = locations.map(loc => {
        const { cx, cy } = mapCoordinates(loc.latitude, loc.longitude);
        const radius = loc.upvotes >= 40 ? 7 : 5;
        const fill = loc.upvotes >= 40 ? '#ff4444' : '#ff9900';
        
        return `
            <circle cx="${cx}" cy="${cy}" r="${radius}" fill="${fill}" stroke="white" stroke-width="2" 
                class="heatmap-pin" data-title="${escapeHtml(loc.title)}" data-upvotes="${loc.upvotes}" 
                data-status="${escapeHtml(loc.status)}" style="cursor: pointer; transition: all 0.2s;" />
        `;
    }).join('');
    
    // Attach click listeners to heatmap pins
    container.querySelectorAll('.heatmap-pin').forEach(pin => {
        pin.addEventListener('mouseenter', function() {
            this.setAttribute('r', parseInt(this.getAttribute('r')) + 2);
        });
        
        pin.addEventListener('mouseleave', function() {
            this.setAttribute('r', parseInt(this.getAttribute('r')) - 2);
        });
        
        pin.addEventListener('click', function() {
            const title = this.getAttribute('data-title');
            const upvotes = this.getAttribute('data-upvotes');
            const status = this.getAttribute('data-status');
            showNotification(`📍 [${status}] ${title} (${upvotes} votes)`, 'info');
        });
    });
}

function mapCoordinates(lat, lng) {
    if (!lat || !lng) {
        return { cx: Math.random() * 600 + 100, cy: Math.random() * 300 + 100 };
    }
    
    // Map bounding (Dhaka metropolitan coordinate systems)
    const latMin = 23.7000;
    const latMax = 23.8900;
    const lngMin = 90.3500;
    const lngMax = 90.4300;
    
    // SVG Heatmap size: 800 width, 500 height.
    const cx = ((lng - lngMin) / (lngMax - lngMin)) * 700 + 50;
    const cy = 500 - (((lat - latMin) / (latMax - latMin)) * 400 + 50);
    
    return { cx, cy };
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
            return `<option value="${w.id}" ${isSelected}>${escapeHtml(w.full_name)}</option>`;
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
    btn.innerHTML = '<span>⏳</span> Dispatching...';

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
            btn.innerHTML = '<span>🚀</span> Dispatch Worker';
        }
    } catch (error) {
        console.error('Assignment dispatch error:', error);
        showNotification('Could not assign task. Please try again.', 'error');
        btn.disabled = false;
        btn.innerHTML = '<span>🚀</span> Dispatch Worker';
    }
}

