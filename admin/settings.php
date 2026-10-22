<?php
$pageTitle = 'Setting Global';
$pageSubtitle = 'Konfigurasi aplikasi (harga, kontak, pembayaran)';
require __DIR__ . '/includes/admin_header.php';
?>

<div class="card" id="settingsCard" style="display:none">
    <div class="card-header">
        <div>
            <h3><i class="fas fa-cog"></i> Konfigurasi Aplikasi</h3>
            <p>Perubahan berlaku langsung tanpa restart server</p>
        </div>
        <button class="btn btn-primary" onclick="saveSettings()">
            <i class="fas fa-save"></i> Simpan Semua
        </button>
    </div>

    <!-- ============ HARGA ============ -->
    <div class="settings-section">
        <div class="settings-section-header">
            <div class="settings-icon" style="background:rgba(245,158,11,0.12);color:#f59e0b;">
                <i class="fas fa-tags"></i>
            </div>
            <div>
                <h4>Harga Paket</h4>
                <p>Harga langganan yang ditampilkan di halaman <code>harga.php</code></p>
            </div>
        </div>
        <div class="settings-fields">
            <div class="settings-field">
                <label>Harga Normal (Rp)</label>
                <input type="number" id="price_monthly" placeholder="35000" min="0">
                <span class="hint">
                    💡 Harga utama yang tampil. Contoh: <strong>35000</strong> = Rp 35.000
                </span>
            </div>
            <div class="settings-field">
                <label>Harga Promo / Early Bird (Rp)</label>
                <input type="number" id="early_price_monthly" placeholder="25000" min="0">
                <span class="hint">
                    🔥 Isi <strong>lebih kecil</strong> dari harga normal untuk aktifkan promo.<br>
                    Kalau dikosongkan / lebih besar → promo otomatis OFF.
                </span>
            </div>
            <div class="settings-field">
                <label>Kuota Early Bird</label>
                <input type="number" id="early_bird_limit" placeholder="100" min="1">
                <span class="hint">Jumlah user pertama yang dapat harga promo</span>
            </div>
            <div class="settings-field">
                <label>Sudah Terklaim</label>
                <input type="number" id="early_bird_count" placeholder="0" min="0">
                <span class="hint">Jumlah user yang sudah klaim early bird</span>
            </div>
        </div>

        <!-- LIVE PREVIEW -->
        <div class="settings-preview">
            <div class="preview-label">Preview tampilan di harga.php:</div>
            <div class="preview-card">
                <div class="preview-title">🔥 Pro Bulanan</div>
                <div id="preview_strike"
                    style="font-size:0.85rem;color:#64748b;text-decoration:line-through;font-weight:600;display:none;margin-top:4px;">
                    Rp 35.000
                </div>
                <div class="preview-price">
                    Rp <span id="preview_price">35.000</span>
                    <span class="preview-period">/bulan</span>
                </div>
                <div id="preview_early_note"
                    style="font-size:0.72rem;color:#fbbf24;font-weight:700;margin-top:6px;display:none;">
                    🔥 Harga Early Bird aktif
                </div>
            </div>
        </div>
    </div>

    <!-- ============ KONTAK ============ -->
    <div class="settings-section">
        <div class="settings-section-header">
            <div class="settings-icon" style="background:rgba(34,197,94,0.12);color:#22c55e;">
                <i class="fab fa-whatsapp"></i>
            </div>
            <div>
                <h4>Kontak &amp; Support</h4>
                <p>Kontak yang ditampilkan ke user</p>
            </div>
        </div>
        <div class="settings-fields">
            <div class="settings-field">
                <label>Nomor WhatsApp Admin</label>
                <input type="text" id="whatsapp_admin" placeholder="6282398106023">
                <span class="hint">Format: 62xxx (tanpa +, tanpa spasi)</span>
            </div>
            <div class="settings-field">
                <label>Email Support</label>
                <input type="email" id="support_email" placeholder="support@clipora.com">
                <span class="hint">Email untuk user menghubungi Anda</span>
            </div>
        </div>
    </div>

    <!-- ============ PEMBAYARAN ============ -->
    <div class="settings-section">
        <div class="settings-section-header">
            <div class="settings-icon" style="background:rgba(6,182,212,0.12);color:#06b6d4;">
                <i class="fas fa-university"></i>
            </div>
            <div>
                <h4>Informasi Pembayaran</h4>
                <p>Data rekening untuk transfer pembayaran</p>
            </div>
        </div>
        <div class="settings-fields">
            <div class="settings-field">
                <label>Nama Bank</label>
                <input type="text" id="bank_name" placeholder="BCA" maxlength="50">
                <span class="hint">Contoh: BCA, Mandiri, BNI, BRI</span>
            </div>
            <div class="settings-field">
                <label>Nomor Rekening</label>
                <input type="text" id="bank_account" placeholder="1234567890">
                <span class="hint">Nomor rekening tujuan transfer</span>
            </div>
            <div class="settings-field settings-field-full">
                <label>Nama Pemilik Rekening</label>
                <input type="text" id="bank_owner" placeholder="Semuel Krimadi" maxlength="100">
                <span class="hint">Nama sesuai rekening bank</span>
            </div>
        </div>
    </div>

    <!-- ============ LAINNYA ============ -->
    <div class="settings-section">
        <div class="settings-section-header">
            <div class="settings-icon" style="background:rgba(139,92,246,0.12);color:#8b5cf6;">
                <i class="fas fa-info-circle"></i>
            </div>
            <div>
                <h4>Lainnya</h4>
                <p>Konfigurasi tambahan</p>
            </div>
        </div>
        <div class="settings-fields">
            <div class="settings-field">
                <label>Garansi (Hari)</label>
                <input type="number" id="guarantee_days" placeholder="7" min="0" max="365">
                <span class="hint">Berapa hari garansi uang kembali</span>
            </div>
            <div class="settings-field">
                <label>Tagline Website</label>
                <input type="text" id="site_tagline" placeholder="Create Better Videos with AI" maxlength="100">
                <span class="hint">Slogan di logo website</span>
            </div>
        </div>
    </div>

    <div style="display:flex;justify-content:flex-end;gap:10px;margin-top:20px;padding-top:20px;border-top:1px solid var(--border);">
        <button class="btn btn-ghost" onclick="loadSettings()">
            <i class="fas fa-redo"></i> Reset
        </button>
        <button class="btn btn-primary" onclick="saveSettings()">
            <i class="fas fa-save"></i> Simpan Semua
        </button>
    </div>
