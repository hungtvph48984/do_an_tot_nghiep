<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="{{ route('admin.products.index') }}" class="brand-link">
        <img src="{{ asset('assets/admin/img/AdminLTELogo.png') }}" alt="AdminLTE Logo"
            class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">Admin</span>
    </a>

    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" role="menu">
                <li class="nav-item">
                    <a href="{{ url('admin') }}" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>


                <li class="nav-item">
                    <a href="{{ route('admin.categories.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-th"></i>
                        <p>Danh mục</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.products.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-truck"></i>
                        <p>Sản phẩm</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.brands.index') }}" class="nav-link">
                        <i class="nav-icon fa fa-book"></i>
                        <p>Brand</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.vouchers.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-inbox"></i>
                        <p>Voucher</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.orders.index')}}" class="nav-link">
                        <i class="nav-icon fas fa-user"></i>
                        <p>Đơn hàng</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.user.index')}}" class="nav-link">
                        <i class="nav-icon fas fa-user"></i>
                        <p>Người dùng</p>
                    </a>
                </li>

                                <li class="nav-item">
                    <a href="{{ route('admin.contacts.index')}}" class="nav-link">
                        <i class="nav-icon fas fa-user"></i>
                        <p>Liên hệ</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>
