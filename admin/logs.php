<?php
$pageTitle = 'System Logs';
$pageSubtitle = 'Monitoring log & error sistem';
require __DIR__ . '/includes/admin_header.php';
?>

<!-- ============ INFO BANNER ============ -->
<div class="card" style="background:linear-gradient(135deg, rgba(139,92,246,0.08), rgba(139,92,246,0.02)); border-color:rgba(139,92,246,0.25);">
    <div style="display:flex; align-items:start; gap:16px;">
        <div style="width:52px;height:52px;border-radius:14px;background:rgba(139,92,246,0.15);color:#8b5cf6;display:flex;align-items:center;justify-content:center;font-size:1.4rem;flex-shrink:0;">
            <i class="fas fa-file-alt"></i>
        </div>
        <div style="flex:1;">
            <h3 style="font-size:1.05rem; font-weight:700; margin-bottom:6px; color:var(--text);">
                System Logs
            </h3>
            <p style="font-size:0.85rem; color:var(--text-soft); line-height:1.6; margin:0;">
                Lihat semua log aktivitas server, error, dan HTTP request. Log lama <strong style="color:var(--text);">otomatis dihapus</strong> setelah 30 hari.
            </p>
        </div>
    </div>
</div>

<!-- ============ STATS ============ -->
<div class="stats-grid" id="statsGrid">
    <div class="stat-card">
        <div class="stat-icon"><i class="fas fa-file-alt"></i></div>
        <div class="stat-label">Total File Log</div>
        <div class="stat-value loading" id="statFiles">—</div>
    </div>
    <div class="stat-card blue">
        <div class="stat-icon"><i class="fas fa-hdd"></i></div>
        <div class="stat-label">Total Size</div>
        <div class="stat-value loading" id="statSize" style="font-size:1.4rem;">—</div>
    </div>
    <div class="stat-card purple">
        <div class="stat-icon"><i class="fas fa-history"></i></div>
        <div class="stat-label">Log Terbaru</div>
        <div class="stat-value loading" id="statLatest" style="font-size:0.9rem;">—</div>
    </div>
    <div class="stat-card green">
        <div class="stat-icon"><i class="fas fa-clock"></i></div>
        <div class="stat-label">Retensi</div>
        <div class="stat-value" style="color:var(--success);">30 hari</div>
    </div>
</div>

<!-- ============ FILE LIST ============ -->
<div class="card">
    <div class="card-header">
        <div>
            <h3><i class="fas fa-folder-open"></i> File Log</h3>
            <p>Klik file untuk melihat isinya</p>
        </div>
        <button class="btn btn-ghost btn-sm" onclick="loadLogs()">
            <i class="fas fa-redo"></i> Refresh
        </button>
    </div>

    <div id="logsList" style="margin-top:12px;">
        <div class="skeleton" style="width:100%;height:60px;margin-bottom:8px;"></div>
        <div class="skeleton" style="width:100%;height:60px;"></div>
    </div>
</div>

<!-- ============ LOG VIEWER MODAL ============ -->
<div class="log-modal-overlay" id="logModal">
    <div class="log-modal-box">
        <div class="log-modal-header">
            <div style="display:flex;align-items:center;gap:12px;">
                <div class="log-file-icon" id="modalFileIcon">
                    <i class="fas fa-file-alt"></i>
                </div>
                <div>
                    <div class="log-modal-title" id="modalFilename">filename.log</div>
                    <div class="log-modal-subtitle" id="modalFileInfo">—</div>
                </div>
            </div>
            <button class="log-modal-close" onclick="closeLogModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="log-modal-toolbar">
            <div class="log-filter-btns">
                <button class="log-filter-btn active" onclick="filterLog('all', this)">
                    <i class="fas fa-list"></i> Semua
                </button>
                <button class="log-filter-btn" onclick="filterLog('error', this)">
                    <i class="fas fa-times-circle"></i> Error
                </button>
                <button class="log-filter-btn" onclick="filterLog('warn', this)">
                    <i class="fas fa-exclamation-triangle"></i> Warning
                </button>
                <button class="log-filter-btn" onclick="filterLog('info', this)">
                    <i class="fas fa-info-circle"></i> Info
                </button>
            </div>

            <div class="log-toolbar-actions">
                <select class="log-lines-select" id="logLinesSelect" onchange="changeLines()">
                    <option value="100">100 baris</option>
                    <option value="200" selected>200 baris</option>
                    <option value="500">500 baris</option>
                    <option value="1000">1000 baris</option>
                </select>
                <button class="btn btn-ghost btn-sm" onclick="downloadLog()" title="Download file">
                    <i class="fas fa-download"></i>
                </button>
            </div>
        </div>

        <div class="log-content" id="logContent">
            <div style="padding:40px;text-align:center;color:var(--muted);">
                <i class="fas fa-spinner fa-spin" style="font-size:2rem;margin-bottom:12px;display:block;"></i>
                Memuat log...
            </div>
        </div>
    </div>
