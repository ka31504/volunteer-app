@extends('layouts.dashboard')

@section('title', 'Sửa nhà tài trợ')

@include('participants._styles')

@section('content')
<div class="p-header">
    <div>
        <div class="p-header-title">Sửa nhà tài trợ</div>
        <div class="p-header-sub">{{ $sponsor->name }}</div>
    </div>
</div>

<div class="p-card">
    <form method="POST" action="{{ route('sponsors.update', $sponsor) }}">
        @csrf
        @method('PUT')
        @include('sponsors._form')

        <div class="p-form-actions">
            <a href="{{ route('sponsors.index') }}" class="p-btn p-btn-ghost">Huỷ</a>
            <button type="submit" class="p-btn p-btn-primary">Cập nhật</button>
        </div>
    </form>
</div>
@endsection