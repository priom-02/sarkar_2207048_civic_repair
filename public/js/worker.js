/* ============================================
   WORKER DASHBOARD - FIELD OPERATIONS PORTAL
   JavaScript Functionality
   ============================================ */

const apiBase = window.AppConfig?.apiBaseUrl || '';

// State Management
let currentAssignments = [];
let currentOrder = null;
let selectedStatus = null;

// ============================================
// INITIALIZATION
// ============================================

document.addEventListener('DOMContentLoaded', function() {
    initializeTime();
    fetchAssignments();
    initializeEventListeners();
});

// ============================================
// API COMMUNICATION
// ============================================

async function fetchAssignments() {
    try {
        const response = await fetch(`${apiBase}/worker/api/assignments`);
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        const data = await response.json();
        
        currentAssignments = data.assignments;
        
        // Update Stats Overview
        updateStats(data.stats);
        
        // Render Bento Cards
        renderBentoGrid(data.assignments);
        
        // Plot SVG map pins
        plotMapPins(data.assignments);
        
    } catch (error) {
        console.error('Error fetching worker assignments:', error);
    }
}

function updateStats(stats) {
    const statNumbers = document.querySelectorAll('.stats-overview .stat-number');
    if (statNumbers.length >= 3) {
        statNumbers[0].textContent = stats.total;
        statNumbers[1].textContent = stats.high;
        statNumbers[2].textContent = stats.completed;
    }

    const summaryCompleted = document.getElementById('summaryCompleted');
    if (summaryCompleted && stats.completed_text) {
        summaryCompleted.textContent = stats.completed_text;
    }
    const summaryAvgTime = document.getElementById('summaryAvgTime');
    if (summaryAvgTime && stats.avg_time) {
        summaryAvgTime.textContent = stats.avg_time;
    }
    const summaryNextPriority = document.getElementById('summaryNextPriority');
    if (summaryNextPriority && stats.next_priority) {
        summaryNextPriority.textContent = stats.next_priority;
    }
    const summaryRating = document.getElementById('summaryRating');
    if (summaryRating && stats.rating) {
        summaryRating.textContent = stats.rating;
    }
}

// ============================================
// TIME & CLOCK
// ============================================

function initializeTime() {
    updateTime();
    setInterval(updateTime, 1000);
}

function updateTime() {
    const now = new Date();
    const timeElement = document.getElementById('currentTime');
    if (!timeElement) return;
    
    let hours = now.getHours();
    let minutes = now.getMinutes();
    const ampm = hours >= 12 ? 'PM' : 'AM';
    
    hours = hours % 12;
    hours = hours ? hours : 12;
    minutes = minutes < 10 ? '0' + minutes : minutes;
    
    timeElement.textContent = `${hours}:${minutes} ${ampm}`;
}

let workerLiveMap = null;
let markerGroup = null;

function plotMapPins(assignments) {
    const defaultLat = 23.8103;
    const defaultLng = 90.4125;

    const mappedOrders = assignments.filter(a => a.latitude && a.longitude);

    if (!workerLiveMap) {
        workerLiveMap = L.map('liveWorkerMap').setView([defaultLat, defaultLng], 12);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(workerLiveMap);

        markerGroup = L.layerGroup().addTo(workerLiveMap);
    } else {
        markerGroup.clearLayers();
    }

    if (mappedOrders.length === 0) return;

    const bounds = [];

    mappedOrders.forEach(order => {
        const marker = L.marker([order.latitude, order.longitude])
            .bindPopup(`
                <div style="font-family: inherit; font-size: 0.9rem; padding: 0.25rem; color: #1f2937;">
                    <strong style="display:block; margin-bottom: 0.25rem;">${escapeHtml(order.title)}</strong>
                    <span style="font-size: 0.75rem; color:#6b7280; display:block; margin-bottom: 0.5rem;">📍 ${escapeHtml(order.area_name)}</span>
                    <span style="font-size: 0.75rem; font-weight:700; color:${order.priority === 'High' ? '#ef4444' : '#f59e0b'}; display:block; margin-bottom: 0.5rem;">Priority: ${escapeHtml(order.priority)}</span>
                    <button onclick="openStatusModal(${order.id}, '${escapeHtml(order.title).replace(/'/g, "\\'")}', '${order.status}')" 
                            style="background:#3182ce; color:white; border:none; border-radius:6px; padding:0.4rem 0.8rem; font-size:0.8rem; font-weight:600; cursor:pointer; width:100%;">
                        Update Status
                    </button>
                </div>
            `);
        
        markerGroup.addLayer(marker);
        bounds.push([order.latitude, order.longitude]);
    });

    setTimeout(() => {
        workerLiveMap.invalidateSize();
        if (bounds.length > 0) {
            workerLiveMap.fitBounds(bounds, { padding: [30, 30] });
        }
    }, 200);
}

