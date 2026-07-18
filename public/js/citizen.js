const apiBase = window.AppConfig?.apiBaseUrl || '';
let currentActiveIssueId = null;

// =====================================
// STATE MANAGEMENT
// =====================================

let currentFilterId = 'all';
let searchQuery = '';

// =====================================
// API COMMUNICATION
// =====================================

async function fetchIssues() {
    try {
        const response = await fetch(`${apiBase}/citizen/api/issues?category_id=${currentFilterId}&search=${encodeURIComponent(searchQuery)}`);
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        const data = await response.json();
        
        // Render stats
        updateStats(data.stats);
        
        // Render leaders
        renderLeaderboard(data.leaders);
        
        // Render issue feed
        renderIssues(data.issues);
    } catch (error) {
        console.error('Error loading issues from API:', error);
    }
}

// =====================================
// UTILITY FUNCTIONS
// =====================================

function scrollToSection(sectionId) {
    const element = document.getElementById(sectionId);
    if (element) {
        element.scrollIntoView({ behavior: 'smooth' });
    }
}

function openModal(modalId) {
    const modal = document.getElementById(modalId);
    const overlay = document.getElementById('modalOverlay');
    if (modal) {
        modal.classList.add('active');
        overlay.classList.add('active');
        if (modalId === 'reportModal') {
            initReportMap();
        }
    }
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    const overlay = document.getElementById('modalOverlay');
    if (modal) {
        modal.classList.remove('active');
        overlay.classList.remove('active');
    }
}

function closeCurrentModal() {
    const modals = document.querySelectorAll('.modal.active');
    modals.forEach(modal => modal.classList.remove('active'));
    document.getElementById('modalOverlay').classList.remove('active');
}

function updateStats(stats) {
    document.getElementById('totalIssues').textContent = stats.total;
    document.getElementById('resolvedIssues').textContent = stats.resolved;
    document.getElementById('inProgressIssues').textContent = stats.inprogress;
    document.getElementById('totalVotes').textContent = formatCount(stats.total_votes);
}

function formatCount(num) {
    if (num >= 1000) {
        return (num / 1000).toFixed(1) + 'K';
    }
    return num;
}

// =====================================
// ISSUE RENDERING
// =====================================

function renderIssues(issues) {
    const issuesList = document.getElementById('issuesList');
    
    if (issues.length === 0) {
        issuesList.innerHTML = '<div style="text-align: center; padding: 2rem; color: var(--light-text); font-weight: 500;">No reports found in this section</div>';
        return;
    }

    issuesList.innerHTML = issues.map(issue => createIssueCard(issue)).join('');
    
    // Attach event listeners to vote buttons
    document.querySelectorAll('.vote-btn').forEach(btn => {
        btn.addEventListener('click', handleVote);
    });

    // Attach event listeners to comment buttons
    document.querySelectorAll('.comment-btn').forEach(btn => {
        btn.addEventListener('click', handleCommentClick);
    });

    // Attach event listeners to issue titles
    document.querySelectorAll('.issue-title').forEach(title => {
        title.addEventListener('click', handleIssueClick);
    });
}

function createIssueCard(issue) {
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

    const emoji = categoryEmoji[issue.category_name] || '📋';

    let mediaHtml = '';
    if (issue.media && issue.media.length > 0) {
        mediaHtml = `<div class="issue-media-gallery">` +
            issue.media.map(url => `<img src="${url}" class="issue-media-item" onclick="window.open('${url}', '_blank')">`).join('') +
            `</div>`;
    }

    return `
        <div class="issue-card" data-issue-id="${issue.id}">
            <div class="issue-header">
                <h3 class="issue-title">${escapeHtml(issue.title)}</h3>
                <span class="issue-status status-${issue.status_class}">${issue.status_name}</span>
            </div>
            
            <div class="issue-meta">
                <span class="issue-category">
                    ${emoji} ${issue.category_name}
                </span>
                <span class="issue-area">
                    📍 ${issue.area_name}
                </span>
                <span class="issue-time">
                    🕐 ${issue.time_ago}
                </span>
            </div>

            <p class="issue-description">${escapeHtml(issue.description)}</p>
            ${mediaHtml}

            <div class="issue-footer">
                <div class="issue-actions">
                    <button class="vote-btn ${issue.voted ? 'voted' : ''}" data-issue-id="${issue.id}">
                        👍 <span class="vote-count">${issue.votes}</span>
                    </button>
                    <button class="comment-btn" data-issue-id="${issue.id}">
                        💬 <span>${issue.comments}</span>
                    </button>
                </div>
                <div class="issue-reporter">
                    <div class="reporter-avatar">${issue.reported_by_initial}</div>
                    <span>${escapeHtml(issue.reported_by)}</span>
                </div>
            </div>
        </div>
    `;
}

