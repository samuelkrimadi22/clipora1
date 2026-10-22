<?php
$pageTitle = 'Semua Videos';
$pageSubtitle = 'Daftar video yang diproses user';
require __DIR__ . '/includes/admin_header.php';
?>

<div class="card">
    <div class="toolbar" style="margin-bottom:18px;">
        <div style="font-size:0.85rem;color:var(--muted);">
            <i class="fas fa-info-circle"></i> Menampilkan 100 video terbaru
        </div>
        <button class="btn btn-success btn-sm" onclick="exportVideos()" style="margin-left:auto;" title="Download CSV">
            <i class="fas fa-file-csv"></i> Export CSV
        </button>
    </div>

    <div class="table-wrap">
        <table id="videosTable">
            <thead>
                <tr><th>ID</th><th>User</th><th>File</th><th>Durasi Asli</th>
                    <th>Durasi Baru</th><th>Hemat</th><th>Tanggal</th></tr>
            </thead>
            <tbody>
                <tr><td colspan="7"><div class="skeleton" style="width:100%"></div></td></tr>
            </tbody>
        </table>
    </div>
</div>

<script>
const API = <?= json_encode($apiUrl) ?>;
const TOKEN = <?= json_encode($authToken) ?>;

function fmt(sec) {
    if (!sec) return '—';
    const m = Math.floor(sec / 60), s = Math.round(sec % 60);
    return m + 'm ' + s + 's';
}

async function loadVideos() {
    const tbody = document.querySelector('#videosTable tbody');
    try {
        const r = await fetch(API + '/admin/videos', { headers: { 'Authorization': 'Bearer ' + TOKEN } });
        const d = await r.json();
        if (!d.success) throw new Error(d.error);

        if (d.videos.length === 0) {
            tbody.innerHTML = '<tr><td colspan="7"><div class="empty-state"><i class="fas fa-film"></i><p>Belum ada video diproses</p></div></td></tr>';
            return;
        }

        tbody.innerHTML = d.videos.map(v => `
            <tr>
                <td><strong>#${v.id}</strong></td>
                <td><small>${v.username || v.user_email || 'Guest'}</small></td>
                <td style="font-size:0.78rem;color:var(--muted);max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap" title="${v.filename}">${v.filename}</td>
                <td>${fmt(v.original_duration)}</td>
                <td>${fmt(v.new_duration)}</td>
                <td><span class="badge badge-success">${Math.round(v.saved_percent || 0)}%</span></td>
                <td style="font-size:0.8rem">${new Date(v.created_at).toLocaleDateString('id-ID', {day:'2-digit',month:'short',year:'numeric'})}</td>
            </tr>
        `).join('');
    } catch (e) {
        tbody.innerHTML = '<tr><td colspan="7"><div class="empty-state"><i class="fas fa-exclamation-triangle" style="color:var(--danger)"></i><p>Error: ' + e.message + '</p></div></td></tr>';
    }
}

// ============ EXPORT CSV ============
async function exportVideos() {
    try {
        toastInfo('Menyiapkan file CSV...');

        const r = await fetch(API + '/admin/export/videos', {
            headers: { 'Authorization': 'Bearer ' + TOKEN }
        });

        if (!r.ok) throw new Error('Gagal export');

        const blob = await r.blob();
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'clipora_videos_' + new Date().toISOString().slice(0, 10) + '.csv';
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        window.URL.revokeObjectURL(url);

        toastSuccess('✅ File CSV berhasil didownload!');
    } catch (e) {
        toastError('Gagal export: ' + e.message);
    }
}

loadVideos();
</script>

</div></body></html>