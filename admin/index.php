<?php
$pageTitle = 'Dashboard';
$pageSubtitle = 'Ringkasan sistem Clipora';
require __DIR__ . '/includes/admin_header.php';
?>

<!-- ============ STATS GRID ============ -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon"><i class="fas fa-users"></i></div>
        <div class="stat-label">Total Users</div>
        <div class="stat-value loading" id="statUsers">—</div>
    </div>
    <div class="stat-card blue">
        <div class="stat-icon"><i class="fas fa-film"></i></div>
        <div class="stat-label">Total Videos</div>
        <div class="stat-value loading" id="statVideos">—</div>
    </div>
    <div class="stat-card purple">
        <div class="stat-icon"><i class="fas fa-clock"></i></div>
        <div class="stat-label">Pending Langganan</div>
        <div class="stat-value loading" id="statPending">—</div>
    </div>
    <div class="stat-card green">
        <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
        <div class="stat-label">Langganan Aktif</div>
        <div class="stat-value loading" id="statActive">—</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon"><i class="fas fa-wallet"></i></div>
        <div class="stat-label">Total Revenue</div>
        <div class="stat-value loading" id="statRevenue">—</div>
    </div>
    <div class="stat-card red">
        <div class="stat-icon"><i class="fas fa-user-plus"></i></div>
        <div class="stat-label">User Baru Hari Ini</div>
        <div class="stat-value loading" id="statNew">—</div>
    </div>
</div>

<!-- ============ CHARTS SECTION ============ -->
<div class="charts-grid">

    <div class="card chart-card chart-wide">
        <div class="card-header">
            <div>
                <h3><i class="fas fa-chart-line"></i> Aktivitas 7 Hari Terakhir</h3>
                <p>User baru dan video diproses per hari</p>
            </div>
            <div class="chart-legend">
                <span><span class="legend-dot" style="background:#f59e0b"></span> Users</span>
                <span><span class="legend-dot" style="background:#06b6d4"></span> Videos</span>
            </div>
        </div>
        <div class="chart-wrap">
            <canvas id="chartActivity"></canvas>
        </div>
    </div>

    <div class="card chart-card">
        <div class="card-header">
            <div>
                <h3><i class="fas fa-chart-pie"></i> Distribusi Plan</h3>
                <p>Sebaran user per paket</p>
            </div>
        </div>
        <div class="chart-wrap chart-doughnut">
            <canvas id="chartPlan"></canvas>
        </div>
    </div>

    <div class="card chart-card chart-wide">
        <div class="card-header">
            <div>
                <h3><i class="fas fa-chart-bar"></i> Revenue 6 Bulan Terakhir</h3>
                <p>Total pendapatan dari langganan aktif</p>
            </div>
        </div>
        <div class="chart-wrap">
            <canvas id="chartRevenue"></canvas>
        </div>
    </div>

    <div class="card chart-card">
        <div class="card-header">
            <div>
                <h3><i class="fas fa-bolt"></i> Ringkasan Hari Ini</h3>
                <p>Metrik cepat</p>
            </div>
        </div>
        <div class="quick-stats">
            <div class="quick-stat">
                <div class="quick-icon" style="background:rgba(245,158,11,0.12);color:#f59e0b;">
                    <i class="fas fa-user-plus"></i>
                </div>
                <div>
                    <div class="quick-value" id="qUsers">—</div>
                    <div class="quick-label">User Baru Hari Ini</div>
                </div>
            </div>
            <div class="quick-stat">
                <div class="quick-icon" style="background:rgba(6,182,212,0.12);color:#06b6d4;">
                    <i class="fas fa-film"></i>
                </div>
                <div>
                    <div class="quick-value" id="qVideos">—</div>
                    <div class="quick-label">Video Hari Ini</div>
                </div>
            </div>
            <div class="quick-stat">
                <div class="quick-icon" style="background:rgba(34,197,94,0.12);color:#22c55e;">
                    <i class="fas fa-wallet"></i>
                </div>
                <div>
                    <div class="quick-value" id="qRevenue">—</div>
                    <div class="quick-label">Revenue Bulan Ini</div>
                </div>
            </div>
            <div class="quick-stat">
                <div class="quick-icon" style="background:rgba(139,92,246,0.12);color:#8b5cf6;">
                    <i class="fas fa-crown"></i>
                </div>
                <div>
                    <div class="quick-value" id="qPremium">—</div>
                    <div class="quick-label">User Premium</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ============ BROADCAST ============ -->