function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// =====================================
// FILTERING & SEARCH
// =====================================

function filterIssues(categoryId) {
    currentFilterId = categoryId;
    fetchIssues();
    updateFilterChips(categoryId);
}

function updateFilterChips(activeCategoryId) {
    document.querySelectorAll('.filter-chip').forEach(chip => {
        const categoryId = chip.dataset.categoryId;
        if (categoryId == activeCategoryId) {
            chip.classList.add('active');
        } else {
            chip.classList.remove('active');
        }
    });
}

function searchIssues(query) {
    searchQuery = query;
    fetchIssues();
}

// =====================================
// EVENT HANDLERS
// =====================================

async function handleVote(e) {
    e.preventDefault();
    e.stopPropagation();
    
    const voteBtn = e.currentTarget;
    const issueId = voteBtn.dataset.issueId;
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    try {
        const response = await fetch(`${apiBase}/citizen/api/issues/${issueId}/vote`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        });

        if (!response.ok) throw new Error('Error processing vote');
        
        const data = await response.json();
        if (data.success) {
            // Update individual count and status
            const voteCount = voteBtn.querySelector('.vote-count');
            voteCount.textContent = data.votes;
            if (data.voted) {
                voteBtn.classList.add('voted');
            } else {
                voteBtn.classList.remove('voted');
            }

            // Simple scaling micro-animation
            voteBtn.style.transform = 'scale(1.2)';
            setTimeout(() => {
                voteBtn.style.transform = 'scale(1)';
            }, 100);

            // Fetch latest global metrics to keep dashboard in sync
            fetchIssues();
        }
    } catch (error) {
        console.error('Failed to vote:', error);
    }
}

function handleCommentClick(e) {
    e.preventDefault();
    e.stopPropagation();
    const issueId = e.currentTarget.dataset.issueId;
    openDetailsModal(issueId, 'comments');
}

function handleIssueClick(e) {
    const issueCard = e.currentTarget.closest('.issue-card');
    const issueId = issueCard.dataset.issueId;
    openDetailsModal(issueId, 'overview');
}

function switchTab(tabName) {
    document.querySelectorAll('.details-modal .tab-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    document.querySelectorAll('.details-modal .tab-content').forEach(content => {
        content.classList.remove('active');
    });
    
    if (tabName === 'overview') {
        document.querySelector('.tab-btn[onclick="switchTab(\'overview\')"]').classList.add('active');
        document.getElementById('tab-overview').classList.add('active');
    } else if (tabName === 'comments') {
        document.querySelector('.tab-btn[onclick="switchTab(\'comments\')"]').classList.add('active');
        document.getElementById('tab-comments').classList.add('active');
    }
}
window.switchTab = switchTab;

