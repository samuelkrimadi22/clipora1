<?php
$pageTitle = 'Detail User';
$pageSubtitle = 'Profil lengkap & riwayat aktivitas user';
require __DIR__ . '/includes/admin_header.php';
?>

<a href="users.php" style="display:inline-flex;align-items:center;gap:8px;padding:8px 16px;background:rgba(255,255,255,0.03);border:1px solid var(--border);border-radius:10px;font-size:0.82rem;color:var(--text-soft);margin-bottom:20px;transition:0.2s;" onmouseover="this.style.color='var(--primary)';this.style.borderColor='rgba(245,158,11,0.3)'" onmouseout="this.style.color='var(--text-soft)';this.style.borderColor='var(--border)'">
    <i class="fas fa-arrow-left"></i> Kembali ke Daftar User
</a>

<div class="card" id="profileCard" style="display:none">
    <div style="display:grid;grid-template-columns:auto 1fr auto;gap:24px;align-items:start;">
        <div style="width:80px;height:80px;border-radius:20px;background:var(--gradient);display:flex;align-items:center;justify-content:center;font-size:2rem;font-weight:800;color:#000;" id="userAvatar">U</div>
        <div>
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:6px;flex-wrap:wrap;">
                <h2 style="font-size:1.5rem;font-weight:800;letter-spacing:-0.5px;" id="userName">-</h2>
                <span class="badge badge-info" id="userPlanBadge">-</span>
                <span class="badge" id="userStatusBadge">-</span>
                <span class="badge badge-purple" id="userAdminBadge" style="display:none"><i class="fas fa-crown"></i> Admin</span>
            </div>
            <div style="color:var(--muted);font-size:0.85rem;margin-bottom:4px;"><i class="fas fa-envelope"></i> <span id="userEmail">-</span></div>
            <div style="color:var(--muted);font-size:0.85rem;"><i class="fas fa-calendar"></i> Bergabung <span id="userCreated">-</span></div>
        </div>
        <div style="display:flex;flex-direction:column;gap:8px;">
            <button class="btn btn-primary btn-sm" onclick="openEditModal()"><i class="fas fa-pen"></i> Edit</button>
            <button class="btn btn-ghost btn-sm" onclick="resetQuota()"><i class="fas fa-redo"></i> Reset Kuota</button>
            <button class="btn btn-danger btn-sm" onclick="deleteUser()"><i class="fas fa-trash"></i> Hapus</button>
        </div>
    </div>

    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(140px,1fr));gap:12px;margin-top:24px;padding-top:24px;border-top:1px solid var(--border);">
        <div>
            <div style="font-size:0.7rem;color:var(--muted);text-transform:uppercase;letter-spacing:1px;font-weight:700;margin-bottom:4px;">Total Video</div>
            <div style="font-size:1.4rem;font-weight:800;color:var(--primary);" id="statVideos">-</div>
        </div>
        <div>
            <div style="font-size:0.7rem;color:var(--muted);text-transform:uppercase;letter-spacing:1px;font-weight:700;margin-bottom:4px;">Kuota Harian</div>
            <div style="font-size:1.4rem;font-weight:800;color:var(--info);" id="statQuota">-</div>
        </div>
        <div>
            <div style="font-size:0.7rem;color:var(--muted);text-transform:uppercase;letter-spacing:1px;font-weight:700;margin-bottom:4px;">Hemat Waktu</div>
            <div style="font-size:1.4rem;font-weight:800;color:var(--success);" id="statSaved">-</div>
        </div>
        <div>
            <div style="font-size:0.7rem;color:var(--muted);text-transform:uppercase;letter-spacing:1px;font-weight:700;margin-bottom:4px;">Rata-rata Hemat</div>
            <div style="font-size:1.4rem;font-weight:800;color:var(--purple);" id="statAvg">-</div>
        </div>
        <div>
            <div style="font-size:0.7rem;color:var(--muted);text-transform:uppercase;letter-spacing:1px;font-weight:700;margin-bottom:4px;">Last Login</div>
            <div style="font-size:0.9rem;font-weight:600;color:var(--text);" id="statLogin">-</div>
        </div>
    </div>
