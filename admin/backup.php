<?php
$pageTitle = 'Backup Database';
$pageSubtitle = 'Backup & restore data aplikasi';
require __DIR__ . '/includes/admin_header.php';
?>

<!-- ============ INFO BANNER ============ -->
<div class="card" style="background:linear-gradient(135deg, rgba(6,182,212,0.08), rgba(6,182,212,0.02)); border-color:rgba(6,182,212,0.25);">
    <div style="display:flex; align-items:start; gap:16px;">
        <div style="width:52px;height:52px;border-radius:14px;background:rgba(6,182,212,0.15);color:#06b6d4;display:flex;align-items:center;justify-content:center;font-size:1.4rem;flex-shrink:0;">
            <i class="fas fa-database"></i>
        </div>
        <div style="flex:1;">
            <h3 style="font-size:1.05rem; font-weight:700; margin-bottom:6px; color:var(--text);">
                Backup Database
            </h3>
            <p style="font-size:0.85rem; color:var(--text-soft); line-height:1.6; margin:0;">
                Backup semua data aplikasi (users, videos, subscriptions, dll) ke file <strong style="color:var(--text);">JSON</strong>.
                File ini bisa digunakan untuk <strong style="color:var(--text);">restore data</strong> jika terjadi hal yang tidak diinginkan.
            </p>
        </div>
    </div>
</div>

<!-- ============ STATS GRID ============ -->
<div class="stats-grid" id="statsGrid">
    <div class="stat-card">
        <div class="stat-icon"><i class="fas fa-users"></i></div>
        <div class="stat-label">Users</div>
        <div class="stat-value loading" id="statUsers">—</div>
    </div>
    <div class="stat-card blue">
        <div class="stat-icon"><i class="fas fa-film"></i></div>
        <div class="stat-label">Videos</div>
        <div class="stat-value loading" id="statVideos">—</div>
    </div>
    <div class="stat-card green">
        <div class="stat-icon"><i class="fas fa-credit-card"></i></div>
        <div class="stat-label">Subscriptions</div>
        <div class="stat-value loading" id="statSubs">—</div>
    </div>
    <div class="stat-card purple">
        <div class="stat-icon"><i class="fas fa-history"></i></div>
        <div class="stat-label">Activity Log</div>
        <div class="stat-value loading" id="statActivity">—</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon"><i class="fas fa-bell"></i></div>
        <div class="stat-label">Notifications</div>
        <div class="stat-value loading" id="statNotif">—</div>
    </div>
    <div class="stat-card red">
        <div class="stat-icon"><i class="fas fa-key"></i></div>
        <div class="stat-label">API Keys</div>
        <div class="stat-value loading" id="statApiKeys">—</div>
    </div>
</div>

<!-- ============ BACKUP INFO CARD ============ -->
<div class="card">
    <div class="card-header">
        <div>
            <h3><i class="fas fa-info-circle"></i> Ringkasan Database</h3>
            <p>Informasi umum tentang database Anda</p>
        </div>
    </div>

    <div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(200px,1fr)); gap:16px; margin-top:8px;">
        <div class="info-tile">
            <div class="info-tile-label">Ukuran Database</div>
            <div class="info-tile-value" id="dbSize">—</div>
        </div>
        <div class="info-tile">
            <div class="info-tile-label">Total Records</div>
            <div class="info-tile-value" id="totalRecords">—</div>
        </div>
        <div class="info-tile">
            <div class="info-tile-label">Backup Terakhir</div>
            <div class="info-tile-value" id="lastBackup" style="font-size:0.9rem;">—</div>
        </div>
        <div class="info-tile">
            <div class="info-tile-label">Database Provider</div>
            <div class="info-tile-value" style="color:#22c55e;">
                <i class="fas fa-check-circle"></i> PostgreSQL
            </div>
        </div>
    </div>
</div>

