<?php
$pageTitle = 'Activity Log';
$pageSubtitle = 'Riwayat aktivitas sistem & user';
require __DIR__ . '/includes/admin_header.php';
?>

<div class="card">
    <!-- Toolbar -->
    <div class="toolbar">
        <button class="filter-btn active" data-range="today" onclick="loadActivity('today', this)">📅 Hari Ini</button>
        <button class="filter-btn" data-range="7days" onclick="loadActivity('7days', this)">📆 7 Hari</button>
        <button class="filter-btn" data-range="30days" onclick="loadActivity('30days', this)">🗓️ 30 Hari</button>
        <button class="filter-btn" data-range="all" onclick="loadActivity('all', this)">📋 Semua</button>

        <div class="search-box" style="flex:1;min-width:220px;margin-left:auto;">
            <i class="fas fa-search"></i>
            <input type="text" id="searchInput" placeholder="Cari action, detail, atau user...">
        </div>
        <button class="btn btn-ghost btn-sm" onclick="clearSearch()" title="Reset">
            <i class="fas fa-redo"></i>
        </button>
        <button class="btn btn-success btn-sm" onclick="exportActivity()" title="Download CSV">
            <i class="fas fa-file-csv"></i> Export CSV
        </button>
    </div>

    <div class="table-wrap">
        <table id="actTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>Action</th>
                    <th>Detail</th>
                    <th>IP</th>
                    <th>Waktu</th>
                </tr>
            </thead>
            <tbody>
                <tr><td colspan="6"><div class="skeleton" style="width:100%"></div></td></tr>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div id="pagination" style="display:flex;justify-content:space-between;align-items:center;margin-top:20px;padding-top:16px;border-top:1px solid var(--border);">
        <div style="font-size:0.8rem;color:var(--muted)" id="pageInfo">—</div>
        <div style="display:flex;gap:8px;">
            <button class="btn btn-ghost btn-sm" id="prevBtn" onclick="changePage(-1)">
                <i class="fas fa-chevron-left"></i> Prev
            </button>
            <button class="btn btn-ghost btn-sm" id="nextBtn" onclick="changePage(1)">
                Next <i class="fas fa-chevron-right"></i>
            </button>
        </div>
    </div>
</div>

<script>
const API = <?= json_encode($apiUrl) ?>;
const TOKEN = <?= json_encode($authToken) ?>;

let currentRange = 'today';
let currentSearch = '';
let currentPage = 1;
let totalPages = 1;
const PER_PAGE = 50;

async function loadActivity(range = currentRange, btn = null) {
    if (btn) {
        document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
    }
    currentRange = range;
    currentPage = 1;
    await fetchActivity();
}