</div>

<style>
    .settings-section {
        padding: 20px 0;
        border-bottom: 1px solid var(--border);
    }

    .settings-section:last-of-type {
        border-bottom: none;
    }

    .settings-section-header {
        display: flex;
        align-items: center;
        gap: 14px;
        margin-bottom: 18px;
    }

    .settings-icon {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.05rem;
        flex-shrink: 0;
    }

    .settings-section-header h4 {
        font-size: 0.95rem;
        font-weight: 700;
        margin-bottom: 3px;
    }

    .settings-section-header p {
        font-size: 0.78rem;
        color: var(--muted);
    }

    .settings-section-header p code {
        background: rgba(245, 158, 11, 0.1);
        color: var(--primary);
        padding: 1px 6px;
        border-radius: 4px;
        font-size: 0.72rem;
        font-family: 'Courier New', monospace;
    }

    .settings-fields {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
        padding-left: 56px;
    }

    .settings-field {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .settings-field-full {
        grid-column: span 2;
    }

    .settings-field label {
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--text-soft);
    }

    .settings-field input {
        padding: 11px 14px;
        background: rgba(0, 0, 0, 0.3);
        border: 1px solid var(--border);
        border-radius: 10px;
        color: var(--text);
        font-family: inherit;
        font-size: 0.875rem;
        transition: 0.2s;
    }

    .settings-field input:focus {
        outline: none;
        border-color: var(--primary);
        background: rgba(0, 0, 0, 0.5);
        box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.08);
    }

    .settings-field .hint {
        font-size: 0.72rem;
        color: var(--muted);
        line-height: 1.5;
    }

    .settings-field .hint strong {
        color: var(--text-soft);
        font-weight: 700;
    }

    .settings-field .hint code {
        background: rgba(245, 158, 11, 0.1);
        color: var(--primary);
        padding: 1px 6px;
        border-radius: 4px;
        font-size: 0.7rem;
        font-family: 'Courier New', monospace;
    }

    /* ============ PREVIEW ============ */
    .settings-preview {
        margin-top: 16px;
        margin-left: 56px;
        padding: 16px;
        background: rgba(245, 158, 11, 0.04);
        border: 1px dashed rgba(245, 158, 11, 0.25);
        border-radius: 12px;
    }

    .preview-label {
        font-size: 0.7rem;
        color: var(--muted);
        text-transform: uppercase;
        letter-spacing: 1px;
        font-weight: 700;
        margin-bottom: 10px;
    }

    .preview-card {
        display: inline-flex;
        flex-direction: column;
        align-items: center;
        padding: 14px 28px;
        background: linear-gradient(135deg, rgba(245, 158, 11, 0.12), rgba(245, 158, 11, 0.03));
        border: 1px solid rgba(245, 158, 11, 0.35);
        border-radius: 14px;
        min-width: 200px;
    }

    .preview-title {
        font-size: 0.85rem;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 6px;
    }

    .preview-price {
        font-size: 1.6rem;
        font-weight: 800;
        color: var(--primary);
        letter-spacing: -0.5px;
        line-height: 1;
    }

    .preview-period {
        font-size: 0.75rem;
        color: var(--muted);
        font-weight: 500;
    }

    @media(max-width:768px) {
        .settings-fields {
            grid-template-columns: 1fr;
            padding-left: 0;
        }

        .settings-field-full {
            grid-column: span 1;
        }

        .settings-preview {
            margin-left: 0;
        }
    }
