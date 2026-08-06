@extends('layouts.dashboard')

@section('title', 'Sửa tài khoản')

@include('participants._styles')

@section('content')

<div class="p-header">
    <div>
        <div class="p-header-title">Sửa tài khoản</div>
        <div class="p-header-sub">{{ $user->name }} — {{ $user->email }}</div>
    </div>
</div>

<div class="p-card">
    <div class="p-card-body">
        <form method="POST" action="{{ route('users.update', $user) }}">
            @csrf
            @method('PUT')
            <div class="p-form-grid">
                <div class="p-form-section">
                    <div class="p-field">
                        <label class="p-label req">Họ tên</label>
                        <input type="text" name="name" class="p-input @error('name') error @enderror"
                            value="{{ old('name', $user->name) }}">
                        @error('name')<div class="p-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="p-field">
                        <label class="p-label req">Email</label>
                        <input type="email" name="email" class="p-input @error('email') error @enderror"
                            value="{{ old('email', $user->email) }}">
                        @error('email')<div class="p-error">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="p-form-section">
                    <div class="p-field">
                        <label class="p-label">Mật khẩu mới</label>
                        <input type="password" name="password" class="p-input @error('password') error @enderror"
                            placeholder="Để trống nếu không đổi">
                        @error('password')<div class="p-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="p-field">
                        <label class="p-label">Xác nhận mật khẩu mới</label>
                        <input type="password" name="password_confirmation" class="p-input">
                    </div>
                    <div class="p-field">
                        <label class="p-label req">Vai trò</label>
                        <select name="role" class="p-input @error('role') error @enderror">
                            <option value="user" @selected(old('role', $user->role)==='user')>User</option>
                            <option value="editor" @selected(old('role', $user->role)==='editor')>Editor</option>
                            <option value="admin" @selected(old('role', $user->role)==='admin')>Admin</option>
                        </select>
                        @error('role')<div class="p-error">{{ $message }}</div>@enderror
                        @if($user->id === Auth::id())
                        <div style="font-size:12px;color:var(--ink-mute);margin-top:4px">
                            Bạn không thể tự hạ quyền admin của chính mình.
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="p-form-actions">
                <a href="{{ route('users.index') }}" class="p-btn p-btn-ghost">Huỷ</a>
                <button type="submit" class="p-btn p-btn-primary">Lưu thay đổi</button>
            </div>
        </form>
    </div>
</div>

@endsection