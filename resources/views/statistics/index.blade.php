@extends('layouts.dashboard')

@section('title', 'Thống kê')

@push('styles')
<style>
    /* ── STATISTICS PAGE ─────────────────────────────────────────── */
    .stat-page {
        padding: 0;
    }

    .stat-page-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 24px;
    }

    .stat-page-title {
        font-size: 20px;
        font-weight: 600;
        color: var(--ink);
        line-height: 1.2;
    }

    .stat-page-sub {
        font-size: 13px;
        color: var(--ink-mute);
        margin-top: 4px;
    }

    .stat-year-form {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .stat-year-form label {
        font-size: 12px;
        font-weight: 500;
        color: var(--ink-mute);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        white-space: nowrap;
    }

    /* Stat cards */
    .stat-cards {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
        margin-bottom: 20px;
    }

    @media (max-width: 900px) {
        .stat-cards {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 560px) {
        .stat-cards {
            grid-template-columns: 1fr;
        }
    }

    .stat-card {
        background: var(--canvas);
        border: 1px solid var(--hairline-cool);
        border-radius: var(--r-lg);
        padding: 18px 20px;
        display: flex;
        align-items: center;
        gap: 14px;
        transition: border-color .15s;
    }

    .stat-card:hover {
        border-color: var(--ink-mute);
    }

    .stat-card-icon {
        width: 44px;
        height: 44px;
        border-radius: var(--r-md);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }

    .stat-card-icon.blue {
        background: #eff6ff;
    }

    .stat-card-icon.green {
        background: #f0fdf4;
    }

    .stat-card-icon.amber {
        background: #fffbeb;
    }

    .stat-card-icon.purple {
        background: #faf5ff;
    }

    .stat-card-icon.sky {
        background: #f0f9ff;
    }

    .stat-card-icon.rose {
        background: #fff1f2;
    }

    .stat-card-value {
        font-size: 22px;
        font-weight: 700;
        color: var(--ink);
        line-height: 1.1;
        letter-spacing: -0.5px;
    }

    .stat-card-label {
        font-size: 12px;
        color: var(--ink-mute);
        margin-top: 3px;
    }

    /* Section title */
    .stat-section-title {
        font-size: 11px;
        font-weight: 600;
        color: var(--ink-mute);
        text-transform: uppercase;
        letter-spacing: 0.6px;
        margin: 20px 0 12px;
    }

    /* Charts grid */
    .stat-charts-2col {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 12px;
        margin-bottom: 12px;
    }

    .stat-charts-2col-equal {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        margin-bottom: 12px;
    }

    @media (max-width: 860px) {

        .stat-charts-2col,
        .stat-charts-2col-equal {
            grid-template-columns: 1fr;
        }
    }

    .stat-chart-card {
        background: var(--canvas);
        border: 1px solid var(--hairline-cool);
        border-radius: var(--r-lg);
        padding: 18px 20px;
    }

    .stat-chart-title {
        font-size: 13px;
        font-weight: 600;
        color: var(--ink);
        margin: 0 0 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .stat-chart-sub {
        font-size: 11px;
        font-weight: 400;
        color: var(--ink-mute);
    }

    .stat-chart-wrap {
        position: relative;
    }

    /* Doughnut legend */
    .stat-donut-legend {
        display: flex;
        gap: 16px;
        justify-content: center;
        margin-top: 12px;
        font-size: 12px;
        color: var(--ink-mute);
    }

    .stat-donut-legend strong {
        color: var(--ink);
    }

    /* Progress bars */
    .stat-progress-row {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .stat-progress-label {
        display: flex;
        justify-content: space-between;
        font-size: 12px;
        color: var(--ink);
        margin-bottom: 4px;
    }

    .stat-progress-label span:last-child {
        color: var(--ink-mute);
    }

    .stat-progress-bar {
        height: 7px;
        background: var(--canvas-soft);
        border-radius: 99px;
        overflow: hidden;
    }

    .stat-progress-fill {
        height: 100%;
        border-radius: 99px;
        background: var(--primary);
    }

    .stat-progress-fill.over {
        background: #10b981;
    }

    /* Chart loading placeholder */
    .stat-chart-loading {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 160px;
        color: var(--ink-mute);
        font-size: 13px;
    }
</style>
@endpush

@section('content')
@include('participants._styles')

{{-- Data bridge PHP → JS, không dùng Blade directive trong
<script> --}}
    <script type="application/json" id="stat-data">{
        "monthlyDonations": @json($monthlyDonations),
    "participantLabels": @json($participantsByProject->pluck('name')->values()),
    "participantCounts": @json($participantsByProject->pluck('count')->values()),
        "moneyCount": {{ $moneyCount }},
        "goodsCount": {{ $goodsCount }},
    "progressLabels": @json($projectProgress->pluck('name')->values()),
    "progressValues": @json($projectProgress->pluck('progress')->values())
}</script>

<div class="stat-page">

    {{-- Header --}}
    <div class="stat-page-header">
        <div>
            <h1 class="stat-page-title">Thống kê & Báo cáo</h1>
            <p class="stat-page-sub">Tổng quan hoạt động thiện nguyện</p>
        </div>
        <form method="GET" action="{{ route('statistics.index') }}" class="stat-year-form">
            <label for="year">Năm</label>
            <select name="year" id="year" class="p-input" style="width:100px" onchange="this.form.submit()">
                @foreach($availableYears as $y)
                <option value="{{ $y }}" @selected($y==$year)>{{ $y }}</option>
                @endforeach
            </select>
        </form>
    </div>

    {{-- Stat Cards --}}
    <div class="stat-cards">
        <div class="stat-card">
            <div class="stat-card-icon blue">📁</div>
            <div>
                <div class="stat-card-value">{{ $totalProjects }}</div>
                <div class="stat-card-label">Tổng dự án</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-card-icon green">🟢</div>
            <div>
                <div class="stat-card-value">{{ $activeProjects }}</div>
                <div class="stat-card-label">Đang hoạt động</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-card-icon rose">✅</div>
            <div>
                <div class="stat-card-value">{{ $completedProjects }}</div>
                <div class="stat-card-label">Dự án hoàn thành</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-card-icon amber">💰</div>
            <div>
                <div class="stat-card-value">{{ number_format($totalDonationMoney) }}đ</div>
                <div class="stat-card-label">Tổng đóng góp tiền</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-card-icon purple">👥</div>
            <div>
                <div class="stat-card-value">{{ $totalParticipants }}</div>
                <div class="stat-card-label">Tình nguyện viên</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-card-icon sky">⏱️</div>
            <div>
                <div class="stat-card-value">{{ number_format($totalHours) }}h</div>
                <div class="stat-card-label">Tổng giờ đóng góp</div>
            </div>
        </div>
    </div>

    {{-- Row 1: Đóng góp --}}
    <div class="stat-section-title">Đóng góp — {{ $year }}</div>
    <div class="stat-charts-2col">
        <div class="stat-chart-card">
            <div class="stat-chart-title">
                Đóng góp tiền theo tháng
                <span class="stat-chart-sub">đơn vị: VNĐ</span>
            </div>
            <div class="stat-chart-wrap">
                <div class="stat-chart-loading" id="loading-monthly">Đang tải biểu đồ…</div>
                <canvas id="chartMonthly" height="110" style="display:none"></canvas>
            </div>
        </div>
        <div class="stat-chart-card">
            <div class="stat-chart-title">Tỷ lệ loại đóng góp</div>
            <div class="stat-chart-wrap">
                <div class="stat-chart-loading" id="loading-donut">Đang tải biểu đồ…</div>
                <canvas id="chartDonationType" height="180" style="display:none"></canvas>
            </div>
            <div class="stat-donut-legend">
                <span>💵 Tiền mặt: <strong>{{ $moneyCount }}</strong></span>
                <span>📦 Hiện vật: <strong>{{ $goodsCount }}</strong></span>
            </div>
        </div>
    </div>

    {{-- Row 2: Dự án & TNV --}}
    <div class="stat-section-title">Dự án & Tình nguyện viên</div>
    <div class="stat-charts-2col-equal">
        <div class="stat-chart-card">
            <div class="stat-chart-title">Tình nguyện viên theo dự án</div>
            <div class="stat-chart-wrap">
                <div class="stat-chart-loading" id="loading-participants">Đang tải biểu đồ…</div>
                <canvas id="chartParticipants" height="160" style="display:none"></canvas>
            </div>
        </div>
        <div class="stat-chart-card">
            <div class="stat-chart-title">Tiến độ gây quỹ</div>
            <div class="stat-progress-row">
                @forelse($projectProgress as $proj)
                <div>
                    <div class="stat-progress-label">
                        <span title="{{ $proj['name'] }}">{{ \Illuminate\Support\Str::limit($proj['name'], 26) }}</span>
                        <span>{{ number_format($proj['progress'], 1) }}%</span>
                    </div>
                    <div class="stat-progress-bar">
                        <div class="stat-progress-fill {{ $proj['progress'] >= 100 ? 'over' : '' }}"
                            style="width:{{ min($proj['progress'], 100) }}%"></div>
                    </div>
                </div>
                @empty
                <p style="font-size:13px;color:var(--ink-mute)">Chưa có dữ liệu.</p>
                @endforelse
            </div>
        </div>
    </div>

</div>

{{--
QUAN TRỌNG: Load Chart.js TRỰC TIẾP ở đây, KHÔNG dùng @push
để đảm bảo script chạy đúng sau khi canvas đã render.
Dùng onload callback thay vì DOMContentLoaded vì layout có thể
đã fire DOMContentLoaded trước khi section này được inject.
--}}
<script>
    window.addEventListener('load', function () {
    if (typeof Chart === 'undefined') {
        document.querySelectorAll('.stat-chart-loading').forEach(function (el) {
            el.textContent = 'Không tải được Chart.js.';
        });
    return;
    }

    var raw  = document.getElementById('stat-data').textContent;
    var data = JSON.parse(raw);

    var cs       = getComputedStyle(document.documentElement);
    var primary  = cs.getPropertyValue('--primary').trim()       || '#6366f1';
    var inkMute  = cs.getPropertyValue('--ink-mute').trim()      || '#6b7280';
    var hairline = cs.getPropertyValue('--hairline-cool').trim() || '#e5e7eb';

    Chart.defaults.color       = inkMute;
    Chart.defaults.borderColor = hairline;
    Chart.defaults.font.size   = 12;

    function showCanvas(canvasId, loadingId) {
        var canvas  = document.getElementById(canvasId);
    var loading = document.getElementById(loadingId);
    if (loading) loading.style.display = 'none';
    if (canvas)  canvas.style.display  = 'block';
    return canvas;
    }

    /* 1. Đóng góp theo tháng */
    var c1 = showCanvas('chartMonthly', 'loading-monthly');
    if (c1) {
        new Chart(c1, {
            type: 'bar',
            data: {
                labels: ['T1', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'T8', 'T9', 'T10', 'T11', 'T12'],
                datasets: [{
                    label: 'Đóng góp',
                    data: data.monthlyDonations,
                    backgroundColor: primary + '99',
                    borderColor: primary,
                    borderWidth: 1,
                    borderRadius: 5,
                    borderSkipped: false
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function (ctx) {
                                return ' ' + ctx.parsed.y.toLocaleString('vi-VN') + 'đ';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: hairline },
                        ticks: {
                            callback: function (v) {
                                if (v === 0) return '0';
                                if (v >= 1000000) return (v / 1000000).toFixed(1) + 'M';
                                if (v >= 1000) return (v / 1000).toFixed(0) + 'K';
                                return v;
                            }
                        }
                    },
                    x: { grid: { display: false } }
                }
            }
        });
    }

    /* 2. Tỷ lệ loại đóng góp */
    var c2 = showCanvas('chartDonationType', 'loading-donut');
    if (c2) {
        new Chart(c2, {
            type: 'doughnut',
            data: {
                labels: ['Tiền mặt', 'Hiện vật'],
                datasets: [{
                    data: [data.moneyCount, data.goodsCount],
                    backgroundColor: [primary, '#10b981'],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                cutout: '65%',
                plugins: { legend: { display: false } }
            }
        });
    }

    /* 3. TNV theo dự án */
    var c3 = showCanvas('chartParticipants', 'loading-participants');
    if (c3) {
        new Chart(c3, {
            type: 'bar',
            data: {
                labels: data.participantLabels,
                datasets: [{
                    label: 'Số TNV',
                    data: data.participantCounts,
                    backgroundColor: '#10b98199',
                    borderColor: '#10b981',
                    borderWidth: 1,
                    borderRadius: 4,
                    borderSkipped: false
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    x: {
                        beginAtZero: true,
                        grid: { color: hairline },
                        ticks: { stepSize: 1, precision: 0 }
                    },
                    y: { grid: { display: false } }
                }
            }
        });
    }
});
</script>
@endsection