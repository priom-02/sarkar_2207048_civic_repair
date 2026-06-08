// =====================================
// DATA & STATE MANAGEMENT
// =====================================

// Sample data - will be replaced with API calls
const sampleIssues = [
    {
        id: 1,
        title: 'Hazardous Pothole on Maple Avenue',
        description: 'Large pothole causing traffic issues and potential vehicle damage.',
        category: 'road',
        area: 'downtown',
        status: 'inprogress',
        reportedBy: 'James Wilson',
        reportedByInitial: 'J',
        votes: 45,
        comments: 12,
        timeAgo: '2 hours ago',
        voted: false
    },
    {
        id: 2,
        title: 'Overflowing Trash Bins at Central Park',
        description: 'Multiple trash bins overflow during weekends causing litter issues.',
        category: 'waste',
        area: 'downtown',
        status: 'reported',
        reportedBy: 'Sarah Chen',
        reportedByInitial: 'S',
        votes: 28,
        comments: 5,
        timeAgo: '5 hours ago',
        voted: false
    },
    {
        id: 3,
        title: 'Broken Street Light on Oak Street',
        description: 'Street light is broken creating a safety hazard in the evening.',
        category: 'safety',
        area: 'westside',
        status: 'assigned',
        reportedBy: 'Mike Johnson',
        reportedByInitial: 'M',
        votes: 32,
        comments: 8,
        timeAgo: '1 day ago',
        voted: false
    },
    {
        id: 4,
        title: 'Water Main Leak on 5th Street',
        description: 'Water continuously flowing from underground, possible main leak.',
        category: 'water',
        area: 'eastside',
        status: 'completed',
        reportedBy: 'Lisa Park',
        reportedByInitial: 'L',
        votes: 56,
        comments: 15,
        timeAgo: '3 days ago',
        voted: false
    },
    {
        id: 5,
        title: 'Damaged Sidewalk - Tripping Hazard',
        description: 'Uneven sidewalk sections creating potential tripping hazard for pedestrians.',
        category: 'road',
        area: 'northside',
        status: 'inprogress',
        reportedBy: 'David Brown',
        reportedByInitial: 'D',
        votes: 18,
        comments: 3,
        timeAgo: '4 days ago',
        voted: false
    }
];

const sampleLeaders = [
    { rank: 1, name: 'Community Hero Alex', issues: 24, votes: 156 },
    { rank: 2, name: 'Active Reporter Jordan', issues: 18, votes: 142 },
    { rank: 3, name: 'Civic Champion Morgan', issues: 15, votes: 128 },
    { rank: 4, name: 'Dedicated Citizen Casey', issues: 12, votes: 95 }
];

// State
let allIssues = [...sampleIssues];
let filteredIssues = [...sampleIssues];
let currentFilter = 'all';

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

// =====================================
// ISSUE RENDERING
// =====================================