async function openDetailsModal(issueId, startTab = 'overview') {
    currentActiveIssueId = issueId;
    switchTab(startTab);
    
    document.getElementById('detailTitle').textContent = 'Loading Report details...';
    document.getElementById('detailDescription').textContent = '';
    document.getElementById('detailStatus').textContent = 'LOADING';
    document.getElementById('detailStatus').className = 'issue-status';
    document.getElementById('detailCategory').textContent = '🛣️ Category';
    document.getElementById('detailArea').textContent = '📍 Area';
    document.getElementById('detailTime').textContent = '🕐 Time';
    document.getElementById('detailCoordinates').textContent = '';
    document.getElementById('detailMediaGallery').innerHTML = '';
    document.getElementById('detailTimeline').innerHTML = '';
    document.getElementById('detailCommentsList').innerHTML = '';
    document.getElementById('detailCommentCount').textContent = '0';
    document.getElementById('newCommentBody').value = '';
    
    openModal('detailsModal');
    
    try {
        const response = await fetch(`${apiBase}/citizen/api/issues/${issueId}`);
        if (!response.ok) throw new Error('Failed to load issue details');
        const data = await response.json();
        
        document.getElementById('detailTitle').textContent = data.title;
        document.getElementById('detailDescription').textContent = data.description;
        
        const statusBadge = document.getElementById('detailStatus');
        statusBadge.textContent = data.status_name;
        statusBadge.className = `issue-status status-${data.status_class}`;
        
        document.getElementById('detailCategory').textContent = `📋 ${data.category_name}`;
        document.getElementById('detailArea').textContent = `📍 ${data.area_name}`;
        document.getElementById('detailTime').textContent = `🕐 ${data.time_ago}`;
        
        if (data.latitude && data.longitude) {
            const coordEl = document.getElementById('detailCoordinates');
            coordEl.textContent = '📍 Fetching address...';
            // Reverse geocode using backend proxy
            fetch(`${apiBase}/api/geocode/reverse?lat=${data.latitude}&lon=${data.longitude}`)
                .then(r => r.ok ? r.json() : null)
                .then(geo => {
                    if (geo && geo.address) {
                        coordEl.textContent = '📍 ' + geo.address;
                    } else {
                        coordEl.textContent = `📍 ${parseFloat(data.latitude).toFixed(5)}, ${parseFloat(data.longitude).toFixed(5)}`;
                    }
                })
                .catch(() => {
                    coordEl.textContent = `📍 ${parseFloat(data.latitude).toFixed(5)}, ${parseFloat(data.longitude).toFixed(5)}`;
                });
        } else {
            document.getElementById('detailCoordinates').textContent = 'No GPS coordinates recorded.';
        }
        
        const gallery = document.getElementById('detailMediaGallery');
        const citizenPhotos = data.citizen_media || [];
        const workerPhotos = data.worker_media || [];

        const photoGroupHtml = (photos) => {
            if (photos.length === 0) {
                return `<span style="font-size:0.88rem;color:var(--light-text);font-style:italic;">No photos available.</span>`;
            }
            return `<div style="display:flex;flex-wrap:wrap;gap:0.5rem;">` +
                photos.map(url => `<img src="${url}" class="issue-media-item" onclick="window.open('${url}','_blank')" style="width:90px;height:70px;object-fit:cover;border-radius:8px;cursor:pointer;border:2px solid #e2e8f0;">`).join('') +
                `</div>`;
        };

        if (citizenPhotos.length === 0 && workerPhotos.length === 0) {
            gallery.innerHTML = '<span style="font-size:0.95rem;color:var(--light-text);font-style:italic;">No photos attached to this report.</span>';
        } else {
            gallery.innerHTML = `
                <div style="margin-bottom:1rem;">
                    <div style="font-size:0.78rem;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;color:#0d9488;margin-bottom:0.4rem;">🏠 Reported by Citizen</div>
                    ${photoGroupHtml(citizenPhotos)}
                </div>
                <div>
                    <div style="font-size:0.78rem;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;color:#7c3aed;margin-bottom:0.4rem;">🔧 Resolved by Worker</div>
                    ${photoGroupHtml(workerPhotos)}
                </div>
            `;
        }
        
        const timeline = document.getElementById('detailTimeline');
        if (data.history && data.history.length > 0) {
            timeline.innerHTML = data.history.map((h, index) => `
                <div class="timeline-item ${index === 0 ? 'active' : 'completed'}">
                    <div class="timeline-marker"></div>
                    <div class="timeline-content">
                        <div class="timeline-header">
                            <span class="timeline-status">${h.new_status}</span>
                            <span class="timeline-time">${h.time_ago}</span>
                        </div>
                        <span class="timeline-user">Updated by ${escapeHtml(h.changed_by)}</span>
                        ${h.remark ? `<p class="timeline-remark">"${escapeHtml(h.remark)}"</p>` : ''}
                    </div>
                </div>
            `).join('');
        } else {
            timeline.innerHTML = `
                <div class="timeline-item active">
                    <div class="timeline-marker"></div>
                    <div class="timeline-content">
                        <div class="timeline-header">
                            <span class="timeline-status">Reported</span>
                            <span class="timeline-time">${data.time_ago}</span>
                        </div>
                        <span class="timeline-user">Reported by ${escapeHtml(data.reported_by)}</span>
                    </div>
                </div>
            `;
        }
        
        document.getElementById('detailCommentCount').textContent = data.comments.length;
        renderCommentsList(data.comments);

        const feedbackSec = document.getElementById('feedbackSection');
        if (feedbackSec) {
            if (data.status_id === 5 && data.is_own_report) {
                feedbackSec.style.display = 'block';
            } else {
                feedbackSec.style.display = 'none';
            }
        }
        
    } catch (error) {
        console.error('Error fetching issue details:', error);
        document.getElementById('detailTitle').textContent = 'Error Loading Details';
    }
}