<!-- ============ ACTION CARD ============ -->
<div class="card">
    <div class="card-header">
        <div>
            <h3><i class="fas fa-download"></i> Download Backup</h3>
            <p>Klik tombol di bawah untuk membuat file backup</p>
        </div>
    </div>

    <div style="background:rgba(245,158,11,0.06); border:1px solid rgba(245,158,11,0.15); border-radius:14px; padding:20px; margin-top:8px;">
        <div style="display:flex; align-items:center; justify-content:space-between; gap:16px; flex-wrap:wrap;">
            <div style="display:flex; align-items:center; gap:14px;">
                <div style="width:48px;height:48px;border-radius:12px;background:var(--gradient);display:flex;align-items:center;justify-content:center;font-size:1.2rem;color:#000;flex-shrink:0;">
                    <i class="fas fa-file-code"></i>
                </div>
                <div>
                    <div style="font-weight:700; font-size:0.95rem; margin-bottom:3px; color:var(--text);">
                        Backup Lengkap (JSON)
                    </div>
                    <div style="font-size:0.8rem; color:var(--text-muted);">
                        Semua tabel: users, videos, subscriptions, activity, dll
                    </div>
                </div>
            </div>

            <button class="btn btn-primary" onclick="downloadBackup()" id="backupBtn" style="min-width:200px;">
                <i class="fas fa-download"></i> Download Backup Sekarang
            </button>
        </div>
    </div>

    <!-- Warning -->
    <div style="background:rgba(239,68,68,0.06); border:1px solid rgba(239,68,68,0.15); border-radius:14px; padding:16px; margin-top:14px; display:flex; gap:12px;">
        <i class="fas fa-exclamation-triangle" style="color:var(--danger); font-size:1.2rem; flex-shrink:0; margin-top:2px;"></i>
        <div style="font-size:0.82rem; color:var(--text-soft); line-height:1.6;">
            <strong style="color:var(--danger);">Perhatian:</strong>
            File backup berisi <strong>semua data user</strong> termasuk hash password.
            Simpan file ini di tempat yang <strong>aman</strong> dan <strong>jangan dibagikan</strong> ke orang lain.
        </div>
    </div>
</div>

<!-- ============ INFO TIPS ============ -->
<div class="card">
    <div class="card-header">
        <div>
            <h3><i class="fas fa-lightbulb"></i> Tips Backup</h3>
            <p>Rekomendasi untuk menjaga keamanan data</p>
        </div>
    </div>

    <div style="display:grid; gap:12px; margin-top:8px;">
        <div class="tip-item">
            <div class="tip-icon" style="background:rgba(34,197,94,0.12); color:#22c55e;">
                <i class="fas fa-calendar-check"></i>
            </div>
            <div>
                <div class="tip-title">Backup rutin</div>
                <div class="tip-desc">Lakukan backup minimal <strong>1x seminggu</strong>, atau setelah ada perubahan besar</div>
            </div>
        </div>

        <div class="tip-item">
            <div class="tip-icon" style="background:rgba(245,158,11,0.12); color:#f59e0b;">
                <i class="fas fa-cloud-upload-alt"></i>
            </div>
            <div>
                <div class="tip-title">Simpan di cloud</div>
                <div class="tip-desc">Upload file backup ke <strong>Google Drive</strong>, <strong>Dropbox</strong>, atau cloud storage lain</div>
            </div>
        </div>

        <div class="tip-item">
            <div class="tip-icon" style="background:rgba(139,92,246,0.12); color:#8b5cf6;">
                <i class="fas fa-shield-alt"></i>
            </div>
            <div>
                <div class="tip-title">Simpan 2 tempat</div>
                <div class="tip-desc">Simpan salinan di <strong>2 lokasi berbeda</strong> untuk berjaga-jaga</div>
            </div>
        </div>

        <div class="tip-item">
            <div class="tip-icon" style="background:rgba(6,182,212,0.12); color:#06b6d4;">
                <i class="fas fa-clock"></i>
            </div>
            <div>
                <div class="tip-title">Retensi 3-6 bulan</div>
                <div class="tip-desc">Simpan backup minimal 3 bulan terakhir, hapus yang sudah terlalu lama</div>
            </div>
        </div>
    </div>
</div>

<style>
.info-tile{
    background:rgba(0,0,0,0.25);
    border:1px solid var(--border);
    border-radius:12px;
    padding:16px;
    transition:0.2s;
}
.info-tile:hover{
    border-color:rgba(245,158,11,0.3);
    background:rgba(245,158,11,0.04);
}
.info-tile-label{
    font-size:0.72rem;
    color:var(--muted);
    text-transform:uppercase;
    letter-spacing:1px;
    font-weight:700;
    margin-bottom:8px;
}
.info-tile-value{
    font-size:1.3rem;
    font-weight:800;
    color:var(--text);
    letter-spacing:-0.3px;
}
.info-tile-value.loading{
    color:var(--muted);
    opacity:0.3;
}

