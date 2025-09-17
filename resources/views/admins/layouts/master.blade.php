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
        @yield('content')
    </div>
    <!-- /.content-wrapper -->

    @include('admins.blocks.footer')

</div>

<!-- thêm js nếu cần -->
<script src="{{ asset('admins/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('admins/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<<<<<<< HEAD

@yield('scripts')
=======
<script>
    // ẩn thông báo sau 3 giây
    setTimeout(function () {
        const alertEl = document.querySelector('.alert');
        if (alertEl) {
            alertEl.classList.remove('show');
            alertEl.classList.add('fade');
            setTimeout(() => alertEl.remove(), 500);
        }
    }, 3000);
</script>

@yield('scripts')
@stack('scripts')

>>>>>>> ef97e0d6fd0e636da8df978d1157cfe6edf30bc8

</body>
</html>