function renderCommentsList(comments) {
    const commentsList = document.getElementById('detailCommentsList');
    if (comments.length === 0) {
        commentsList.innerHTML = '<p style="text-align: center; padding: 2rem; color: var(--light-text); font-style: italic;">No discussions started yet. Be the first to comment!</p>';
        return;
    }
    
    commentsList.innerHTML = comments.map(c => `
        <div class="comment-card">
            <div class="comment-avatar">${c.author_initial}</div>
            <div class="comment-content">
                <div class="comment-author-row">
                    <span class="comment-author">${escapeHtml(c.author_name)}</span>
                    <span class="comment-time">${c.time_ago}</span>
                </div>
                <p class="comment-body">${escapeHtml(c.body)}</p>
            </div>
        </div>
    `).join('');
}

async function submitComment(e) {
    e.preventDefault();
    if (!currentActiveIssueId) return;
    
    const textarea = document.getElementById('newCommentBody');
    const bodyText = textarea.value.trim();
    if (!bodyText) return;
    
    const submitBtn = document.getElementById('btnSubmitComment');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    submitBtn.disabled = true;
    submitBtn.textContent = 'Posting...';
    
    try {
        const response = await fetch(`${apiBase}/citizen/api/issues/${currentActiveIssueId}/comments`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ body: bodyText })
        });
        
        if (!response.ok) throw new Error('Failed to post comment');
        
        const data = await response.json();
        if (data.success) {
            textarea.value = '';
            const commentCountEl = document.getElementById('detailCommentCount');
            const count = parseInt(commentCountEl.textContent) + 1;
            commentCountEl.textContent = count;
            
            openDetailsModal(currentActiveIssueId, 'comments');
            fetchIssues();
        }
    } catch (error) {
        console.error('Failed to post comment:', error);
        alert('Could not submit comment. Please try again.');
    } finally {
        submitBtn.disabled = false;
        submitBtn.textContent = 'Post Comment';
    }
}
window.submitComment = submitComment;

