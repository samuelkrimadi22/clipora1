-<?php
require_once __DIR__ . '/../config/config.php';

// Cek admin DULU sebelum output apapun
$isAdminUser = !empty($isAdmin) || !empty($_SESSION['is_admin']);
if (!$isAdminUser) {
    header('Location: /clipora/login.php');
    exit;
}

// Baru include header (mulai output HTML)
require_once __DIR__ . '/../includes/header.php';

// Ambil token dari session untuk JS
$authToken = $_SESSION['auth_token'] ?? $authToken ?? '';
?>

<style>
/* ============================================================
   2FA SETUP PAGE - Modern Redesign
   ============================================================ */
.twofa-page {
    min-height: 85vh;
    padding: 40px 20px;
    display: flex;
    align-items: flex-start;
    justify-content: center;
    background: 
        radial-gradient(circle at 15% 15%, rgba(139,92,246,0.08) 0%, transparent 40%),
        radial-gradient(circle at 85% 85%, rgba(6,182,212,0.06) 0%, transparent 40%);
}

.twofa-wrapper {
    max-width: 680px;
    width: 100%;
}

/* ===== HEADER ===== */
.twofa-hero {
    text-align: center;
    margin-bottom: 32px;
}
.twofa-hero-icon {
    width: 72px;
    height: 72px;
    margin: 0 auto 20px;
    border-radius: 20px;
    background: linear-gradient(135deg, #8b5cf6, #06b6d4);
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 12px 40px rgba(139,92,246,0.4);
    animation: pulse 3s ease-in-out infinite;
}
@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.05); }
}
.twofa-hero-icon i {
    color: white;
    font-size: 2rem;
}
.twofa-hero h1 {
    font-size: 1.9rem;
    font-weight: 800;
    margin: 0 0 8px 0;
    background: linear-gradient(135deg, #a78bfa, #22d3ee);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    letter-spacing: -0.5px;
}
.twofa-hero p {
    color: var(--text-muted, #94a3b8);
    font-size: 0.95rem;
    margin: 0;
    line-height: 1.6;
}

/* ===== STEP INDICATOR ===== */
.twofa-steps {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    margin-bottom: 32px;
    padding: 16px 20px;
    background: rgba(0,0,0,0.25);
    border-radius: 16px;
    border: 1px solid rgba(255,255,255,0.06);
}
.twofa-step-dot {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.85rem;
    font-weight: 700;
    transition: 0.3s;
}
.twofa-step-dot .dot {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.75rem;
    font-weight: 800;
    flex-shrink: 0;
    transition: 0.3s;
}
.twofa-step-dot.pending .dot {
    background: rgba(255,255,255,0.08);
    color: #64748b;
    border: 1px solid rgba(255,255,255,0.1);
}
.twofa-step-dot.pending span {
    color: #64748b;
}
.twofa-step-dot.active .dot {
    background: linear-gradient(135deg, #8b5cf6, #06b6d4);
    color: white;
    box-shadow: 0 4px 12px rgba(139,92,246,0.5);
    animation: pulse 2s ease-in-out infinite;
}
.twofa-step-dot.active span {
    color: #a78bfa;
}
.twofa-step-dot.done .dot {
    background: #22c55e;
    color: white;
}
.twofa-step-dot.done span {
    color: #22c55e;
}
.twofa-step-sep {
    width: 30px;
    height: 2px;
    background: rgba(255,255,255,0.1);
    border-radius: 2px;
}

/* ===== CARD ===== */
.twofa-card {
    background: linear-gradient(135deg, rgba(26,26,46,0.95), rgba(22,22,42,0.95));
    border: 1px solid rgba(139,92,246,0.2);
    border-radius: 20px;
    padding: 28px;
    margin-bottom: 20px;
    box-shadow: 0 20px 60px rgba(0,0,0,0.4);
    animation: fadeIn 0.4s ease;
}
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(12px); }
    to { opacity: 1; transform: translateY(0); }
}
.twofa-card.hidden {
    display: none !important;
}

.twofa-card-title {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 1rem;
    font-weight: 700;
    color: #a78bfa;
    margin: 0 0 16px 0;
    padding-bottom: 12px;
    border-bottom: 1px solid rgba(255,255,255,0.06);
}
.twofa-card-title i {
    font-size: 1rem;
}
.twofa-card-desc {
    color: #94a3b8;
    font-size: 0.88rem;
    line-height: 1.6;
    margin: 0 0 16px 0;
}
.twofa-card-desc strong {
    color: #22d3ee;
}

/* ===== QR BOX ===== */
.twofa-qr-box {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 24px;
    background: #ffffff;
    border-radius: 16px;
    margin: 16px 0;
    box-shadow: 0 8px 24px rgba(0,0,0,0.2);
}
.twofa-qr-box img {
    width: 220px;
    height: 220px;
    display: block;
    border-radius: 8px;
}

/* ===== SECRET FALLBACK ===== */
.twofa-secret-label {
    font-size: 0.75rem;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 1px;
    font-weight: 700;
    margin: 16px 0 8px 0;
    text-align: center;
}
.twofa-secret {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px 16px;
    background: rgba(139,92,246,0.08);
    border: 1px dashed rgba(139,92,246,0.35);
    border-radius: 10px;
    font-family: 'Courier New', monospace;
    font-size: 0.8rem;
    color: #22d3ee;
    word-break: break-all;
    letter-spacing: 0.5px;
}
.twofa-secret code {
    flex: 1;
    user-select: all;
}
.twofa-secret button {
    padding: 6px 10px;
    background: rgba(139,92,246,0.2);
    border: 1px solid rgba(139,92,246,0.4);
    color: #a78bfa;
    border-radius: 6px;
    cursor: pointer;
    font-size: 0.75rem;
    transition: 0.2s;
    flex-shrink: 0;
    font-family: inherit;
}
.twofa-secret button:hover {
    background: rgba(139,92,246,0.3);
    color: #fff;
}

/* ===== INPUT ===== */
.twofa-code-input {
    width: 100%;
    padding: 20px;
    background: rgba(0,0,0,0.5);
    border: 2px solid rgba(139,92,246,0.3);
    border-radius: 14px;
    color: #fff;
    font-family: 'Courier New', monospace;
    font-size: 2rem;
    font-weight: 700;
    letter-spacing: 0.6em;
    text-align: center;
    box-sizing: border-box;
    outline: none;
    transition: 0.25s;
    margin: 8px 0 16px 0;
}
.twofa-code-input:focus {
    border-color: #8b5cf6;
    background: rgba(0,0,0,0.7);
    box-shadow: 0 0 0 4px rgba(139,92,246,0.15);
}
.twofa-code-input::placeholder {
    color: rgba(255,255,255,0.2);
    letter-spacing: 0.4em;
}

/* ===== BUTTON ===== */
.twofa-btn {
    width: 100%;
    padding: 15px 24px;
    background: linear-gradient(135deg, #8b5cf6, #06b6d4);
    color: white;
    border: none;
    border-radius: 12px;
    font-size: 0.95rem;
    font-weight: 700;
    cursor: pointer;
    font-family: inherit;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    transition: 0.25s;
    box-shadow: 0 6px 20px rgba(139,92,246,0.35);
}
.twofa-btn:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 10px 28px rgba(139,92,246,0.5);
}
.twofa-btn:active:not(:disabled) {
    transform: translateY(0);
}
.twofa-btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none;
}
.twofa-btn-secondary {
    background: rgba(255,255,255,0.06);
    border: 1px solid rgba(255,255,255,0.15);
    box-shadow: none;
    color: #94a3b8;
    margin-top: 12px;
}
.twofa-btn-secondary:hover:not(:disabled) {
    background: rgba(255,255,255,0.1);
    color: #fff;
    box-shadow: none;
}
.twofa-btn-success {
    background: linear-gradient(135deg, #22c55e, #16a34a);
    box-shadow: 0 6px 20px rgba(34,197,94,0.35);
}
.twofa-btn-success:hover:not(:disabled) {
    box-shadow: 0 10px 28px rgba(34,197,94,0.5);
}

/* ===== ERROR ===== */
.twofa-error {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 12px 16px;
    background: rgba(239,68,68,0.1);
    border: 1px solid rgba(239,68,68,0.3);
    border-radius: 10px;
    color: #f87171;
    font-size: 0.85rem;
    margin-top: 12px;
    animation: shake 0.3s ease;
}
@keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-4px); }
    75% { transform: translateX(4px); }
}
.twofa-error.hidden { display: none; }

