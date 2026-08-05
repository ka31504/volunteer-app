@extends('layouts.dashboard')

@section('title', 'Sửa — ' . $project->name)

@include('participants._styles')

@section('content')

<div class="p-header">
    <div>
        <div class="p-header-title">Chỉnh sửa dự án</div>
        <div class="p-breadcrumb">
            <a href="{{ route('projects.index') }}">Dự án</a>
            &rsaquo; <a href="{{ route('projects.show', $project) }}">{{ $project->name }}</a>
            &rsaquo; Chỉnh sửa
        </div>
    </div>
</div>

<div class="p-card">
    <div class="p-card-header">Thông tin dự án</div>
    <div class="p-card-body">
        <form action="{{ route('projects.update', $project) }}" method="POST">
            @csrf
            @method('PUT')
            @include('projects._form', ['project' => $project])

            <div class="p-form-actions">
                <a href="{{ route('projects.show', $project) }}" class="p-btn p-btn-ghost">Huỷ</a>
                <button type="submit" class="p-btn p-btn-primary">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                    Lưu thay đổi
                </button>
            </div>
        </form>
    </div>
</div>

@endsection