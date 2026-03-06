<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <!-- Dark Logo-->
        <a href="{{ route('home') }}" class="logo logo-dark">
            <span class="logo-sm">
                <img src="{{ Storage::disk('spaces')->url(App\Models\Settings::first()->logo) }}" alt="logo" height="22">
            </span>
            <span class="logo-lg">
                <img src="{{ Storage::disk('spaces')->url(App\Models\Settings::first()->logo) }}" alt="logo" height="60">
            </span>
        </a>
        <!-- Light Logo-->
        <a href="{{ route('home') }}" class="logo logo-light">
            <span class="logo-sm">
                <img src="{{ Storage::disk('spaces')->url(App\Models\Settings::first()->logo) }}" alt="logo" height="22">
            </span>
            <span class="logo-lg">
                <img src="{{ Storage::disk('spaces')->url(App\Models\Settings::first()->logo) }}" alt="logo" height="60">
            </span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover"
            id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>
    @php
    $user = Auth::user();
    $type = $user->type;
    $isAdmin = in_array($type, ['admin', 'dealer']);
    @endphp
    <div id="scrollbar">
        <div class="container-fluid">

            <div id="two-column-menu">
            </div>
            <ul class="navbar-nav" id="navbar-nav">

                <li class="menu-title"><span data-key="t-menu">Menu</span></li>

                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->routeIs('home') ? 'active' : '' }}"
                        href="{{ route('home') }}">
                        <i class="fa fa-home"></i> <span data-key="t-widgets">Dashboard</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->routeIs('facilities') || request()->routeIs('facilities.create') || request()->routeIs('facilities.edit') || request()->routeIs('facilities.view') ? 'active' : '' }}"
                        href="#facilities" data-bs-toggle="collapse" role="button"
                        aria-expanded="{{ request()->routeIs('facilities') || request()->routeIs('facilities.create') || request()->routeIs('facilities.edit') || request()->routeIs('facilities.view') ? 'true' : 'false' }}"
                        aria-controls="facilities">
                        <i class="fa fa-table"></i> <span data-key="t-tables">Facilities</span>
                    </a>
                    <div class="collapse menu-dropdown {{ request()->routeIs('facilities') || request()->routeIs('facilities.create') || request()->routeIs('facilities.edit') || request()->routeIs('facilities.view') ? 'show' : '' }}"
                        id="facilities">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('facilities') }}"
                                    class="nav-link {{ request()->routeIs('facilities') || request()->routeIs('facilities.edit') || request()->routeIs('facilities.view') ? 'active' : '' }}"
                                    data-key="t-basic-tables">List
                                    Facilities</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('facilities.create') }}"
                                    class="nav-link {{ request()->routeIs('facilities.create') ? 'active' : '' }}"
                                    data-key="t-basic-tables">Add
                                    Facilities</a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->routeIs('salons') || request()->routeIs('salon.create') || request()->routeIs('salon.edit') || request()->routeIs('salon.view') || request()->routeIs('salon.appointments') || request()->routeIs('salon.appointment.view') || request()->routeIs('salon.orders') || request()->routeIs('salon.order.view') || request()->routeIs('salon.requests') || request()->routeIs('salon.request.view') ? 'active' : '' }}"
                        href="#partners" data-bs-toggle="collapse" role="button"
                        aria-expanded="{{ request()->routeIs('salons') || request()->routeIs('salon.create') || request()->routeIs('salon.edit') || request()->routeIs('salon.view') || request()->routeIs('salon.appointments') || request()->routeIs('salon.appointment.view') || request()->routeIs('salon.orders') || request()->routeIs('salon.order.view') || request()->routeIs('salon.requests') || request()->routeIs('salon.request.view') ? 'true' : 'false' }}"
                        aria-controls="partners">
                        <i class="fa fa-building"></i> <span data-key="t-tables">Partners</span>
                    </a>
                    <div class="collapse menu-dropdown {{ request()->routeIs('salons') || request()->routeIs('salon.create') || request()->routeIs('salon.edit') || request()->routeIs('salon.view') || request()->routeIs('salon.appointments') || request()->routeIs('salon.appointment.view') || request()->routeIs('salon.orders') || request()->routeIs('salon.order.view') || request()->routeIs('salon.requests') || request()->routeIs('salon.request.view') ? 'show' : '' }}"
                        id="partners">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('salons') }}"
                                    class="nav-link {{ request()->routeIs('salons') || request()->routeIs('salon.edit') || request()->routeIs('salon.view') ? 'active' : '' }}"
                                    data-key="t-basic-tables">List
                                    Partners</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('salon.create') }}"
                                    class="nav-link {{ request()->routeIs('salon.create') ? 'active' : '' }}"
                                    data-key="t-basic-tables">Add
                                    Partner</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('salon.appointments') }}"
                                    class="nav-link {{ request()->routeIs('salon.appointments') || request()->routeIs('salon.appointment.view') ? 'active' : '' }}"
                                    data-key="t-grid-js">Appointments</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('salon.orders') }}"
                                    class="nav-link {{ request()->routeIs('salon.orders') || request()->routeIs('salon.order.view') ? 'active' : '' }}"
                                    data-key="t-grid-js">Product Orders</a>
                            </li>
                            @if($isAdmin)
                            <li class="nav-item">
                                <a href="{{ route('salon.requests') }}"
                                    class="nav-link {{ request()->routeIs('salon.requests') || request()->routeIs('salon.request.view') ? 'active' : '' }}"
                                    data-key="t-grid-js">Joining
                                    Request</a>
                            </li>
                            @endif
                        </ul>
                    </div>
                </li>
                @if($isAdmin)
                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->routeIs('report.appointment.salon') || request()->routeIs('report.appointments') || request()->routeIs('report.orders') || request()->routeIs('report.appointments.upcoming') || request()->routeIs('report.offers') || request()->routeIs('report.wallet')|| request()->routeIs('report.withdrawals') || request()->routeIs('report.ads') || request()->routeIs('report.holidays') ? 'active' : '' }}"
                        href="#partner-reports" data-bs-toggle="collapse" role="button"
                        aria-expanded="{{ request()->routeIs('report.appointment.salon') || request()->routeIs('report.appointments') || request()->routeIs('report.orders') || request()->routeIs('report.appointments.upcoming') || request()->routeIs('report.offers') || request()->routeIs('report.wallet')|| request()->routeIs('report.withdrawals') || request()->routeIs('report.ads') || request()->routeIs('report.holidays') ? 'true' : 'false' }}"
                        aria-controls="partner-reports">
                        <i class="fa fa-file"></i> <span data-key="t-tables">Partner Reports</span>
                    </a>
                    <div class="collapse menu-dropdown {{ request()->routeIs('report.appointment.salon') || request()->routeIs('report.appointments') || request()->routeIs('report.orders') || request()->routeIs('report.appointments.upcoming') || request()->routeIs('report.offers') || request()->routeIs('report.wallet')|| request()->routeIs('report.withdrawals') || request()->routeIs('report.ads') || request()->routeIs('report.holidays') ? 'show' : '' }}"
                        id="partner-reports">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('report.appointment.salon') }}"
                                    class="nav-link {{ request()->routeIs('report.appointment.salon') ? 'active' : '' }}"
                                    data-key="t-grid-js">Partner Appointment Report</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('report.appointments') }}"
                                    class="nav-link {{ request()->routeIs('report.appointments') ? 'active' : '' }}"
                                    data-key="t-grid-js">Total Appointments Report</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('report.orders') }}"
                                    class="nav-link {{ request()->routeIs('report.orders') ? 'active' : '' }}"
                                    data-key="t-grid-js">Total Product Orders Report</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('report.appointments.upcoming') }}"
                                    class="nav-link {{ request()->routeIs('report.appointments.upcoming') ? 'active' : '' }}"
                                    data-key="t-grid-js">Upcoming Appointments Report</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('report.offers') }}"
                                    class="nav-link {{ request()->routeIs('report.offers') ? 'active' : '' }}"
                                    data-key="t-grid-js">Coupons Report</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('report.wallet') }}"
                                    class="nav-link {{ request()->routeIs('report.wallet') ? 'active' : '' }}"
                                    data-key="t-grid-js">Wallet Report</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('report.withdrawals') }}"
                                    class="nav-link {{ request()->routeIs('report.withdrawals') ? 'active' : '' }}"
                                    data-key="t-grid-js">Withdrawals Report</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('report.ads') }}"
                                    class="nav-link {{ request()->routeIs('report.ads') ? 'active' : '' }}"
                                    data-key="t-grid-js">Ads Report</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('report.holidays') }}"
                                    class="nav-link {{ request()->routeIs('report.holidays') ? 'active' : '' }}"
                                    data-key="t-grid-js">Partner Holidays Report</a>
                            </li>
                        </ul>
                    </div>
                </li>
                @endif
                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->routeIs('freelancers') || request()->routeIs('freelancer.create') || request()->routeIs('freelancer.edit') || request()->routeIs('freelancer.view') || request()->routeIs('freelancer.appointments') || request()->routeIs('freelancer.appointment.view') || request()->routeIs('freelancer.orders') || request()->routeIs('freelancer.order.view') || request()->routeIs('freelancer.requests') || request()->routeIs('freelancer.request.view') ? 'active' : '' }}"
                        href="#freelancers" data-bs-toggle="collapse" role="button"
                        aria-expanded="{{ request()->routeIs('freelancers') || request()->routeIs('freelancer.create') || request()->routeIs('freelancer.edit') || request()->routeIs('freelancer.view') || request()->routeIs('freelancer.appointments') || request()->routeIs('freelancer.appointment.view') || request()->routeIs('freelancer.orders') || request()->routeIs('freelancer.order.view') || request()->routeIs('freelancer.requests') || request()->routeIs('freelancer.request.view') ? 'true' : 'false' }}"
                        aria-controls="freelancers">
                        <i class="fa fa-user-astronaut"></i> <span data-key="t-tables">Freelancers</span>
                    </a>
                    <div class="collapse menu-dropdown {{ request()->routeIs('freelancers') || request()->routeIs('freelancer.create') || request()->routeIs('freelancer.edit') || request()->routeIs('freelancer.view') || request()->routeIs('freelancer.appointments') || request()->routeIs('freelancer.appointment.view') || request()->routeIs('freelancer.orders') || request()->routeIs('freelancer.order.view') || request()->routeIs('freelancer.requests') || request()->routeIs('freelancer.request.view') ? 'show' : '' }}"
                        id="freelancers">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('freelancers') }}"
                                    class="nav-link {{ request()->routeIs('freelancers') || request()->routeIs('freelancer.edit') || request()->routeIs('freelancer.view') ? 'active' : '' }}"
                                    data-key="t-basic-tables">List
                                    Freelancers</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('freelancer.create') }}"
                                    class="nav-link {{ request()->routeIs('freelancer.create') ? 'active' : '' }}"
                                    data-key="t-basic-tables">Add
                                    Freelancer</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('freelancer.appointments') }}"
                                    class="nav-link {{ request()->routeIs('freelancer.appointments') || request()->routeIs('freelancer.appointment.view') ? 'active' : '' }}"
                                    data-key="t-grid-js">Appointments</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('freelancer.orders') }}"
                                    class="nav-link {{ request()->routeIs('freelancer.orders') || request()->routeIs('freelancer.order.view') ? 'active' : '' }}"
                                    data-key="t-grid-js">Product Orders</a>
                            </li>
                            @if($isAdmin)
                            <li class="nav-item">
                                <a href="{{ route('freelancer.requests') }}"
                                    class="nav-link {{ request()->routeIs('freelancer.requests') || request()->routeIs('freelancer.request.view') ? 'active' : '' }}"
                                    data-key="t-grid-js">Joining
                                    Request</a>
                            </li>
                            @endif
                        </ul>
                    </div>
                </li>
                @if($isAdmin)
                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->routeIs('report.appointment.freelancer') || request()->routeIs('report.appointments') || request()->routeIs('report.orders') || request()->routeIs('report.appointments.upcoming') || request()->routeIs('report.offers') || request()->routeIs('report.wallet') || request()->routeIs('report.withdrawals') || request()->routeIs('report.ads') || request()->routeIs('report.holidays') ? 'active' : '' }}"
                        href="#freelancer-reports" data-bs-toggle="collapse" role="button"
                        aria-expanded="{{ request()->routeIs('report.appointment.freelancer') || request()->routeIs('report.appointments') || request()->routeIs('report.orders') || request()->routeIs('report.appointments.upcoming') || request()->routeIs('report.offers') || request()->routeIs('report.wallet') || request()->routeIs('report.withdrawals') || request()->routeIs('report.ads') || request()->routeIs('report.holidays') ? 'true' : 'false' }}"
                        aria-controls="freelancer-reports">
                        <i class="fa fa-file"></i> <span data-key="t-tables">Freelancer Reports</span>
                    </a>
                    <div class="collapse menu-dropdown {{ request()->routeIs('report.appointment.freelancer') || request()->routeIs('report.appointments') || request()->routeIs('report.orders') || request()->routeIs('report.appointments.upcoming') || request()->routeIs('report.offers') || request()->routeIs('report.wallet') || request()->routeIs('report.withdrawals') || request()->routeIs('report.ads') || request()->routeIs('report.holidays') ? 'show' : '' }}"
                        id="freelancer-reports">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('report.appointment.freelancer') }}"
                                    class="nav-link {{ request()->routeIs('report.appointment.freelancer') ? 'active' : '' }}"
                                    data-key="t-grid-js">Freelancer Appointment Report</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('report.appointments') }}"
                                    class="nav-link {{ request()->routeIs('report.appointments') ? 'active' : '' }}"
                                    data-key="t-grid-js">Total Appointments Report</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('report.orders') }}"
                                    class="nav-link {{ request()->routeIs('report.orders') ? 'active' : '' }}"
                                    data-key="t-grid-js">Total Product Orders Report</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('report.appointments.upcoming') }}"
                                    class="nav-link {{ request()->routeIs('report.appointments.upcoming') ? 'active' : '' }}"
                                    data-key="t-grid-js">Upcoming Appointments Report</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('report.offers') }}"
                                    class="nav-link {{ request()->routeIs('report.offers') ? 'active' : '' }}"
                                    data-key="t-grid-js">Coupons Report</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('report.wallet') }}"
                                    class="nav-link {{ request()->routeIs('report.wallet') ? 'active' : '' }}"
                                    data-key="t-grid-js">Wallet Report</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('report.withdrawals') }}"
                                    class="nav-link {{ request()->routeIs('report.withdrawals') ? 'active' : '' }}"
                                    data-key="t-grid-js">Withdrawals Report</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('report.ads') }}"
                                    class="nav-link {{ request()->routeIs('report.ads') ? 'active' : '' }}"
                                    data-key="t-grid-js">Ads Report</a>
                            </li>
                        </ul>
                    </div>
                </li>
                @endif
                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->routeIs('shop.categories') || request()->routeIs('shop.subcategories') || request()->routeIs('shop.products') || request()->routeIs('shop.orders') ? 'active' : '' }}"
                        href="dashboard-projects.html#shop" data-bs-toggle="collapse" role="button"
                        aria-expanded="{{ request()->routeIs('shop.categories') || request()->routeIs('shop.subcategories') || request()->routeIs('shop.products') || request()->routeIs('shop.orders') ? 'true' : 'false' }}"
                        aria-controls="shop">
                        <i class="fa fa-shopping-bag"></i> <span data-key="t-tables">Shop</span>
                    </a>
                    <div class="collapse menu-dropdown {{ request()->routeIs('shop.categories') || request()->routeIs('shop.subcategories') || request()->routeIs('shop.products') || request()->routeIs('shop.orders') ? 'show' : '' }}"
                        id="shop">
                        <ul class="nav nav-sm flex-column">
                            @if(Auth::user()->type == 'admin')
                            <li class="nav-item">
                                <a href="{{ route('shop.categories') }}"
                                    class="nav-link {{ request()->routeIs('shop.categories') ? 'active' : '' }}"
                                    data-key="t-basic-tables">Categories</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('shop.subcategories') }}"
                                    class="nav-link {{ request()->routeIs('shop.subcategories') ? 'active' : '' }}"
                                    data-key="t-grid-js">Sub Categories</a>
                            </li>
                            @endif
                            <li class="nav-item">
                                <a href="{{ route('shop.products') }}"
                                    class="nav-link {{ request()->routeIs('shop.products') ? 'active' : '' }}"
                                    data-key="t-grid-js">Products</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('shop.orders') }}"
                                    class="nav-link {{ request()->routeIs('shop.orders') ? 'active' : '' }}"
                                    data-key="t-grid-js">Orders</a>
                            </li>
                        </ul>
                    </div>
                </li>
                @if ($isAdmin)
                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->routeIs('report.orders') ? 'active' : '' }}"
                        href="#shop-reports" data-bs-toggle="collapse" role="button"
                        aria-expanded="{{ request()->routeIs('report.orders') ? 'true' : 'false' }}"
                        aria-controls="shop-reports">
                        <i class="fa fa-file"></i> <span data-key="t-tables">Shop Reports</span>
                    </a>
                    <div class="collapse menu-dropdown {{ request()->routeIs('report.orders') ? 'show' : '' }}"
                        id="shop-reports">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('report.orders') }}"
                                    class="nav-link {{ request()->routeIs('report.orders') ? 'active' : '' }}"
                                    data-key="t-grid-js">Total Product Orders Report</a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->routeIs('withdrawals') ? 'active' : '' }}"
                        href="{{ route('withdrawals') }}">
                        <i class="fa fa-wallet"></i> <span data-key="t-widgets">Wallet Withdrawals</span>
                    </a>
                </li>
                @endif
                @if(Auth::user()->type == 'admin')
                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->routeIs('chat') || request()->routeIs('chat.*') ? 'active' : '' }}"
                        href="{{ route('chat') }}">
                        <i class="fa fa-comments"></i> <span data-key="t-widgets">Chat</span>
                    </a>
                </li>
                @endif
                @if(Auth::user()->type == 'admin')
                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->routeIs('banners') || request()->routeIs('banner.create') || request()->routeIs('banner.edit') ? 'active' : '' }}"
                        href="dashboard-projects.html#ads" data-bs-toggle="collapse" role="button"
                        aria-expanded="{{ request()->routeIs('banners') || request()->routeIs('banner.create') || request()->routeIs('banner.edit') ? 'true' : 'false' }}"
                        aria-controls="ads">
                        <i class="fa fa-image"></i> <span data-key="t-tables">ADS Banners</span>
                    </a>
                    <div class="collapse menu-dropdown {{ request()->routeIs('banners') || request()->routeIs('banner.create') || request()->routeIs('banner.edit') ? 'show' : '' }}"
                        id="ads">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('banners') }}"
                                    class="nav-link {{ request()->routeIs('banners') || request()->routeIs('banner.edit') ? 'active' : '' }}"
                                    data-key="t-basic-tables">List ADS Banners</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('banner.create') }}"
                                    class="nav-link {{ request()->routeIs('banner.create') ? 'active' : '' }}"
                                    data-key="t-grid-js">Add ADS Banner</a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->routeIs('banners') || request()->routeIs('banner.create') || request()->routeIs('banner.edit') ? 'active' : '' }}"
                        href="dashboard-projects.html#partner_ads" data-bs-toggle="collapse" role="button"
                        aria-expanded="{{ request()->routeIs('banners') || request()->routeIs('banner.create') || request()->routeIs('banner.edit') ? 'true' : 'false' }}"
                        aria-controls="partner_ads">
                        <i class="fa fa-image"></i> <span data-key="t-tables">Partner Ads</span>
                    </a>
                    <div class="collapse menu-dropdown {{ request()->routeIs('partner-ads') || request()->routeIs('partner-ads.create') || request()->routeIs('partner-ads.edit') ? 'show' : '' }}"
                        id="partner_ads">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('partner-ads') }}"
                                    class="nav-link {{ request()->routeIs('partner-ads') || request()->routeIs('partner-ads.edit') ? 'active' : '' }}"
                                    data-key="t-basic-tables">List Partner Ads</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('partner-ads.create') }}"
                                    class="nav-link {{ request()->routeIs('partner-ads.create') ? 'active' : '' }}"
                                    data-key="t-grid-js">Add Partner Ads</a>
                            </li>
                        </ul>
                    </div>
                </li>
                @endif
                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->routeIs('offers') || request()->routeIs('offer.create') || request()->routeIs('offer.edit') ? 'active' : '' }}"
                        href="dashboard-projects.html#offers" data-bs-toggle="collapse" role="button"
                        aria-expanded="{{ request()->routeIs('offers') || request()->routeIs('offer.create') || request()->routeIs('offer.edit') ? 'true' : 'false' }}"
                        aria-controls="offers">
                        <i class="fa fa-volume-high"></i> <span data-key="t-tables">Coupons</span>
                    </a>
                    <div class="collapse menu-dropdown {{ request()->routeIs('offers') || request()->routeIs('offer.create') || request()->routeIs('offer.edit') ? 'show' : '' }}"
                        id="offers">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('offers') }}"
                                    class="nav-link {{ request()->routeIs('offers') || request()->routeIs('offer.edit') ? 'active' : '' }}"
                                    data-key="t-basic-tables">List Coupons</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('offer.create') }}"
                                    class="nav-link {{ request()->routeIs('offer.create') ? 'active' : '' }}"
                                    data-key="t-grid-js">Add Coupon</a>
                            </li>
                        </ul>
                    </div>
                </li>

                @if($isAdmin)
                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->routeIs('report.*') ? 'active' : '' }}"
                        href="dashboard-blogs.html#report" data-bs-toggle="collapse" role="button"
                        aria-expanded="{{ request()->routeIs('report.*') ? 'true' : 'false' }}"
                        aria-controls="report">
                        <i class="fa fa-file"></i> <span data-key="t-tables">Reports</span>
                    </a>
                    <div class="collapse menu-dropdown {{ request()->routeIs('report.*') ? 'show' : '' }}"
                        id="report">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('report.category') }}"
                                    class="nav-link {{ request()->routeIs('report.category') ? 'active' : '' }}"
                                    data-key="t-basic-tables">Category Report</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('report.service') }}"
                                    class="nav-link {{ request()->routeIs('report.service') ? 'active' : '' }}"
                                    data-key="t-grid-js">Service Report</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('report.appointments.district') }}"
                                    class="nav-link {{ request()->routeIs('report.appointments.district') ? 'active' : '' }}"
                                    data-key="t-grid-js">City wise Appointments Report</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('report.orders.district') }}"
                                    class="nav-link {{ request()->routeIs('report.orders.district') ? 'active' : '' }}"
                                    data-key="t-grid-js">City wise Product Orders Report</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('report.earnings') }}"
                                    class="nav-link {{ request()->routeIs('report.earnings') ? 'active' : '' }}"
                                    data-key="t-grid-js">Earnings Report</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('report.top.products') }}"
                                    class="nav-link {{ request()->routeIs('report.top.products') ? 'active' : '' }}"
                                    data-key="t-grid-js">Top Selling Product Report</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('report.top.services') }}"
                                    class="nav-link {{ request()->routeIs('report.top.services') ? 'active' : '' }}"
                                    data-key="t-grid-js">Top Booked Service Report</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('report.appointments.reminder') }}"
                                    class="nav-link {{ request()->routeIs('report.appointments.reminder') ? 'active' : '' }}"
                                    data-key="t-grid-js">Completed Appointment Reminder Report</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('report.customers') }}"
                                    class="nav-link {{ request()->routeIs('report.customers') ? 'active' : '' }}"
                                    data-key="t-grid-js">Customers Report</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('report.executives') }}"
                                    class="nav-link {{ request()->routeIs('report.executives') ? 'active' : '' }}"
                                    data-key="t-grid-js">Executives Report</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('report.referrals') }}"
                                    class="nav-link {{ request()->routeIs('report.referrals') ? 'active' : '' }}"
                                    data-key="t-grid-js">Referrals Report</a>
                            </li>
                        </ul>
                    </div>
                </li>
                @endif

                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->routeIs('users') ? 'active' : '' }}"
                        href="{{ route('users') }}">
                        <i class="fa fa-user"></i> <span data-key="t-widgets">Users</span>
                    </a>
                </li>
                @if(Auth::user()->type == 'admin')
                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->routeIs('dealers') || request()->routeIs('dealer.create') ? 'active' : '' }}"
                        href="#dealers" data-bs-toggle="collapse" role="button"
                        aria-expanded="{{ request()->routeIs('dealers') || request()->routeIs('dealer.create') ? 'true' : 'false' }}"
                        aria-controls="dealers">
                        <i class="fa fa-user-tie"></i> <span data-key="t-tables">Dealers</span>
                    </a>
                    <div class="collapse menu-dropdown {{ request()->routeIs('dealers') || request()->routeIs('dealer.create') ? 'show' : '' }}"
                        id="dealers">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('dealers') }}"
                                    class="nav-link {{ request()->routeIs('dealers') || request()->routeIs('dealer.edit') || request()->routeIs('dealer.view') ? 'active' : '' }}"
                                    data-key="t-basic-tables">List
                                    Dealers</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('dealer.create') }}"
                                    class="nav-link {{ request()->routeIs('dealer.create') ? 'active' : '' }}"
                                    data-key="t-basic-tables">Add
                                    Dealer</a>
                            </li>
                        </ul>
                    </div>
                </li>
                @endif
                @if(Auth::user()->type == 'admin')
                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->routeIs('pages') ? 'active' : '' }}"
                        href="{{ route('pages') }}">
                        <i class="fa fa-file"></i> <span data-key="t-widgets">App Pages</span>
                    </a>
                </li>
                @endif
                @if(Auth::user()->type == 'admin')
                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->routeIs('complaint') ? 'active' : '' }}"
                        href="{{ route('complaint') }}">
                        <i class="fa fa-headphones"></i> <span data-key="t-widgets">Complaints</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->routeIs('contactform') ? 'active' : '' }}"
                        href="{{ route('contactform') }}">
                        <i class="fa fa-address-book"></i> <span data-key="t-widgets">Contact Form</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->routeIs('referral') ? 'active' : '' }}"
                        href="{{ route('referral') }}">
                        <i class="fa fa-tag"></i> <span data-key="t-widgets">Referral</span>
                    </a>
                </li>
                @endif

                @if(Auth::user()->type == 'admin')
                <li class="menu-title"><span data-key="t-menu">Master Data</span></li>

                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->routeIs('categories') ? 'active' : '' }}"
                        href="{{ route('categories') }}">
                        <i class="fa fa-list"></i> <span data-key="t-widgets">Categories</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->routeIs('services') || request()->routeIs('service.create') || request()->routeIs('service.edit') ? 'active' : '' }}"
                        href="dashboard-service.html#service" data-bs-toggle="collapse" role="button"
                        aria-expanded="{{ request()->routeIs('services') || request()->routeIs('service.create') || request()->routeIs('service.edit') ? 'true' : 'false' }}"
                        aria-controls="service">
                        <i class="fa fa-briefcase"></i> <span data-key="t-tables">Services</span>
                    </a>
                    <div class="collapse menu-dropdown {{ request()->routeIs('services') || request()->routeIs('service.create') || request()->routeIs('service.edit') ? 'show' : '' }}"
                        id="service">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('services') }}"
                                    class="nav-link {{ request()->routeIs('services') || request()->routeIs('service.edit') ? 'active' : '' }}"
                                    data-key="t-basic-tables">List Services</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('service.create') }}"
                                    class="nav-link {{ request()->routeIs('service.create') ? 'active' : '' }}"
                                    data-key="t-grid-js">Add Service</a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->routeIs('cities') ? 'active' : '' }}"
                        href="{{ route('cities') }}">
                        <i class="fa fa-globe"></i> <span data-key="t-widgets">Cities</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->routeIs('filters') ? 'active' : '' }}"
                        href="{{ route('filters') }}">
                        <i class="fa fa-search"></i> <span data-key="t-widgets">Filters</span>
                    </a>
                </li>
                @endif

                <li class="menu-title"><span data-key="t-menu">General</span></li>
                @if (Auth::user()->type == 'admin')
                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->routeIs('settings') ? 'active' : '' }}"
                        href="{{ route('settings') }}">
                        <i class="fa fa-cog"></i> <span data-key="t-widgets">Settings</span>
                    </a>
                </li>
                @endif
                @if (Auth::user()->type == 'admin')
                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->routeIs('deletion-requests') ? 'active' : '' }}"
                        href="{{ route('deletion-requests') }}">
                        <i class="fa fa-trash"></i> <span data-key="t-widgets">Deletion Requests</span>
                    </a>
                </li>
                @endif

                <li class="nav-item">
                    <a class="nav-link menu-link" href="{{ route('user.logout') }}">
                        <i class="fa fa-sign-out-alt"></i> <span data-key="t-widgets">LogOut</span>
                    </a>
                </li>

            </ul>
        </div>
        <!-- Sidebar -->
    </div>

    <div class="sidebar-background"></div>
</div>