/* ===== LOADING ===== */
.twofa-loading {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 40px 20px;
    text-align: center;
    color: #94a3b8;
}
.twofa-loading .spinner {
    width: 40px;
    height: 40px;
    border: 3px solid rgba(139,92,246,0.2);
    border-top-color: #8b5cf6;
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
    margin-bottom: 16px;
}
@keyframes spin {
    to { transform: rotate(360deg); }
}

/* ===== BACKUP CODES ===== */
.twofa-success-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: rgba(34,197,94,0.15);
    border: 2px solid rgba(34,197,94,0.4);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 16px;
    animation: pulse 2s ease-in-out infinite;
}
.twofa-success-icon i {
    color: #22c55e;
    font-size: 1.6rem;
}

.twofa-warning-box {
    display: flex;
    gap: 12px;
    padding: 16px;
    background: rgba(245,158,11,0.08);
    border: 1px solid rgba(245,158,11,0.3);
    border-radius: 12px;
    margin: 16px 0;
}
.twofa-warning-box i {
    color: #f59e0b;
    font-size: 1.1rem;
    flex-shrink: 0;
    margin-top: 2px;
}
.twofa-warning-box-content {
    flex: 1;
    font-size: 0.82rem;
    color: #fbbf24;
    line-height: 1.55;
}
.twofa-warning-box-content strong {
    display: block;
    margin-bottom: 4px;
    color: #f59e0b;
}

