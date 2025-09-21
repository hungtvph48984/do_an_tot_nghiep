<!DOCTYPE html>
<html lang="vi">
<head>
    @include('clients.layouts.header')

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Thêm Font Awesome từ CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    @stack('styles')
</head>
<body>

    {{-- Nội dung chính --}}
    @yield('content')

    {{-- Footer --}}
    @include('clients.layouts.footer')

    @stack('scripts')
</body>
</html>