// ============================================
// BENTO GRID RENDERING
// ============================================

function renderBentoGrid(assignments) {
    const grid = document.getElementById('workOrdersGrid');
    if (!grid) return;
    
    if (assignments.length === 0) {
        grid.innerHTML = '<div style="text-align: center; padding: 3rem; color: var(--light-text); font-weight: 500; grid-column: 1/-1;">No active work orders assigned to you today.</div>';
        return;
    }
    
    grid.innerHTML = assignments.map((order, index) => {
        const statusLabel = {
            assigned: 'Assigned',
            working: 'Working',
            completed: 'Completed'
        }[order.status];
        
        const cardClass = order.status === 'completed' ? 'bento-card wide' : (order.priority === 'High' ? 'bento-card urgent' : 'bento-card');
        const progressClass = order.status === 'completed' ? 'progress-fill success' : 'progress-fill';
        const buttonClass = order.status === 'completed' ? 'update-btn completed' : 'update-btn';
        const disabledAttr = order.status === 'completed' ? 'disabled' : '';
        const buttonText = order.status === 'completed' ? 'Completed' : 'Update Status';

        return `
            <div class="${cardClass}" data-order-id="${order.id}">
                <div class="card-header">
                    <h3>${index + 1}. ${escapeHtml(order.title)}</h3>
                    <span class="urgency-badge ${order.priority === 'High' ? 'urgent' : ''}">${order.priority_badge}</span>
                </div>
                <p class="location">${escapeHtml(order.location)}</p>
                <div class="card-details">
                    <span class="status-badge ${order.status}">${statusLabel}</span>
                    <span class="time">${order.time_ago}</span>
                </div>
                <div class="progress-bar">
                    <div class="${progressClass}" style="width: ${order.progress}%"></div>
                </div>
                <p class="completion">${order.status === 'completed' ? '100% Complete ✓' : `${order.progress}% Complete`}</p>
                <button class="${buttonClass}" ${disabledAttr} onclick="openStatusModal(${order.id}, '${escapeHtml(order.title)}', '${order.status}')">${buttonText}</button>
            </div>
        `;
    }).join('');
}

function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// ============================================
// EVENT LISTENERS
// ============================================

function initializeEventListeners() {
    // Close modal when clicking outside
    document.getElementById('statusModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeStatusModal();
        }
    });
    
    // Close modal on ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeStatusModal();
        }
    });
}

// ============================================
// MODAL MANAGEMENT
// ============================================