.twofa-codes-box {
    background: rgba(0,0,0,0.35);
    border: 1px solid rgba(34,197,94,0.25);
    border-radius: 12px;
    padding: 20px;
    margin: 16px 0;
}
.twofa-codes-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 10px;
    margin-top: 4px;
}
.twofa-codes-grid span {
    padding: 10px 14px;
    background: rgba(34,197,94,0.08);
    border: 1px solid rgba(34,197,94,0.2);
    border-radius: 8px;
    color: #4ade80;
    font-family: 'Courier New', monospace;
    font-size: 0.85rem;
    font-weight: 700;
    text-align: center;
    letter-spacing: 1px;
    user-select: all;
    transition: 0.15s;
}
.twofa-codes-grid span:hover {
    background: rgba(34,197,94,0.15);
    border-color: rgba(34,197,94,0.4);
}

/* ===== ALREADY ENABLED STATE ===== */
.twofa-enabled-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 14px;
    background: rgba(34,197,94,0.12);
    border: 1px solid rgba(34,197,94,0.3);
    color: #22c55e;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 700;
    letter-spacing: 0.5px;
    text-transform: uppercase;
}

/* ===== RESPONSIVE ===== */
@media (max-width: 640px) {
    .twofa-page { padding: 24px 16px; }
    .twofa-hero h1 { font-size: 1.5rem; }
    .twofa-hero-icon { width: 60px; height: 60px; }
    .twofa-hero-icon i { font-size: 1.5rem; }
    .twofa-card { padding: 20px; }
    .twofa-qr-box img { width: 180px; height: 180px; }
    .twofa-code-input { font-size: 1.5rem; padding: 16px; }
    .twofa-codes-grid { grid-template-columns: 1fr; }
    .twofa-steps { flex-wrap: wrap; gap: 8px; padding: 12px; }
    .twofa-step-dot span { display: none; }
    .twofa-step-sep { width: 16px; }
}
</style>
<link rel="stylesheet" href="../assets/css/style.css">
<main class="twofa-page">
    <div class="twofa-wrapper">

        <!-- HEADER -->
        <div class="twofa-hero">
            <div class="twofa-hero-icon">
                <i class="fas fa-shield-halved"></i>
            </div>
            <h1>Setup 2FA</h1>
            <p>Tambahkan lapisan keamanan ekstra untuk akun admin Anda</p>
        </div>

        <!-- STEP INDICATOR -->
        <div class="twofa-steps">
            <div class="twofa-step-dot pending" id="stepIndicator1">
                <div class="dot">1</div>
                <span>Scan QR</span>
            </div>
            <div class="twofa-step-sep"></div>
            <div class="twofa-step-dot pending" id="stepIndicator2">
                <div class="dot">2</div>
                <span>Verifikasi</span>
            </div>
            <div class="twofa-step-sep"></div>
            <div class="twofa-step-dot pending" id="stepIndicator3">
                <div class="dot">3</div>
                <span>Backup Codes</span>
            </div>
        </div>

        <!-- LOADING -->
        <div class="twofa-card" id="loadingCard">
            <div class="twofa-loading">
                <div class="spinner"></div>
                <p>Memuat QR code...</p>
            </div>
        </div>

        <!-- STEP 1: QR CODE -->
        <div class="twofa-card hidden" id="qrCard">
            <h2 class="twofa-card-title">
                <i class="fas fa-qrcode"></i>
                Langkah 1 - Scan QR Code
            </h2>
            <p class="twofa-card-desc">
                Buka <strong>Google Authenticator</strong> di HP, tap tombol <strong>"+"</strong> lalu pilih <strong>"Scan QR code"</strong>.
            </p>

            <div class="twofa-qr-box">
                <img id="qrImage" alt="QR Code" />
            </div>

            <p class="twofa-secret-label">Tidak bisa scan? Masukkan manual:</p>
            <div class="twofa-secret">
                <code id="secretText">-</code>
                <button type="button" onclick="copySecret()" id="copyBtn">
                    <i class="fas fa-copy"></i>
                </button>
            </div>
        </div>

        <!-- STEP 2: VERIFY -->
        <div class="twofa-card hidden" id="verifyCard">
            <h2 class="twofa-card-title">
                <i class="fas fa-key"></i>
                Langkah 2 - Verifikasi Kode
            </h2>
            <p class="twofa-card-desc">
                Masukkan kode <strong>6-digit</strong> yang muncul di Google Authenticator:
            </p>

            <input 
                type="text" 
                id="tokenInput" 
                class="twofa-code-input" 
                maxlength="6" 
                placeholder="000000" 
                inputmode="numeric"
                autocomplete="one-time-code"
            />

            <button id="verifyBtn" class="twofa-btn">
                <i class="fas fa-check-circle"></i>
                Verifikasi & Aktifkan 2FA
            </button>

            <div id="verifyError" class="twofa-error hidden">
                <i class="fas fa-exclamation-circle"></i>
                <span id="verifyErrorText"></span>
            </div>
        </div>

        <!-- STEP 3: DONE -->
        <div class="twofa-card hidden" id="doneCard">
            <div style="text-align:center; margin-bottom: 20px;">
                <div class="twofa-success-icon">
                    <i class="fas fa-check"></i>
                </div>
                <h2 class="twofa-card-title" style="justify-content:center; border:none; color:#22c55e; margin-bottom:8px;">
                    2FA Berhasil Diaktifkan!
                </h2>
                <p class="twofa-card-desc" style="margin:0;">
                    Akun Anda sekarang lebih aman.
                </p>
            </div>

            <div class="twofa-warning-box">
                <i class="fas fa-exclamation-triangle"></i>
                <div class="twofa-warning-box-content">
                    <strong>Simpan Backup Codes Sekarang</strong>
                    Kode-kode ini hanya ditampilkan <strong>SEKALI</strong>. Gunakan kalau HP Anda hilang.
                </div>
            </div>

            <div class="twofa-codes-box">
                <p style="font-size:0.8rem; color:#4ade80; margin:0 0 12px; font-weight:700; text-transform:uppercase; letter-spacing:1px;">
                    <i class="fas fa-key"></i> 10 Backup Codes
                </p>
                <div class="twofa-codes-grid" id="backupCodesGrid"></div>
            </div>

            <button class="twofa-btn twofa-btn-success" onclick="downloadCodes()">
                <i class="fas fa-download"></i>
                Download Backup Codes
            </button>

            <button class="twofa-btn twofa-btn-secondary" onclick="window.location.href='/clipora/admin/'">
                <i class="fas fa-arrow-right"></i>
                Selesai - Dashboard
            </button>
        </div>

    </div>