async function handleReportFormSubmit(e) {
    e.preventDefault();
    
    const title = document.getElementById('issueTitle').value.trim();
    const description = document.getElementById('issueDescription').value.trim();
    const categoryId = document.getElementById('issueCategory').value;
    const areaName = document.getElementById('issueArea').value.trim();
    const photoInput = document.getElementById('issuePhoto');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    if (!title || !description || !categoryId || !areaName) {
        alert('Please fill in all required fields');
        return;
    }

    try {
        const formData = new FormData();
        formData.append('title', title);
        formData.append('description', description);
        formData.append('category_id', categoryId);
        formData.append('area_name', areaName);
        
        const latVal = document.getElementById('issueLat').value;
        const lngVal = document.getElementById('issueLng').value;
        if (latVal && lngVal) {
            formData.append('latitude', latVal);
            formData.append('longitude', lngVal);
        }

        if (photoInput && photoInput.files.length > 0) {
            for (let i = 0; i < photoInput.files.length; i++) {
                formData.append('photos[]', photoInput.files[i]);
            }
        }

        const response = await fetch(`${apiBase}/citizen/api/issues`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            body: formData
        });

        if (!response.ok) throw new Error('Failed to create report');
        
        const data = await response.json();
        if (data.success) {
            document.getElementById('reportForm').reset();
            closeModal('reportModal');
            
            alert('Issue reported successfully! Thank you for helping improve our neighborhood.');
            fetchIssues();
            fetchMyReports();
        } else {
            alert(data.message || 'Could not save report. Please check input data.');
        }
    } catch (error) {
        console.error('Failed to submit form:', error);
        alert('An error occurred. Please try again.');
    }
}

// =====================================
// LEADERBOARD RENDERING
// =====================================

function renderLeaderboard(leaders) {
    const leaderboardList = document.getElementById('leaderboardList');
    if (!leaderboardList) return;
    
    leaderboardList.innerHTML = leaders.map(leader => `
        <div class="leaderboard-item">
            <div class="rank-badge rank-${leader.rank}">
                ${leader.rank === 1 ? '🥇' : leader.rank === 2 ? '🥈' : leader.rank === 3 ? '🥉' : leader.rank}
            </div>
            <div class="leaderboard-info">
                <div class="leaderboard-name">${escapeHtml(leader.name)}</div>
                <div class="leaderboard-stat">${leader.issues} issues reported • ${leader.votes} upvotes received</div>
            </div>
            <div class="leaderboard-score">${leader.votes}</div>
        </div>
    `).join('');
}

// =====================================
// INITIALIZATION
// =====================================

document.addEventListener('DOMContentLoaded', function() {
    // Load initial feed and metrics
    fetchIssues();

    // Event listeners - Search
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', (e) => {
            searchIssues(e.target.value);
        });
    }

    // Event listeners - Filter chips
    document.querySelectorAll('.filter-chip').forEach(chip => {
        chip.addEventListener('click', () => {
            const categoryId = chip.dataset.categoryId;
            filterIssues(categoryId);
        });
    });

    // Event listeners - Report Issue modal trigger
    const reportBtn = document.querySelector('.nav-link.btn-report');
    if (reportBtn) {
        reportBtn.addEventListener('click', (e) => {
            e.preventDefault();
            openModal('reportModal');
        });
    }

    // Event listeners - Form submission
    const reportForm = document.getElementById('reportForm');
    if (reportForm) {
        reportForm.addEventListener('submit', handleReportFormSubmit);
    }

    // Smooth scroll for nav anchors
    document.querySelectorAll('.nav-link').forEach(link => {
        link.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            if (href && href.startsWith('#') && href !== '#') {
                e.preventDefault();
                const element = document.querySelector(href);
                if (element) {
                    element.scrollIntoView({ behavior: 'smooth' });
                }
            }
        });
    });

    // Load citizen's own space data
    fetchMyReports();

    // Initialize cascading area hierarchy selector
    initAreaCascadingSelector();
});

document.addEventListener('DOMContentLoaded', function() {
    const reportIssueBtn = document.querySelector('.btn-report-trigger');
    if (reportIssueBtn) {
        reportIssueBtn.addEventListener('click', () => {
            openModal('reportModal');
        });
    }
    
    // Load citizen's own space data
    fetchMyReports();
});

// =====================================
// MY SPACE (PROFILE & MY REPORTS)
// =====================================

