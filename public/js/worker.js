/* ============================================
   WORKER DASHBOARD - FIELD OPERATIONS PORTAL
   JavaScript Functionality
   ============================================ */

// State Management
let currentOrder = null;
let selectedStatus = null;
const workOrders = {
    1: { title: 'Pothole Repair', status: 'assigned', location: 'Downtown - Maple Ave' },
    2: { title: 'Trash Bin Service', status: 'working', location: 'Downtown - Central Park' },
    3: { title: 'Street Light Installation', status: 'completed', location: 'West Side - Oak Street' },
    4: { title: 'Water Main Leak', status: 'assigned', location: 'East Side - 5th Street' },
    5: { title: 'Sidewalk Repair', status: 'working', location: 'North Side - Park Lane' }
};

// ============================================
// INITIALIZATION
// ============================================

document.addEventListener('DOMContentLoaded', function() {
    initializeTime();
    initializeMap();
    initializeEventListeners();
});

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
    
    let hours = now.getHours();
    let minutes = now.getMinutes();
    const ampm = hours >= 12 ? 'PM' : 'AM';
    
    hours = hours % 12;
    hours = hours ? hours : 12;
    minutes = minutes < 10 ? '0' + minutes : minutes;
    
    timeElement.textContent = `${hours}:${minutes} ${ampm}`;
}

// ============================================
// MAP INITIALIZATION
// ============================================

function initializeMap() {
    const mapSvg = document.querySelector('.map-svg');
    const pins = mapSvg.querySelectorAll('.incident-pin');
    
    pins.forEach(pin => {
        pin.addEventListener('click', function(e) {
            e.stopPropagation();
            const orderId = this.getAttribute('data-order');
            openStatusModal(orderId, workOrders[orderId].title, workOrders[orderId].status);
        });
        
        pin.addEventListener('mouseenter', function() {
            const orderId = this.getAttribute('data-order');
            highlightCard(orderId);
        });
        
        pin.addEventListener('mouseleave', function() {
            removeCardHighlight();
        });
    });
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
    
    // Map incident pin interactions
    const incidentPins = document.querySelectorAll('.incident-pin');
    incidentPins.forEach(pin => {
        pin.addEventListener('click', function() {
            const orderId = this.getAttribute('data-order');
            openStatusModal(orderId, workOrders[orderId].title, workOrders[orderId].status);
        });
    });
}

// ============================================
// MODAL MANAGEMENT
// ============================================

