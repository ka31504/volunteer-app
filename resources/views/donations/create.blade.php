@extends('layouts.dashboard')

@section('title', 'Thêm đóng góp mới')

@include('participants._styles')

@section('content')

<div class="p-header">
    <div>
        <div class="p-header-title">Thêm đóng góp mới</div>
        <div class="p-breadcrumb">
            <a href="{{ route('donations.index') }}">Đóng góp</a>
            &rsaquo; Thêm mới
        </div>
    </div>
</div>

<div class="p-card">
    <div class="p-card-header">Ghi nhận đóng góp</div>
    <div class="p-card-body">
        <form action="{{ route('donations.store') }}" method="POST">
            @csrf
            @include('donations._form', ['projects' => $projects])

            <div class="p-form-actions">
                <a href="{{ route('donations.index') }}" class="p-btn p-btn-ghost">Huỷ</a>
                <button type="submit" class="p-btn p-btn-primary">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                    Lưu đóng góp
                </button>
            </div>
        </form>
    </div>
</div>

@endsection