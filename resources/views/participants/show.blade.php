@extends('layouts.dashboard')

@section('title', $participant->full_name)

@include('participants._styles')

@section('content')

{{-- HEADER --}}
<div class="p-header">
    <div>
        <div class="p-header-title">{{ $participant->full_name }}</div>
        <div class="p-breadcrumb">
            <a href="{{ route('participants.index') }}">Tình nguyện viên</a>
            &rsaquo; Chi tiết
        </div>
    </div>
    <div style="display:flex;gap:8px;align-items:center">
        <a href="{{ route('participants.edit', $participant) }}" class="p-btn p-btn-outline">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
            Chỉnh sửa
        </a>
        <form action="{{ route('participants.destroy', $participant) }}" method="POST"
              onsubmit="return confirm('Xoá tình nguyện viên này?')" style="display:contents">
            @csrf @method('DELETE')
            <button type="submit" class="p-btn p-btn-danger">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4h6v2"/></svg>
                Xoá
            </button>
        </form>
    </div>
</div>

{{-- STAT MINI --}}
<div class="p-stat-row" style="margin-bottom:20px">
    <div class="p-stat">
        <div class="p-stat-num">{{ number_format($participant->hours_contributed) }}h</div>
        <div class="p-stat-label">Số giờ tình nguyện</div>
    </div>
    <div class="p-stat">
        <div class="p-stat-num">{{ $participant->duration_days }}</div>
        <div class="p-stat-label">Ngày tham gia</div>
    </div>
    <div class="p-stat">
        @php $bc = match($participant->status) { 'active'=>'p-badge-green','pending'=>'p-badge-yellow','inactive'=>'p-badge-red',default=>'p-badge-gray' }; @endphp
        <div class="p-stat-num" style="font-size:14px;padding-top:4px">
            <span class="p-badge {{ $bc }}">{{ $participant->status_label }}</span>
        </div>
        <div class="p-stat-label">Trạng thái</div>
    </div>
</div>

{{-- DETAIL CARDS --}}
<div class="p-detail-grid">

    <div class="p-card">
        <div class="p-card-header">Thông tin cá nhân</div>
        <div class="p-card-body">
            <dl class="p-dl">
                <div class="p-dl-row">
                    <dt class="p-dt">Họ và tên</dt>
                    <dd class="p-dd" style="font-weight:500">{{ $participant->full_name }}</dd>
                </div>
                <div class="p-dl-row">
                    <dt class="p-dt">Giới tính</dt>
                    <dd class="p-dd">{{ $participant->gender_label }}</dd>
                </div>
                @if($participant->birth_date)
                <div class="p-dl-row">
                    <dt class="p-dt">Ngày sinh</dt>
                    <dd class="p-dd">{{ $participant->birth_date->format('d/m/Y') }} ({{ $participant->age }} tuổi)</dd>
                </div>
                @endif
                @if($participant->phone)
                <div class="p-dl-row">
                    <dt class="p-dt">Điện thoại</dt>
                    <dd class="p-dd">{{ $participant->phone }}</dd>
                </div>
                @endif
                @if($participant->email)
                <div class="p-dl-row">
                    <dt class="p-dt">Email</dt>
                    <dd class="p-dd">{{ $participant->email }}</dd>
                </div>
                @endif
                @if($participant->address)
                <div class="p-dl-row">
                    <dt class="p-dt">Địa chỉ</dt>
                    <dd class="p-dd">{{ $participant->address }}</dd>
                </div>
                @endif
            </dl>
        </div>
    </div>

    <div class="p-card">
        <div class="p-card-header">Hoạt động tình nguyện</div>
        <div class="p-card-body">
            <dl class="p-dl">
                <div class="p-dl-row">
                    <dt class="p-dt">Dự án</dt>
                    <dd class="p-dd">
                        <a href="{{ route('projects.show', $participant->project) }}"
                           style="color:var(--primary);text-decoration:none">
                            {{ $participant->project->name }}
                        </a>
                    </dd>
                </div>
                <div class="p-dl-row">
                    <dt class="p-dt">Vai trò</dt>
                    <dd class="p-dd">{{ $participant->role_label }}</dd>
                </div>
                <div class="p-dl-row">
                    <dt class="p-dt">Ngày tham gia</dt>
                    <dd class="p-dd">{{ $participant->joined_at->format('d/m/Y') }}</dd>
                </div>
                @if($participant->ended_at)
                <div class="p-dl-row">
                    <dt class="p-dt">Ngày kết thúc</dt>
                    <dd class="p-dd">{{ $participant->ended_at->format('d/m/Y') }}</dd>
                </div>
                @endif
                <div class="p-dl-row">
                    <dt class="p-dt">Số giờ TV</dt>
                    <dd class="p-dd" style="font-weight:600;color:var(--primary)">{{ number_format($participant->hours_contributed) }} giờ</dd>
                </div>
                @if($participant->notes)
                <div class="p-dl-row">
                    <dt class="p-dt">Ghi chú</dt>
                    <dd class="p-dd" style="color:var(--ink-mute)">{{ $participant->notes }}</dd>
                </div>
                @endif
            </dl>
        </div>
    </div>

</div>

@endsection
