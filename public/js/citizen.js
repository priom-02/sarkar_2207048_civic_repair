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
            document.getElementById('detailCoordinates').textContent = `Latitude: ${data.latitude.toFixed(4)}, Longitude: ${data.longitude.toFixed(4)}`;
        } else {
            document.getElementById('detailCoordinates').textContent = 'No GPS coordinates recorded.';
        }
        
        const gallery = document.getElementById('detailMediaGallery');
        if (data.media && data.media.length > 0) {
            gallery.innerHTML = data.media.map(url => `
                <img src="${url}" class="issue-media-item" onclick="window.open('${url}', '_blank')">
            `).join('');
        } else {
            gallery.innerHTML = '<span style="font-size: 0.95rem; color: var(--light-text); font-style: italic;">No photos attached to this report.</span>';
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
    const areaId = document.getElementById('issueArea').value;
    const photoInput = document.getElementById('issuePhoto');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    if (!title || !description || !categoryId || !areaId) {
        alert('Please fill in all required fields');
        return;
    }

    try {
        const formData = new FormData();
        formData.append('title', title);
        formData.append('description', description);
        formData.append('category_id', categoryId);
        formData.append('area_id', areaId);

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
});

document.addEventListener('DOMContentLoaded', function() {
    const reportIssueBtn = document.querySelector('.btn-report-trigger');
    if (reportIssueBtn) {
        reportIssueBtn.addEventListener('click', () => {
            openModal('reportModal');
        });
    }
});
