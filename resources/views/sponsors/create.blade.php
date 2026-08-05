@extends('layouts.dashboard')

@section('title', 'Thêm nhà tài trợ')

@include('participants._styles')

@section('content')
<div class="p-header">
    <div>
        <div class="p-header-title">Thêm nhà tài trợ</div>
        <div class="p-header-sub">Tạo mới thông tin nhà tài trợ</div>
    </div>
</div>

<div class="p-card">
    <form method="POST" action="{{ route('sponsors.store') }}">
        @csrf
        @php($sponsor = new \App\Models\Sponsor())
        @include('sponsors._form')

        <div class="p-form-actions">
            <a href="{{ route('sponsors.index') }}" class="p-btn p-btn-ghost">Huỷ</a>
            <button type="submit" class="p-btn p-btn-primary">Lưu nhà tài trợ</button>
        </div>
    </form>
</div>
@endsection