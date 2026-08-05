@extends('layouts.dashboard')

@section('title', 'Sửa đóng góp #' . $donation->id)

@include('participants._styles')

@section('content')

<div class="p-header">
    <div>
        <div class="p-header-title">Chỉnh sửa đóng góp</div>
        <div class="p-breadcrumb">
            <a href="{{ route('donations.index') }}">Đóng góp</a>
            &rsaquo; <a href="{{ route('donations.show', $donation) ?? '#' }}">#{{ $donation->id }}</a>
            &rsaquo; Chỉnh sửa
        </div>
    </div>
</div>

<div class="p-card">
    <div class="p-card-header">Ghi nhận đóng góp</div>
    <div class="p-card-body">
        <form action="{{ route('donations.update', $donation) }}" method="POST">
            @csrf
            @method('PUT')
            @include('donations._form', ['projects' => $projects, 'donation' => $donation])

            <div class="p-form-actions">
                <a href="{{ route('donations.index') }}" class="p-btn p-btn-ghost">Huỷ</a>
                <button type="submit" class="p-btn p-btn-primary">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                    Cập nhật
                </button>
            </div>
        </form>
    </div>
</div>

@endsection