</main>

<script>
// - V9.7.0: Token dari PHP (HttpOnly cookie tidak bisa dibaca JS)
const TOKEN = '<?= htmlspecialchars($authToken ?? "", ENT_QUOTES) ?>';
console.log('- Token loaded:', TOKEN ? 'YES (' + TOKEN.length + ' chars)' : 'NO');

let currentBackupCodes = [];
let currentSecret = '';

// ============================================================
// STEP INDICATOR
// ============================================================
function setStep(step) {
    // step: 1 = QR, 2 = Verify, 3 = Done
    for (let i = 1; i <= 3; i++) {
        const el = document.getElementById('stepIndicator' + i);
        if (i < step) el.className = 'twofa-step-dot done';
        else if (i === step) el.className = 'twofa-step-dot active';
        else el.className = 'twofa-step-dot pending';
    }
}

// ============================================================
// LOAD SETUP (generate QR)
// ============================================================
async function loadSetup() {
    setStep(1);
    try {
        const res = await fetch('/api/admin/2fa/setup', {
            method: 'POST',
            headers: { 'Authorization': 'Bearer ' + TOKEN }
        });
        
        const text = await res.text();
        let data;
        try {
            data = JSON.parse(text);
        } catch (e) {
            console.error('Server response bukan JSON:', text.slice(0, 500));
            showLoadError('Server error. Cek console untuk detail.');
            return;
        }
        
        if (!data.success) {
            showLoadError(data.error || 'Gagal generate QR code');
            return;
        }

        document.getElementById('qrImage').src = data.qr_code;
        document.getElementById('secretText').textContent = data.secret;
        currentSecret = data.secret;
        
        document.getElementById('loadingCard').classList.add('hidden');
        document.getElementById('qrCard').classList.remove('hidden');
        document.getElementById('verifyCard').classList.remove('hidden');
        
        // Auto-focus input kode
        setTimeout(() => document.getElementById('tokenInput').focus(), 300);
        
    } catch (e) {
        console.error('loadSetup error:', e);
        showLoadError(e.message);
    }
}

