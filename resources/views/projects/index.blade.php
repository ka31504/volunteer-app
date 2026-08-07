@extends('layouts.dashboard')

@section('title', 'Dự án')

@include('participants._styles')

@section('content')

{{-- PAGE HEADER --}}
<div class="p-header">
    <div>
        <div class="p-header-title">Dự án</div>
        <div class="p-header-sub">Quản lý các dự án thiện nguyện</div>
    </div>
    @if(auth()->user()->isAdmin() || auth()->user()->isEditor())
    <a href="{{ route('projects.create') }}" class="p-btn p-btn-primary">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path d="M12 5v14M5 12h14" />
        </svg>
        Thêm dự án mới
    </a>
    @endif
</div>

@if(session('success'))
<div class="p-alert p-alert-success">{{ session('success') }}</div>
@endif

<div class="p-card">

    {{-- FILTER BAR --}}
    <form method="GET" action="{{ route('projects.index') }}">
        <div class="p-filter">
            <div class="p-filter-group grow">
                <label for="search">Tìm kiếm</label>
                <input id="search" type="text" name="search" class="p-input" placeholder="Tên dự án, mô tả..."
                    value="{{ request('search') }}">
            </div>

            <div class="p-filter-group">
                <label for="status">Trạng thái</label>
                <select id="status" name="status" class="p-input" style="width:200px">
                    <option value="">Tất cả trạng thái</option>
                    <option value="planning" @selected(request('status')==='planning' )>Chuẩn bị</option>
                    <option value="ongoing" @selected(request('status')==='ongoing' )>Đang thực hiện</option>
                    <option value="completed" @selected(request('status')==='completed' )>Hoàn thành</option>
                    <option value="closed" @selected(request('status')==='closed' )>Đã đóng</option>
                </select>
            </div>

            <button type="submit" class="p-btn p-btn-outline">Lọc</button>

            @if(request()->hasAny(['search', 'status', 'sort', 'direction']))
            <a href="{{ route('projects.index') }}" class="p-btn p-btn-ghost">Xoá lọc & sắp xếp</a>
            @endif
        </div>
    </form>

    <div class="p-table-wrap">
        <table class="p-table">
            <thead>
                <tr>
                    <th style="width:40px">#</th>
                    <th>{!! sortLink('name', 'Tên dự án') !!}</th>
                    <th>{!! sortLink('status', 'Trạng thái') !!}</th>
                    <th>{!! sortLink('target_amount', 'Mục tiêu') !!}</th>
                    <th>Tiến độ</th>
                    <th>{!! sortLink('start_date', 'Thời gian') !!}</th>
                    <th style="width:110px">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($projects as $project)
                <tr>
                    <td style="color:var(--ink-mute);font-size:12px">{{ $loop->iteration }}</td>
                    <td>
                        <div style="font-weight:500">{{ $project->name }}</div>
                        <div class="mute">{{ Str::limit($project->description, 80) }}</div>
                    </td>
                    <td>
                        @php
                        $statusClass = match($project->status) {
                        'planning' => 'p-badge-yellow',
                        'ongoing' => 'p-badge-green',
                        'completed'=> 'p-badge-green',
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
                    </td>
                    <td class="font-medium">{{ number_format($project->target_amount) }}đ</td>
                    <td>
                        <div class="flex items-center gap-3 w-52">
                            <div class="h-2 bg-[var(--hairline-cool)] rounded-full flex-1 overflow-hidden">
                                <div class="h-2 bg-[var(--primary)] rounded-full transition-all"
                                    style="width: {{ $project->progressPercentage() }}%"></div>
                            </div>
                            <span class="text-sm font-medium">{{ number_format($project->progressPercentage(), 2, ',',
                                '.') }}%</span>
                        </div>
                    </td>
                    <td class="text-sm">
                        {{ $project->start_date?->format('d/m/Y') }} — {{ $project->end_date?->format('d/m/Y') }}
                    </td>
                    <td>
                        <div class="p-actions">
                            <a href="{{ route('projects.show', $project) }}" class="p-btn-icon" title="Xem">
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                    <circle cx="12" cy="12" r="3" />
                                </svg>
                            </a>
                            @if(auth()->user()->isAdmin() || auth()->user()->isEditor())
                            <a href="{{ route('projects.edit', $project) }}" class="p-btn-icon" title="Sửa">
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                                </svg>
                            </a>
                            @endif
                            @if(auth()->user()->isAdmin())
                            <form action="{{ route('projects.destroy', $project) }}" method="POST"
                                onsubmit="return confirm('Xác nhận xoá dự án này?')" style="display:contents">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-btn-icon danger" title="Xoá">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"
                                        viewBox="0 0 24 24">
                                        <polyline points="3 6 5 6 21 6" />
                                        <path d="M19 6l-1 14H6L5 6" />
                                        <path d="M10 11v6M14 11v6" />
                                        <path d="M9 6V4h6v2" />
                                    </svg>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7">
                        <div class="p-empty">Chưa có dự án nào.</div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($projects->hasPages())
    <div class="p-card-footer" style="display:flex;align-items:center;justify-content:space-between">
        <span style="font-size:12px;color:var(--ink-mute)">
            {{ $projects->firstItem() }}–{{ $projects->lastItem() }} / {{ $projects->total() }} dự án
        </span>
        {{ $projects->links() }}
    </div>
    @endif

</div>

@endsection
