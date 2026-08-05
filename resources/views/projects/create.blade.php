@extends('layouts.dashboard')

@section('title', 'Thêm dự án mới')

@include('participants._styles')  {{-- Dùng chung styles --}}

@section('content')

<div class="p-header">
    <div>
        <div class="p-header-title">Thêm dự án mới</div>
        <div class="p-breadcrumb">
            <a href="{{ route('projects.index') }}">Dự án</a>
            &rsaquo; Thêm mới
        </div>
    </div>
</div>

<div class="p-card">
    <div class="p-card-header">Thông tin dự án</div>
    <div class="p-card-body">
        <form action="{{ route('projects.store') }}" method="POST">
            @csrf
            @include('projects._form')

            <div class="p-form-actions">
                <a href="{{ route('projects.index') }}" class="p-btn p-btn-ghost">Huỷ</a>
                <button type="submit" class="p-btn p-btn-primary">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                    Tạo dự án
                </button>
            </div>
        </form>
    </div>
</div>

@endsection