</div>

<div class="card" id="subCard" style="display:none">
    <div class="card-header">
        <div>
            <h3><i class="fas fa-credit-card"></i> Riwayat Langganan</h3>
            <p>Semua transaksi langganan user ini</p>
        </div>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr><th>ID</th><th>Paket</th><th>Harga</th><th>Status</th><th>Mulai</th><th>Berakhir</th></tr>
            </thead>
            <tbody id="subsTable">
                <tr><td colspan="6"><div class="skeleton" style="width:100%"></div></td></tr>
            </tbody>
        </table>
    </div>
</div>

<div class="card" id="videoCard" style="display:none">
    <div class="card-header">
        <div>
            <h3><i class="fas fa-film"></i> Video Terakhir</h3>
            <p>10 video terakhir yang diproses user</p>
        </div>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr><th>File</th><th>Durasi Asli</th><th>Durasi Baru</th><th>Hemat</th><th>Tanggal</th></tr>
            </thead>
            <tbody id="videosTable">
                <tr><td colspan="5"><div class="skeleton" style="width:100%"></div></td></tr>
            </tbody>
        </table>
    </div>
</div>

<div class="card" id="activityCard" style="display:none">
    <div class="card-header">
        <div>
            <h3><i class="fas fa-history"></i> Aktivitas Terakhir</h3>
            <p>20 log aktivitas user ini</p>
        </div>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr><th>Action</th><th>Detail</th><th>IP</th><th>Waktu</th></tr>
            </thead>
            <tbody id="activityTable">
                <tr><td colspan="4"><div class="skeleton" style="width:100%"></div></td></tr>
            </tbody>
        </table>
    </div>
</div>

<!-- ============ EDIT USER MODAL ============ -->
<div class="edit-modal-overlay" id="editModal">
    <div class="edit-modal-box">
        <div class="edit-modal-header">
            <div style="display:flex;align-items:center;gap:12px;">
                <div class="edit-modal-icon">
                    <i class="fas fa-user-edit"></i>
                </div>
                <div>
                    <div class="edit-modal-title">Edit User</div>
                    <div class="edit-modal-subtitle" id="editUserInfo">—</div>
                </div>
            </div>
            <button class="edit-modal-close" onclick="closeEditModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="edit-modal-body">
            <!-- Plan -->
            <div class="edit-field">
                <label>
                    <i class="fas fa-tag" style="color:#f59e0b;"></i>
                    Plan
                </label>
                <div class="plan-options">
                    <label class="plan-option">
                        <input type="radio" name="editPlan" value="free">
                        <div class="plan-card">
                            <div class="plan-icon">🎁</div>
                            <div class="plan-name">Free</div>
                            <div class="plan-desc">5 video/hari</div>
                        </div>
                    </label>
                    <label class="plan-option">
                        <input type="radio" name="editPlan" value="pro">
                        <div class="plan-card">
                            <div class="plan-icon">🚀</div>
                            <div class="plan-name">Pro</div>
                            <div class="plan-desc">100 video/hari</div>
                        </div>
                    </label>
                    <label class="plan-option">
                        <input type="radio" name="editPlan" value="business">
                        <div class="plan-card">
                            <div class="plan-icon">👑</div>
                            <div class="plan-name">Business</div>
                            <div class="plan-desc">1000 video/hari</div>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Quota -->
            <div class="edit-field">
                <label for="editQuota">
                    <i class="fas fa-tachometer-alt" style="color:#06b6d4;"></i>
                    Kuota Harian
                </label>
                <input type="number" id="editQuota" class="edit-input" min="0" max="10000" placeholder="5">
                <div class="edit-hint">Berapa video yang bisa diproses user per hari</div>
            </div>

            <!-- Status -->
            <div class="edit-field">
                <label>
                    <i class="fas fa-circle" style="color:#22c55e;"></i>
                    Status Akun
                </label>
                <div class="status-toggle">
                    <label class="status-option">
                        <input type="radio" name="editStatus" value="active">
                        <div class="status-card active">
                            <i class="fas fa-check-circle"></i>
                            <span>Aktif</span>
                        </div>
                    </label>
                    <label class="status-option">
                        <input type="radio" name="editStatus" value="inactive">
                        <div class="status-card inactive">
                            <i class="fas fa-ban"></i>
                            <span>Nonaktif</span>
                        </div>
                    </label>
                </div>
            </div>
        </div>

        <div class="edit-modal-footer">
            <button class="btn btn-ghost" onclick="closeEditModal()">
                <i class="fas fa-times"></i> Batal
            </button>
            <button class="btn btn-primary" onclick="saveEditUser()" id="saveEditBtn">
                <i class="fas fa-save"></i> Simpan Perubahan
            </button>
        </div>
    </div>