</style>

<script>
    const API = <?= json_encode($apiUrl) ?>;
    const TOKEN = <?= json_encode($authToken) ?>;

    // V9.3.0: Semua key yang bisa diatur dari admin panel
    const SETTING_KEYS = [
        'price_monthly',
        'early_price_monthly',
        'early_bird_limit',
        'early_bird_count',
        'whatsapp_admin',
        'support_email',
        'bank_name', 'bank_account', 'bank_owner',
        'guarantee_days',
        'site_tagline'
    ];

    // ============================================================
    // FORMAT RUPIAH
    // ============================================================
    function formatRupiah(n) {
        return Number(n || 0).toLocaleString('id-ID');
    }

    // ============================================================
    // LIVE PREVIEW HARGA
    // ============================================================
    function updatePreview() {
        const normalEl = document.getElementById('price_monthly');
        const earlyEl = document.getElementById('early_price_monthly');
        const preview = document.getElementById('preview_price');
        const strike = document.getElementById('preview_strike');
        const earlyNote = document.getElementById('preview_early_note');

        if (!normalEl || !preview) return;

        const normal = parseInt(normalEl.value, 10) || 0;
        const early = parseInt(earlyEl?.value, 10) || 0;

        // V9.3.0: early bird hanya aktif kalau early < normal & early > 0
        const useEarly = early > 0 && early < normal;
        const displayPrice = useEarly ? early : normal;

        preview.textContent = formatRupiah(displayPrice);

        if (strike) {
            if (useEarly) {
                strike.style.display = 'block';
                strike.textContent = 'Rp ' + formatRupiah(normal);
            } else {
                strike.style.display = 'none';
            }
        }

        if (earlyNote) {
            earlyNote.style.display = useEarly ? 'block' : 'none';
            if (useEarly && normal > 0) {
                const disc = Math.round(((normal - early) / normal) * 100);
                earlyNote.textContent = `🔥 Harga Early Bird aktif — hemat ${disc}%`;
            }
        }
    }

    // ============================================================
    // LOAD SETTINGS
    // ============================================================
    async function loadSettings() {
        try {
            const r = await fetch(API + '/admin/settings', {
                headers: {
                    'Authorization': 'Bearer ' + TOKEN
                }
            });
            const d = await r.json();
            if (!d.success) throw new Error(d.error);

            SETTING_KEYS.forEach(key => {
                const el = document.getElementById(key);
                if (el) el.value = d.settings[key] || '';
            });

            // Fallback: kalau early_price_monthly kosong, biarkan blank
            // (jangan paksa 25000 biar admin bisa matikan promo dengan clear field)

            updatePreview();
            document.getElementById('settingsCard').style.display = 'block';

            // V9.3.0: Bind live preview untuk kedua field harga
            ['price_monthly', 'early_price_monthly'].forEach(id => {
                const el = document.getElementById(id);
                if (el && !el._bound) {
                    el._bound = true;
                    el.addEventListener('input', updatePreview);
                }
            });
        } catch (e) {
            toastError('Gagal load setting: ' + e.message);
        }
    }

    // ============================================================
    // SAVE SETTINGS
    // ============================================================
    async function saveSettings() {
        const data = {};
        SETTING_KEYS.forEach(key => {
            const el = document.getElementById(key);
            if (el) data[key] = el.value.trim();
        });

        // ============ VALIDASI ============
        const normal = parseInt(data.price_monthly, 10);
        const early = parseInt(data.early_price_monthly, 10);
        const limit = parseInt(data.early_bird_limit, 10);
        const count = parseInt(data.early_bird_count, 10);

        if (data.price_monthly && (isNaN(normal) || normal < 0)) {
            toastError('Harga normal tidak valid');
            return;
        }
        if (data.early_price_monthly && (isNaN(early) || early < 0)) {
            toastError('Harga early bird tidak valid');
            return;
        }
        if (data.early_bird_limit && (isNaN(limit) || limit < 1)) {
            toastError('Kuota early bird minimal 1');
            return;
        }
        if (data.early_bird_count && (isNaN(count) || count < 0)) {
            toastError('Jumlah terklaim tidak boleh negatif');
            return;
        }
        if (data.early_bird_count && data.early_bird_limit && count > limit) {
            toastError('Jumlah terklaim tidak boleh lebih besar dari kuota');
            return;
        }
        // Warning (bukan error): early >= normal → promo OFF
        if (data.early_price_monthly && early > 0 && early >= normal) {
            const proceed = await window.showConfirm({
                title: '⚠️ Harga Early Bird Tidak Lebih Murah',
                message: `Harga early bird (Rp ${formatRupiah(early)}) tidak lebih murah dari harga normal (Rp ${formatRupiah(normal)}).\n\nPromo Early Bird akan OTOMATIS OFF di halaman harga.\n\nLanjut simpan?`,
                type: 'warning',
                okText: 'Ya, Simpan',
                cancelText: 'Batal'
            });
            if (!proceed) return;
        }

        // ============ SUBMIT ============
        const btns = document.querySelectorAll('[onclick="saveSettings()"]');
        btns.forEach(b => {
            b.disabled = true;
            b.dataset.oldText = b.innerHTML;
            b.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
        });

        try {
            const r = await fetch(API + '/admin/settings', {
                method: 'PUT',
                headers: {
                    'Authorization': 'Bearer ' + TOKEN,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            });
            const d = await r.json();
            if (!d.success) throw new Error(d.error);

            toastSuccess('✅ ' + d.message + ' — harga langsung aktif di website');
        } catch (e) {
            toastError('Gagal simpan: ' + e.message);
        } finally {
            btns.forEach(b => {
                b.disabled = false;
                b.innerHTML = b.dataset.oldText || '<i class="fas fa-save"></i> Simpan Semua';
            });
        }
    }

    loadSettings();
</script>

</div>
</body>

</html>