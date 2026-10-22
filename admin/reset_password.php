<?php
if (session_status() === PHP_SESSION_NONE) session_start();

require_once __DIR__ . '/../config/config.php';

$isAdminAccess = (!empty($_SESSION['is_admin'])) || (!empty($isAdmin));

if (!$isLoggedIn || !$isAdminAccess) {
    header('Location: ../login.php');
    exit;
}

$adminName = $userName ?? ($_SESSION['user_name'] ?? 'Admin');
$currentPage = basename($_SERVER['PHP_SELF'], '.php');
$authToken = getAuthToken();
$apiUrl = API_URL;

$pageTitle = 'Reset Password User';
$pageSubtitle = 'Cari user & reset password manual, lalu kirim via WhatsApp';
$waNumber = '6282398106023'; // sesuaikan kalau beda

require_once __DIR__ . '/includes/admin_header.php';
?>

<link rel="icon" href="logo_clipora.png" type="image/png">
<link rel="shortcut icon" href="logo_clipora1.png" type="image/png">

<!-- ============================================================ -->
<!-- INFO WARNING                                                  -->
<!-- ============================================================ -->
<div class="card" style="border-color:rgba(245,158,11,0.3);background:linear-gradient(135deg,rgba(245,158,11,0.05),transparent);">
    <div style="display:flex;gap:14px;align-items:flex-start;">
        <div style="width:44px;height:44px;border-radius:12px;background:rgba(245,158,11,0.15);color:var(--primary);display:flex;align-items:center;justify-content:center;font-size:1.1rem;flex-shrink:0;">
            <i class="fas fa-key"></i>
        </div>
        <div>
            <h3 style="font-size:1rem;font-weight:700;margin-bottom:6px;">Cara Kerja Reset Password</h3>
            <ol style="font-size:0.85rem;color:var(--text-soft);line-height:1.7;margin:0;padding-left:20px;">
                <li>Masukkan email user yang lupa password</li>
                <li>Klik <strong>Cari User</strong> untuk verifikasi identitas</li>
                <li>Klik <strong>Reset Password</strong> → sistem generate password sementara</li>
                <li>Copy password baru &amp; kirim ke user via WhatsApp</li>
                <li><strong style="color:var(--primary);">⚠️ Password hanya tampil sekali — segera kirim!</strong></li>
            </ol>
        </div>
    </div>
</div>

<!-- ============================================================ -->
<!-- FORM CARI USER                                                -->
<!-- ============================================================ -->
<div class="card">
    <div class="card-header">
        <div>
            <h3><i class="fas fa-search"></i> Cari User</h3>
            <p>Masukkan email user yang terdaftar di sistem</p>
        </div>
    </div>

    <div style="display:flex;gap:10px;flex-wrap:wrap;">
        <div class="search-box" style="flex:1;min-width:240px;">
            <i class="fas fa-envelope"></i>
            <input type="email" id="rpEmail" placeholder="user@example.com" autocomplete="email">
        </div>
        <button class="btn btn-primary" id="rpSearchBtn" onclick="rpSearch()">
            <i class="fas fa-search"></i> Cari User
        </button>
    </div>

    <div id="rpError" style="display:none;margin-top:14px;" class="alert alert-danger"></div>
</div>

