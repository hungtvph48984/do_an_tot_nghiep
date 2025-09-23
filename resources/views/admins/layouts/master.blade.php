<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Quản trị Admin</title>
    <link rel="stylesheet" href="{{ asset('admins/plugins/bootstrap/css/bootstrap.min.css') }}">
    <!-- thêm các file css/js nếu có -->
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

    @include('admins.blocks.header')
    @include('admins.blocks.nav')
    @include('admins.blocks.sidebar')

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <div class="container mt-3">
            {{-- Hiển thị thông báo flash --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>
    <!-- /.content-wrapper -->

    @include('admins.blocks.footer')

</div>

<!-- thêm js nếu cần -->
<script src="{{ asset('admins/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('admins/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script>
    // Ẩn thông báo sau 3 giây
    setTimeout(function () {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alertEl => {
            alertEl.classList.remove('show');
            alertEl.classList.add('fade');
            setTimeout(() => alertEl.remove(), 500);
        });
    }, 3000);
</script>

@yield('scripts')
@stack('scripts')

</body>
</html>