<div class="card">
    <div class="card-header">
        <div>
            <h3><i class="fas fa-bullhorn"></i> Broadcast Notifikasi</h3>
            <p>Kirim notifikasi ke semua user aktif</p>
        </div>
    </div>
    <div class="bc-form">
        <input type="text" id="bcTitle" placeholder="Judul notifikasi..." maxlength="100">
        <textarea id="bcMessage" rows="3" placeholder="Tulis pesan Anda di sini..." maxlength="500"></textarea>
        <div>
            <button class="btn btn-primary" onclick="sendBroadcast(event)">
                <i class="fas fa-paper-plane"></i> Kirim ke Semua User
            </button>
        </div>
    </div>
</div>

<!-- ============ CHART.JS CDN ============ -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<style>
.charts-grid{
    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:16px;
    margin-bottom:22px;
}
.chart-card{padding:22px;}
.chart-wide{grid-column:span 2;}
.chart-wrap{
    position:relative;
    height:260px;
    margin-top:8px;
}
.chart-doughnut{height:260px;}

.chart-legend{
    display:flex;
    gap:16px;
    font-size:0.75rem;
    color:var(--muted);
}
.chart-legend span{display:flex;align-items:center;gap:6px;}
.legend-dot{
    width:8px;height:8px;
    border-radius:50%;
    display:inline-block;
}

.quick-stats{
    display:flex;
    flex-direction:column;
    gap:14px;
    margin-top:8px;
}
.quick-stat{display:flex;align-items:center;gap:12px;}
.quick-icon{
    width:40px;height:40px;
    border-radius:11px;
    display:flex;align-items:center;justify-content:center;
    font-size:1rem;flex-shrink:0;
}
.quick-value{
    font-size:1.3rem;font-weight:800;
    color:var(--text);line-height:1;margin-bottom:2px;
}
.quick-label{font-size:0.72rem;color:var(--muted);}

@media(max-width:1100px){
    .charts-grid{grid-template-columns:1fr 1fr;}
    .chart-wide{grid-column:span 2;}
}
@media(max-width:768px){
    .charts-grid{grid-template-columns:1fr;}
    .chart-wide{grid-column:span 1;}
    .chart-legend{display:none;}
}
</style>

<script>
const API = <?= json_encode($apiUrl) ?>;
const TOKEN = <?= json_encode($authToken) ?>;

Chart.defaults.color = '#94a3b8';
Chart.defaults.borderColor = 'rgba(255,255,255,0.06)';
Chart.defaults.font.family = "'Inter', sans-serif";
Chart.defaults.font.size = 11;

let charts = {};

// ============ LOAD STATS ============
async function loadStats() {
    try {
        const r = await fetch(API + '/admin/stats', {
            headers: { 'Authorization': 'Bearer ' + TOKEN }
        });
        const d = await r.json();
        if (!d.success) throw new Error(d.error);

        const animate = (el, target, prefix = '') => {
            el.classList.remove('loading');
            el.textContent = prefix + Number(target).toLocaleString('id-ID');
        };
        animate(document.getElementById('statUsers'), d.stats.total_users);
        animate(document.getElementById('statVideos'), d.stats.total_videos);
        animate(document.getElementById('statPending'), d.stats.pending_subs);
        animate(document.getElementById('statActive'), d.stats.active_subs);
        animate(document.getElementById('statRevenue'), d.stats.total_revenue, 'Rp');
        animate(document.getElementById('statNew'), d.stats.new_users_today);
    } catch (e) {
        console.error('Stats error:', e);
        document.querySelectorAll('.stat-value').forEach(el => {
            el.classList.remove('loading');
            el.textContent = '—';
        });
    }
}

