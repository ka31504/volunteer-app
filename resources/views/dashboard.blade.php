@extends('layouts.dashboard')

@section('title', 'Dashboard')

@push('styles')
<style>
/* STATS CARDS */
.stats-row { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 28px; }
.stat-card {
    background: #fff; border: 1px solid var(--hairline-cool);
    border-radius: var(--r-lg); padding: 20px 24px;
    display: flex; flex-direction: column; gap: 8px;
}
.stat-card-label { font-size: 12px; font-weight: 500; color: var(--ink-mute); text-transform: uppercase; letter-spacing: 0.5px; }
.stat-card-num { font-size: 28px; font-weight: 500; letter-spacing: -0.5px; color: var(--ink); line-height: 1; }
.stat-card-sub { font-size: 12px; color: var(--ink-faint); }
.stat-card-icon { font-size: 20px; margin-bottom: 4px; }

/* SECTION */
.db-section { margin-bottom: 28px; }
.db-section-header {
    display: flex; align-items: center; justify-content: space-between;
    margin-bottom: 14px;
}
.db-section-title { font-size: 14px; font-weight: 500; color: var(--ink); }
.db-section-link { font-size: 13px; color: var(--primary); text-decoration: none; }
.db-section-link:hover { text-decoration: underline; }

/* TABLE */
.db-table-wrap {
    background: #fff; border: 1px solid var(--hairline-cool);
    border-radius: var(--r-lg); overflow: hidden;
}
.db-table { width: 100%; border-collapse: collapse; font-size: 13px; }
.db-table thead th {
    text-align: left; padding: 10px 16px;
    font-size: 11px; font-weight: 500; color: var(--ink-mute);
    text-transform: uppercase; letter-spacing: 0.5px;
    background: var(--canvas-soft); border-bottom: 1px solid var(--hairline-cool);
}
.db-table tbody td { padding: 11px 16px; color: var(--ink); border-bottom: 1px solid var(--hairline-cool); }
.db-table tbody tr:last-child td { border-bottom: none; }
.db-table tbody tr:hover td { background: var(--canvas-soft); }

