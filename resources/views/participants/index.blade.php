@extends('layouts.dashboard')

@section('title', 'Tình nguyện viên')

@include('participants._styles')

@section('content')

{{-- PAGE HEADER --}}
<div class="p-header">
    <div>
        <div class="p-header-title">Tình nguyện viên</div>
        <div class="p-header-sub">Quản lý danh sách tình nguyện viên các dự án</div>
    </div>
    @if(auth()->user()->isAdmin() || auth()->user()->isEditor())
    <a href="{{ route('participants.create') }}" class="p-btn p-btn-primary">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path d="M12 5v14M5 12h14" />
        </svg>
        Thêm tình nguyện viên
    </a>
    @endif
</div>

@if(session('success'))
<div class="p-alert p-alert-success">{{ session('success') }}</div>
@endif

<div class="p-card">

    <form method="GET" action="{{ route('participants.index') }}">
        <div class="p-filter">
            <div class="p-filter-group grow">
                <label for="search">Tìm kiếm</label>
                <input id="search" type="text" name="search" class="p-input" placeholder="Tên, email, số điện thoại..."
                    value="{{ request('search') }}">
            </div>
            <div class="p-filter-group">
                <label for="project_id">Dự án</label>
                <select id="project_id" name="project_id" class="p-input" style="width:180px">
                    <option value="">Tất cả dự án</option>
                    @foreach($projects as $p)
                    <option value="{{ $p->id }}" @selected(request('project_id')==$p->id)>
                        {{ $p->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="p-filter-group">
                <label for="status">Trạng thái</label>
                <select id="status" name="status" class="p-input" style="width:150px">
                    <option value="">Tất cả</option>
                    <option value="active" @selected(request('status')==='active' )>Đang hoạt động</option>
                    <option value="pending" @selected(request('status')==='pending' )>Chờ xác nhận</option>
                    <option value="inactive" @selected(request('status')==='inactive' )>Ngưng hoạt động</option>
                </select>
            </div>
            <button type="submit" class="p-btn p-btn-outline">Lọc</button>

            @if(request()->hasAny(['search', 'project_id', 'status', 'sort', 'direction']))
            <a href="{{ route('participants.index') }}" class="p-btn p-btn-ghost">Xoá lọc & sắp xếp</a>
            @endif
        </div>
    </form>

    <div class="p-table-wrap">
        <table class="p-table">
            <thead>
                <tr>
                    <th style="width:40px">#</th>
                    <th>{!! sortLink('full_name', 'Họ và tên') !!}</th>
                    <th>Liên hệ</th>
                    <th>Dự án</th>
                    <th>{!! sortLink('role', 'Vai trò') !!}</th>
                    <th class="text-right">{!! sortLink('hours_contributed', 'Giờ TV') !!}</th>
                    <th>{!! sortLink('joined_at', 'Ngày tham gia') !!}</th>
                    <th>Trạng thái</th>
                    <th style="width:90px">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($participants as $participant)
                <tr>
                    <td style="color:var(--ink-mute);font-size:12px">{{ $loop->iteration }}</td>
                    <td>
                        <div style="font-weight:500">{{ $participant->full_name }}</div>
                        @if($participant->age)
                        <div class="mute">{{ $participant->age }} tuổi · {{ $participant->gender_label }}</div>
                        @endif
                    </td>
                    <td>
                        @if($participant->email)<div>{{ $participant->email }}</div>@endif
                        @if($participant->phone)<div class="mute">{{ $participant->phone }}</div>@endif
                        @if(!$participant->email && !$participant->phone)<span class="mute">—</span>@endif
                    </td>
                    <td>
                        <a href="{{ route('projects.show', $participant->project) }}"
                            style="color:var(--primary);text-decoration:none">
                            {{ $participant->project->name }}
                        </a>
                    </td>
                    <td>{{ $participant->role_label }}</td>
                    <td style="font-weight:500">{{ number_format($participant->hours_contributed) }}h</td>
                    <td>{{ $participant->joined_at->format('d/m/Y') }}</td>
                    <td>
                        @php
                        $bc = match($participant->status) {
                        'active'=>'p-badge-green',
                        'pending'=>'p-badge-yellow',
                        'inactive'=>'p-badge-red',
                        default=>'p-badge-gray'
                        };
                        @endphp
                        <span class="p-badge {{ $bc }}">{{ $participant->status_label }}</span>
                    </td>
                    <td>
                        <div class="p-actions">
                            <a href="{{ route('participants.show', $participant) }}" class="p-btn-icon" title="Xem">
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                    <circle cx="12" cy="12" r="3" />
                                </svg>
                            </a>
                            @if(auth()->user()->isAdmin() || auth()->user()->isEditor())
                            <a href="{{ route('participants.edit', $participant) }}" class="p-btn-icon" title="Sửa">
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                                </svg>
                            </a>
                            @endif
                            @if(auth()->user()->isAdmin())
                            <form action="{{ route('participants.destroy', $participant) }}" method="POST"
                                onsubmit="return confirm('Xác nhận xoá?')" style="display:contents">
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
                    <td colspan="9">
                        <div class="p-empty">
                            <svg width="32" height="32" fill="none" stroke="currentColor" stroke-width="1.5"
                                viewBox="0 0 24 24" style="margin:0 auto 12px;display:block;color:var(--ink-faint)">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                                <circle cx="9" cy="7" r="4" />
                                <path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75" />
                            </svg>
                            Chưa có tình nguyện viên nào.
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($participants->hasPages())
    <div class="p-card-footer" style="display:flex;align-items:center;justify-content:space-between">
        <span style="font-size:12px;color:var(--ink-mute)">
            {{ $participants->firstItem() }}–{{ $participants->lastItem() }} / {{ $participants->total() }} kết quả
        </span>
        {{ $participants->links() }}
    </div>
    @endif

</div>

@endsection
