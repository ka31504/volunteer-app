@extends('layouts.dashboard')

@section('title', $project->name)

@include('participants._styles')

@section('content')

{{-- HEADER --}}
<div class="p-header">
    <div>
        <div class="p-header-title">{{ $project->name }}</div>
        <div class="p-breadcrumb">
            <a href="{{ route('projects.index') }}">Dự án</a>
            &rsaquo; Chi tiết
        </div>
    </div>
    <div style="display:flex;gap:8px;align-items:center">
        <a href="{{ route('projects.edit', $project) }}" class="p-btn p-btn-outline">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
            Chỉnh sửa
        </a>
        <form action="{{ route('projects.destroy', $project) }}" method="POST"
              onsubmit="return confirm('Xóa dự án này?')" style="display:contents">
            @csrf @method('DELETE')
            <button type="submit" class="p-btn p-btn-danger">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4h6v2"/></svg>
                Xoá
            </button>
        </form>
    </div>
</div>

{{-- STAT MINI --}}
<div class="p-stat-row" style="margin-bottom:20px">
    <div class="p-stat">
        <div class="p-stat-num">{{ number_format($project->target_amount) }}đ</div>
        <div class="p-stat-label">Mục tiêu</div>
    </div>
    <div class="p-stat">
        <div class="p-stat-num">{{ number_format($project->current_amount) }}đ</div>
        <div class="p-stat-label">Đã nhận</div>
    </div>
    <div class="p-stat">
        <div class="p-stat-num">{{ number_format($project->progressPercentage(), 2, ',', '.') }}%</div>
        <div class="p-stat-label">Tiến độ</div>
    </div>
</div>

{{-- DETAIL CARDS --}}
<div class="p-detail-grid">

    <div class="p-card">
        <div class="p-card-header">Thông tin dự án</div>
        <div class="p-card-body">
            <dl class="p-dl">
                <div class="p-dl-row">
                    <dt class="p-dt">Trạng thái</dt>
                    <dd class="p-dd">
                        @php 
                            $statusClass = match($project->status) {
                                'planning' => 'p-badge-yellow',
                                'ongoing' => 'p-badge-green',
                                'completed' => 'p-badge-green',
                                'closed' => 'p-badge-gray',
                                default => 'p-badge-gray'
                            };
                        @endphp
                        <span class="p-badge {{ $statusClass }}">
                            {{ match($project->status) {
                                'planning' => 'Chuẩn bị',
                                'ongoing' => 'Đang thực hiện',
                                'completed' => 'Hoàn thành',
                                'closed' => 'Đã đóng',
                                default => ucfirst($project->status)
                            } }}
                        </span>
                    </dd>
                </div>
                <div class="p-dl-row">
                    <dt class="p-dt">Mô tả</dt>
                    <dd class="p-dd whitespace-pre-line">{{ $project->description }}</dd>
                </div>
            </dl>
        </div>
    </div>

    <div class="p-card">
        <div class="p-card-header">Thời gian & Tiến độ</div>
        <div class="p-card-body">
            <dl class="p-dl">
                <div class="p-dl-row">
                    <dt class="p-dt">Ngày bắt đầu</dt>
                    <dd class="p-dd">{{ $project->start_date?->format('d/m/Y') }}</dd>
                </div>
                <div class="p-dl-row">
                    <dt class="p-dt">Ngày kết thúc</dt>
                    <dd class="p-dd">{{ $project->end_date?->format('d/m/Y') }}</dd>
                </div>
                <div class="p-dl-row">
                    <dt class="p-dt">Tiến độ quyên góp</dt>
                    <dd class="p-dd">
                        <div class="flex items-center gap-3">
                            <div class="h-2.5 bg-[var(--hairline-cool)] rounded-full flex-1">
                                <div class="h-2.5 bg-[var(--primary)] rounded-full" 
                                     style="width: {{ $project->progressPercentage(),2 }}%"></div>
                            </div>
                            <span class="font-semibold">{{ number_format($project->progressPercentage(), 2, ',', '.') }}%</span>
                        </div>
                    </dd>
                </div>
            </dl>
        </div>
    </div>

</div>

@endsection