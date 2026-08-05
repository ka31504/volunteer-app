@extends('layouts.dashboard')

@section('title', 'Sửa — ' . $participant->full_name)

@section('content')

<div class="p-header">
    <div>
        <div class="p-header-title">Chỉnh sửa tình nguyện viên</div>
        <div class="p-breadcrumb">
            <a href="{{ route('participants.index') }}">Tình nguyện viên</a>
            &rsaquo; <a href="{{ route('participants.show', $participant) }}">{{ $participant->full_name }}</a>
            &rsaquo; Chỉnh sửa
        </div>
    </div>
</div>

<div class="p-card">
    <div class="p-card-header">Thông tin tình nguyện viên</div>
    <div class="p-card-body">
        <form action="{{ route('participants.update', $participant) }}" method="POST">
            @csrf @method('PUT')
            @include('participants._form')

            <div class="p-form-actions">
                <a href="{{ route('participants.show', $participant) }}" class="p-btn p-btn-ghost">Huỷ</a>
                <button type="submit" class="p-btn p-btn-primary">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                    Lưu thay đổi
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
