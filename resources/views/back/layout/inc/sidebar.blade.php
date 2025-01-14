<div class="vertical-menu">

    <div data-simplebar class="h-100">

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title" data-key="t-menu">Menu</li>

                <li>
                    <a href="{{ route('admin.dashboard') }}">
                        <i data-feather="home"></i>
                        <span data-key="t-dashboard">Dashboard</span>
                    </a>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow">
                        <i data-feather="grid"></i>
                        <span data-key="t-apps">Product</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li>
                            <a href="{{ route('admin.all-products') }}">
                                <span data-key="t-calendar">View Products</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.add-product') }}">
                                <span data-key="t-calendar">Add Product</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="{{ route('admin.order-items') }}">
                        <i data-feather="shopping-cart"></i>
                        <span data-key="t-cart">Orders</span>
                    </a>
                </li>

                <li>
                    <a href="index.html">
                        <i data-feather="home"></i>
                        <span data-key="t-dashboard">Products Page</span>
                    </a>
                </li>

            </ul>


        </div>
        <!-- Sidebar -->
    </div>
</div>
