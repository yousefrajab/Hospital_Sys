<div class="nav-outer clearfix">
    {{-- تم حذف الـ div الذي يحتوي على نص الترحيب من هنا --}}

    <!--Mobile Navigation Toggler For Mobile-->
    <div class="mobile-nav-toggler"><span class="icon flaticon-menu"></span></div>
    <nav class="main-menu navbar-expand-md navbar-light">
        <div class="navbar-header">
            <!-- Toggle Button -->
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="icon flaticon-menu"></span>
            </button>
        </div>

        <div class="collapse navbar-collapse clearfix" id="navbarSupportedContent">
            <ul class="navigation clearfix">
                <li class="current dropdown"><a href="#">الرئيسية</a>

                </li>
                <li class="dropdown"><a href="#">من نحن</a>
                    <ul>
                        <li><a href="about.html">About Us</a></li>
                        <li><a href="team.html">Our Team</a></li>
                        <li><a href="faq.html">Faq</a></li>
                        <li><a href="services.html">Services</a></li>
                        <li><a href="gallery.html">Gallery</a></li>
                        <li><a href="comming-soon.html">Comming Soon</a></li>
                    </ul>
                </li>
                <li class="dropdown has-mega-menu"><a href="#">الصفحات</a>
                    <div class="mega-menu">
                        <div class="mega-menu-bar row clearfix">
                            <div class="column col-md-3 col-xs-12">
                                <h3>About Us</h3>
                                <ul>
                                    <li><a href="about.html">About Us</a></li>
                                    <li><a href="team.html">Our Team</a></li>
                                    <li><a href="faq.html">Faq</a></li>
                                    <li><a href="services.html">Services</a></li>
                                </ul>
                            </div>
                            <div class="column col-md-3 col-xs-12">
                                <h3>Doctors</h3>
                                <ul>
                                    <li><a href="doctors.html">Doctors</a></li>
                                    <li><a href="doctors-detail.html">Doctors Detail</a></li>
                                </ul>
                            </div>
                            <div class="column col-md-3 col-xs-12">
                                <h3>Blog</h3>
                                <ul>
                                    <li><a href="blog.html">Our Blog</a></li>
                                    <li><a href="blog-classic.html">Blog Classic</a></li>
                                    <li><a href="blog-detail.html">Blog Detail</a></li>
                                </ul>
                            </div>
                            <div class="column col-md-3 col-xs-12">
                                <h3>Shops</h3>
                                <ul>
                                    <li><a href="shop.html">Shop</a></li>
                                    <li><a href="shop-single.html">Shop Details</a></li>
                                    <li><a href="shoping-cart.html">Cart Page</a></li>
                                    <li><a href="checkout.html">Checkout Page</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="dropdown"><a href="#">الاطباء</a>
                    <ul>
                        <li><a href="doctors.html">Doctors</a></li>
                        <li><a href="doctors-detail.html">Doctors Detail</a></li>
                    </ul>
                </li>
                <li class="dropdown"><a href="#">الاقسام</a>
                    <ul>
                        <li><a href="department.html">Department</a></li>
                        <li><a href="department-detail.html">Department Detail</a></li>
                    </ul>
                </li>
                <li class="dropdown"><a href="#">المقالات</a>
                    <ul>
                        <li><a href="blog.html">Our Blog</a></li>
                        <li><a href="blog-classic.html">Blog Classic</a></li>
                        <li><a href="blog-detail.html">Blog Detail</a></li>
                    </ul>
                </li>
                <li class="dropdown"><a href="#">المتجر</a>
                    <ul>
                        <li><a href="shop.html">Shop</a></li>
                        <li><a href="shop-single.html">Shop Details</a></li>
                        <li><a href="shoping-cart.html">Cart Page</a></li>
                        <li><a href="checkout.html">Checkout Page</a></li>
                    </ul>
                </li>

                <li><a href="contact.html">تواصل معانا</a></li>

                <li class="dropdown"><a href="#">{{ LaravelLocalization::getCurrentLocaleNative() }}</a>
                    <ul>
                        @foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                            <li>
                                <a rel="alternate" hreflang="{{ $localeCode }}"
                                    href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}">
                                    {{ $properties['native'] }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </li>
            </ul>
        </div>

    </nav>
    <!-- Main Menu End-->

    <!-- Main Menu End-->
    <div class="outer-box clearfix">

        {{-- ** هذا هو المكان الجديد لنص الترحيب ** --}}
        {{-- نستخدم div ونعطيه كلاس للتنسيق --}}
        <div class="welcome-text">
            مرحبا بعودتك {{ Auth::user()->name }}
            <form method="POST" action="{{ route('logout.patient') }}">
                @csrf
                <a class="dropdown-item" style="color: white" href="#"
                    onclick="event.preventDefault();
                                this.closest('form').submit();">
                                <i
                        class="bx bx-log-out" style="color: white"></i>تسجيل الخروج</a>
            </form>
        </div>
        {{-- ** نهاية المكان الجديد ** --}}


        <!-- Main Menu End-->
        <div class="nav-box">
            <div class="nav-btn nav-toggler navSidebar-button"><span class="icon flaticon-menu-1"></span></div>
        </div>
        <!-- Social Box -->
        <ul class="social-box clearfix">
            <li><a href="#"><span class="fab fa-facebook-f"></span></a></li>
            <li><a href="#"><span class="fab fa-twitter"></span></a></li>
            <li><a href="#"><span class="fab fa-linkedin-in"></span></a></li>
            {{-- <li><a title="تسجيل دخول" href="{{route('dashboard.patient.home')}}"><span class="fas fa-user"></span></a> --}}
            <li>
                <a title="لوحة التحكم"
                    href="@auth
@if (auth()->guard('patient')->check())
                            {{ route('dashboard.patient') }}
                        @elseif(auth()->guard('doctor')->check())
                            {{ route('dashboard.doctor') }}
                        @elseif(auth()->guard('admin')->check())
                            {{ route('dashboard.admin') }}
                        @elseif(auth()->guard('ray_employee')->check())
                            {{ route('dashboard.ray_employee') }}
                        @elseif(auth()->guard('laboratorie_employee')->check())
                            {{ route('dashboard.laboratorie_employee') }}
                        @else
                            {{ route('login') }} {{-- إذا لم يكن أي نوع معروف --}}
                        @endif
                    @else
                        {{ route('login') }} {{-- إذا لم يكن مسجل دخول --}} @endauth">
                    <span class="fas fa-user"></span>
                </a>
            </li>
            </li>


        </ul>

        <!-- Search Btn -->
        <div class="search-box-btn"><span class="icon flaticon-search"></span></div>

    </div>
</div>
<style>
    .outer-box {
        display: flex;
        align-items: center;
    }

    .welcome-text {
        font-size: 16px;
        color: #fff;
        margin-right: 15px;
        margin-top: 0;
        margin-bottom: 0;
    }

    .social-box {
        margin-right: 10px;
    }
</style>