</div>

<style>
/* ============ LOG LIST ============ */
.log-item{
    display:flex;
    align-items:center;
    gap:16px;
    padding:16px 20px;
    background:rgba(255,255,255,0.02);
    border:1px solid var(--border);
    border-radius:14px;
    margin-bottom:10px;
    cursor:pointer;
    transition:0.2s;
}
.log-item:hover{
    background:rgba(139,92,246,0.05);
    border-color:rgba(139,92,246,0.3);
    transform:translateX(4px);
}
.log-item-icon{
    width:44px;height:44px;
    border-radius:12px;
    display:flex;align-items:center;justify-content:center;
    font-size:1.1rem;
    flex-shrink:0;
}
.log-item-icon.app{
    background:rgba(245,158,11,0.12);
    color:#f59e0b;
}
.log-item-icon.access{
    background:rgba(6,182,212,0.12);
    color:#06b6d4;
}
.log-item-body{
    flex:1;
    min-width:0;
}
.log-item-name{
    font-weight:600;
    font-size:0.9rem;
    color:var(--text);
    margin-bottom:3px;
    display:flex;
    align-items:center;
    gap:8px;
}
.log-item-name .badge-mini{
    font-size:0.65rem;
    padding:2px 8px;
    border-radius:6px;
}
.log-item-meta{
    font-size:0.75rem;
    color:var(--muted);
    display:flex;
    gap:12px;
    flex-wrap:wrap;
}
.log-item-actions{
    display:flex;
    gap:6px;
    flex-shrink:0;
}

/* ============ LOG MODAL ============ */
.log-modal-overlay{
    position:fixed;
    inset:0;
    background:rgba(0,0,0,0.85);
    backdrop-filter:blur(8px);
    z-index:10000;
    display:flex;
    align-items:center;
    justify-content:center;
    padding:20px;
    opacity:0;
    visibility:hidden;
    transition:0.25s;
}
.log-modal-overlay.active{
    opacity:1;
    visibility:visible;
}
.log-modal-box{
    background:var(--bg-soft);
    border:1px solid var(--border);
    border-radius:20px;
    width:100%;
    max-width:1000px;
    max-height:90vh;
    display:flex;
    flex-direction:column;
    transform:scale(0.95);
    transition:transform 0.25s;
    overflow:hidden;
    box-shadow:0 30px 100px rgba(0,0,0,0.7);
}
.log-modal-overlay.active .log-modal-box{
    transform:scale(1);
}

.log-modal-header{
    padding:20px 24px;
    border-bottom:1px solid var(--border);
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:16px;
}
.log-file-icon{
    width:44px;height:44px;
    border-radius:12px;
    background:rgba(139,92,246,0.15);
    color:#8b5cf6;
    display:flex;align-items:center;justify-content:center;
    font-size:1.1rem;
    flex-shrink:0;
}
.log-modal-title{
    font-weight:700;
    font-size:1rem;
    color:var(--text);
    margin-bottom:3px;
}
.log-modal-subtitle{
    font-size:0.75rem;
    color:var(--muted);
}
.log-modal-close{
    width:36px;height:36px;
    border-radius:10px;
    background:rgba(255,255,255,0.03);
    border:1px solid var(--border);
    color:var(--text-soft);
    cursor:pointer;
    display:flex;align-items:center;justify-content:center;
    transition:0.2s;
    font-size:1rem;
}
.log-modal-close:hover{
    background:rgba(239,68,68,0.15);
    color:var(--danger);
    border-color:rgba(239,68,68,0.3);
}