function showLoadError(msg) {
    document.getElementById('loadingCard').innerHTML = `
        <div style="text-align:center; padding: 20px;">
            <i class="fas fa-times-circle" style="color:#ef4444; font-size:2rem; display:block; margin-bottom:12px;"></i>
            <p style="color:#f87171; margin:0 0 16px;">${msg}</p>
            <button class="twofa-btn twofa-btn-secondary" onclick="location.reload()" style="max-width:200px; margin:0 auto;">
                <i class="fas fa-redo"></i> Coba Lagi
            </button>
        </div>
    `;
}

// ============================================================
// COPY SECRET
// ============================================================
function copySecret() {
    if (!currentSecret) return;
    navigator.clipboard.writeText(currentSecret).then(() => {
        const btn = document.getElementById('copyBtn');
        const original = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-check"></i>';
        btn.style.color = '#22c55e';
        setTimeout(() => {
            btn.innerHTML = original;
            btn.style.color = '';
        }, 1500);
    });
}

// ============================================================
// INPUT FORMATTING (auto-limit 6 digit)
// ============================================================
document.getElementById('tokenInput').addEventListener('input', (e) => {
    let val = e.target.value.replace(/\D/g, '');
    if (val.length > 6) val = val.slice(0, 6);
    e.target.value = val;
    
    // Auto-submit kalau 6 digit
    if (val.length === 6) {
        // Delay sedikit biar user lihat kode terisi
        setTimeout(() => {
            if (e.target.value.length === 6) {
                document.getElementById('verifyBtn').click();
            }
        }, 300);
    }
});