// ============ LOAD CHARTS ============
async function loadCharts() {
    try {
        const r = await fetch(API + '/admin/charts', {
            headers: { 'Authorization': 'Bearer ' + TOKEN }
        });
        const d = await r.json();
        if (!d.success) throw new Error(d.error);

        renderActivityChart(d.usersDaily, d.videosDaily);
        renderPlanChart(d.planDistribution);
        renderRevenueChart(d.revenueMonthly);
        renderQuickStats(d.summary);
    } catch (e) {
        console.error('Charts error:', e);
    }
}

// ============ ACTIVITY CHART ============
function renderActivityChart(users, videos) {
    const ctx = document.getElementById('chartActivity');
    if (!ctx) return;
    if (charts.activity) charts.activity.destroy();

    const labels = users.map(u => u.label);

    charts.activity = new Chart(ctx, {
        type: 'line',
        data: {
            labels,
            datasets: [
                {
                    label: 'Users',
                    data: users.map(u => u.count),
                    borderColor: '#f59e0b',
                    backgroundColor: 'rgba(245,158,11,0.1)',
                    tension: 0.4,
                    fill: true,
                    borderWidth: 2,
                    pointBackgroundColor: '#f59e0b',
                    pointBorderColor: '#0a0a0f',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6
                },
                {
                    label: 'Videos',
                    data: videos.map(v => v.count),
                    borderColor: '#06b6d4',
                    backgroundColor: 'rgba(6,182,212,0.1)',
                    tension: 0.4,
                    fill: true,
                    borderWidth: 2,
                    pointBackgroundColor: '#06b6d4',
                    pointBorderColor: '#0a0a0f',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#16161f',
                    borderColor: 'rgba(245,158,11,0.3)',
                    borderWidth: 1,
                    padding: 10,
                    titleColor: '#e8e8ef',
                    bodyColor: '#a0a0b0'
                }
            },
            scales: {
                x: { grid: { display: false }, ticks: { font: { size: 10 } } },
                y: { beginAtZero: true, ticks: { precision: 0 } }
            },
            interaction: { intersect: false, mode: 'index' }
        }
    });
}

// ============ PLAN CHART ============
function renderPlanChart(plans) {
    const ctx = document.getElementById('chartPlan');
    if (!ctx) return;
    if (charts.plan) charts.plan.destroy();

    const colorMap = { 'free': '#6b7280', 'pro': '#f59e0b', 'business': '#8b5cf6' };
    const labels = plans.map(p => p.label.toUpperCase());
    const data = plans.map(p => p.count);
    const colors = plans.map(p => colorMap[p.label] || '#06b6d4');

    if (data.length === 0 || data.every(d => d === 0)) {
        ctx.parentElement.innerHTML = '<div class="empty-state" style="padding:60px 20px;"><i class="fas fa-chart-pie"></i><p>Belum ada user</p></div>';
        return;
    }

    charts.plan = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels,
            datasets: [{
                data,
                backgroundColor: colors,
                borderColor: '#16161f',
                borderWidth: 4,
                hoverOffset: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '65%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 16,
                        usePointStyle: true,
                        pointStyle: 'circle',
                        font: { size: 11, weight: '600' }
                    }
                },
                tooltip: {
                    backgroundColor: '#16161f',
                    borderColor: 'rgba(245,158,11,0.3)',
                    borderWidth: 1,
                    padding: 10,
                    callbacks: {
                        label: (ctx) => {
                            const total = ctx.dataset.data.reduce((a, b) => a + b, 0);
                            const pct = ((ctx.parsed / total) * 100).toFixed(1);
                            return ' ' + ctx.label + ': ' + ctx.parsed + ' (' + pct + '%)';
                        }
                    }
                }
            }
        }
    });
}