.tip-item{
    display:flex;
    gap:14px;
    align-items:flex-start;
    padding:12px 0;
    border-bottom:1px solid rgba(255,255,255,0.04);
}
.tip-item:last-child{border-bottom:none;}
.tip-icon{
    width:40px;height:40px;
    border-radius:11px;
    display:flex;align-items:center;justify-content:center;
    font-size:1rem;
    flex-shrink:0;
}
.tip-title{
    font-weight:600;
    font-size:0.88rem;
    margin-bottom:3px;
    color:var(--text);
}
.tip-desc{
    font-size:0.8rem;
    color:var(--text-muted);
    line-height:1.5;
}

/* Spinner animation untuk tombol download */
@keyframes pulseBtn {
    0%,100%{transform:scale(1);}
    50%{transform:scale(1.03);}
}
#backupBtn:disabled{
    animation:pulseBtn 1.5s infinite;
}
</style>

<script>
const API = <?= json_encode($apiUrl) ?>;
const TOKEN = <?= json_encode($authToken) ?>;

async function loadBackupStats() {
    try {
        const r = await fetch(API + '/admin/backup/stats', {
            headers: { 'Authorization': 'Bearer ' + TOKEN }
        });
        const d = await r.json();
        if (!d.success) throw new Error(d.error);

        // Update stat cards
        const setStat = (id, val) => {
            const el = document.getElementById(id);
            el.classList.remove('loading');
            el.textContent = Number(val).toLocaleString('id-ID');
        };
        setStat('statUsers', d.stats.users);
        setStat('statVideos', d.stats.videos);
        setStat('statSubs', d.stats.subscriptions);
        setStat('statActivity', d.stats.activity_log);
        setStat('statNotif', d.stats.notifications);
        setStat('statApiKeys', d.stats.api_keys);

        // Update info tiles
        document.getElementById('dbSize').textContent = d.dbSize;
        document.getElementById('totalRecords').textContent = Number(d.totalRecords).toLocaleString('id-ID') + ' rows';

        if (d.lastBackup) {
            const date = new Date(d.lastBackup.created_at);
            const formatted = date.toLocaleString('id-ID', {
                day: '2-digit',
                month: 'short',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
            document.getElementById('lastBackup').textContent = formatted;
        } else {
            document.getElementById('lastBackup').innerHTML = '<em style="color:var(--muted);">Belum pernah backup</em>';
        }
    } catch (e) {
        console.error('Backup stats error:', e);
        toastError('Gagal load statistik backup: ' + e.message);
    }
}

async function downloadBackup() {
    const btn = document.getElementById('backupBtn');
    const originalText = btn.innerHTML;

    const ok = await showConfirm({
        title: 'Download Backup?',
        message: 'Semua data akan di-export ke file JSON. Proses ini mungkin butuh beberapa detik.',
        type: 'info',
        okText: 'Ya, Download',
        cancelText: 'Batal'
    });
    if (!ok) return;

    try {
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Membuat backup...';

        showLoading('Membuat backup...');

        const r = await fetch(API + '/admin/backup/download', {
            headers: { 'Authorization': 'Bearer ' + TOKEN }
        });

        if (!r.ok) throw new Error('Gagal backup');

        const blob = await r.blob();

        // Cek kalau error response
        if (blob.type === 'application/json' && blob.size < 500) {
            const text = await blob.text();
            const json = JSON.parse(text);
            if (!json.success) throw new Error(json.error);
        }

        // Cek ukuran
        const sizeMB = (blob.size / 1024 / 1024).toFixed(2);
        const sizeKB = (blob.size / 1024).toFixed(2);
        const sizeText = blob.size > 1024 * 1024 ? sizeMB + ' MB' : sizeKB + ' KB';

        // Download
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;

        const now = new Date();
        const timestamp = now.toISOString().slice(0, 19).replace(/[:T]/g, '-');
        a.download = 'clipora_backup_' + timestamp + '.json';
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        window.URL.revokeObjectURL(url);

        toastSuccess('✅ Backup berhasil didownload (' + sizeText + ')');

        // Refresh stats (biar last backup terupdate)
        setTimeout(loadBackupStats, 1000);

    } catch (e) {
        console.error('Download error:', e);
        toastError('Gagal backup: ' + e.message);
    } finally {
        btn.disabled = false;
        btn.innerHTML = originalText;
        hideLoading();
    }
}

// Init
loadBackupStats();
</script>

</div></body></html>