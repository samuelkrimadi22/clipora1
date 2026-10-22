<?php
$pageTitle = 'Manage Reviews';
$pageSubtitle = 'Kelola rating dan review pengguna Clipora';
require __DIR__ . '/includes/admin_header.php';
?>

<style>
.review-stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
    margin-bottom: 24px;
}
.review-stat-card {
    background: linear-gradient(135deg, rgba(30,27,75,0.5), rgba(45,27,78,0.3));
    border: 1px solid rgba(255,255,255,0.08);
    border-radius: 14px;
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 14px;
    transition: 0.2s;
}
.review-stat-card:hover {
    transform: translateY(-2px);
    border-color: rgba(245,158,11,0.3);
}
.review-stat-icon {
    width: 48px; height: 48px;
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 20px;
    flex-shrink: 0;
}
.review-stat-icon.orange { background: rgba(245,158,11,0.15); color: #f59e0b; }
.review-stat-icon.blue   { background: rgba(59,130,246,0.15); color: #3b82f6; }
.review-stat-icon.green  { background: rgba(34,197,94,0.15); color: #22c55e; }
.review-stat-icon.red    { background: rgba(239,68,68,0.15); color: #ef4444; }
.review-stat-value {
    font-size: 26px;
    font-weight: 800;
    color: #e2e8f0;
    line-height: 1;
    margin-bottom: 4px;
}
.review-stat-label {
    font-size: 0.78rem;
    color: #94a3b8;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.4px;
}

.review-filter-bar {
    display: flex;
    gap: 8px;
    margin-bottom: 20px;
    flex-wrap: wrap;
}
.review-filter-btn {
    padding: 8px 18px;
    background: rgba(255,255,255,0.04);
    border: 1px solid rgba(255,255,255,0.08);
    color: #94a3b8;
    border-radius: 8px;
    cursor: pointer;
    font-family: inherit;
    font-size: 13px;
    font-weight: 600;
    transition: 0.2s;
}
.review-filter-btn:hover,
.review-filter-btn.active {
    background: rgba(245,158,11,0.15);
    border-color: rgba(245,158,11,0.4);
    color: #f59e0b;
}

.review-list {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(360px, 1fr));
    gap: 16px;
}
.review-item {
    background: rgba(255,255,255,0.03);
    border: 1px solid rgba(255,255,255,0.08);
    border-radius: 14px;
    padding: 18px;
    transition: 0.2s;
    position: relative;
}
.review-item.hidden-review {
    opacity: 0.55;
    border-color: rgba(239,68,68,0.2);
}
.review-item.featured-review {
    border-color: rgba(245,158,11,0.5);
    background: rgba(245,158,11,0.04);
}
.review-item-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 12px;
}
.review-item-user {
    display: flex;
    align-items: center;
    gap: 10px;
}
.review-item-avatar {
    width: 36px; height: 36px;
    border-radius: 50%;
    background: linear-gradient(135deg, #f59e0b, #d97706);
    color: white;
    display: flex; align-items: center; justify-content: center;
    font-weight: 800;
    font-size: 14px;
    flex-shrink: 0;
}
.review-item-user-info {
    display: flex;
    flex-direction: column;
    line-height: 1.3;
}
.review-item-user-info strong {
    color: #e2e8f0;
    font-size: 13px;
    font-weight: 700;
}
.review-item-user-info span {
    color: #64748b;
    font-size: 11px;
}
.review-item-stars {
    color: #fbbf24;
    font-size: 16px;
    letter-spacing: 2px;
}
.review-item-comment {
    color: #cbd5e1;
    font-size: 13px;
    line-height: 1.6;
    margin-bottom: 14px;
    font-style: italic;
}
.review-item-reply {
    background: rgba(245,158,11,0.06);
    border-left: 3px solid #f59e0b;
    padding: 10px 12px;
    border-radius: 6px;
    margin-bottom: 12px;
    font-size: 12px;
    color: #cbd5e1;
    line-height: 1.5;
}
.review-item-reply strong { color: #f59e0b; display: block; margin-bottom: 4px; }

.review-item-actions {
    display: flex;
    gap: 6px;
    flex-wrap: wrap;
    padding-top: 12px;
    border-top: 1px solid rgba(255,255,255,0.06);
}
.review-action-btn {
    padding: 6px 12px;
    background: rgba(255,255,255,0.04);
    border: 1px solid rgba(255,255,255,0.08);
    color: #94a3b8;
    border-radius: 6px;
    cursor: pointer;
    font-family: inherit;
    font-size: 11px;
    font-weight: 600;
    transition: 0.2s;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}
.review-action-btn:hover {
    background: rgba(255,255,255,0.08);
    color: #e2e8f0;
}
.review-action-btn.warn {
    color: #ef4444;
    border-color: rgba(239,68,68,0.3);
}
.review-action-btn.warn:hover {
    background: rgba(239,68,68,0.1);
}
.review-action-btn.primary {
    color: #f59e0b;
    border-color: rgba(245,158,11,0.3);
}
.review-action-btn.primary:hover {
    background: rgba(245,158,11,0.1);
}

.review-badge {
    position: absolute;
    top: 12px; right: 12px;
    padding: 2px 8px;
    border-radius: 10px;
    font-size: 9px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.review-badge.featured { background: #f59e0b; color: white; }
.review-badge.hidden   { background: #ef4444; color: white; }

.review-loading,
.review-empty {
    grid-column: 1 / -1;
    text-align: center;
    padding: 60px 20px;
    color: #64748b;
}
.review-loading i,
.review-empty i {
    font-size: 3rem;
    opacity: 0.3;
    display: block;
    margin-bottom: 12px;
}

/* Modal */
.review-modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.75);
    backdrop-filter: blur(6px);
    z-index: 9999;
    display: none;
    align-items: center;
    justify-content: center;
}
.review-modal-overlay.active { display: flex; }
.review-modal-box {
    background: linear-gradient(135deg, #1e1b4b, #2d1b4e);
    border: 1px solid rgba(245,158,11,0.3);
    border-radius: 16px;
    padding: 28px;
    max-width: 520px;
    width: 90%;
    box-shadow: 0 20px 60px rgba(0,0,0,0.5);
}
.review-modal-box h3 {
    color: white;
    margin: 0 0 16px;
    font-size: 18px;
    font-weight: 800;
}
.review-modal-original {
    background: rgba(255,255,255,0.03);
    border-radius: 8px;
    padding: 12px;
    margin-bottom: 16px;
    font-size: 12px;
    color: #cbd5e1;
    font-style: italic;
    line-height: 1.5;
}
.review-modal-box textarea {
    width: 100%;
    min-height: 100px;
    padding: 12px;
    background: #0f172a;
    border: 1px solid #334155;
    border-radius: 8px;
    color: white;
    font-family: inherit;
    font-size: 13px;
    resize: vertical;
    box-sizing: border-box;
}
.review-modal-actions {
    margin-top: 16px;
    display: flex;
    gap: 10px;
    justify-content: flex-end;
}
.review-modal-actions button {
    padding: 10px 20px;
    border-radius: 8px;
    cursor: pointer;
    font-family: inherit;
    font-size: 13px;
    font-weight: 700;
    border: none;
}
.review-modal-cancel {
    background: transparent;
    border: 1px solid #334155;
    color: #94a3b8;
}
.review-modal-cancel:hover { background: rgba(255,255,255,0.05); color: #e2e8f0; }
.review-modal-submit {
    background: linear-gradient(135deg, #f59e0b, #d97706);
    color: white;
}
.review-modal-submit:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(245,158,11,0.4); }
</style>

<!-- ============ STATS ============ -->
<div class="review-stats-grid">
    <div class="review-stat-card">
        <div class="review-stat-icon orange"><i class="fas fa-star"></i></div>
        <div>
            <div class="review-stat-value" id="statAvg">—</div>
            <div class="review-stat-label">Rating Rata-rata</div>
        </div>
    </div>
    <div class="review-stat-card">
        <div class="review-stat-icon blue"><i class="fas fa-comments"></i></div>
        <div>
            <div class="review-stat-value" id="statTotal">—</div>
            <div class="review-stat-label">Total Review</div>
        </div>
    </div>
    <div class="review-stat-card">
        <div class="review-stat-icon green"><i class="fas fa-eye"></i></div>
        <div>
            <div class="review-stat-value" id="statVisible">—</div>
            <div class="review-stat-label">Visible</div>
        </div>
    </div>
    <div class="review-stat-card">
        <div class="review-stat-icon red"><i class="fas fa-eye-slash"></i></div>
        <div>
            <div class="review-stat-value" id="statHidden">—</div>
            <div class="review-stat-label">Hidden</div>
        </div>
    </div>
</div>

<!-- ============ FILTER ============ -->
<div class="review-filter-bar">
    <button class="review-filter-btn active" data-filter="all">Semua</button>
    <button class="review-filter-btn" data-filter="visible">Visible</button>
    <button class="review-filter-btn" data-filter="hidden">Hidden</button>
    <button class="review-filter-btn" data-filter="featured">Featured</button>
</div>

<!-- ============ REVIEW LIST ============ -->
<div class="review-list" id="reviewList">
    <div class="review-loading">
        <i class="fas fa-spinner fa-spin"></i>
        <p>Memuat review...</p>
    </div>
</div>

<!-- ============ REPLY MODAL ============ -->
<div class="review-modal-overlay" id="replyModal">
    <div class="review-modal-box">
        <h3>💬 Balas Review</h3>
        <div class="review-modal-original" id="replyOriginal">—</div>
        <textarea id="replyText" placeholder="Tulis balasan admin..."></textarea>
        <div class="review-modal-actions">
            <button class="review-modal-cancel" onclick="closeReplyModal()">Batal</button>
            <button class="review-modal-submit" onclick="submitReply()">Kirim Balasan</button>
        </div>
    </div>
</div>

<script>
(function() {
    // V9.128.4: USE window.API - dari admin_header.php (sudah global)
    var API = window.API || 'http://localhost:3000/api';
    var TOKEN = window.TOKEN || '';
    var allReviews = [];
    var currentFilter = 'all';
    var replyTargetId = null;

    function escapeHtml(s) {
        if (!s) return '';
        return String(s).replace(/[&<>"']/g, function(m) {
            return { '&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;' }[m];
        });
    }

    function renderStars(r) {
        var s = '';
        for (var i = 1; i <= 5; i++) s += (i <= r ? '★' : '☆');
        return s;
    }

    function timeAgo(d) {
        var t = new Date(d);
        var diff = Math.floor((Date.now() - t.getTime()) / 1000);
        if (diff < 60) return 'Baru saja';
        if (diff < 3600) return Math.floor(diff/60) + 'm lalu';
        if (diff < 86400) return Math.floor(diff/3600) + 'j lalu';
        if (diff < 2592000) return Math.floor(diff/86400) + 'd lalu';
        return t.toLocaleDateString('id-ID');
    }

    function renderReviews() {
        var list = document.getElementById('reviewList');
        var filtered = allReviews.filter(function(r) {
            if (currentFilter === 'visible') return r.is_visible !== false;
            if (currentFilter === 'hidden') return r.is_visible === false;
            if (currentFilter === 'featured') return r.is_featured === true;
            return true;
        });

        if (filtered.length === 0) {
            list.innerHTML = '<div class="review-empty"><i class="fas fa-inbox"></i><p>Belum ada review</p></div>';
            return;
        }

        var html = '';
        filtered.forEach(function(r) {
            var initial = (r.username || 'U').charAt(0).toUpperCase();
            var cls = '';
            if (r.is_visible === false) cls += ' hidden-review';
            if (r.is_featured === true) cls += ' featured-review';
            html += '<div class="review-item' + cls + '">';
            if (r.is_featured) html += '<span class="review-badge featured">Featured</span>';
            else if (r.is_visible === false) html += '<span class="review-badge hidden">Hidden</span>';
            html += '<div class="review-item-header">';
            html += '<div class="review-item-user">';
            html += '<div class="review-item-avatar">' + initial + '</div>';
            html += '<div class="review-item-user-info">';
            html += '<strong>' + escapeHtml(r.username || 'Pengguna') + '</strong>';
            html += '<span>' + escapeHtml(r.email || '') + '</span>';
            html += '</div></div>';
            html += '<div class="review-item-stars">' + renderStars(r.rating) + '</div>';
            html += '</div>';
            if (r.comment) {
                html += '<div class="review-item-comment">"' + escapeHtml(r.comment) + '"</div>';
            }
            if (r.admin_reply) {
                html += '<div class="review-item-reply"><strong>Balasan Admin:</strong>' + escapeHtml(r.admin_reply) + '</div>';
            }
            html += '<div class="review-item-actions">';
            html += '<button class="review-action-btn primary" onclick="replyReview(' + r.id + ')"><i class="fas fa-reply"></i> Balas</button>';
            html += '<button class="review-action-btn ' + (r.is_visible === false ? 'primary' : 'warn') + '" onclick="toggleVisibility(' + r.id + ', ' + (r.is_visible === false) + ')">';
            html += '<i class="fas fa-' + (r.is_visible === false ? 'eye' : 'eye-slash') + '"></i> ' + (r.is_visible === false ? 'Tampilkan' : 'Sembunyikan');
            html += '</button>';
            html += '<button class="review-action-btn ' + (r.is_featured ? 'warn' : 'primary') + '" onclick="toggleFeature(' + r.id + ', ' + (r.is_featured === true) + ')">';
            html += '<i class="fas fa-star"></i> ' + (r.is_featured ? 'Unfeature' : 'Feature');
            html += '</button>';
            html += '<span style="margin-left:auto;font-size:11px;color:#64748b;">' + timeAgo(r.created_at) + '</span>';
            html += '</div></div>';
        });
        list.innerHTML = html;
    }

    async function loadReviews() {
        try {
            var r = await fetch(API + '/reviews/admin/all', {
                headers: { 'Authorization': 'Bearer ' + TOKEN }
            });
            var d = await r.json();
            if (!d.success) throw new Error(d.error || 'Gagal load');

            allReviews = d.reviews || [];

            // Stats
            var visible = allReviews.filter(function(x) { return x.is_visible !== false; }).length;
            var hidden = allReviews.length - visible;
            var sum = allReviews.reduce(function(a, x) { return a + (x.rating || 0); }, 0);
            var avg = allReviews.length > 0 ? (sum / allReviews.length).toFixed(1) : '—';

            document.getElementById('statAvg').textContent = avg;
            document.getElementById('statTotal').textContent = allReviews.length;
            document.getElementById('statVisible').textContent = visible;
            document.getElementById('statHidden').textContent = hidden;

            renderReviews();
            console.log('[Admin Reviews] Loaded', allReviews.length, 'reviews');
        } catch (e) {
            console.error('[Admin Reviews]', e);
            document.getElementById('reviewList').innerHTML = '<div class="review-empty"><i class="fas fa-exclamation-triangle"></i><p>Error: ' + escapeHtml(e.message) + '</p></div>';
        }
    }

    window.toggleVisibility = async function(id, currentHidden) {
        var newVisible = currentHidden; // kalau sekarang hidden, jadi visible
        try {
            var r = await fetch(API + '/reviews/admin/' + id + '/visibility', {
                method: 'PUT',
                headers: { 'Authorization': 'Bearer ' + TOKEN, 'Content-Type': 'application/json' },
                body: JSON.stringify({ is_visible: newVisible })
            });
            var d = await r.json();
            if (d.success) {
                var item = allReviews.find(function(x) { return x.id === id; });
                if (item) item.is_visible = newVisible;
                renderReviews();
            }
        } catch (e) { alert('Error: ' + e.message); }
    };

    window.toggleFeature = async function(id, currentFeatured) {
        var newFeatured = !currentFeatured;
        try {
            var r = await fetch(API + '/reviews/admin/' + id + '/feature', {
                method: 'PUT',
                headers: { 'Authorization': 'Bearer ' + TOKEN, 'Content-Type': 'application/json' },
                body: JSON.stringify({ is_featured: newFeatured })
            });
            var d = await r.json();
            if (d.success) {
                var item = allReviews.find(function(x) { return x.id === id; });
                if (item) item.is_featured = newFeatured;
                renderReviews();
            }
        } catch (e) { alert('Error: ' + e.message); }
    };

    window.replyReview = function(id) {
        var item = allReviews.find(function(x) { return x.id === id; });
        if (!item) return;
        replyTargetId = id;
        document.getElementById('replyOriginal').textContent = '"' + (item.comment || '(no comment)') + '" — ' + (item.username || 'User');
        document.getElementById('replyText').value = item.admin_reply || '';
        document.getElementById('replyModal').classList.add('active');
    };

    window.closeReplyModal = function() {
        document.getElementById('replyModal').classList.remove('active');
        replyTargetId = null;
    };

    window.submitReply = async function() {
        if (!replyTargetId) return;
        var reply = document.getElementById('replyText').value.trim();
        try {
            var r = await fetch(API + '/reviews/admin/' + replyTargetId + '/reply', {
                method: 'POST',
                headers: { 'Authorization': 'Bearer ' + TOKEN, 'Content-Type': 'application/json' },
                body: JSON.stringify({ reply: reply })
            });
            var d = await r.json();
            if (d.success) {
                var item = allReviews.find(function(x) { return x.id === replyTargetId; });
                if (item) item.admin_reply = reply || null;
                renderReviews();
                closeReplyModal();
            }
        } catch (e) { alert('Error: ' + e.message); }
    };

    // Filter
    document.querySelectorAll('.review-filter-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.review-filter-btn').forEach(function(b) { b.classList.remove('active'); });
            btn.classList.add('active');
            currentFilter = btn.getAttribute('data-filter');
            renderReviews();
        });
    });

    // Init
    loadReviews();
})();
</script>

</div></body></html>