</div>

<style>
.edit-modal-overlay{
    position:fixed;
    inset:0;
    background:rgba(0,0,0,0.8);
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
.edit-modal-overlay.active{
    opacity:1;
    visibility:visible;
}
.edit-modal-box{
    background:var(--bg-soft);
    border:1px solid var(--border);
    border-radius:20px;
    width:100%;
    max-width:520px;
    max-height:90vh;
    display:flex;
    flex-direction:column;
    transform:scale(0.95);
    transition:transform 0.25s;
    overflow:hidden;
    box-shadow:0 30px 100px rgba(0,0,0,0.7);
}
.edit-modal-overlay.active .edit-modal-box{
    transform:scale(1);
}
.edit-modal-header{
    padding:22px 24px;
    border-bottom:1px solid var(--border);
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:16px;
}
.edit-modal-icon{
    width:48px;height:48px;
    border-radius:14px;
    background:linear-gradient(135deg,rgba(245,158,11,0.15),rgba(245,158,11,0.05));
    color:var(--primary);
    display:flex;align-items:center;justify-content:center;
    font-size:1.2rem;
    flex-shrink:0;
}
.edit-modal-title{
    font-weight:700;
    font-size:1.05rem;
    color:var(--text);
    margin-bottom:3px;
}
.edit-modal-subtitle{
    font-size:0.78rem;
    color:var(--muted);
}
.edit-modal-close{
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
.edit-modal-close:hover{
    background:rgba(239,68,68,0.15);
    color:var(--danger);
    border-color:rgba(239,68,68,0.3);
}
.edit-modal-body{
    padding:24px;
    overflow-y:auto;
    flex:1;
}
.edit-field{
    margin-bottom:24px;
}
.edit-field:last-child{
    margin-bottom:0;
}
.edit-field > label{
    display:flex;
    align-items:center;
    gap:8px;
    font-size:0.85rem;
    font-weight:600;
    color:var(--text);
    margin-bottom:12px;
}
.edit-input{
    width:100%;
    padding:12px 16px;
    background:rgba(0,0,0,0.3);
    border:1px solid var(--border);
    border-radius:10px;
    color:var(--text);
    font-family:inherit;
    font-size:0.9rem;
    transition:0.2s;
}
.edit-input:focus{
    outline:none;
    border-color:var(--primary);
    background:rgba(0,0,0,0.5);
    box-shadow:0 0 0 4px rgba(245,158,11,0.08);
}
.edit-hint{
    font-size:0.72rem;
    color:var(--muted);
    margin-top:6px;
}
.plan-options{
    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:10px;
}
.plan-option{
    cursor:pointer;
}
.plan-option input[type="radio"]{
    display:none;
}
.plan-card{
    padding:14px 10px;
    background:rgba(255,255,255,0.02);
    border:2px solid var(--border);
    border-radius:12px;
    text-align:center;
    transition:0.2s;
}
.plan-option:hover .plan-card{
    background:rgba(245,158,11,0.05);
    border-color:rgba(245,158,11,0.3);
}
.plan-option input:checked + .plan-card{
    background:rgba(245,158,11,0.1);
    border-color:var(--primary);
    box-shadow:0 0 0 4px rgba(245,158,11,0.08);
}
.plan-icon{
    font-size:1.5rem;
    margin-bottom:6px;
}
.plan-name{
    font-weight:700;
    font-size:0.85rem;
    color:var(--text);
    margin-bottom:2px;
}
.plan-desc{
    font-size:0.65rem;
    color:var(--muted);
}
.status-toggle{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:10px;
}
.status-option{
    cursor:pointer;
}
.status-option input[type="radio"]{
    display:none;
}
.status-card{
    display:flex;
    align-items:center;
    justify-content:center;
    gap:8px;
    padding:14px;
    background:rgba(255,255,255,0.02);
    border:2px solid var(--border);
    border-radius:12px;
    font-weight:600;
    font-size:0.85rem;
    transition:0.2s;
}
.status-card i{
    font-size:1rem;
}
.status-card.active{
    color:#22c55e;
}
.status-card.inactive{
    color:#ef4444;
}
.status-option:hover .status-card{
    background:rgba(255,255,255,0.05);
}
.status-option input:checked + .status-card.active{
    background:rgba(34,197,94,0.1);
    border-color:#22c55e;
    box-shadow:0 0 0 4px rgba(34,197,94,0.08);
}
.status-option input:checked + .status-card.inactive{
    background:rgba(239,68,68,0.1);
    border-color:#ef4444;
    box-shadow:0 0 0 4px rgba(239,68,68,0.08);
}
.edit-modal-footer{
    padding:16px 24px;
    border-top:1px solid var(--border);
    display:flex;
    justify-content:flex-end;
    gap:10px;
    background:rgba(0,0,0,0.2);
}
@media(max-width:600px){
    .edit-modal-box{max-height:95vh;}
    .plan-options{grid-template-columns:1fr;}
    .plan-card{display:flex;align-items:center;gap:10px;text-align:left;padding:12px 16px;}
    .plan-icon{margin-bottom:0;font-size:1.2rem;}
}
</style>

<script>
const API = <?= json_encode($apiUrl) ?>;
const TOKEN = <?= json_encode($authToken) ?>;

const urlParams = new URLSearchParams(window.location.search);
const USER_ID = urlParams.get('id');

let currentUser = null;

if (!USER_ID) {
    document.body.innerHTML = '<div style="padding:40px;text-align:center;color:#ef4444;font-family:Inter;">Error: ID user tidak ditemukan di URL</div>';
    throw new Error('No user id');
}

async function loadUser() {
    try {
        const r = await fetch(API + '/admin/users/' + USER_ID, {
            headers: { 'Authorization': 'Bearer ' + TOKEN }
        });
        const d = await r.json();

        if (!d.success) {
            toastError(d.error);
            return;
        }

        currentUser = d.user;
        renderProfile(d);
        renderStats(d);
        renderSubscriptions(d.subscriptions);
        renderVideos(d.videos);
        renderActivities(d.activities);

        ['profileCard', 'subCard', 'videoCard', 'activityCard'].forEach(id => {
            document.getElementById(id).style.display = 'block';
        });

    } catch (e) {
        toastError('Gagal load user: ' + e.message);
    }
}

function renderProfile(d) {
    const u = d.user;
    document.getElementById('userAvatar').textContent = (u.username || 'U').charAt(0).toUpperCase();
    document.getElementById('userName').textContent = u.full_name || u.username;
    document.getElementById('userEmail').textContent = u.email;
    document.getElementById('userCreated').textContent = formatDate(u.created_at);

    const planBadge = document.getElementById('userPlanBadge');
    planBadge.textContent = u.plan.toUpperCase();
    planBadge.className = 'badge ' + (u.plan === 'free' ? 'badge-muted' : 'badge-warning');

    const statusBadge = document.getElementById('userStatusBadge');
    if (u.is_active) {
        statusBadge.textContent = 'Aktif';
        statusBadge.className = 'badge badge-success';
    } else {
        statusBadge.textContent = 'Nonaktif';
        statusBadge.className = 'badge badge-danger';
    }

    document.getElementById('userAdminBadge').style.display = u.is_admin ? 'inline-flex' : 'none';
}

function renderStats(d) {
    const s = d.stats;
    document.getElementById('statVideos').textContent = s.total_videos;
    document.getElementById('statQuota').textContent = d.user.daily_quota;
    document.getElementById('statSaved').textContent = formatDuration(s.total_saved_seconds);
    document.getElementById('statAvg').textContent = s.avg_saved_percent + '%';
    document.getElementById('statLogin').textContent = d.user.last_login ? formatDate(d.user.last_login, true) : 'Belum pernah';
}

function renderSubscriptions(subs) {
    const tbody = document.getElementById('subsTable');
    if (!subs || subs.length === 0) {
        tbody.innerHTML = '<tr><td colspan="6"><div class="empty-state"><i class="fas fa-inbox"></i><p>Belum ada langganan</p></div></td></tr>';
        return;
    }
    tbody.innerHTML = subs.map(s => `
        <tr>
            <td>#${s.id}</td>
            <td><span class="badge badge-info">${s.plan}</span></td>
            <td>Rp${Number(s.price).toLocaleString('id-ID')}</td>
            <td>${subBadge(s.status)}</td>
            <td>${s.started_at ? formatDate(s.started_at) : '—'}</td>
            <td>${s.expires_at ? formatDate(s.expires_at) : '—'}</td>
        </tr>
    `).join('');
}

function subBadge(status) {
    const map = {
        'pending': '<span class="badge badge-warning">⏳ Pending</span>',
        'active': '<span class="badge badge-success">✅ Aktif</span>',
        'rejected': '<span class="badge badge-danger">❌ Ditolak</span>',
        'expired': '<span class="badge badge-muted">Kadaluarsa</span>',
        'cancelled': '<span class="badge badge-muted">Dibatalkan</span>'
    };
    return map[status] || status;
}

function renderVideos(videos) {
    const tbody = document.getElementById('videosTable');
    if (!videos || videos.length === 0) {
        tbody.innerHTML = '<tr><td colspan="5"><div class="empty-state"><i class="fas fa-film"></i><p>Belum ada video</p></div></td></tr>';
        return;
    }
    tbody.innerHTML = videos.map(v => `
        <tr>
            <td style="font-size:0.75rem;max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" title="${v.filename}">${v.filename}</td>
            <td>${formatDuration(v.original_duration)}</td>
            <td>${formatDuration(v.new_duration)}</td>
            <td><span class="badge badge-success">${Math.round(v.saved_percent || 0)}%</span></td>
            <td style="font-size:0.8rem;">${formatDate(v.created_at)}</td>
        </tr>
    `).join('');
}

function renderActivities(logs) {
    const tbody = document.getElementById('activityTable');
    if (!logs || logs.length === 0) {
        tbody.innerHTML = '<tr><td colspan="4"><div class="empty-state"><i class="fas fa-history"></i><p>Belum ada aktivitas</p></div></td></tr>';
        return;
    }
    tbody.innerHTML = logs.map(l => `
        <tr>
            <td><span class="badge badge-info">${l.action}</span></td>
            <td style="font-size:0.8rem;color:var(--muted);max-width:300px;">${l.details || '—'}</td>
            <td style="font-size:0.75rem;color:var(--muted);">${l.ip_address || '—'}</td>
            <td style="font-size:0.8rem;">${formatDate(l.created_at, true)}</td>
        </tr>
    `).join('');
}

function formatDate(dateStr, withTime = false) {
    if (!dateStr) return '—';
    const d = new Date(dateStr);
    const opts = { day: '2-digit', month: 'short', year: 'numeric' };
    if (withTime) { opts.hour = '2-digit'; opts.minute = '2-digit'; }
    return d.toLocaleDateString('id-ID', opts);
}

function formatDuration(seconds) {
    if (!seconds || seconds < 0) return '0s';
    seconds = Math.round(seconds);
    if (seconds < 60) return seconds + 's';
    const m = Math.floor(seconds / 60);
    const s = seconds % 60;
    if (m < 60) return m + 'm ' + s + 's';
    const h = Math.floor(m / 60);
    return h + 'j ' + (m % 60) + 'm';
}

// ============ EDIT MODAL ============
function openEditModal() {
    if (!currentUser) return;
    const u = currentUser;

    document.getElementById('editUserInfo').textContent = u.username + ' • ' + u.email;

    // Set plan
    document.querySelectorAll('input[name="editPlan"]').forEach(r => {
        r.checked = r.value === u.plan;
    });

    // Set quota
    document.getElementById('editQuota').value = u.daily_quota;

    // Set status
    document.querySelectorAll('input[name="editStatus"]').forEach(r => {
        r.checked = (r.value === 'active' && u.is_active) || (r.value === 'inactive' && !u.is_active);
    });

    document.getElementById('editModal').classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeEditModal() {
    document.getElementById('editModal').classList.remove('active');
    document.body.style.overflow = '';
}

async function saveEditUser() {
    if (!currentUser) return;

    const plan = document.querySelector('input[name="editPlan"]:checked')?.value;
    const quota = parseInt(document.getElementById('editQuota').value);
    const status = document.querySelector('input[name="editStatus"]:checked')?.value;

    if (!plan) {
        toastError('Pilih plan terlebih dahulu');
        return;
    }
    if (isNaN(quota) || quota < 0) {
        toastError('Kuota harus berupa angka positif');
        return;
    }

    const btn = document.getElementById('saveEditBtn');
    const originalText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';

    try {
        const r = await fetch(API + '/admin/users/' + currentUser.id, {
            method: 'PATCH',
            headers: { 'Authorization': 'Bearer ' + TOKEN, 'Content-Type': 'application/json' },
            body: JSON.stringify({
                plan: plan,
                daily_quota: quota,
                is_active: status === 'active'
            })
        });
        const d = await r.json();
        if (!d.success) throw new Error(d.error);

        toastSuccess('✅ User berhasil diupdate!');
        closeEditModal();
        loadUser();
    } catch (e) {
        toastError('Gagal: ' + e.message);
    } finally {
        btn.disabled = false;
        btn.innerHTML = originalText;
    }
}

// Close modal on overlay click
document.getElementById('editModal').addEventListener('click', (e) => {
    if (e.target.id === 'editModal') closeEditModal();
});

// ESC to close
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        const modal = document.getElementById('editModal');
        if (modal.classList.contains('active')) closeEditModal();
    }
});