<!-- ============================================================ -->
<!-- HASIL: USER FOUND                                             -->
<!-- ============================================================ -->
<div class="card" id="rpUserCard" style="display:none;">
    <div class="card-header">
        <div>
            <h3><i class="fas fa-user-check"></i> User Ditemukan</h3>
            <p>Verifikasi identitas sebelum reset password</p>
        </div>
    </div>

    <div style="display:flex;gap:16px;align-items:center;padding:18px;background:rgba(255,255,255,0.02);border:1px solid var(--border);border-radius:14px;margin-bottom:18px;">
        <div class="user-avatar" id="rpUserAvatar" style="width:52px;height:52px;font-size:1.2rem;">U</div>
        <div style="flex:1;min-width:0;">
            <div id="rpUserName" style="font-weight:700;font-size:1rem;margin-bottom:2px;">-</div>
            <div id="rpUserEmail" style="font-size:0.85rem;color:var(--muted);">-</div>
        </div>
        <span class="badge" id="rpUserPlan">-</span>
    </div>

    <div style="display:flex;gap:10px;flex-wrap:wrap;">
        <button class="btn btn-danger" id="rpResetBtn" onclick="rpConfirmReset()">
            <i class="fas fa-key"></i> Reset Password Sekarang
        </button>
        <button class="btn btn-ghost" onclick="rpReset()">
            <i class="fas fa-times"></i> Batal
        </button>
    </div>
</div>

<!-- ============================================================ -->
<!-- HASIL: PASSWORD BARU                                          -->
<!-- ============================================================ -->
<div class="card" id="rpResultCard" style="display:none;border-color:rgba(34,197,94,0.3);background:linear-gradient(135deg,rgba(34,197,94,0.05),transparent);">
    <div class="card-header">
        <div>
            <h3 style="color:var(--success);"><i class="fas fa-check-circle"></i> Password Berhasil Direset</h3>
            <p>Kirim password baru ke user <strong>SEKARANG</strong> — password ini hanya tampil sekali</p>
        </div>
    </div>

    <div style="padding:22px;background:rgba(0,0,0,0.3);border:1px dashed rgba(34,197,94,0.4);border-radius:14px;text-align:center;margin-bottom:18px;">
        <div style="font-size:0.75rem;color:var(--muted);text-transform:uppercase;letter-spacing:1px;font-weight:700;margin-bottom:10px;">Password Sementara</div>
        <div id="rpTempPassword" style="font-family:'Courier New',monospace;font-size:1.8rem;font-weight:800;color:var(--success);letter-spacing:2px;user-select:all;">-</div>
    </div>

    <div id="rpResultInfo" style="font-size:0.85rem;color:var(--text-soft);margin-bottom:18px;line-height:1.6;"></div>

    <div style="display:flex;gap:10px;flex-wrap:wrap;">
        <button class="btn btn-success" id="rpCopyBtn" onclick="rpCopy()">
            <i class="fas fa-copy"></i> Copy Password
        </button>
        <button class="btn btn-primary" id="rpWaBtn" onclick="rpSendWA()">
            <i class="fab fa-whatsapp"></i> Kirim ke WhatsApp
        </button>
        <button class="btn btn-ghost" onclick="rpReset()">
            <i class="fas fa-plus"></i> Reset User Lain
        </button>
    </div>
</div>