function renderIssues(issues) {
    const issuesList = document.getElementById('issuesList');
    
    if (issues.length === 0) {
        issuesList.innerHTML = '<div style="text-align: center; padding: 2rem; color: var(--light-text);">No issues found</div>';
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
        road: '🛣️',
        waste: '♻️',
        safety: '🚨',
        water: '💧',
        other: '📋'
    };

    const categoryLabel = {
        road: 'Road Maintenance',
        waste: 'Waste Management',
        safety: 'Public Safety',
        water: 'Water/Utilities',
        other: 'Other'
    };

    const areaLabel = {
        downtown: 'Downtown',
        westside: 'West Side',
        eastside: 'East Side',
        northside: 'North Side'
    };

    const statusLabel = {
        reported: 'Reported',
        assigned: 'Assigned',
        inprogress: 'In Progress',
        completed: 'Completed'
    };

    return `
        <div class="issue-card" data-issue-id="${issue.id}">
            <div class="issue-header">
                <h3 class="issue-title">${escapeHtml(issue.title)}</h3>
                <span class="issue-status status-${issue.status}">${statusLabel[issue.status]}</span>
            </div>
            
            <div class="issue-meta">
                <span class="issue-category">
                    ${categoryEmoji[issue.category]} ${categoryLabel[issue.category]}
                </span>
                <span class="issue-area">
                    📍 ${areaLabel[issue.area]}
                </span>
                <span class="issue-time">
                    🕐 ${issue.timeAgo}
                </span>
            </div>

            <p class="issue-description">${escapeHtml(issue.description)}</p>

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
                    <div class="reporter-avatar">${issue.reportedByInitial}</div>
                    <span>${escapeHtml(issue.reportedBy)}</span>
                </div>
            </div>
        </div>
    `;
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// =====================================
// FILTERING & SEARCH
// =====================================

function filterIssues(category) {
    currentFilter = category;
    
    if (category === 'all') {
        filteredIssues = [...allIssues];
    } else {
        filteredIssues = allIssues.filter(issue => issue.category === category);
    }

    renderIssues(filteredIssues);
    updateFilterChips(category);
}

function updateFilterChips(activeCategory) {
    document.querySelectorAll('.filter-chip').forEach(chip => {
        const category = chip.dataset.category;
        if (category === activeCategory) {
            chip.classList.add('active');
        } else {
            chip.classList.remove('active');
        }
    });
}

function searchIssues(query) {
    const lowerQuery = query.toLowerCase().trim();
    
    if (lowerQuery === '') {
        filteredIssues = currentFilter === 'all' 
            ? [...allIssues] 
            : allIssues.filter(issue => issue.category === currentFilter);
    } else {
        const filtered = allIssues.filter(issue => 
            (currentFilter === 'all' || issue.category === currentFilter) &&
            (issue.title.toLowerCase().includes(lowerQuery) ||
             issue.description.toLowerCase().includes(lowerQuery))
        );
        filteredIssues = filtered;
    }

    renderIssues(filteredIssues);
}

// =====================================
// EVENT HANDLERS
// =====================================

function handleVote(e) {
    e.preventDefault();
    const issueId = parseInt(e.currentTarget.dataset.issueId);
    const issue = allIssues.find(i => i.id === issueId);

    if (issue) {
        if (issue.voted) {
            issue.votes--;
            issue.voted = false;
        } else {
            issue.votes++;
            issue.voted = true;
        }

        // Update filtered issues if displayed
        const filteredIssue = filteredIssues.find(i => i.id === issueId);
        if (filteredIssue) {
            filteredIssue.votes = issue.votes;
            filteredIssue.voted = issue.voted;
        }

        renderIssues(filteredIssues);
        
        // Animate vote count
        const voteBtn = e.currentTarget;
        voteBtn.style.transform = 'scale(1.2)';
        setTimeout(() => {
            voteBtn.style.transform = 'scale(1)';
        }, 100);
    }
}

function handleCommentClick(e) {
    e.preventDefault();
    const issueId = parseInt(e.currentTarget.dataset.issueId);
    // This will navigate to issue detail page or open comments modal
    console.log('View comments for issue:', issueId);
    alert('Comments feature - showing comments for issue ' + issueId);
}

function handleIssueClick(e) {
    const issueCard = e.currentTarget.closest('.issue-card');
    const issueId = parseInt(issueCard.dataset.issueId);
    // Navigate to issue detail page
    console.log('Viewing issue details:', issueId);
    alert('Navigating to issue detail page for issue ' + issueId);
}

function handleReportFormSubmit(e) {
    e.preventDefault();
    
    const title = document.getElementById('issueTitle').value;
    const description = document.getElementById('issueDescription').value;
    const category = document.getElementById('issueCategory').value;
    const area = document.getElementById('issueArea').value;
    const photo = document.getElementById('issuePhoto').files.length > 0;

    // Validation
    if (!title || !description || !category || !area) {
        alert('Please fill in all required fields');
        return;
    }

    // Create new issue object
    const newIssue = {
        id: allIssues.length + 1,
        title: title,
        description: description,
        category: category,
        area: area,
        status: 'reported',
        reportedBy: 'You',
        reportedByInitial: 'Y',
        votes: 0,
        comments: 0,
        timeAgo: 'just now',
        voted: false
    };

    // Add to issues list
    allIssues.unshift(newIssue);
    
    // Reset and close form
    document.getElementById('reportForm').reset();
    closeModal('reportModal');
    
    // Show success message
    alert('Issue reported successfully! Thank you for helping improve our community.');
    
    // Refresh the display
    filterIssues('all');
}

// =====================================
// LEADERBOARD RENDERING
// =====================================

function renderLeaderboard(leaders) {
    const leaderboardList = document.getElementById('leaderboardList');
    
    leaderboardList.innerHTML = leaders.map(leader => `
        <div class="leaderboard-item">
            <div class="rank-badge rank-${leader.rank}">
                ${leader.rank === 1 ? '🥇' : leader.rank === 2 ? '🥈' : leader.rank === 3 ? '🥉' : leader.rank}
            </div>
            <div class="leaderboard-info">
                <div class="leaderboard-name">${escapeHtml(leader.name)}</div>
                <div class="leaderboard-stat">${leader.issues} issues reported • ${leader.votes} community votes</div>
            </div>
            <div class="leaderboard-score">${leader.votes}</div>
        </div>
    `).join('');
}

// =====================================
// INITIALIZATION
// =====================================

document.addEventListener('DOMContentLoaded', function() {
    // Initial render
    renderIssues(filteredIssues);
    renderLeaderboard(sampleLeaders);

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
            const category = chip.dataset.category;
            filterIssues(category);
        });
    });

    // Event listeners - Report Issue button
    const reportBtn = document.querySelector('.nav-link.btn-report');
    if (reportBtn) {
        reportBtn.addEventListener('click', (e) => {
            e.preventDefault();
            openModal('reportModal');
        });
    }

    // Event listeners - Report Form
    const reportForm = document.getElementById('reportForm');
    if (reportForm) {
        reportForm.addEventListener('submit', handleReportFormSubmit);
    }

    // Smooth scroll for navigation links
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

// Make report issue accessible from button
document.addEventListener('DOMContentLoaded', function() {
    const reportIssueBtn = document.querySelector('[onclick="scrollToSection(\'report-issue\')"]');
    if (reportIssueBtn) {
        reportIssueBtn.addEventListener('click', () => {
            openModal('reportModal');
        });
    }
});