.log-modal-toolbar{
    padding:14px 24px;
    border-bottom:1px solid var(--border);
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:12px;
    flex-wrap:wrap;
    background:rgba(0,0,0,0.2);
}
.log-filter-btns{
    display:flex;
    gap:6px;
    flex-wrap:wrap;
}
.log-filter-btn{
    padding:7px 14px;
    border-radius:8px;
    border:1px solid var(--border);
    background:rgba(255,255,255,0.02);
    color:var(--text-soft);
    font-size:0.75rem;
    font-weight:600;
    cursor:pointer;
    transition:0.2s;
    font-family:inherit;
    display:flex;
    align-items:center;
    gap:6px;
}
.log-filter-btn:hover{
    background:rgba(255,255,255,0.06);
    color:var(--text);
}
.log-filter-btn.active{
    background:rgba(139,92,246,0.15);
    color:#8b5cf6;
    border-color:rgba(139,92,246,0.3);
}
.log-toolbar-actions{
    display:flex;
    gap:8px;
    align-items:center;
}
.log-lines-select{
    padding:7px 12px;
    background:var(--bg);
    border:1px solid var(--border);
    border-radius:8px;
    color:var(--text);
    font-family:inherit;
    font-size:0.78rem;
    cursor:pointer;
}
.log-lines-select:focus{
    outline:none;
    border-color:var(--primary);
}

.log-content{
    flex:1;
    overflow-y:auto;
    padding:0;
    background:#0a0a0f;
    font-family:'Consolas','Monaco','Courier New',monospace;
    font-size:0.78rem;
    line-height:1.6;
}
.log-line{
    padding:6px 20px;
    border-bottom:1px solid rgba(255,255,255,0.02);
    color:#a0a0b0;
    white-space:pre-wrap;
    word-break:break-all;
    transition:0.15s;
}
.log-line:hover{
    background:rgba(255,255,255,0.03);
}
.log-line.error{
    color:#ef4444;
    background:rgba(239,68,68,0.03);
    border-left:3px solid #ef4444;
}
.log-line.warn{
    color:#f59e0b;
    background:rgba(245,158,11,0.03);
    border-left:3px solid #f59e0b;
}
.log-line.info{
    color:#06b6d4;
    border-left:3px solid #06b6d4;
}
.log-line.success{
    color:#22c55e;
    border-left:3px solid #22c55e;
}
.log-line .log-time{
    color:#6b7280;
}

.log-empty{
    padding:60px 20px;
    text-align:center;
    color:var(--muted);
}
.log-empty i{
    font-size:2.5rem;
    opacity:0.3;
    margin-bottom:12px;
    display:block;
}

@media(max-width:768px){
    .log-item{
        padding:12px;
        flex-wrap:wrap;
    }
    .log-item-body{width:100%;}
    .log-item-actions{width:100%;}
    .log-modal-box{max-height:95vh;}
    .log-filter-btn span{display:none;}
}
</style>

<script>
const API = <?= json_encode($apiUrl) ?>;
const TOKEN = <?= json_encode($authToken) ?>;

let currentFile = null;
let currentLines = [];
let currentFilter = 'all';