function openStatusModal(orderId, title, status) {
    currentOrder = orderId;
    selectedStatus = status;
    
    const modal = document.getElementById('statusModal');
    const modalTitle = document.getElementById('modalTitle');
    const currentStatusDisplay = document.getElementById('currentStatusDisplay');
    
    modalTitle.textContent = `Update: ${title}`;
    currentStatusDisplay.textContent = status.charAt(0).toUpperCase() + status.slice(1);
    updateStatusBadgeColor(currentStatusDisplay, status);
    
    // Reset form
    document.getElementById('progressNotes').value = '';
    document.getElementById('photoUpload').value = '';
    
    // Reset status selection
    document.querySelectorAll('.status-option').forEach(opt => {
        opt.classList.remove('active');
        if (opt.getAttribute('data-status') === status) {
            opt.classList.add('active');
            selectedStatus = status;
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

function submitStatusUpdate() {
    if (!selectedStatus) {
        showNotification('Please select a status', 'error');
        return;
    }
    
    if (!currentOrder) {
        showNotification('Error: No order selected', 'error');
        return;
    }
    
    const notes = document.getElementById('progressNotes').value;
    const photoFile = document.getElementById('photoUpload').files[0];
    
    // Update work order in state
    workOrders[currentOrder].status = selectedStatus;
    
    // Update card UI
    updateCardStatus(currentOrder, selectedStatus);
    
    // Show success notification
    const orderTitle = workOrders[currentOrder].title;
    showNotification(`✓ ${orderTitle} updated to ${selectedStatus}`, 'success');
    
    // Log the update (in production, send to server)
    console.log({
        orderId: currentOrder,
        newStatus: selectedStatus,
        notes: notes,
        photo: photoFile ? photoFile.name : 'none',
        timestamp: new Date().toISOString()
    });
    
    // Close modal after a short delay
    setTimeout(() => {
        closeStatusModal();
    }, 500);
}

function updateCardStatus(orderId, newStatus) {
    const card = document.querySelector(`[data-order-id="${orderId}"]`);
    if (!card) return;
    
    // Update status badge
    const statusBadge = card.querySelector('.status-badge');
    statusBadge.className = `status-badge ${newStatus}`;
    statusBadge.textContent = newStatus.charAt(0).toUpperCase() + newStatus.slice(1);
    
    // Update progress bar if completing
    if (newStatus === 'completed') {
        const progressFill = card.querySelector('.progress-fill');
        progressFill.style.width = '100%';
        progressFill.classList.add('success');
        card.querySelector('.completion').textContent = '100% Complete ✓';
        card.querySelector('.update-btn').classList.add('completed');
        card.querySelector('.update-btn').textContent = 'Completed';
        card.querySelector('.update-btn').disabled = true;
    } else if (newStatus === 'working') {
        const progressFill = card.querySelector('.progress-fill');
        if (parseInt(progressFill.style.width) < 50) {
            progressFill.style.width = '50%';
            card.querySelector('.completion').textContent = '50% Complete';
        }
    }
    
    // Update card urgency status
    card.classList.remove('urgent');
    if (newStatus !== 'completed') {
        const urgencyBadge = card.querySelector('.urgency-badge');
        if (urgencyBadge.textContent.includes('High Priority')) {
            card.classList.add('urgent');
        }
    }
    
    // Animate card update
    card.style.animation = 'none';
    setTimeout(() => {
        card.style.animation = 'slideInLeft 0.3s ease-out';
    }, 10);
}

// ============================================
// CARD INTERACTIONS
// ============================================

function highlightCard(orderId) {
    const card = document.querySelector(`[data-order-id="${orderId}"]`);
    if (card) {
        card.style.boxShadow = '0 12px 30px rgba(102, 126, 234, 0.4)';
        card.style.transform = 'translateY(-8px) scale(1.02)';
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
    const notificationText = document.getElementById('notificationText');
    
    notificationText.textContent = message;
    notification.classList.add('show');
    
    // Auto-hide after 3 seconds
    setTimeout(() => {
        notification.classList.remove('show');
    }, 3000);
}

// ============================================
// PHOTO UPLOAD
// ============================================

document.addEventListener('DOMContentLoaded', function() {
    const uploadArea = document.querySelector('.upload-area');
    const photoUpload = document.getElementById('photoUpload');
    
    if (uploadArea) {
        uploadArea.addEventListener('click', function() {
            photoUpload.click();
        });
        
        // Drag and drop
        uploadArea.addEventListener('dragover', function(e) {
            e.preventDefault();
            uploadArea.style.borderColor = '#764ba2';
            uploadArea.style.background = '#f0f4ff';
        });
        
        uploadArea.addEventListener('dragleave', function() {
            uploadArea.style.borderColor = '#667eea';
            uploadArea.style.background = '#f8f9ff';
        });
        
        uploadArea.addEventListener('drop', function(e) {
            e.preventDefault();
            uploadArea.style.borderColor = '#667eea';
            uploadArea.style.background = '#f8f9ff';
            
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                photoUpload.files = files;
                showNotification(`📷 Photo added: ${files[0].name}`);
            }
        });
    }
    
    if (photoUpload) {
        photoUpload.addEventListener('change', function(e) {
            if (this.files.length > 0) {
                const fileName = this.files[0].name;
                const uploadArea = document.querySelector('.upload-area');
                uploadArea.innerHTML = `
                    <div class="upload-icon">✓</div>
                    <p style="color: #16a34a;">Photo selected: ${fileName}</p>
                `;
            }
        });
    }
});

// ============================================
// RESPONSIVE ADJUSTMENTS
// ============================================

function adjustForScreenSize() {
    const width = window.innerWidth;
    
    if (width < 1200) {
        // Adjust grid layout
        const main = document.querySelector('.worker-main');
        if (main) {
            main.style.gridTemplateColumns = '1fr';
        }
    }
}

window.addEventListener('resize', adjustForScreenSize);

// ============================================
// KEYBOARD SHORTCUTS
// ============================================

document.addEventListener('keydown', function(e) {
    // Alt + Q to open status modal for first order
    if (e.altKey && e.key === 'q') {
        e.preventDefault();
        openStatusModal(1, workOrders[1].title, workOrders[1].status);
    }
});

console.log('Worker Dashboard initialized successfully! 🚀');