// Enter key submit
document.getElementById('tokenInput').addEventListener('keypress', (e) => {
    if (e.key === 'Enter') document.getElementById('verifyBtn').click();
});

// ============================================================
// VERIFY + ENABLE 2FA
// ============================================================
document.getElementById('verifyBtn').addEventListener('click', async () => {
    const btn = document.getElementById('verifyBtn');
    const input = document.getElementById('tokenInput');
    const errorEl = document.getElementById('verifyError');
    const errorText = document.getElementById('verifyErrorText');
    const token = input.value.trim();

    errorEl.classList.add('hidden');

    if (!/^\d{6}$/.test(token)) {
        errorText.textContent = 'Masukkan 6 digit angka';
        errorEl.classList.remove('hidden');
        return;
    }

    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memverifikasi...';

    try {
        const res = await fetch('/api/admin/2fa/enable', {
            method: 'POST',
            headers: {
                'Authorization': 'Bearer ' + TOKEN,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ token })
        });
        const data = await res.json();

        if (!data.success) {
            errorText.textContent = data.error || 'Kode salah. Coba lagi.';
            errorEl.classList.remove('hidden');
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-check-circle"></i> Verifikasi & Aktifkan 2FA';
            input.value = '';
            input.focus();
            return;
        }

        // Success! Show backup codes
        currentBackupCodes = data.backupCodes || [];
        const codesHtml = currentBackupCodes.map(c => `<span>${c}</span>`).join('');
        document.getElementById('backupCodesGrid').innerHTML = codesHtml;

        document.getElementById('qrCard').classList.add('hidden');
        document.getElementById('verifyCard').classList.add('hidden');
        document.getElementById('doneCard').classList.remove('hidden');
        
        setStep(3);
        
        // Scroll to top
        window.scrollTo({ top: 0, behavior: 'smooth' });
        
    } catch (e) {
        errorText.textContent = e.message;
        errorEl.classList.remove('hidden');
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-check-circle"></i> Verifikasi & Aktifkan 2FA';
    }
});

// ============================================================
// DOWNLOAD BACKUP CODES
// ============================================================
function downloadCodes() {
    const content = 
        '========================================\n' +
        '  CLIPORA 2FA BACKUP CODES\n' +
        '========================================\n\n' +
        'Account: admin@clipora.com\n' +
        'Generated: ' + new Date().toISOString() + '\n\n' +
        'IMPORTANT:\n' +
        '- Setiap kode hanya bisa dipakai SEKALI\n' +
        '- Simpan di tempat aman (password manager)\n' +
        '- Gunakan kalau HP hilang\n\n' +
        '========================================\n\n' +
        currentBackupCodes.join('\n') + '\n\n' +
        '========================================\n';
    
    const blob = new Blob([content], { type: 'text/plain' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'clipora-2fa-backup-codes.txt';
    a.click();
    URL.revokeObjectURL(url);
}

// ============================================================
// INIT
// ============================================================
loadSetup();
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
