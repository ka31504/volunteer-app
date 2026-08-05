<!-- NAV -->
<nav>
    <a href="{{ route('welcome') }}" class="logo">
        <span class="logo-dot"></span>
        Thiện Nguyện Hub
    </a>

    <div class="nav-links">
        <a href="#features">Tính năng</a>
        <a href="#projects">Dự án</a>
        <a href="#reports">Báo cáo</a>
        <a href="#about">Về chúng tôi</a>
    </div>

    <div class="nav-right">

        @auth
            <a href="{{ route('dashboard') }}" class="btn-primary">
                Dashboard
            </a>
        @else
            <a href="{{ route('login') }}" class="btn-outline">
                Đăng nhập
            </a>

            <a href="{{ route('register') }}" class="btn-primary">
                Bắt đầu miễn phí
            </a>
        @endauth

    </div>
</nav>