// ============ OTHER ACTIONS ============
async function resetQuota() {
    if (!currentUser) return;
    const ok = await showConfirm({
        title: 'Reset Kuota?',
        message: 'Kuota user ini akan direset untuk hari ini.',
        type: 'warning',
        okText: 'Ya, Reset'
    });
    if (!ok) return;

    try {
        const r = await fetch(API + '/admin/users/' + currentUser.id + '/reset-quota', {
            method: 'POST',
            headers: { 'Authorization': 'Bearer ' + TOKEN }
        });
        const d = await r.json();
        if (!d.success) throw new Error(d.error);
        toastSuccess('Kuota berhasil direset');
        loadUser();
    } catch (e) {
        toastError('Gagal: ' + e.message);
    }
}

async function deleteUser() {
    if (!currentUser) return;
    const ok = await showConfirm({
        title: 'Hapus User?',
        message: 'User "' + currentUser.username + '" akan dihapus permanen. Semua data (video, langganan, log) akan ikut terhapus!',
        type: 'danger',
        okText: 'Ya, Hapus'
    });
    if (!ok) return;

    try {
        const r = await fetch(API + '/admin/users/' + currentUser.id, {
            method: 'DELETE',
            headers: { 'Authorization': 'Bearer ' + TOKEN }
        });
        const d = await r.json();
        if (!d.success) throw new Error(d.error);
        toastSuccess('User berhasil dihapus');
        setTimeout(() => window.location.href = 'users.php', 800);
    } catch (e) {
        toastError('Gagal: ' + e.message);
    }
}

loadUser();
</script>

</div></body></html>