async function fetchActivity() {
    const tbody = document.querySelector('#actTable tbody');
    tbody.innerHTML = '<tr><td colspan="6"><div class="skeleton" style="width:100%;height:20px"></div></td></tr>';

    try {
        const url = API + '/admin/activity?' +
            'range=' + currentRange +
            '&search=' + encodeURIComponent(currentSearch) +
            '&page=' + currentPage +
            '&limit=' + PER_PAGE;

        const r = await fetch(url, { headers: { 'Authorization': 'Bearer ' + TOKEN } });
        const d = await r.json();
        if (!d.success) throw new Error(d.error);

        totalPages = Math.max(1, Math.ceil(d.pagination.total / PER_PAGE));

        if (d.logs.length === 0) {
            tbody.innerHTML = '<tr><td colspan="6"><div class="empty-state"><i class="fas fa-history"></i><p>Tidak ada aktivitas ditemukan</p></div></td></tr>';
            document.getElementById('pageInfo').textContent = 'Total: 0 aktivitas';
            document.getElementById('prevBtn').disabled = true;
            document.getElementById('nextBtn').disabled = true;
            return;
        }

        tbody.innerHTML = d.logs.map(l => `
            <tr>
                <td><strong>#${l.id}</strong></td>
                <td>
                    <small>${l.username
                        ? '<strong style="color:var(--text)">' + l.username + '</strong><br><span style="color:var(--muted)">' + (l.email || '') + '</span>'
                        : '<em style="color:var(--muted)">System</em>'}</small>
                </td>
                <td>${actionBadge(l.action)}</td>
                <td style="font-size:0.8rem;color:var(--muted);max-width:320px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" title="${escapeHtml(l.details || '')}">
                    ${escapeHtml(l.details || '—')}
                </td>
                <td style="font-size:0.75rem;color:var(--muted);">${l.ip_address || '—'}</td>
                <td style="font-size:0.8rem;white-space:nowrap;">${formatDate(l.created_at)}</td>
            </tr>
        `).join('');

        const start = ((currentPage - 1) * PER_PAGE) + 1;
        const end = Math.min(currentPage * PER_PAGE, d.pagination.total);
        document.getElementById('pageInfo').textContent = 'Menampilkan ' + start + '–' + end + ' dari ' + d.pagination.total + ' aktivitas';
        document.getElementById('prevBtn').disabled = currentPage <= 1;
        document.getElementById('nextBtn').disabled = currentPage >= totalPages;
    } catch (e) {
        tbody.innerHTML = '<tr><td colspan="6"><div class="empty-state"><i class="fas fa-exclamation-triangle" style="color:var(--danger)"></i><p>Error: ' + e.message + '</p></div></td></tr>';
    }
}

function actionBadge(action) {
    let cls = 'badge-info';
    if (action.includes('login') || action.includes('register')) cls = 'badge-success';
    else if (action.includes('delete') || action.includes('reject')) cls = 'badge-danger';
    else if (action.includes('subscription') || action.includes('admin')) cls = 'badge-warning';
    else if (action.includes('logout')) cls = 'badge-muted';
    else if (action.includes('reset')) cls = 'badge-purple';

    return '<span class="badge ' + cls + '">' + escapeHtml(action) + '</span>';
}

function changePage(delta) {
    const newPage = currentPage + delta;
    if (newPage < 1 || newPage > totalPages) return;
    currentPage = newPage;
    fetchActivity();
    document.querySelector('.card').scrollIntoView({ behavior: 'smooth', block: 'start' });
}

function clearSearch() {
    document.getElementById('searchInput').value = '';
    currentSearch = '';
    currentPage = 1;
    fetchActivity();
}

function formatDate(dateStr) {
    if (!dateStr) return '—';
    const d = new Date(dateStr);
    return d.toLocaleString('id-ID', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}

function escapeHtml(str) {
    if (!str) return '';
    const div = document.createElement('div');
    div.textContent = str;
    return div.innerHTML;
}

// ============ EXPORT CSV ============
async function exportActivity() {
    try {
        toastInfo('Menyiapkan file CSV...');

        const url = API + '/admin/export/activity?range=' + currentRange;
        const r = await fetch(url, {
            headers: { 'Authorization': 'Bearer ' + TOKEN }
        });

        if (!r.ok) throw new Error('Gagal export');

        const blob = await r.blob();
        const downloadUrl = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = downloadUrl;
        a.download = 'clipora_activity_' + currentRange + '_' + new Date().toISOString().slice(0, 10) + '.csv';
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        window.URL.revokeObjectURL(downloadUrl);

        toastSuccess('✅ File CSV berhasil didownload!');
    } catch (e) {
        toastError('Gagal export: ' + e.message);
    }
}

// Search dengan delay (debounce)
let searchTimeout = null;
document.getElementById('searchInput').addEventListener('input', (e) => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        currentSearch = e.target.value.trim();
        currentPage = 1;
        fetchActivity();
    }, 400);
});

document.getElementById('searchInput').addEventListener('keyup', (e) => {
    if (e.key === 'Enter') {
        clearTimeout(searchTimeout);
        currentSearch = e.target.value.trim();
        currentPage = 1;
        fetchActivity();
    }
});

// Init
loadActivity('today');
</script>

</div></body></html>