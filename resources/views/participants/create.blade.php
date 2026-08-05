@extends('layouts.dashboard')

@section('title', 'Thêm tình nguyện viên')

@section('content')

<div class="p-header">
    <div>
        <div class="p-header-title">Thêm tình nguyện viên</div>
        <div class="p-breadcrumb">
            <a href="{{ route('participants.index') }}">Tình nguyện viên</a>
            &rsaquo; Thêm mới
        </div>
    </div>
</div>

<div class="p-card">
    <div class="p-card-header">Thông tin tình nguyện viên</div>
    <div class="p-card-body">
        <form action="{{ route('participants.store') }}" method="POST">
            @csrf
            @include('participants._form', ['participant' => new \App\Models\Participant()])

            <div class="p-form-actions">
                <a href="{{ route('participants.index') }}" class="p-btn p-btn-ghost">Huỷ</a>
                <button type="submit" class="p-btn p-btn-primary">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                    Thêm tình nguyện viên
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
