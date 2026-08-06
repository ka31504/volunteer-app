@extends('layouts.dashboard')

@section('title', 'Thêm tài khoản')

@include('participants._styles')

@section('content')

<div class="p-header">
    <div>
        <div class="p-header-title">Thêm tài khoản mới</div>
        <div class="p-header-sub">Tạo tài khoản và phân quyền cho người dùng</div>
    </div>
</div>

<div class="p-card">
    <div class="p-card-body">
        <form method="POST" action="{{ route('users.store') }}">
            @csrf
            <div class="p-form-grid">
                <div class="p-form-section">
                    <div class="p-field">
                        <label class="p-label req">Họ tên</label>
                        <input type="text" name="name" class="p-input @error('name') error @enderror"
                            value="{{ old('name') }}">
                        @error('name')<div class="p-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="p-field">
                        <label class="p-label req">Email</label>
                        <input type="email" name="email" class="p-input @error('email') error @enderror"
                            value="{{ old('email') }}">
                        @error('email')<div class="p-error">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="p-form-section">
                    <div class="p-field">
                        <label class="p-label req">Mật khẩu</label>
                        <input type="password" name="password" class="p-input @error('password') error @enderror">
                        @error('password')<div class="p-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="p-field">
                        <label class="p-label req">Xác nhận mật khẩu</label>
                        <input type="password" name="password_confirmation" class="p-input">
                    </div>
                    <div class="p-field">
                        <label class="p-label req">Vai trò</label>
                        <select name="role" class="p-input @error('role') error @enderror">
                            <option value="user" @selected(old('role')==='user')>User</option>
                            <option value="editor" @selected(old('role')==='editor')>Editor</option>
                            <option value="admin" @selected(old('role')==='admin')>Admin</option>
                        </select>
                        @error('role')<div class="p-error">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            <div class="p-form-actions">
                <a href="{{ route('users.index') }}" class="p-btn p-btn-ghost">Huỷ</a>
                <button type="submit" class="p-btn p-btn-primary">Tạo tài khoản</button>
            </div>
        </form>
    </div>
</div>

@endsection