/* BADGES */
.badge { display: inline-block; padding: 2px 8px; border-radius: 999px; font-size: 11px; font-weight: 500; }
.badge-active   { background: #d1fae5; color: #065f46; }
.badge-done     { background: #f3f4f6; color: #6b7280; }
.badge-upcoming { background: #fef3c7; color: #92400e; }

/* PROGRESS */
.prog-wrap { display: flex; align-items: center; gap: 8px; }
.prog-bar { flex: 1; height: 4px; background: var(--hairline); border-radius: 2px; overflow: hidden; min-width: 60px; }
.prog-fill { height: 100%; background: var(--primary); border-radius: 2px; }
.prog-pct { font-size: 11px; color: var(--ink-mute); width: 48px; text-align: right; }

/* TWO COLS */
.db-two-col { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }

/* DONATION LIST */
.donation-list { background: #fff; border: 1px solid var(--hairline-cool); border-radius: var(--r-lg); overflow: hidden; }
.donation-item {
    display: flex; align-items: center; gap: 12px;
    padding: 12px 16px; border-bottom: 1px solid var(--hairline-cool);
    font-size: 13px;
}
.donation-item:last-child { border-bottom: none; }
.donation-avatar {
    width: 30px; height: 30px; border-radius: 50%;
    background: var(--canvas-soft); border: 1px solid var(--hairline);
    display: flex; align-items: center; justify-content:center;
    font-size: 11px; font-weight: 600; color: var(--ink-mute); flex-shrink: 0;
}
.donation-name { flex: 1; color: var(--ink); font-weight: 400; }
.donation-project { font-size: 11px; color: var(--ink-faint); }
.donation-amt { font-size: 13px; font-weight: 500; color: var(--primary); }
.donation-type { font-size: 11px; color: var(--ink-mute); background: var(--canvas-soft); border: 1px solid var(--hairline); padding: 2px 8px; border-radius: 999px; }

/* EMPTY */
.empty-state { text-align: center; padding: 40px 20px; color: var(--ink-mute); font-size: 13px; }
.empty-state-icon { font-size: 28px; margin-bottom: 8px; }

/* RESPONSIVE */
@media (max-width: 900px) {
    .stats-row { grid-template-columns: repeat(2, 1fr); }
    .db-two-col { grid-template-columns: 1fr; }
}
@media (max-width: 500px) {
    .stats-row { grid-template-columns: 1fr 1fr; }
}
</style>
@endpush

@section('content')

{{-- GREETING --}}
<div style="margin-bottom:24px">
    <div style="font-size:20px;font-weight:500;color:var(--ink);letter-spacing:-0.3px">
        Xin chào, {{ Auth::user()->name }} 👋
    </div>
    <div style="font-size:13px;color:var(--ink-mute);margin-top:4px">
        {{ now()->format('l, d/m/Y') }} — Đây là tổng quan hoạt động thiện nguyện
    </div>
</div>

{{-- STATS --}}
<div class="stats-row">
    <div class="stat-card">
        <div class="stat-card-icon">📋</div>
        <div class="stat-card-label">Tổng dự án</div>
        <div class="stat-card-num">{{ $totalProjects }}</div>
        <div class="stat-card-sub">{{ $activeProjects }} đang diễn ra</div>
    </div>
    <div class="stat-card">
        <div class="stat-card-icon">👥</div>
        <div class="stat-card-label">Tình nguyện viên</div>
        <div class="stat-card-num">{{ $totalParticipants }}</div>
        <div class="stat-card-sub">{{ $totalSponsors }} nhà tài trợ</div>
    </div>
    <div class="stat-card">
        <div class="stat-card-icon">💰</div>
        <div class="stat-card-label">Tổng đóng góp</div>
        <div class="stat-card-num">{{ number_format($totalMoney / 1000000, 1) }}M</div>
        <div class="stat-card-sub">{{ $totalDonations }} lượt đóng góp</div>
    </div>
    <div class="stat-card">
        <div class="stat-card-icon">✅</div>
        <div class="stat-card-label">Dự án hoàn thành</div>
        <div class="stat-card-num">{{ $doneProjects }}</div>
        <div class="stat-card-sub">{{ $upcomingProjects }} sắp diễn ra</div>
    </div>
</div>

{{-- PROJECTS TABLE --}}
{{-- PROJECTS TABLE --}}
<div class="db-section">
    <div class="db-section-header">
        <span class="db-section-title">Dự án gần đây</span>
        <a href="{{ route('projects.index') }}" class="db-section-link">Xem tất cả →</a>
    </div>
    
    <div class="db-table-wrap">
        @if($recentProjects->count())
        <table class="db-table">
            <thead>
                <tr>
                    <th>Tên dự án</th>
                    <th>Trạng thái</th>
                    <th>Đóng góp</th>
                    <th>Tiến độ</th>
                    <th>Thời gian</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentProjects as $project)
                <tr>
                    <td style="font-weight:500">{{ $project->name }}</td>
                    <td>
                        @php
                            $statusLabel = match($project->status) {
                                'planning' => 'Chuẩn bị',
                                'ongoing' => 'Đang diễn ra',
                                'completed' => 'Hoàn thành',
                                'closed' => 'Đã đóng',
                                default => ucfirst($project->status)
                            };
                            $statusClass = match($project->status) {
                                'planning' => 'bg-blue-100 text-blue-700',
                                'ongoing' => 'bg-amber-100 text-amber-700',
                                'completed' => 'bg-emerald-100 text-emerald-700',
                                'closed' => 'bg-gray-100 text-gray-700',
                                default => 'bg-gray-100 text-gray-700'
                            };
                        @endphp
                        <span class="badge {{ $statusClass }}">{{ $statusLabel }}</span>
                    </td>
                    <td>
                        {{ number_format($project->current_amount) }} đ
                    </td>
                    <td style="min-width:140px">
                        @php 
                            $pct = $project->progressPercentage();
                            $barPct = min($pct, 100);
                            $isOver = $pct > 100;
                            $fillColor = $isOver ? '#10b981' : 'var(--primary)';
                        @endphp
                        <div class="prog-wrap">
                            <div class="prog-bar">
                                <div class="prog-fill" style="width:{{ $barPct }}%; background:{{ $fillColor }}"></div>
                            </div>
                            <span class="prog-pct" style="width:48px; {{ $isOver ? 'color:#059669;font-weight:500' : '' }}">
                                {{ number_format($pct, 2) }}%
                            </span>
                        </div>
                    </td>
                    <td style="color:var(--ink-mute)">
                        {{ $project->start_date?->format('d/m/Y') }} 
                        - {{ $project->end_date?->format('d/m/Y') }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="empty-state">
            <div class="empty-state-icon">📋</div>
            Chưa có dự án nào. 
            <a href="{{ route('projects.create') }}" style="color:var(--primary)">Tạo dự án đầu tiên</a>
        </div>
        @endif
    </div>
</div>

{{-- TWO COLS: DONATIONS + PARTICIPANTS --}}
<div class="db-two-col">

    {{-- RECENT DONATIONS --}}
    <div class="db-section">
        <div class="db-section-header">
            <span class="db-section-title">Đóng góp gần đây</span>
            <a href="{{ route('donations.index') }}" class="db-section-link">Xem tất cả →</a>
        </div>
        <div class="donation-list">
            @if($recentDonations->count())
                @foreach($recentDonations as $d)
                <div class="donation-item">
                    <div style="flex:1;min-width:0">
                        <div class="donation-name">{{ $d->donor_name }}</div>
                        <div class="donation-project">{{ $d->project->name ?? '—' }}</div>
                    </div>
                    <div style="text-align:right">
                        @if($d->type === 'money')
                            <div class="donation-amt">{{ number_format($d->amount) }} đ</div>
                        @else
                            <div class="donation-amt" style="font-size:12px">{{ $d->goods_description }}</div>
                        @endif
                        <span class="donation-type">{{ $d->type === 'money' ? 'Tiền' : 'Hiện vật' }}</span>
                    </div>
                </div>
                @endforeach
            @else
            <div class="empty-state">
                <div class="empty-state-icon">💰</div>
                Chưa có đóng góp nào
            </div>
            @endif
        </div>
    </div>

    {{-- RECENT PARTICIPANTS --}}
    <div class="db-section">
        <div class="db-section-header">
            <span class="db-section-title">Tình nguyện viên mới</span>
            <a href="{{ route('participants.index') }}" class="db-section-link">Xem tất cả →</a>
        </div>
        <div class="donation-list">
            @if($recentParticipants->count())
                @foreach($recentParticipants as $p)
                <div class="donation-item">
                    <div style="flex:1;min-width:0">
                        <div class="donation-name">{{ $p->full_name }}</div>
                        <div class="donation-project">{{ $p->project->name ?? '—' }}</div>
                    </div>
                    <div style="text-align:right">
                        <span class="donation-type">
                            @if($p->role === 'volunteer') Tình nguyện viên
                            @elseif($p->role === 'sponsor') Nhà tài trợ
                            @else Tổ chức
                            @endif
                        </span>
                    </div>
                </div>
                @endforeach
            @else
            <div class="empty-state">
                <div class="empty-state-icon">👥</div>
                Chưa có thành viên nào
            </div>
            @endif
        </div>
    </div>

</div>

@endsection