async function fetchMyReports() {
    try {
        const response = await fetch(`${apiBase}/citizen/api/issues?my_reports=1`);
        if (!response.ok) throw new Error('Failed to fetch my reports');
        const data = await response.json();
        renderMyReportsTable(data.issues);
    } catch (error) {
        console.error('Error fetching my reports:', error);
        const tbody = document.getElementById('myReportsTableBody');
        if (tbody) {
            tbody.innerHTML = `<tr><td colspan="5" style="text-align: center; color: #e53e3e; padding: 2rem;">Error loading your reports.</td></tr>`;
        }
    }
}

function renderMyReportsTable(issues) {
    const tbody = document.getElementById('myReportsTableBody');
    if (!tbody) return;

    if (issues.length === 0) {
        tbody.innerHTML = `<tr><td colspan="5" style="text-align: center; color: #718096; padding: 3rem 0; font-style: italic;">You have not reported any issues yet.</td></tr>`;
        return;
    }

    tbody.innerHTML = issues.map(issue => {
        let statusColor = '#4a5568';
        let statusBg = '#edf2f7';
        if (issue.status_name === 'Pending') {
            statusColor = '#9b59b6';
            statusBg = '#f3e5f5';
        } else if (issue.status_name === 'Acknowledged') {
            statusColor = '#2b6cb0';
            statusBg = '#ebf8ff';
        } else if (issue.status_name === 'In Progress') {
            statusColor = '#d69e2e';
            statusBg = '#fefcbf';
        } else if (issue.status_name === 'Resolved') {
            statusColor = '#2f855a';
            statusBg = '#f0fff4';
        } else if (issue.status_name === 'Closed') {
            statusColor = '#2d3748';
            statusBg = '#edf2f7';
        } else if (issue.status_name === 'Rejected') {
            statusColor = '#c53030';
            statusBg = '#fff5f5';
        }

        return `
            <tr style="border-bottom: 1px solid #edf2f7; font-size: 0.95rem;">
                <td style="padding: 1rem 0.5rem; font-weight: 600; color: #2d3748;">${escapeHtml(issue.title)}</td>
                <td style="padding: 1rem 0.5rem; color: #4a5568;">${escapeHtml(issue.category_name)}</td>
                <td style="padding: 1rem 0.5rem;">
                    <span style="background: ${statusBg}; color: ${statusColor}; font-size: 0.8rem; font-weight: 700; padding: 0.25rem 0.65rem; border-radius: 9999px; display: inline-block;">${escapeHtml(issue.status_name)}</span>
                </td>
                <td style="padding: 1rem 0.5rem; font-weight: 600; color: #4a5568;">👍 ${issue.votes}</td>
                <td style="padding: 1rem 0.5rem; text-align: right;">
                    <button onclick="openDetailsModal(${issue.id})" style="background: var(--primary-color); color: white; border: none; border-radius: 6px; padding: 0.4rem 0.8rem; font-size: 0.85rem; font-weight: 600; cursor: pointer; transition: all 0.2s;">View Status</button>
                </td>
            </tr>
        `;
    }).join('');
}

window.openDetailsModal = openDetailsModal;
window.fetchMyReports = fetchMyReports;

// =====================================
// MAP LOCATION PICKER SELECTOR
// =====================================

let selectionMap = null;
let selectionMarker = null;

