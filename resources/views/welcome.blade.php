<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thiện Nguyện Hub</title>

    <link rel="stylesheet" href="{{ asset('assets/css/landing.css') }}">
</head>
<body>

@include('partials.navbar')

<!-- HERO -->
<section class="hero">

    <div class="hero-pill">
        <span class="dot"></span>
        Dành cho tổ chức, câu lạc bộ & nhóm thiện nguyện
    </div>

    <h1>
        Quản lý thiện nguyện
        <span>minh bạch, hiệu quả</span>
    </h1>

    <p class="hero-sub">
        Theo dõi dự án, người tham gia và đóng góp trong một nền tảng thống nhất.
    </p>

    <div class="hero-actions">

        @auth
            <a href="{{ route('dashboard') }}" class="btn-primary-lg">
                Vào Dashboard
            </a>
        @else
            <a href="{{ route('register') }}" class="btn-primary-lg">
                Tạo tài khoản miễn phí
            </a>

            <a href="{{ route('login') }}" class="btn-outline-lg">
                Đăng nhập →
            </a>
        @endauth

    </div>

</section>

<!-- MOCKUP: DỰ ÁN NỔI BẬT (DỮ LIỆU THẬT) -->
<div class="mockup-wrap" id="projects">
  <div class="mockup-ui">
    <div class="mockup-bar">
      <span class="dot-red"></span>
      <span class="dot-yellow"></span>
      <span class="dot-green"></span>
    </div>
    <div class="mockup-body">
      <div class="mockup-sidebar">
        <div class="sidebar-item active"><span class="si-dot"></span> Tổng quan</div>
        <div class="sidebar-item">📋 Dự án</div>
        <div class="sidebar-item">👥 Tình nguyện viên</div>
        <div class="sidebar-item">💰 Đóng góp</div>
        <div class="sidebar-item">📊 Thống kê</div>
        <div class="sidebar-item">⚙️ Cài đặt</div>
      </div>
      <div class="mockup-main">
        <div class="tbl-header">
          <span class="tbl-title">Dự án nổi bật</span>
          @if(auth()->check() && auth()->user()->isAdmin())
          <a href="{{ route('projects.create') }}" class="tbl-add">+ Thêm dự án</a>
          @endif
        </div>
        <table>
          <thead>
            <tr>
              <th>Tên dự án</th>
              <th>Trạng thái</th>
              <th>Tình nguyện viên</th>
              <th>Đóng góp</th>
              <th>Tiến độ</th>
            </tr>
          </thead>
          <tbody>
            @forelse($featuredProjects as $project)
            <tr>
              <td>{{ $project->name }}</td>
              <td>
                @php
                    // Giá trị enum thật trong DB (xem migration update_projects_status_column)
                    $statusClass = match($project->status) {
                        'completed' => 's-done',
                        'planning'  => 's-upcoming',
                        'closed'    => 's-done',
                        'ongoing'   => 's-active',
                        default     => 's-active',
                    };
                    $statusLabel = match($project->status) {
                        'completed' => 'Hoàn thành',
                        'planning'  => 'Đang lên kế hoạch',
                        'closed'    => 'Đã đóng',
                        'ongoing'   => 'Đang diễn ra',
                        default     => ucfirst($project->status),
                    };
                @endphp
                <span class="status-pill {{ $statusClass }}">{{ $statusLabel }}</span>
              </td>
              <td>{{ $project->participants_count }} người</td>
              <td>{{ number_format($project->current_amount ?? 0) }} đ</td>
              <td>
                <div class="prog-bar">
                    <div class="prog-fill" style="width:{{ min($project->progressPercentage(), 100) }}%"></div>
                </div>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="5" style="text-align:center;color:#8a8a8a">Chưa có dự án nào được đăng.</td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- FEATURES -->
<section class="features-section" id="features">
  <p class="section-label">Tính năng</p>
  <h2 class="section-title">Mọi thứ bạn cần để vận hành thiện nguyện</h2>
  <p class="section-sub">Từ quản lý dự án đến theo dõi đóng góp — được thiết kế cho sự minh bạch và dễ sử dụng.</p>
  <div class="features-grid">
    <div class="feat-card">
      <div class="feat-icon">📂</div>
      <div class="feat-title">Quản lý dự án toàn diện</div>
      <div class="feat-desc">Tạo, chỉnh sửa, theo dõi tiến độ dự án. Cập nhật trạng thái và số tiền gây quỹ theo thời gian thực.</div>
    </div>
    <div class="feat-card">
      <div class="feat-icon">👥</div>
      <div class="feat-title">Quản lý tình nguyện viên</div>
      <div class="feat-desc">Đăng ký tham gia, hồ sơ cá nhân, lịch sử hoạt động và phân công theo từng dự án cụ thể.</div>
    </div>
    <div class="feat-card">
      <div class="feat-icon">💸</div>
      <div class="feat-title">Theo dõi đóng góp</div>
      <div class="feat-desc">Ghi nhận đóng góp bằng tiền và hiện vật, liên kết với nhà tài trợ. Xuất báo cáo minh bạch.</div>
    </div>
    <div class="feat-card">
      <div class="feat-icon">📊</div>
      <div class="feat-title">Thống kê & Báo cáo</div>
      <div class="feat-desc">Biểu đồ trực quan theo dự án, theo thời gian. Xuất báo cáo PDF để chia sẻ với nhà tài trợ.</div>
    </div>
    <div class="feat-card">
      <div class="feat-icon">🔒</div>
      <div class="feat-title">Phân quyền người dùng</div>
      <div class="feat-desc">Admin quản lý toàn bộ hệ thống. Người dùng xem dữ liệu được phân quyền theo vai trò.</div>
    </div>
    <div class="feat-card">
      <div class="feat-icon">📑</div>
      <div class="feat-title">Quản lý nhà tài trợ</div>
      <div class="feat-desc">Lưu trữ thông tin nhà tài trợ, lịch sử đóng góp, phân biệt cá nhân/tổ chức.</div>
    </div>
    <div class="feat-card">
      <div class="feat-icon">🛡️</div>
      <div class="feat-title">Bảo vệ quyền riêng tư</div>
      <div class="feat-desc">Thông tin người đóng góp được ẩn tự động với người xem không có quyền quản trị, đảm bảo minh bạch mà vẫn tôn trọng quyền riêng tư cá nhân.</div>
    </div>
  </div>
