@extends('layouts.dashboard')

@section('title', 'Nhà tài trợ')

@include('participants._styles')

@section('content')

<div class="p-header">
    <div>
        <div class="p-header-title">Nhà tài trợ</div>
        <div class="p-header-sub">Quản lý danh sách nhà tài trợ</div>
    </div>
    @if(auth()->user()->isAdmin())
    <a href="{{ route('sponsors.create') }}" class="p-btn p-btn-primary">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path d="M12 5v14M5 12h14" />
        </svg>
        Thêm nhà tài trợ
    </a>
    @endif
</div>

@if(session('success'))
<div class="p-alert p-alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
<div class="p-alert p-alert-danger">{{ session('error') }}</div>
@endif

<div class="p-card">

    <form method="GET" action="{{ route('sponsors.index') }}">
        <div class="p-filter">
            <div class="p-filter-group grow">
                <label for="search">Tìm kiếm</label>
                <input id="search" type="text" name="search" class="p-input"
                    placeholder="Tên, SĐT, email, mã số thuế..."
                    value="{{ request('search') }}">
            </div>
            <div class="p-filter-group">
                <label for="type">Loại</label>
                <select id="type" name="type" class="p-input" style="width:150px">
                    <option value="">Tất cả</option>
                    <option value="individual" @selected(request('type')==='individual')>Cá nhân</option>
                    <option value="organization" @selected(request('type')==='organization')>Tổ chức</option>
                </select>
            </div>
            <button type="submit" class="p-btn p-btn-outline">Lọc</button>

            @if(request()->hasAny(['search', 'type', 'sort', 'direction']))
            <a href="{{ route('sponsors.index') }}" class="p-btn p-btn-ghost">Xoá lọc & sắp xếp</a>
            @endif
        </div>
    </form>

    <div class="p-table-wrap">
        <table class="p-table">
            <thead>
                <tr>
                    <th style="width:40px">#</th>
                    <th>{!! sortLink('name', 'Tên') !!}</th>
                    <th>Loại</th>
                    <th>Liên hệ</th>
                    <th class="text-right">Số lần đóng góp</th>
                    <th class="text-right">Tổng đóng góp (tiền)</th>
                    <th style="width:90px">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sponsors as $sponsor)
                <tr>
                    <td style="color:var(--ink-mute);font-size:12px">{{ $loop->iteration }}</td>
                    <td style="font-weight:500">{{ $sponsor->name }}</td>
                    <td>
                        <span class="p-badge {{ $sponsor->type === 'organization' ? 'p-badge-blue' : 'p-badge-gray' }}">
                            {{ $sponsor->type_label }}
                        </span>
                    </td>
                    <td>
                        @if($sponsor->email)<div>{{ $sponsor->email }}</div>@endif
                        @if($sponsor->phone)<div class="mute">{{ $sponsor->phone }}</div>@endif
                        @if(!$sponsor->email && !$sponsor->phone)<span class="mute">—</span>@endif
                    </td>
                    <td class="text-right">{{ $sponsor->donation_count }}</td>
                    <td class="text-right" style="font-weight:500">
                        {{ number_format($sponsor->total_contributed, 0, ',', '.') }} đ
                    </td>
                    <td>
                        <div class="p-actions">
                            <a href="{{ route('sponsors.show', $sponsor) }}" class="p-btn-icon" title="Xem">
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                    <circle cx="12" cy="12" r="3" />
                                </svg>
                            </a>
                            @if(auth()->user()->isAdmin())
                            <a href="{{ route('sponsors.edit', $sponsor) }}" class="p-btn-icon" title="Sửa">
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                                </svg>
                            </a>
                            <form action="{{ route('sponsors.destroy', $sponsor) }}" method="POST"
                                onsubmit="return confirm('Xác nhận xoá?')" style="display:contents">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-btn-icon danger" title="Xoá">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
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
                        <div class="p-empty">Chưa có nhà tài trợ nào.</div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($sponsors->hasPages())
    <div class="p-card-footer" style="display:flex;align-items:center;justify-content:space-between">
        <span style="font-size:12px;color:var(--ink-mute)">
            {{ $sponsors->firstItem() }}–{{ $sponsors->lastItem() }} / {{ $sponsors->total() }} kết quả
        </span>
        {{ $sponsors->links() }}
    </div>
    @endif

</div>

@endsection