function initReportMap() {
    const defaultLat = 23.8103;
    const defaultLng = 90.4125;

    document.getElementById('issueLat').value = defaultLat;
    document.getElementById('issueLng').value = defaultLng;

    if (selectionMap) {
        setTimeout(() => {
            selectionMap.invalidateSize();
            selectionMarker.setLatLng([defaultLat, defaultLng]);
            selectionMap.setView([defaultLat, defaultLng], 13);
        }, 200);
        return;
    }

    setTimeout(() => {
        selectionMap = L.map('locationSelectorMap').setView([defaultLat, defaultLng], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(selectionMap);

        selectionMarker = L.marker([defaultLat, defaultLng], { draggable: true }).addTo(selectionMap);

        selectionMarker.on('dragend', function(e) {
            const pos = selectionMarker.getLatLng();
            document.getElementById('issueLat').value = pos.lat.toFixed(6);
            document.getElementById('issueLng').value = pos.lng.toFixed(6);
            reverseGeocode(pos.lat, pos.lng);
        });

        selectionMap.on('click', function(e) {
            const lat = e.latlng.lat;
            const lng = e.latlng.lng;
            selectionMarker.setLatLng([lat, lng]);
            document.getElementById('issueLat').value = lat.toFixed(6);
            document.getElementById('issueLng').value = lng.toFixed(6);
            reverseGeocode(lat, lng);
        });

        selectionMap.invalidateSize();
    }, 200);
}

function locateUser() {
    if (!navigator.geolocation) {
        alert("Geolocation is not supported by your browser");
        return;
    }

    navigator.geolocation.getCurrentPosition(
        (position) => {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;

            if (selectionMap && selectionMarker) {
                selectionMarker.setLatLng([lat, lng]);
                selectionMap.setView([lat, lng], 15);
                document.getElementById('issueLat').value = lat.toFixed(6);
                document.getElementById('issueLng').value = lng.toFixed(6);
                reverseGeocode(lat, lng);
            }
        },
        (error) => {
            alert("Unable to retrieve your location. Please select it manually on the map.");
        }
    );
}

window.initReportMap = initReportMap;
window.locateUser = locateUser;

// =====================================
// REGION CASCADING AREA SELECTOR
// =====================================

function initAreaCascadingSelector() {
    // No dropdown population needed since it is now a direct text input.
}

// =====================================
// CITIZEN RESOLUTION FEEDBACK
// =====================================

let currentFeedbackAction = null;

function submitFeedbackAction(action) {
    currentFeedbackAction = action;
}

async function handleFeedbackSubmit(e) {
    e.preventDefault();
    
    if (!currentFeedbackAction) {
        alert('Please select whether you are satisfied or want to reopen the issue.');
        return;
    }

    if (!currentActiveIssueId) {
        alert('Error: No active issue.');
        return;
    }

    const rating = document.getElementById('feedbackRating').value;
    const comment = document.getElementById('feedbackComment').value.trim();
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    const submitBtn = e.target.querySelector(`button[onclick*="${currentFeedbackAction}"]`);
    const originalText = submitBtn ? submitBtn.textContent : 'Submitting...';
    
    if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.textContent = 'Submitting...';
    }

    try {
        const response = await fetch(`${apiBase}/citizen/api/issues/${currentActiveIssueId}/feedback`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                rating: parseInt(rating),
                comment: comment,
                action: currentFeedbackAction
            })
        });

        if (!response.ok) {
            throw new Error('Failed to submit feedback');
        }

        const data = await response.json();
        if (data.success) {
            alert(data.message);
            closeModal('detailsModal');
            document.getElementById('feedbackForm').reset();
            fetchIssues(); // Refresh feed
        } else {
            alert(data.message || 'An error occurred.');
        }
    } catch (error) {
        console.error('Feedback submit error:', error);
        alert('Could not submit review. Please try again.');
    } finally {
        if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.textContent = originalText;
        }
        currentFeedbackAction = null;
    }
}

window.submitFeedbackAction = submitFeedbackAction;
window.handleFeedbackSubmit = handleFeedbackSubmit;

async function reverseGeocode(lat, lng) {
    const areaInput = document.getElementById('issueArea');
    if (!areaInput) return;

    areaInput.value = "Fetching exact address...";

    try {
        const response = await fetch(`${apiBase}/api/geocode/reverse?lat=${lat}&lon=${lng}`);
        if (!response.ok) {
            throw new Error('Reverse geocoding request failed');
        }
        const data = await response.json();
        if (data.success && data.address) {
            areaInput.value = data.address;
        } else {
            areaInput.value = "Address not found";
        }
    } catch (error) {
        console.error('Error reverse geocoding coordinates:', error);
        areaInput.value = "Unable to fetch address";
    }
}
