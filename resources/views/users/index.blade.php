@extends('layouts.dashboard')

@section('title', 'Quản lý tài khoản')

@include('participants._styles')

@section('content')

<div class="p-header">
    <div>
        <div class="p-header-title">Quản lý tài khoản</div>
        <div class="p-header-sub">Phân quyền và quản lý người dùng hệ thống</div>
    </div>
    <a href="{{ route('users.create') }}" class="p-btn p-btn-primary">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path d="M12 5v14M5 12h14" />
        </svg>
        Thêm tài khoản
    </a>
</div>

@if(session('success'))
<div class="p-alert p-alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
<div class="p-alert p-alert-error">{{ session('error') }}</div>
@endif

<div class="p-card">

    <form method="GET" action="{{ route('users.index') }}">
        <div class="p-filter">
            <div class="p-filter-group grow">
                <label for="search">Tìm kiếm</label>
                <input id="search" type="text" name="search" class="p-input" placeholder="Tên, email..."
                    value="{{ request('search') }}">
            </div>
            <div class="p-filter-group">
                <label for="role">Vai trò</label>
                <select id="role" name="role" class="p-input" style="width:160px">
                    <option value="">Tất cả</option>
                    <option value="admin" @selected(request('role')==='admin')>Admin</option>
                    <option value="editor" @selected(request('role')==='editor')>Editor</option>
                    <option value="user" @selected(request('role')==='user')>User</option>
                </select>
            </div>
            <button type="submit" class="p-btn p-btn-outline">Lọc</button>
            @if(request()->hasAny(['search', 'role']))
            <a href="{{ route('users.index') }}" class="p-btn p-btn-ghost">Xoá lọc</a>
            @endif
        </div>
    </form>

    <div class="p-table-wrap">
        <table class="p-table">
            <thead>
                <tr>
                    <th style="width:40px">#</th>
                    <th>Tên</th>
                    <th>Email</th>
                    <th>Vai trò</th>
                    <th style="width:100px">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td style="color:var(--ink-mute);font-size:12px">{{ $loop->iteration }}</td>
                    <td style="font-weight:500">
                        {{ $user->name }}
                        @if($user->id === Auth::id())
                            <span class="p-badge p-badge-gray" style="margin-left:6px">Bạn</span>
                        @endif
                    </td>
                    <td>{{ $user->email }}</td>
                    <td>
                        @php
                        $roleClass = match($user->role) {
                            'admin' => 'p-badge-green',
                            'editor' => 'p-badge-yellow',
                            default => 'p-badge-gray',
                        };
                        $roleLabel = match($user->role) {
                            'admin' => 'Admin',
                            'editor' => 'Editor',
                            default => 'User',
                        };
                        @endphp
                        <span class="p-badge {{ $roleClass }}">{{ $roleLabel }}</span>
                    </td>
                    <td>
                        <div class="p-actions">
                            <a href="{{ route('users.edit', $user) }}" class="p-btn-icon" title="Sửa">
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                                </svg>
                            </a>
                            @if($user->id !== Auth::id())
                            <form action="{{ route('users.destroy', $user) }}" method="POST"
                                onsubmit="return confirm('Xác nhận xoá tài khoản này?')" style="display:contents">
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
                    <td colspan="5">
                        <div class="p-empty">Chưa có tài khoản nào.</div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($users->hasPages())
    <div class="p-card-footer" style="display:flex;align-items:center;justify-content:space-between">
        <span style="font-size:12px;color:var(--ink-mute)">
            {{ $users->firstItem() }}–{{ $users->lastItem() }} / {{ $users->total() }} tài khoản
        </span>
        {{ $users->links() }}
    </div>
    @endif

</div>

@endsection