</section>

<!-- STATS: SỐ LIỆU THẬT TỪ DATABASE -->
<div class="stats-section">
  <div class="stats-inner">
    <div class="stat-item">
      <div class="stat-num">{{ number_format($stats['projects']) }}</div>
      <div class="stat-desc">Dự án đã được quản lý</div>
    </div>
    <div class="stat-item">
      <div class="stat-num">{{ number_format($stats['active']) }}</div>
      <div class="stat-desc">Dự án đang hoạt động</div>
    </div>
    <div class="stat-item">
      <div class="stat-num">{{ number_format($stats['volunteers']) }}</div>
      <div class="stat-desc">Tình nguyện viên đã đăng ký</div>
    </div>
    <div class="stat-item">
      <div class="stat-num"><span style="color:var(--primary)">{{ number_format($stats['money']) }}</span> đ</div>
      <div class="stat-desc">Tổng đóng góp bằng tiền</div>
    </div>
  </div>
</div>

<!-- WORKFLOW + ĐÓNG GÓP GẦN ĐÂY (DỮ LIỆU THẬT, ĐÃ MASK) -->
<section class="workflow-section" id="reports">
  <div class="workflow-grid">
    <div>
      <p class="section-label">Quy trình</p>
      <h2 class="section-title" style="font-size:36px;letter-spacing:-0.72px">Bắt đầu trong vài phút</h2>
      <div class="workflow-steps">
        <div class="w-step">
          <div class="w-num">1</div>
          <div>
            <div class="w-title">Tạo tài khoản & đăng nhập</div>
            <div class="w-desc">Đăng ký với email tổ chức. Admin được cấp quyền quản trị toàn bộ hệ thống ngay lập tức.</div>
          </div>
        </div>
        <div class="w-step">
          <div class="w-num">2</div>
          <div>
            <div class="w-title">Tạo dự án thiện nguyện</div>
            <div class="w-desc">Điền thông tin dự án, mục tiêu, thời gian. Hệ thống tự tạo trang theo dõi riêng.</div>
          </div>
        </div>
        <div class="w-step">
          <div class="w-num">3</div>
          <div>
            <div class="w-title">Thêm thành viên & đóng góp</div>
            <div class="w-desc">Mời tình nguyện viên, ghi nhận đóng góp tiền và hiện vật theo từng đợt.</div>
          </div>
        </div>
        <div class="w-step">
          <div class="w-num">4</div>
          <div>
            <div class="w-title">Theo dõi & báo cáo</div>
            <div class="w-desc">Xem tổng quan trên dashboard, xuất báo cáo minh bạch chia sẻ với cộng đồng.</div>
          </div>
        </div>
      </div>
    </div>
    <div class="workflow-mockup">
      <div class="contrib-title">Đóng góp gần đây</div>

      @forelse($recentDonations as $donation)
      <div class="contrib-row">
        <div class="contrib-avatar">{{ mb_substr($donation->display_donor_name, 0, 2) }}</div>
        <div class="contrib-name">{{ $donation->display_donor_name }}</div>
        <div class="contrib-amt">
            @if($donation->type === 'money')
                {{ number_format($donation->amount) }} đ
            @else
                {{ $donation->goods_quantity }} {{ $donation->goods_description }}
            @endif
        </div>
        <span class="contrib-type">
            @if($donation->type === 'goods')
                Hiện vật
            @else
                {{ $donation->payment_method === 'transfer' ? 'Chuyển khoản' : 'Tiền mặt' }}
            @endif
        </span>
      </div>
      @empty
      <div style="padding:16px 0;color:#8a8a8a;font-size:13px">Chưa có khoản đóng góp nào được ghi nhận.</div>
      @endforelse

      <div style="margin-top:20px;padding-top:16px;border-top:1px solid #2d2d2d;display:flex;justify-content:space-between;align-items:center">
        <span style="font-size:12px;color:#707070">Tổng đóng góp bằng tiền toàn hệ thống</span>
        <span style="font-size:15px;font-weight:500;color:var(--primary)">{{ number_format($stats['money']) }} đ</span>
      </div>
    </div>
  </div>
</section>

<!-- CTA BAND -->
<section class="cta-band">
  <h2 class="cta-title">Bắt đầu quản lý thiện nguyện hôm nay</h2>
  <p class="cta-sub">Miễn phí, không cần thẻ tín dụng. Thiết lập trong 5 phút.</p>
  <div style="display:flex;justify-content:center;gap:12px">
    @auth
    <a href="{{ route('dashboard') }}" class="btn-primary-lg">Vào Dashboard</a>
    @else
    <a href="{{ route('register') }}" class="btn-primary-lg">Tạo tài khoản miễn phí</a>
    <a href="{{ route('login') }}" class="btn-outline-lg">Đăng nhập</a>
    @endauth
  </div>
</section>

@include('partials.footer')

</body>
</html>
