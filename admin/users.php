<?php
$pageTitle = 'Kelola Users';
$pageSubtitle = 'Daftar dan kelola semua pengguna Clipora';
require __DIR__ . '/includes/admin_header.php';
?>

<!-- ============ BULK ACTION BAR ============ -->
<div id="bulkBar" class="bulk-bar" style="display:none;">
    <div class="bulk-info">
        <i class="fas fa-check-circle"></i>
        <strong id="bulkCount">0</strong> user dipilih
    </div>

    <div class="bulk-actions">
        <div class="bulk-group">
            <label style="font-size:0.75rem;color:var(--muted);">Plan:</label>
            <select id="bulkPlanSelect" class="bulk-select">
                <option value="free">Free</option>
                <option value="pro">Pro</option>
                <option value="business">Business</option>
            </select>
            <button class="btn btn-primary btn-sm" onclick="bulkUpdatePlan()">
                <i class="fas fa-tag"></i> Terapkan
            </button>
        </div>

        <button class="btn btn-success btn-sm" onclick="bulkActivate()">
            <i class="fas fa-check"></i> Aktifkan
        </button>
        <button class="btn btn-ghost btn-sm" onclick="bulkDeactivate()">
            <i class="fas fa-ban"></i> Nonaktifkan
        </button>

        <button class="btn btn-danger btn-sm" onclick="bulkDelete()">
            <i class="fas fa-trash"></i> Hapus Terpilih
        </button>

        <button class="btn btn-ghost btn-sm" onclick="clearSelection()" title="Batal pilih">
            <i class="fas fa-times"></i>
        </button>
    </div>
</div>

<div class="card">
    <div class="toolbar">
        <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" id="searchInput" placeholder="Cari berdasarkan email, username, atau nama...">
        </div>
        <button class="btn btn-primary" onclick="loadUsers()">
            <i class="fas fa-search"></i> Cari
        </button>
        <button class="btn btn-success" onclick="exportUsers()" title="Download CSV">
            <i class="fas fa-file-csv"></i> Export CSV
        </button>
    </div>

    <div class="table-wrap">
        <table id="usersTable">
            <thead>
                <tr>
                    <th style="width:40px;">
                        <input type="checkbox" id="selectAllCheckbox" onchange="toggleSelectAll(this)" style="cursor:pointer;width:18px;height:18px;accent-color:#f59e0b;">
                    </th>
                    <th>ID</th><th>User</th><th>Plan</th><th>Quota</th>
                    <th>Video</th><th>Status</th><th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr><td colspan="8"><div class="skeleton" style="width:100%"></div></td></tr>
            </tbody>
        </table>
    </div>

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
/* ============ BULK ACTION BAR ============ */
.bulk-bar{
    background:linear-gradient(135deg, rgba(245,158,11,0.12), rgba(245,158,11,0.04));
    border:1px solid rgba(245,158,11,0.3);
    border-radius:14px;
    padding:14px 20px;
    margin-bottom:20px;
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:16px;
    flex-wrap:wrap;
    animation:slideDown 0.3s ease;
}
.bulk-info{
    display:flex;
    align-items:center;
    gap:8px;
    color:var(--primary);
    font-size:0.9rem;
    font-weight:600;
}
.bulk-info strong{
    font-size:1.1rem;
    background:var(--gradient);
    -webkit-background-clip:text;
    -webkit-text-fill-color:transparent;
    background-clip:text;
}
.bulk-actions{
    display:flex;
    gap:8px;
    align-items:center;
    flex-wrap:wrap;
}
.bulk-group{
    display:flex;
    align-items:center;
    gap:8px;
    padding:4px 12px;
    background:rgba(0,0,0,0.3);
    border:1px solid var(--border);
    border-radius:10px;
}
.bulk-select{
    padding:5px 10px;
    background:var(--bg);
    border:1px solid var(--border);
    border-radius:6px;
    color:var(--text);
    font-family:inherit;
    font-size:0.8rem;
    cursor:pointer;
}
.bulk-select:focus{
    outline:none;
    border-color:var(--primary);
}
@keyframes slideDown{
    from{opacity:0;transform:translateY(-8px);}
    to{opacity:1;transform:translateY(0);}
}
.user-checkbox{
    width:18px;
    height:18px;
    cursor:pointer;
    accent-color:#f59e0b;
}

/* ============ EDIT MODAL ============ */
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

/* Plan options */
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

/* Status toggle */
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

let currentPage = 1;
let currentSearch = '';
let totalPages = 1;
const PER_PAGE = 20;

const selectedIds = new Set();
let editingUserId = null;

