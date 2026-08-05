@extends('layouts.dashboard')

@section('title', $sponsor->name)

@include('participants._styles')

@section('content')

<div class="p-header">
    <div>
        <div class="p-header-title">{{ $sponsor->name }}</div>
        <div class="p-header-sub">{{ $sponsor->type_label }}</div>
    </div>
    @if(auth()->user()->isAdmin())
    <a href="{{ route('sponsors.edit', $sponsor) }}" class="p-btn p-btn-primary">Sửa thông tin</a>
    @endif
</div>

<div class="p-card" style="margin-bottom:16px">
    <div class="p-form-grid">
        <div>
            <div class="p-section-title">Thông tin liên hệ</div>
            <p><strong>Điện thoại:</strong> {{ $sponsor->phone ?? '—' }}</p>
            <p><strong>Email:</strong> {{ $sponsor->email ?? '—' }}</p>
            <p><strong>Địa chỉ:</strong> {{ $sponsor->address ?? '—' }}</p>
            <p><strong>Mã số thuế:</strong> {{ $sponsor->tax_code ?? '—' }}</p>
        </div>
        <div>
            <div class="p-section-title">Thống kê đóng góp</div>
            <p><strong>Số lần đóng góp:</strong> {{ $sponsor->donation_count }}</p>
            <p><strong>Tổng tiền đóng góp:</strong> {{ number_format($sponsor->total_contributed, 0, ',', '.') }} đ</p>
        </div>
    </div>
    @if($sponsor->notes)
    <div class="p-section-title">Ghi chú</div>
    <p>{{ $sponsor->notes }}</p>
    @endif
</div>

<div class="p-card">
    <div class="p-section-title">Lịch sử đóng góp</div>
    <div class="p-table-wrap">
        <table class="p-table">
            <thead>
                <tr>
                    <th>Dự án</th>
                    <th>Loại</th>
                    <th>Giá trị</th>
                    <th>Ngày đóng góp</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sponsor->donations as $donation)
                <tr>
                    <td>{{ $donation->project->name }}</td>
                    <td>{{ $donation->type_label }}</td>
                    <td>{{ $donation->formatted_amount }}</td>
                    <td>{{ $donation->donated_at->format('d/m/Y') }}</td>
                </tr>
                @empty
                <tr><td colspan="4"><div class="p-empty">Chưa có đóng góp nào.</div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection