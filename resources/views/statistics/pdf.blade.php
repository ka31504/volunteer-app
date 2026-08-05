<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
    @page { margin: 25px 30px; }
    body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #1a1a1a; }
    h1 { font-size: 18px; margin-bottom: 2px; }
    .subtitle { color: #666; font-size: 11px; margin-bottom: 20px; }
    .cards-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
    .cards-table td {
        width: 33.33%; padding: 10px; border: 1px solid #ddd;
        text-align: center; vertical-align: top;
    }
    .card-label { font-size: 10px; color: #666; text-transform: uppercase; }
    .card-value { font-size: 16px; font-weight: bold; margin-top: 4px; }
    h2 { font-size: 14px; margin-top: 22px; margin-bottom: 8px; border-bottom: 1px solid #ccc; padding-bottom: 4px; }
    table.data { width: 100%; border-collapse: collapse; }
    table.data th, table.data td {
        border: 1px solid #ddd; padding: 6px 8px; font-size: 11px; text-align: left;
    }
    table.data th { background: #f5f5f5; }
    .progress-outer { background: #eee; border-radius: 3px; height: 10px; width: 100%; }
    .progress-inner { background: #2f8f4e; height: 10px; border-radius: 3px; }
    .progress-inner.over { background: #1e7e34; }
    .footer { margin-top: 30px; font-size: 9px; color: #999; text-align: right; }
</style>
</head>
<body>

<h1>Báo cáo Thống kê — Thiện Nguyện Hub</h1>
<div class="subtitle">Năm {{ $year }} · Xuất lúc {{ $generatedAt }}</div>

<table class="cards-table">
    <tr>
        <td>
            <div class="card-label">Tổng dự án</div>
            <div class="card-value">{{ $data['totalProjects'] }}</div>
        </td>
        <td>
            <div class="card-label">Đang hoạt động</div>
            <div class="card-value">{{ $data['activeProjects'] }}</div>
        </td>
        <td>
            <div class="card-label">Hoàn thành</div>
            <div class="card-value">{{ $data['completedProjects'] }}</div>
        </td>
    </tr>
    <tr>
        <td>
            <div class="card-label">Tổng đóng góp tiền</div>
            <div class="card-value">{{ number_format($data['totalDonationMoney'], 0, ',', '.') }}đ</div>
        </td>
        <td>
            <div class="card-label">Tình nguyện viên</div>
            <div class="card-value">{{ $data['totalParticipants'] }}</div>
        </td>
        <td>
            <div class="card-label">Tổng giờ</div>
            <div class="card-value">{{ $data['totalHours'] }}</div>
        </td>
    </tr>
</table>

<h2>Đóng góp tiền theo tháng</h2>
<table class="data">
    <thead>
        <tr><th>Tháng</th><th>Số tiền</th></tr>
    </thead>
    <tbody>
        @foreach($data['monthlyDonations'] as $index => $total)
        <tr>
            <td>Tháng {{ $index + 1 }}</td>
            <td>{{ number_format($total, 0, ',', '.') }}đ</td>
        </tr>
        @endforeach
    </tbody>
</table>

<h2>Tỷ lệ loại đóng góp</h2>
<table class="data">
    <thead>
        <tr><th>Loại</th><th>Số lượt</th></tr>
    </thead>
    <tbody>
        <tr>
            <td>Tiền mặt</td>
            <td>{{ $data['moneyCount'] }}</td>
        </tr>
        <tr>
            <td>Hiện vật</td>
            <td>{{ $data['goodsCount'] }}</td>
        </tr>
    </tbody>
</table>

<h2>Top dự án theo số tình nguyện viên</h2>
<table class="data">
    <thead>
        <tr><th>Dự án</th><th>Số TNV</th></tr>
    </thead>
    <tbody>
        @foreach($data['participantsByProject'] as $row)
        <tr>
            <td>{{ $row['name'] }}</td>
            <td>{{ $row['count'] }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<h2>Tiến độ gây quỹ theo dự án</h2>
<table class="data">
    <thead>
        <tr><th>Dự án</th><th style="width: 50%">Tiến độ</th><th>%</th></tr>
    </thead>
    <tbody>
        @foreach($data['projectProgress'] as $row)
        @php $pct = min($row['progress'], 100); @endphp
        <tr>
            <td>{{ $row['name'] }}</td>
            <td>
                <div class="progress-outer">
                    <div class="progress-inner {{ $row['progress'] > 100 ? 'over' : '' }}" style="width: {{ $pct }}%"></div>
                </div>
            </td>
            <td>{{ number_format($row['progress'], 1) }}%</td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="footer">Thiện Nguyện Hub — Báo cáo được tạo tự động</div>

</body>
</html>