// ============ LOAD USERS ============
async function loadUsers() {
    const tbody = document.querySelector('#usersTable tbody');
    tbody.innerHTML = '<tr><td colspan="8"><div class="skeleton" style="width:100%;height:20px"></div></td></tr>';

    try {
        const url = API + '/admin/users?search=' + encodeURIComponent(currentSearch) + '&page=' + currentPage + '&limit=' + PER_PAGE;
        const r = await fetch(url, { headers: { 'Authorization': 'Bearer ' + TOKEN } });
        const d = await r.json();
        if (!d.success) throw new Error(d.error);

        totalPages = Math.max(1, Math.ceil(d.pagination.total / PER_PAGE));

        if (d.users.length === 0) {
            tbody.innerHTML = '<tr><td colspan="8"><div class="empty-state"><i class="fas fa-users"></i><p>Tidak ada user ditemukan</p></div></td></tr>';
            document.getElementById('pageInfo').textContent = 'Total: 0 user';
            document.getElementById('prevBtn').disabled = true;
            document.getElementById('nextBtn').disabled = true;
            return;
        }

        tbody.innerHTML = d.users.map(u => `
            <tr data-user-id="${u.id}">
                <td>
                    <input type="checkbox"
                           class="user-checkbox"
                           data-id="${u.id}"
                           onchange="toggleUserSelect(${u.id}, this.checked)"
                           ${selectedIds.has(u.id) ? 'checked' : ''}>
                </td>
                <td><strong>#${u.id}</strong></td>
                <td>
                    <strong style="color:var(--text)">${u.username}</strong><br>
                    <small style="color:var(--muted)">${u.email}</small>
                </td>
                <td><span class="badge ${u.plan === 'free' ? 'badge-muted' : 'badge-warning'}">${u.plan.toUpperCase()}</span></td>
                <td>${u.daily_quota}</td>
                <td>${u.total_videos}</td>
                <td>
                    ${u.is_active ? '<span class="badge badge-success"><i class="fas fa-circle" style="font-size:0.5rem"></i> Aktif</span>' : '<span class="badge badge-danger">Nonaktif</span>'}
                    ${u.is_admin ? ' <span class="badge badge-purple"><i class="fas fa-crown"></i> Admin</span>' : ''}
                </td>
                <td>
                    <a href="user-detail.php?id=${u.id}" class="btn btn-ghost btn-sm" title="Lihat Detail">
                        <i class="fas fa-eye"></i>
                    </a>
                    <button class="btn btn-primary btn-sm" onclick="openEditModal(${u.id}, '${u.username}', '${u.email}', '${u.plan}', ${u.daily_quota}, ${u.is_active})" title="Edit">
                        <i class="fas fa-pen"></i>
                    </button>
                    <button class="btn btn-danger btn-sm" onclick="deleteUser(${u.id}, '${u.username}')" title="Hapus">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `).join('');

        document.getElementById('pageInfo').textContent =
            'Menampilkan ' + ((currentPage - 1) * PER_PAGE + 1) + '–' + Math.min(currentPage * PER_PAGE, d.pagination.total) + ' dari ' + d.pagination.total + ' user';
        document.getElementById('prevBtn').disabled = currentPage <= 1;
        document.getElementById('nextBtn').disabled = currentPage >= totalPages;

        updateSelectAllState();
    } catch (e) {
        tbody.innerHTML = '<tr><td colspan="8"><div class="empty-state"><i class="fas fa-exclamation-triangle" style="color:var(--danger)"></i><p>Error: ' + e.message + '</p></div></td></tr>';
    }
}

// ============ SELECTION ============
function toggleUserSelect(id, checked) {
    if (checked) selectedIds.add(id);
    else selectedIds.delete(id);
    updateBulkBar();
    updateSelectAllState();
}

function toggleSelectAll(checkbox) {
    document.querySelectorAll('.user-checkbox').forEach(cb => {
        cb.checked = checkbox.checked;
        const id = parseInt(cb.dataset.id);
        if (checkbox.checked) selectedIds.add(id);
        else selectedIds.delete(id);
    });
    updateBulkBar();
}

function updateSelectAllState() {
    const checkboxes = document.querySelectorAll('.user-checkbox');
    const allCheckbox = document.getElementById('selectAllCheckbox');
    if (!allCheckbox || checkboxes.length === 0) return;
    const checkedCount = document.querySelectorAll('.user-checkbox:checked').length;
    allCheckbox.checked = checkedCount === checkboxes.length;
    allCheckbox.indeterminate = checkedCount > 0 && checkedCount < checkboxes.length;
}

function updateBulkBar() {
    const bar = document.getElementById('bulkBar');
    const count = document.getElementById('bulkCount');
    if (selectedIds.size > 0) {
        bar.style.display = 'flex';
        count.textContent = selectedIds.size;
    } else {
        bar.style.display = 'none';
    }
}