// ============ LOAD LOGS ============
async function loadLogs() {
    const container = document.getElementById('logsList');
    container.innerHTML = `
        <div class="skeleton" style="width:100%;height:60px;margin-bottom:8px;"></div>
        <div class="skeleton" style="width:100%;height:60px;"></div>
    `;

    try {
        const r = await fetch(API + '/admin/logs', {
            headers: { 'Authorization': 'Bearer ' + TOKEN }
        });
        const d = await r.json();
        if (!d.success) throw new Error(d.error);

        // Update stats
        document.getElementById('statFiles').classList.remove('loading');
        document.getElementById('statFiles').textContent = d.totalFiles;
        document.getElementById('statSize').classList.remove('loading');
        document.getElementById('statSize').textContent = d.totalSizeFormatted;

        if (d.files.length > 0) {
            document.getElementById('statLatest').classList.remove('loading');
            const latest = new Date(d.files[0].modified);
            document.getElementById('statLatest').textContent = latest.toLocaleString('id-ID', {
                day: '2-digit', month: 'short', hour: '2-digit', minute: '2-digit'
            });
        } else {
            document.getElementById('statLatest').classList.remove('loading');
            document.getElementById('statLatest').textContent = 'Belum ada';
        }

        // Render list
        if (d.files.length === 0) {
            container.innerHTML = `
                <div class="empty-state">
                    <i class="fas fa-file-alt"></i>
                    <p>Belum ada file log</p>
                </div>
            `;
            return;
        }

        container.innerHTML = d.files.map(f => {
            const isAccess = f.type === 'access';
            const icon = isAccess ? 'fa-globe' : 'fa-file-alt';
            const typeLabel = isAccess ? 'ACCESS' : 'APP';
            const badgeCls = isAccess ? 'badge-info' : 'badge-warning';
            const iconCls = f.type;

            const modDate = new Date(f.modified);
            const modStr = modDate.toLocaleString('id-ID', {
                day: '2-digit', month: 'short', year: 'numeric',
                hour: '2-digit', minute: '2-digit'
            });

            return `
                <div class="log-item" onclick="openLog('${f.name}')">
                    <div class="log-item-icon ${iconCls}">
                        <i class="fas ${icon}"></i>
                    </div>
                    <div class="log-item-body">
                        <div class="log-item-name">
                            ${f.name}
                            <span class="badge-mini badge ${badgeCls}">${typeLabel}</span>
                        </div>
                        <div class="log-item-meta">
                            <span><i class="fas fa-hdd"></i> ${f.sizeFormatted}</span>
                            <span><i class="fas fa-clock"></i> ${modStr}</span>
                        </div>
                    </div>
                    <div class="log-item-actions" onclick="event.stopPropagation();">
                        <button class="btn btn-ghost btn-sm" onclick="openLog('${f.name}')" title="Lihat">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="btn btn-danger btn-sm" onclick="deleteLog('${f.name}')" title="Hapus">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            `;
        }).join('');

    } catch (e) {
        console.error('Load logs error:', e);
        container.innerHTML = `
            <div class="empty-state">
                <i class="fas fa-exclamation-triangle" style="color:var(--danger);"></i>
                <p>Error: ${e.message}</p>
            </div>
        `;
        toastError('Gagal load logs: ' + e.message);
    }
}

// ============ OPEN LOG MODAL ============
async function openLog(filename) {
    currentFile = filename;
    currentFilter = 'all';

    document.getElementById('modalFilename').textContent = filename;
    document.getElementById('modalFileInfo').textContent = 'Memuat...';
    document.getElementById('logModal').classList.add('active');
    document.body.style.overflow = 'hidden';

    // Reset filter buttons
    document.querySelectorAll('.log-filter-btn').forEach(b => b.classList.remove('active'));
    document.querySelector('.log-filter-btn').classList.add('active');

    await loadLogContent();
}

async function loadLogContent() {
    const content = document.getElementById('logContent');
    const lines = document.getElementById('logLinesSelect').value;

    content.innerHTML = `
        <div style="padding:40px;text-align:center;color:var(--muted);">
            <i class="fas fa-spinner fa-spin" style="font-size:2rem;margin-bottom:12px;display:block;"></i>
            Memuat log...
        </div>
    `;

    try {
        const r = await fetch(API + '/admin/logs/' + encodeURIComponent(currentFile) + '?lines=' + lines, {
            headers: { 'Authorization': 'Bearer ' + TOKEN }
        });
        const d = await r.json();
        if (!d.success) throw new Error(d.error);

        currentLines = d.lines;

        document.getElementById('modalFileInfo').textContent =
            d.sizeFormatted + ' • ' + d.lines.length + ' dari ' + d.totalLines + ' baris';

        renderLogLines(currentLines, currentFilter);

    } catch (e) {
        content.innerHTML = `
            <div class="log-empty">
                <i class="fas fa-exclamation-triangle" style="color:var(--danger);"></i>
                <p>Error: ${e.message}</p>
            </div>
        `;
    }
}