function openStatusModal(orderId, title, status) {
    currentOrder = orderId;
    selectedStatus = status;
    
    const order = currentAssignments.find(a => a.id == orderId);
    if (!order) return;
    
    const modal = document.getElementById('statusModal');
    const modalTitle = document.getElementById('modalTitle');
    const currentStatusDisplay = document.getElementById('currentStatusDisplay');
    
    modalTitle.textContent = `Update: ${title}`;
    currentStatusDisplay.textContent = status.charAt(0).toUpperCase() + status.slice(1);
    updateStatusBadgeColor(currentStatusDisplay, status);
    
    // Reset form fields
    document.getElementById('progressNotes').value = '';
    
    const uploadArea = document.querySelector('.upload-area');
    if (uploadArea) {
        uploadArea.innerHTML = `
            <div class="upload-icon">📷</div>
            <p>Click to upload photos</p>
            <input type="file" id="photoUpload" accept="image/*" style="display: none;">
        `;
        attachPhotoListener();
    }
    
    // Render status logs dynamically
    const updatesFeed = document.querySelector('.updates-feed');
    if (updatesFeed) {
        if (order.updates && order.updates.length > 0) {
            const feedHtml = order.updates.map(u => `
                <div class="update-item">
                    <span class="update-time">${u.time}</span>
                    <span class="update-status">${escapeHtml(u.status)}</span>
                </div>
            `).join('');
            updatesFeed.innerHTML = `<label class="modal-label">Recent Updates:</label>${feedHtml}`;
        } else {
            updatesFeed.innerHTML = `<label class="modal-label">Recent Updates:</label><p style="font-size: 0.85rem; color: var(--light-text); padding-left: 5px;">No updates logged yet.</p>`;
        }
    }
    
    // Reset active buttons
    document.querySelectorAll('.status-option').forEach(opt => {
        opt.classList.remove('active');
        if (opt.getAttribute('data-status') === status) {
            opt.classList.add('active');
        }
    });
    
    modal.classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeStatusModal() {
    const modal = document.getElementById('statusModal');
    modal.classList.remove('active');
    document.body.style.overflow = 'auto';
    currentOrder = null;
    selectedStatus = null;
}

function updateStatusBadgeColor(element, status) {
    element.className = 'badge';
    
    if (status === 'assigned') {
        element.style.background = '#dbeafe';
        element.style.color = '#1e40af';
    } else if (status === 'working') {
        element.style.background = '#fef08a';
        element.style.color = '#713f12';
    } else if (status === 'completed') {
        element.style.background = '#dcfce7';
        element.style.color = '#166534';
    }
}

// ============================================
// STATUS UPDATE
// ============================================

function selectStatus(status) {
    selectedStatus = status;
    
    // Update button states
    document.querySelectorAll('.status-option').forEach(btn => {
        btn.classList.remove('active');
        if (btn.getAttribute('data-status') === status) {
            btn.classList.add('active');
        }
    });
}

async function submitStatusUpdate() {
    if (!selectedStatus) {
        showNotification('Please select a status', 'error');
        return;
    }
    
    if (!currentOrder) {
        showNotification('Error: No order selected', 'error');
        return;
    }
    
    const notes = document.getElementById('progressNotes').value.trim();
    const photoUpload = document.getElementById('photoUpload');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    const formData = new FormData();
    formData.append('status', selectedStatus);
    if (notes) {
        formData.append('notes', notes);
    }
    if (photoUpload && photoUpload.files.length > 0) {
        formData.append('photo', photoUpload.files[0]);
    }

    try {
        const response = await fetch(`${apiBase}/worker/api/assignments/${currentOrder}/status`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            body: formData
        });

        if (!response.ok) throw new Error('Status update failed');
        
        const data = await response.json();
        if (data.success) {
            showNotification('✓ Work status updated successfully!', 'success');
            closeStatusModal();
            fetchAssignments();
        } else {
            showNotification(data.message || 'Could not update status', 'error');
        }
    } catch (error) {
        console.error('Error submitting status update:', error);
        showNotification('An error occurred. Please try again.', 'error');
    }
}

// ============================================
// CARD HIGHLIGHTS
// ============================================

function highlightCard(orderId) {
    const card = document.querySelector(`[data-order-id="${orderId}"]`);
    if (card) {
        card.style.boxShadow = '0 12px 30px rgba(102, 126, 234, 0.4)';
        card.style.transform = 'translateY(-8px) scale(1.02)';
        card.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }
}

function removeCardHighlight() {
    const cards = document.querySelectorAll('.bento-card');
    cards.forEach(card => {
        card.style.boxShadow = '';
        card.style.transform = '';
    });
}

// ============================================
// NOTIFICATIONS
// ============================================

function showNotification(message, type = 'success') {
    const notification = document.getElementById('notification');
    if (!notification) {
        alert(message);
        return;
    }
    notification.textContent = message;
    
    if (type === 'error') {
        notification.style.background = '#ef4444';
    } else {
        notification.style.background = '#10b981';
    }
    
    notification.classList.add('show');
    setTimeout(() => {
        notification.classList.remove('show');
    }, 3000);
}

// ============================================
// PHOTO UPLOAD LISTENER HELPERS
// ============================================

function attachPhotoListener() {
    const uploadArea = document.querySelector('.upload-area');
    const photoUpload = document.getElementById('photoUpload');
    
    if (uploadArea && photoUpload) {
        uploadArea.addEventListener('click', function() {
            photoUpload.click();
        });
        
        photoUpload.addEventListener('change', function(e) {
            if (this.files.length > 0) {
                const fileName = this.files[0].name;
                uploadArea.innerHTML = `
                    <div class="upload-icon" style="color: #10b981;">✓</div>
                    <p style="color: #10b981; font-weight: 500;">Photo selected: ${fileName}</p>
                    <input type="file" id="photoUpload" accept="image/*" style="display: none;">
                `;
                // Keep file reference bound to input inside new html state
                const newUpload = document.getElementById('photoUpload');
                newUpload.files = e.target.files;
                attachPhotoListener(); // Re-bind clicks
            }
        });
    }
}