function clearSelection() {
    selectedIds.clear();
    document.querySelectorAll('.user-checkbox').forEach(cb => cb.checked = false);
    const allCheckbox = document.getElementById('selectAllCheckbox');
    if (allCheckbox) {
        allCheckbox.checked = false;
        allCheckbox.indeterminate = false;
    }
    updateBulkBar();
}

// ============ EDIT MODAL ============
function openEditModal(id, username, email, plan, quota, active) {
    editingUserId = id;

    document.getElementById('editUserInfo').textContent = username + ' • ' + email;

    // Set plan
    document.querySelectorAll('input[name="editPlan"]').forEach(r => {
        r.checked = r.value === plan;
    });

    // Set quota
    document.getElementById('editQuota').value = quota;

    // Set status
    document.querySelectorAll('input[name="editStatus"]').forEach(r => {
        r.checked = (r.value === 'active' && active) || (r.value === 'inactive' && !active);
    });

    document.getElementById('editModal').classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeEditModal() {
    document.getElementById('editModal').classList.remove('active');
    document.body.style.overflow = '';
    editingUserId = null;
}

async function saveEditUser() {
    if (!editingUserId) return;

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
        const r = await fetch(API + '/admin/users/' + editingUserId, {
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
        loadUsers();
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

// ============ BULK ACTIONS ============
async function executeBulkAction(action, value, actionLabel) {
    const ids = Array.from(selectedIds);

    const ok = await showConfirm({
        title: actionLabel + '?',
        message: `${ids.length} user akan diproses dengan aksi: ${actionLabel.toLowerCase()}.\n\n${action === 'delete' ? '⚠️ Data user akan dihapus permanen dan tidak bisa dikembalikan!' : ''}`,
        type: action === 'delete' ? 'danger' : 'warning',
        okText: 'Ya, Lanjutkan',
        cancelText: 'Batal'
    });

    if (!ok) return;

    try {
        showLoading('Memproses...');

        const body = { action, userIds: ids };
        if (value !== undefined) body.value = value;

        const r = await fetch(API + '/admin/users/bulk-action', {
            method: 'POST',
            headers: {
                'Authorization': 'Bearer ' + TOKEN,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(body)
        });

        const d = await r.json();
        if (!d.success) throw new Error(d.error);

        toastSuccess(d.message);
        clearSelection();
        loadUsers();
    } catch (e) {
        toastError('Gagal: ' + e.message);
    } finally {
        hideLoading();
    }
}

function bulkDelete() { executeBulkAction('delete', undefined, 'Hapus User Terpilih'); }
function bulkActivate() { executeBulkAction('activate', undefined, 'Aktifkan User Terpilih'); }
function bulkDeactivate() { executeBulkAction('deactivate', undefined, 'Nonaktifkan User Terpilih'); }
function bulkUpdatePlan() {
    const plan = document.getElementById('bulkPlanSelect').value;
    executeBulkAction('update-plan', plan, 'Ubah Plan ke ' + plan.toUpperCase());
}

// ============ SINGLE ACTIONS ============
function changePage(delta) {
    const newPage = currentPage + delta;
    if (newPage < 1 || newPage > totalPages) return;
    currentPage = newPage;
    loadUsers();
}

async function deleteUser(id, username) {
    const ok = await showConfirm({
        title: 'Hapus User?',
        message: 'User "' + username + '" (ID #' + id + ') akan dihapus permanen. Semua data video, langganan, dan log akan ikut terhapus. Tindakan ini tidak bisa dibatalkan!',
        type: 'danger',
        okText: 'Ya, Hapus',
        cancelText: 'Batal'
    });
    if (!ok) return;

    try {
        const r = await fetch(API + '/admin/users/' + id, {
            method: 'DELETE',
            headers: { 'Authorization': 'Bearer ' + TOKEN }
        });
        const d = await r.json();
        if (!d.success) throw new Error(d.error);
        toastSuccess('User berhasil dihapus!');
        loadUsers();
    } catch (e) {
        toastError('Gagal: ' + e.message);
    }
}

// ============ EXPORT ============
async function exportUsers() {
    try {
        toastInfo('Menyiapkan file CSV...');
        const r = await fetch(API + '/admin/export/users', {
            headers: { 'Authorization': 'Bearer ' + TOKEN }
        });
        if (!r.ok) throw new Error('Gagal export');
        const blob = await r.blob();
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'clipora_users_' + new Date().toISOString().slice(0, 10) + '.csv';
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        window.URL.revokeObjectURL(url);
        toastSuccess('✅ File CSV berhasil didownload!');
    } catch (e) {
        toastError('Gagal export: ' + e.message);
    }
}

// ============ SEARCH ============
document.getElementById('searchInput').addEventListener('keyup', e => {
    if (e.key === 'Enter') {
        currentSearch = e.target.value;
        currentPage = 1;
        loadUsers();
    }
});

// Init
loadUsers();
</script>

</div></body></html>