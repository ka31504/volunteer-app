@extends('layouts.dashboard')

@section('title', 'Chi tiết đóng góp #' . $donation->id)

@include('participants._styles')

@section('content')

{{-- HEADER --}}
<div class="p-header">
    <div>
        <div class="p-header-title">Đóng góp #{{ $donation->id }}</div>
        <div class="p-breadcrumb">
            <a href="{{ route('donations.index') }}">Đóng góp</a>
            &rsaquo; Chi tiết
        </div>
    </div>
    <div style="display:flex;gap:8px;align-items:center">
        @if(auth()->user()->isAdmin() || auth()->user()->isEditor())
        <a href="{{ route('donations.edit', $donation) }}" class="p-btn p-btn-outline">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
            Chỉnh sửa
        </a>
        @endif
        @if(auth()->user()->isAdmin())
        <form action="{{ route('donations.destroy', $donation) }}" method="POST"
              onsubmit="return confirm('Xóa khoản đóng góp này?')" style="display:contents">
            @csrf @method('DELETE')
            <button type="submit" class="p-btn p-btn-danger">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4h6v2"/></svg>
                Xoá
            </button>
        </form>
        @endif
    </div>
</div>

{{-- STAT MINI --}}
<div class="p-stat-row" style="margin-bottom:20px">
    <div class="p-stat">
        <div class="p-stat-num">
            @if($donation->type === 'money')
                {{ number_format($donation->amount) }}đ
            @else
                {{ $donation->goods_quantity }} 
            @endif
        </div>
        <div class="p-stat-label">
            @if($donation->type === 'money') Giá trị @else Số lượng @endif
        </div>
    </div>
    <div class="p-stat">
        <div class="p-stat-num">{{ $donation->donated_at?->format('d/m/Y') }}</div>
        <div class="p-stat-label">Ngày đóng góp</div>
    </div>
    <div class="p-stat">
        @php 
            $typeClass = $donation->type === 'money' ? 'p-badge-green' : 'p-badge-yellow';
        @endphp
        <div class="p-stat-num" style="font-size:14px;padding-top:4px">
            <span class="p-badge {{ $typeClass }}">
                {{ $donation->type === 'money' ? 'Tiền' : 'Hiện vật' }}
            </span>
        </div>
        <div class="p-stat-label">Loại đóng góp</div>
    </div>
</div>

{{-- DETAIL CARDS --}}
<div class="p-detail-grid">

    <div class="p-card">
        <div class="p-card-header">Thông tin người đóng góp</div>
        <div class="p-card-body">
            <dl class="p-dl">
                <div class="p-dl-row">
                    <dt class="p-dt">Họ và tên</dt>
                    <dd class="p-dd font-medium">{{ $donation->display_donor_name }}</dd>
                </div>
                @if($donation->donor_phone)
                <div class="p-dl-row">
                    <dt class="p-dt">Số điện thoại</dt>
                    <dd class="p-dd">{{ $donation->display_donor_phone }}</dd>
                </div>
                @endif
                <div class="p-dl-row">
                    <dt class="p-dt">Dự án</dt>
                    <dd class="p-dd">
                        <a href="{{ route('projects.show', $donation->project) }}" 
                           style="color:var(--primary);text-decoration:none">
                            {{ $donation->project->name }}
                        </a>
                    </dd>
                </div>
                @if($donation->sponsor)
                <div class="p-dl-row">
                    <dt class="p-dt">Nhà tài trợ</dt>
                    <dd class="p-dd">
                        <a href="{{ route('sponsors.show', $donation->sponsor) }}" 
                           style="color:var(--primary);text-decoration:none">
                            {{ $donation->sponsor->name }}
                        </a>
                    </dd>
                </div>
                @endif
            </dl>
        </div>
    </div>

    <div class="p-card">
        <div class="p-card-header">Chi tiết đóng góp</div>
        <div class="p-card-body">
            <dl class="p-dl">
                <div class="p-dl-row">
                    <dt class="p-dt">Loại đóng góp</dt>
                    <dd class="p-dd">
                        <span class="p-badge {{ $donation->type === 'money' ? 'p-badge-green' : 'p-badge-yellow' }}">
                            {{ $donation->type === 'money' ? 'Tiền' : 'Hiện vật' }}
                        </span>
                    </dd>
                </div>

                @if($donation->type === 'money')
                <div class="p-dl-row">
                    <dt class="p-dt">Số tiền</dt>
                    <dd class="p-dd font-semibold text-emerald-600">{{ number_format($donation->amount) }} VNĐ</dd>
                </div>
                <div class="p-dl-row">
                    <dt class="p-dt">Hình thức thanh toán</dt>
                    <dd class="p-dd">
                        {{ match($donation->payment_method) {
                            'cash' => 'Tiền mặt',
                            'transfer' => 'Chuyển khoản',
                            'other' => 'Khác',
                            default => '—'
                        } }}
                    </dd>
                </div>
                @else
                <div class="p-dl-row">
                    <dt class="p-dt">Mô tả hiện vật</dt>
                    <dd class="p-dd">{{ $donation->goods_description }}</dd>
                </div>
                <div class="p-dl-row">
                    <dt class="p-dt">Số lượng</dt>
                    <dd class="p-dd font-medium">{{ $donation->goods_quantity }}</dd>
                </div>
                @endif

                <div class="p-dl-row">
                    <dt class="p-dt">Ngày đóng góp</dt>
                    <dd class="p-dd">{{ $donation->donated_at?->format('d/m/Y') }}</dd>
                </div>

                @if($donation->note)
                <div class="p-dl-row">
                    <dt class="p-dt">Ghi chú</dt>
                    <dd class="p-dd text-[var(--ink-mute)]">{{ $donation->note }}</dd>
                </div>
                @endif
            </dl>
        </div>
    </div>

</div>

@endsection
