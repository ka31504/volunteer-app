@extends('layouts.dashboard')

@section('title', 'Đóng góp')

@include('participants._styles')

@section('content')

<div class="p-header">
    <div>
        <div class="p-header-title">Đóng góp</div>
        <div class="p-header-sub">Quản lý các khoản đóng góp thiện nguyện</div>
    </div>
    @if(auth()->user()->isAdmin() || auth()->user()->isEditor())
    <a href="{{ route('donations.create') }}" class="p-btn p-btn-primary">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path d="M12 5v14M5 12h14" />
        </svg>
        Thêm đóng góp
    </a>
    @endif
</div>

@if(session('success'))
<div class="p-alert p-alert-success">{{ session('success') }}</div>
@endif

<div class="p-card">

    {{-- FILTER BAR --}}
    <form method="GET" action="{{ route('donations.index') }}">
        <div class="p-filter">
            <div class="p-filter-group grow">
                <label for="search">Tìm kiếm</label>
                <input id="search" type="text" name="search" class="p-input"
                    placeholder="Tên người đóng góp, số điện thoại..." value="{{ request('search') }}">
            </div>

            <div class="p-filter-group">
                <label for="project_id">Dự án</label>
                <select id="project_id" name="project_id" class="p-input" style="width:210px">
                    <option value="">Tất cả dự án</option>
                    @foreach($projects as $project)
                    <option value="{{ $project->id }}" @selected(request('project_id')==$project->id)>
                        {{ $project->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="p-filter-group">
                <label for="type">Loại</label>
                <select id="type" name="type" class="p-input" style="width:160px">
                    <option value="">Tất cả</option>
                    <option value="money" @selected(request('type')==='money' )>Tiền</option>
                    <option value="goods" @selected(request('type')==='goods' )>Hiện vật</option>
                </select>
            </div>

            <button type="submit" class="p-btn p-btn-outline">Lọc</button>

            @if(request()->hasAny(['search', 'project_id', 'type', 'sort']))
            <a href="{{ route('donations.index') }}" class="p-btn p-btn-ghost">Xoá lọc & sắp xếp</a>
            @endif
        </div>
    </form>

    <div class="p-table-wrap">
        <table class="p-table">
            <thead>
                <tr>
                    <th style="width:40px">#</th>
                    <th>{!! sortLink('donor_name', 'Người đóng góp') !!}</th>
                    <th>Dự án</th>
                    <th>Loại</th>
                    <th class="text-right">{!! sortLink('amount', 'Giá trị') !!}</th>
                    <th>{!! sortLink('donated_at', 'Ngày') !!}</th>
                    <th style="width:100px">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($donations as $donation)
                <tr>
                    <td style="color:var(--ink-mute);font-size:12px">{{ $loop->iteration }}</td>
                    <td>
                        <div style="font-weight:500">{{ $donation->display_donor_name }}</div>
                        @if($donation->donor_phone)
                        <div class="mute">{{ $donation->display_donor_phone }}</div>
                        @endif
                    </td>
                    <td>{{ $donation->project->name ?? '—' }}</td>
                    <td>
                        @if($donation->type === 'money')
                        <span class="p-badge p-badge-green">Tiền</span>
                        @else
                        <span class="p-badge p-badge-yellow">Hiện vật</span>
                        @endif
                    </td>
                    <td class="text-right font-medium">
                        @if($donation->type === 'money')
                        {{ number_format($donation->amount ?? 0) }}đ
                        @else
                        {{ $donation->goods_quantity ?? 0 }} cái
                        @endif
                    </td>
                    <td>{{ $donation->donated_at?->format('d/m/Y') }}</td>
                    <td>
                        <div class="p-actions">
                            <a href="{{ route('donations.show', $donation) }}" class="p-btn-icon" title="Xem">
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                    <circle cx="12" cy="12" r="3" />
                                </svg>
                            </a>
                            @if(auth()->user()->isAdmin() || auth()->user()->isEditor())
                            <a href="{{ route('donations.edit', $donation) }}" class="p-btn-icon" title="Sửa">
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                                </svg>
                            </a>
                            @endif
                            @if(auth()->user()->isAdmin())
                            <form action="{{ route('donations.destroy', $donation) }}" method="POST"
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
                    <td colspan="7">
                        <div class="p-empty">Chưa có khoản đóng góp nào.</div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($donations->hasPages())
    <div class="p-card-footer" style="display:flex;align-items:center;justify-content:space-between">
        <span style="font-size:12px;color:var(--ink-mute)">
            {{ $donations->firstItem() }}–{{ $donations->lastItem() }} / {{ $donations->total() }} khoản
        </span>
        {{ $donations->links() }}
    </div>
    @endif

</div>

@endsection
