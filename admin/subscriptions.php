<?php
$pageTitle = 'Kelola Langganan';
$pageSubtitle = 'Approve, tolak, atau batalkan langganan user';
require __DIR__ . '/includes/admin_header.php';
?>

<div class="card">
    <div class="toolbar">
        <button class="filter-btn active" onclick="loadSubs('pending', this)">⏳ Pending</button>
        <button class="filter-btn" onclick="loadSubs('active', this)">✅ Aktif</button>
        <button class="filter-btn" onclick="loadSubs('rejected', this)">❌ Ditolak</button>
        <button class="filter-btn" onclick="loadSubs('cancelled', this)">🚫 Dibatalkan</button>
        <button class="filter-btn" onclick="loadSubs('', this)">📋 Semua</button>

        <button class="btn btn-success btn-sm" onclick="exportSubs()" style="margin-left:auto;" title="Download CSV">
            <i class="fas fa-file-csv"></i> Export CSV
        </button>
    </div>

    <div class="table-wrap">
        <table id="subsTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>Paket</th>
                    <th>Harga</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
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

async function loadSubs(status = 'pending', btn = null) {
    if (btn) {
        document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
    }
    const tbody = document.querySelector('#subsTable tbody');
    tbody.innerHTML = '<tr><td colspan="7"><div class="skeleton" style="width:100%;height:20px"></div></td></tr>';

    try {
        const url = API + '/admin/subscriptions' + (status ? '?status=' + status : '');
        const r = await fetch(url, { headers: { 'Authorization': 'Bearer ' + TOKEN } });
        const d = await r.json();
        if (!d.success) throw new Error(d.error);

        if (d.subscriptions.length === 0) {
            tbody.innerHTML = '<tr><td colspan="7"><div class="empty-state"><i class="fas fa-inbox"></i><p>Tidak ada data langganan</p></div></td></tr>';
            return;
        }

        tbody.innerHTML = d.subscriptions.map(s => `
            <tr>
                <td><strong>#${s.id}</strong></td>
                <td>
                    <strong style="color:var(--text)">${s.username || '-'}</strong><br>
                    <small style="color:var(--muted)">${s.email || '-'}</small>
                </td>
                <td><span class="badge badge-info">${s.plan}</span></td>
                <td><strong style="color:var(--primary)">Rp${Number(s.price).toLocaleString('id-ID')}</strong></td>
                <td>${badge(s.status)}</td>
                <td style="font-size:0.8rem">${new Date(s.created_at).toLocaleDateString('id-ID', {day:'2-digit',month:'short',year:'numeric'})}</td>
                <td>${actions(s)}</td>
            </tr>
        `).join('');
    } catch (e) {
        tbody.innerHTML = '<tr><td colspan="7"><div class="empty-state"><i class="fas fa-exclamation-triangle" style="color:var(--danger)"></i><p>Error: ' + e.message + '</p></div></td></tr>';
    }
}

function badge(status) {
    const map = {
        'pending':   '<span class="badge badge-warning"><i class="fas fa-clock"></i> Pending</span>',
        'active':    '<span class="badge badge-success"><i class="fas fa-check"></i> Aktif</span>',
        'rejected':  '<span class="badge badge-danger"><i class="fas fa-times"></i> Ditolak</span>',
        'expired':   '<span class="badge badge-muted">Kadaluarsa</span>',
        'cancelled': '<span class="badge badge-muted"><i class="fas fa-ban"></i> Dibatalkan</span>'
    };
    return map[status] || status;
}