// ============ RENDER LOG LINES ============
function renderLogLines(lines, filter) {
    const content = document.getElementById('logContent');

    // Filter lines
    let filtered = lines;
    if (filter !== 'all') {
        filtered = lines.filter(line => {
            const upper = line.toUpperCase();
            if (filter === 'error') return upper.includes('[ERROR]');
            if (filter === 'warn') return upper.includes('[WARN]');
            if (filter === 'info') return upper.includes('[INFO]') || upper.includes('[SUCCESS]');
            return true;
        });
    }

    if (filtered.length === 0) {
        content.innerHTML = `
            <div class="log-empty">
                <i class="fas fa-filter"></i>
                <p>Tidak ada log dengan filter "${filter}"</p>
            </div>
        `;
        return;
    }

    content.innerHTML = filtered.map(line => {
        const upper = line.toUpperCase();
        let cls = '';
        if (upper.includes('[ERROR]')) cls = 'error';
        else if (upper.includes('[WARN]')) cls = 'warn';
        else if (upper.includes('[INFO]')) cls = 'info';
        else if (upper.includes('[SUCCESS]')) cls = 'success';

        return `<div class="log-line ${cls}">${escapeHtml(line)}</div>`;
    }).join('');

    // Auto scroll to bottom
    content.scrollTop = content.scrollHeight;
}

// ============ FILTER ============
function filterLog(filter, btn) {
    currentFilter = filter;
    document.querySelectorAll('.log-filter-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    renderLogLines(currentLines, filter);
}

function changeLines() {
    loadLogContent();
}

// ============ DOWNLOAD LOG ============
async function downloadLog() {
    if (!currentFile) return;

    try {
        const r = await fetch(API + '/admin/logs/' + encodeURIComponent(currentFile) + '?lines=1000', {
            headers: { 'Authorization': 'Bearer ' + TOKEN }
        });
        const d = await r.json();
        if (!d.success) throw new Error(d.error);

        const text = d.lines.join('\n');
        const blob = new Blob([text], { type: 'text/plain' });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = currentFile;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        window.URL.revokeObjectURL(url);

        toastSuccess('✅ Log berhasil didownload');
    } catch (e) {
        toastError('Gagal download: ' + e.message);
    }
}

// ============ DELETE LOG ============
async function deleteLog(filename) {
    const ok = await showConfirm({
        title: 'Hapus Log?',
        message: 'File log "' + filename + '" akan dihapus permanen. Tindakan ini tidak bisa dibatalkan.',
        type: 'danger',
        okText: 'Ya, Hapus',
        cancelText: 'Batal'
    });
    if (!ok) return;

    try {
        const r = await fetch(API + '/admin/logs/' + encodeURIComponent(filename), {
            method: 'DELETE',
            headers: { 'Authorization': 'Bearer ' + TOKEN }
        });
        const d = await r.json();
        if (!d.success) throw new Error(d.error);

        toastSuccess(d.message);
        loadLogs();
    } catch (e) {
        toastError('Gagal hapus: ' + e.message);
    }
}

// ============ MODAL CLOSE ============
function closeLogModal() {
    document.getElementById('logModal').classList.remove('active');
    document.body.style.overflow = '';
    currentFile = null;
    currentLines = [];
}

// ESC to close
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        const modal = document.getElementById('logModal');
        if (modal.classList.contains('active')) closeLogModal();
    }
});

// Click overlay to close
document.getElementById('logModal').addEventListener('click', (e) => {
    if (e.target.id === 'logModal') closeLogModal();
});

// ============ HELPER ============
function escapeHtml(str) {
    if (!str) return '';
    const div = document.createElement('div');
    div.textContent = str;
    return div.innerHTML;
}

// Init
loadLogs();
</script>

</div></body></html>