// ============ REVENUE CHART ============
function renderRevenueChart(months) {
    const ctx = document.getElementById('chartRevenue');
    if (!ctx) return;
    if (charts.revenue) charts.revenue.destroy();

    const labels = months.map(m => m.label);
    const data = months.map(m => m.revenue);

    charts.revenue = new Chart(ctx, {
        type: 'bar',
        data: {
            labels,
            datasets: [{
                label: 'Revenue',
                data,
                backgroundColor: (context) => {
                    const chart = context.chart;
                    const { ctx: canvas, chartArea } = chart;
                    if (!chartArea) return 'rgba(245,158,11,0.6)';
                    const gradient = canvas.createLinearGradient(0, chartArea.bottom, 0, chartArea.top);
                    gradient.addColorStop(0, 'rgba(245,158,11,0.3)');
                    gradient.addColorStop(1, 'rgba(245,158,11,0.9)');
                    return gradient;
                },
                borderColor: '#f59e0b',
                borderWidth: 0,
                borderRadius: 8,
                borderSkipped: false,
                maxBarThickness: 50
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#16161f',
                    borderColor: 'rgba(245,158,11,0.3)',
                    borderWidth: 1,
                    padding: 10,
                    titleColor: '#e8e8ef',
                    bodyColor: '#a0a0b0',
                    callbacks: {
                        label: (ctx) => ' Rp' + Number(ctx.parsed.y).toLocaleString('id-ID')
                    }
                }
            },
            scales: {
                x: { grid: { display: false }, ticks: { font: { size: 10 } } },
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: (val) => 'Rp' + (val >= 1000 ? (val / 1000) + 'k' : val)
                    }
                }
            }
        }
    });
}

// ============ QUICK STATS ============
function renderQuickStats(s) {
    if (!s) return;
    document.getElementById('qUsers').textContent = s.users_today || 0;
    document.getElementById('qVideos').textContent = s.videos_today || 0;
    document.getElementById('qRevenue').textContent = 'Rp' + Number(s.revenue_this_month || 0).toLocaleString('id-ID');
    document.getElementById('qPremium').textContent = s.premium_users || 0;
}

// ============ BROADCAST ============
async function sendBroadcast(evt) {
    const title = document.getElementById('bcTitle').value.trim();
    const message = document.getElementById('bcMessage').value.trim();

    if (!title || !message) {
        toastError('Judul & pesan wajib diisi');
        return;
    }

    const ok = await showConfirm({
        title: 'Broadcast Notifikasi?',
        message: 'Notifikasi akan dikirim ke SEMUA user aktif.',
        type: 'warning',
        okText: 'Ya, Kirim',
        cancelText: 'Batal'
    });
    if (!ok) return;

    const btn = evt ? evt.target.closest('button') : null;
    if (btn) {
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengirim...';
    }

    try {
        const r = await fetch(API + '/admin/broadcast', {
            method: 'POST',
            headers: { 'Authorization': 'Bearer ' + TOKEN, 'Content-Type': 'application/json' },
            body: JSON.stringify({ title, message })
        });
        const d = await r.json();
        if (!d.success) throw new Error(d.error);
        toastSuccess(d.message);
        document.getElementById('bcTitle').value = '';
        document.getElementById('bcMessage').value = '';
    } catch (e) {
        toastError('Gagal: ' + e.message);
    } finally {
        if (btn) {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-paper-plane"></i> Kirim ke Semua User';
        }
    }
}

// ============ INIT ============
loadStats();
loadCharts();

setInterval(() => {
    loadStats();
    loadCharts();
}, 5 * 60 * 1000);
</script>

</div></body></html>