function actions(s) {
    // PENDING → Approve / Reject
    if (s.status === 'pending') {
        return `
            <button class="btn btn-success btn-sm" onclick="updateSub(${s.id}, 'active')">
                <i class="fas fa-check"></i> Approve
            </button>
            <button class="btn btn-danger btn-sm" onclick="updateSub(${s.id}, 'rejected')">
                <i class="fas fa-times"></i>
            </button>
        `;
    }

    // ACTIVE → Cancel (downgrade ke Free)
    if (s.status === 'active') {
        return `
            <button class="btn btn-danger btn-sm" onclick="cancelSub(${s.id}, '${(s.username || '').replace(/'/g, "\\'")}')" title="Batalkan langganan & downgrade ke Free">
                <i class="fas fa-ban"></i> Cancel
            </button>
        `;
    }

    // REJECTED/CANCELLED/EXPIRED → bisa re-approve
    if (['rejected', 'cancelled', 'expired'].includes(s.status)) {
        return `
            <button class="btn btn-success btn-sm" onclick="updateSub(${s.id}, 'active')" title="Aktifkan ulang">
                <i class="fas fa-redo"></i> Aktifkan
            </button>
        `;
    }

    return '<span style="color:var(--muted);font-size:0.75rem;">—</span>';
}

async function updateSub(id, status) {
    const act = status === 'active' ? 'APPROVE' : 'TOLAK';
    const ok = await showConfirm({
        title: status === 'active' ? 'Approve Langganan?' : 'Tolak Langganan?',
        message: 'Yakin ingin ' + act + ' langganan #' + id + '?' + (status === 'active' ? ' User akan langsung diupgrade ke PRO.' : ''),
        type: status === 'active' ? 'info' : 'danger',
        okText: status === 'active' ? 'Ya, Approve' : 'Ya, Tolak',
        cancelText: 'Batal'
    });
    if (!ok) return;

    try {
        const r = await fetch(API + '/admin/subscriptions/' + id, {
            method: 'PATCH',
            headers: { 'Authorization': 'Bearer ' + TOKEN, 'Content-Type': 'application/json' },
            body: JSON.stringify({ status })
        });
        const d = await r.json();
        if (!d.success) throw new Error(d.error);
        toastSuccess(d.message);
        loadSubs(status === 'active' ? 'active' : 'pending');
    } catch (e) {
        toastError('Gagal: ' + e.message);
    }
}

async function cancelSub(id, username) {
    const ok = await showConfirm({
        title: '⚠️ Batalkan Langganan?',
        message: `Yakin ingin batalkan langganan #${id}${username ? ' (' + username + ')' : ''}?\n\n` +
                 `User akan langsung di-downgrade ke FREE:\n` +
                 `• Plan: Free\n` +
                 `• Kuota: 3 video/hari\n` +
                 `• Watermark aktif kembali\n` +
                 `• Kuota hari ini akan di-reset\n\n` +
                 `Tindakan ini tidak bisa di-undo.`,
        type: 'danger',
        okText: 'Ya, Batalkan',
        cancelText: 'Batal'
    });
    if (!ok) return;

    try {
        const r = await fetch(API + '/admin/subscriptions/' + id, {
            method: 'PATCH',
            headers: {
                'Authorization': 'Bearer ' + TOKEN,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ status: 'cancelled' })
        });
        const d = await r.json();
        if (!d.success) throw new Error(d.error);

        toastSuccess('✅ Langganan dibatalkan, user downgrade ke Free');
        loadSubs('active');
    } catch (e) {
        toastError('Gagal cancel: ' + e.message);
    }
}

async function exportSubs() {
    try {
        toastInfo('Menyiapkan file CSV...');

        const r = await fetch(API + '/admin/export/subscriptions', {
            headers: { 'Authorization': 'Bearer ' + TOKEN }
        });

        if (!r.ok) throw new Error('Gagal export');

        const blob = await r.blob();
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'clipora_subscriptions_' + new Date().toISOString().slice(0, 10) + '.csv';
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        window.URL.revokeObjectURL(url);

        toastSuccess('✅ File CSV berhasil didownload!');
    } catch (e) {
        toastError('Gagal export: ' + e.message);
    }
}

loadSubs('pending');
</script>

</div></body></html>