<script>
(function(){
    const API = <?= json_encode($apiUrl) ?>;
    const TOKEN = <?= json_encode($authToken) ?>;

    let currentUser = null;
    let currentTempPassword = null;

    window.rpSearch = async function() {
        const email = document.getElementById('rpEmail').value.trim();
        const errBox = document.getElementById('rpError');
        errBox.style.display = 'none';

        if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            errBox.innerHTML = '<i class="fas fa-exclamation-circle"></i> Masukkan email yang valid';
            errBox.style.display = 'flex';
            return;
        }

        showLoading('Mencari user...');

        try {
            const r = await fetch(API + '/admin/find-user?email=' + encodeURIComponent(email), {
                headers: { 'Authorization': 'Bearer ' + TOKEN }
            });
            const d = await r.json();

            hideLoading();

            if (!d.success || !d.user) {
                errBox.innerHTML = '<i class="fas fa-exclamation-circle"></i> ' + (d.error || 'User tidak ditemukan');
                errBox.style.display = 'flex';
                document.getElementById('rpUserCard').style.display = 'none';
                return;
            }

            currentUser = d.user;

            document.getElementById('rpUserAvatar').textContent = (d.user.name || 'U').charAt(0).toUpperCase();
            document.getElementById('rpUserName').textContent = d.user.name || 'Tanpa Nama';
            document.getElementById('rpUserEmail').textContent = d.user.email;

            const planBadge = document.getElementById('rpUserPlan');
            const planMap = {
                free:     { text: 'FREE',     cls: 'badge-muted' },
                pro:      { text: 'PRO',      cls: 'badge-warning' },
                business: { text: 'BUSINESS', cls: 'badge-purple' },
                weekly:   { text: 'MINGGUAN', cls: 'badge-info' },
                monthly:  { text: 'BULANAN',  cls: 'badge-warning' }
            };
            const p = planMap[d.user.plan] || planMap.free;
            planBadge.textContent = p.text;
            planBadge.className = 'badge ' + p.cls;

            document.getElementById('rpUserCard').style.display = 'block';
            document.getElementById('rpResultCard').style.display = 'none';

        } catch (e) {
            hideLoading();
            errBox.innerHTML = '<i class="fas fa-exclamation-circle"></i> Gagal terhubung ke server';
            errBox.style.display = 'flex';
        }
    };

    window.rpConfirmReset = async function() {
        if (!currentUser) return;

        const ok = await showConfirm({
            title: 'Reset Password?',
            message: 'Password user ' + currentUser.email + ' akan direset. Password lama tidak akan bisa dipakai lagi. Lanjutkan?',
            type: 'danger',
            okText: 'Ya, Reset Sekarang',
            cancelText: 'Batal'
        });

        if (!ok) return;

        showLoading('Mereset password...');

        try {
            const r = await fetch(API + '/admin/reset-user-password', {
                method: 'POST',
                headers: {
                    'Authorization': 'Bearer ' + TOKEN,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ user_id: currentUser.id })
            });
            const d = await r.json();

            hideLoading();

            if (!d.success) {
                toastError(d.error || 'Gagal reset password');
                return;
            }

            currentTempPassword = d.temp_password;

            document.getElementById('rpTempPassword').textContent = d.temp_password;
            document.getElementById('rpResultInfo').innerHTML =
                'Password untuk <strong>' + currentUser.email + '</strong> sudah direset. ' +
                'Kirim password di atas ke user via WhatsApp, minta mereka segera ganti setelah login.';

            document.getElementById('rpUserCard').style.display = 'none';
            document.getElementById('rpResultCard').style.display = 'block';

            toastSuccess('Password berhasil direset!');
        } catch (e) {
            hideLoading();
            toastError('Gagal terhubung ke server');
        }
    };

    window.rpCopy = async function() {
        if (!currentTempPassword) return;
        try {
            await navigator.clipboard.writeText(currentTempPassword);
            toastSuccess('Password dicopy ke clipboard!');
        } catch (e) {
            const ta = document.createElement('textarea');
            ta.value = currentTempPassword;
            document.body.appendChild(ta);
            ta.select();
            document.execCommand('copy');
            ta.remove();
            toastSuccess('Password dicopy!');
        }
    };

    window.rpSendWA = function() {
        if (!currentUser || !currentTempPassword) return;

        const msg = 'Halo ' + (currentUser.name || 'Sobat Clipora') + ',\n\n' +
            'Password akun Clipora Anda sudah direset oleh admin.\n\n' +
            '📧 Email: ' + currentUser.email + '\n' +
            '🔑 Password baru: ' + currentTempPassword + '\n\n' +
            'Silakan login & segera ganti password setelah masuk.\n\n' +
            'Terima kasih! 🙏';

        window.open('https://wa.me/?text=' + encodeURIComponent(msg), '_blank');
    };

    window.rpReset = function() {
        currentUser = null;
        currentTempPassword = null;
        document.getElementById('rpEmail').value = '';
        document.getElementById('rpUserCard').style.display = 'none';
        document.getElementById('rpResultCard').style.display = 'none';
        document.getElementById('rpError').style.display = 'none';
    };

    document.getElementById('rpEmail').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') rpSearch();
    });
})();
</script>

