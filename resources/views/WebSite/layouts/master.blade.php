<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    @include('WebSite.layouts.style')
    @livewireStyles
</head>

<body style="font-family: 'Cairo', sans-serif;">

    <div class="page-wrapper {{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'rtl' : '' }}">
        <!-- Preloader -->
        <div class="preloader"></div>

        <header class="main-header header-style-three">

            <!-- Header Upper -->
            <div class="header-upper">
                <div class="inner-container clearfix">

                    <!--Info-->
                    <div class="logo-outer">
                        <div class="logo"><a href="index.html"><img src="images/logo-3.png" alt=""
                                    title=""></a></div>
                    </div>

                    <!--Nav Box-->
                    @include('WebSite.layouts.header')

                </div>
            </div>
            <!--End Header Upper-->

            <!--Sticky Header-->
            <div class="sticky-header">
                <div class="auto-container clearfix">
                    <!--Logo-->
                    <div class="logo pull-left">
                        <a href="index.html" class="img-responsive"><img src="images/logo-small.png" alt=""
                                title=""></a>
                    </div>

                    <!--Right Col-->
                    <div class="right-col pull-right">
                        <!-- Main Menu -->
                        <nav class="main-menu navbar-expand-md">
                            <div class="navbar-collapse collapse clearfix" id="navbarSupportedContent1">
                                <ul class="navigation clearfix">
                                    <!--Keep This Empty / Menu will come through Javascript-->
                                </ul>
                            </div>
                        </nav><!-- Main Menu End-->
                    </div>

                </div>
            </div>
            <!--End Sticky Header-->

            <!-- Mobile Menu  -->
            <div class="mobile-menu">
                <div class="menu-backdrop"></div>
                <div class="close-btn"><span class="icon far fa-window-close"></span></div>

                <!--Here Menu Will Come Automatically Via Javascript / Same Menu as in Header-->
                <nav class="menu-box">
                    <div class="nav-logo"><a href="index.html"><img src="images/nav-logo.png" alt=""
                                title=""></a></div>

                    <ul class="navigation clearfix"><!--Keep This Empty / Menu will come through Javascript--></ul>
                </nav>
            </div><!-- End Mobile Menu -->

        </header>
        <!-- End Main Header -->
        @yield('content')

        @include('WebSite.layouts.footer')

    </div>
    <!--End pagewrapper-->

    <!--Scroll to top-->
    <div class="scroll-to-top scroll-to-target" data-target="html"><span class="fa fa-angle-up"></span></div>

    <!--Search Popup-->
    <div id="search-popup" class="search-popup">
        <div class="close-search theme-btn"><span class="fas fa-window-close"></span></div>
        <div class="popup-inner">
            <div class="overlay-layer"></div>
            <div class="search-form">
                <form method="post" action="index.html">
                    <div class="form-group">
                        <fieldset>
                            <input type="search" class="form-control" name="search-input" value=""
                                placeholder="Search Here" required>
                            <input type="submit" value="Search Now!" class="theme-btn">
                        </fieldset>
                    </div>
                </form>

                <br>
                <h3>Recent Search Keywords</h3>
                <ul class="recent-searches">
                    <li><a href="#">Business</a></li>
                    <li><a href="#">Web Development</a></li>
                    <li><a href="#">SEO</a></li>
                    <li><a href="#">Logistics</a></li>
                    <li><a href="#">Freedom</a></li>
                </ul>

            </div>

        </div>
    </div>

    <!-- sidebar cart item -->
    <div class="xs-sidebar-group info-group">
        <div class="xs-overlay xs-bg-black"></div>
        <div class="xs-sidebar-widget">
            <div class="sidebar-widget-container">
                <div class="widget-heading">
                    <a href="#" class="close-side-widget">
                        X
                    </a>
                </div>
                <div class="sidebar-textwidget">

                    <!-- Sidebar Info Content -->
                    <div class="sidebar-info-contents">
                        <div class="content-inner">
                            <div class="logo">
                                <a href="index.html"><img src="images/logo-3.png" alt="" /></a>
                            </div>
                            <div class="content-box">
                                <h2>من نحن</h2>
                                <p class="text">أين أنت في قلب مهمتنا. نأمل أن تعتبرنا منزلك الطبي - المكان الذي تشعر
                                    فيه بالأمان والراحة والرعاية. كمجموعة طبية متعددة التخصصات.</p>
                                <a href="mailto:momensarsour5@gmail.com" class="theme-btn btn-style-two"><span class="txt">تواصل معنا يعمري
                                    </span></a>
                            </div>
                            <div class="contact-info">
                                <h2>: تواصل عبر </h2>
                                <ul class="list-style-two">
                                    <li><span class="icon flaticon-map"></span>النصيرات - غزة </li>
                                    <li><span class="icon flaticon-telephone"></span><a href="tel:+970567077179"
                                            style="text-decoration: none; color: inherit; direction: ltr; display: inline-block;">+970
                                            56 707 7179</a></li>
                                    <li><span class="icon flaticon-message-1"></span><a
                                            href="mailto:momensarsour5@gmail.com"
                                            style="text-decoration: none; color: inherit;">momensarsour5@gmail.com</a>
                                    </li>
                                    <li><span class="icon flaticon-timetable"></span>من السبت إلى الخميس: من 9:00 صباحًا
                                        حتى 5:00 مساءً </li>
                                </ul>
                            </div>
                            <!-- Social Box -->
                            <ul class="social-box">
                                {{-- <li class="facebook"><a href="#" class="fab fa-facebook-f"></a></li>
                            <li class="twitter"><a href="#" class="fab fa-twitter"></a></li>
                            <li class="linkedin"><a href="#" class="fab fa-linkedin-in"></a></li>
                            <li class="instagram"><a href="#" class="fab fa-instagram"></a></li>
                            <li class="youtube"><a href="#" class="fab fa-youtube"></a></li> --}}
                                <li class="facebook"><a href="https://facebook.com/Momensarsour" target="_blank"
                                        title="Facebook" class="fab fa-facebook-f"></a></li>
                                <li class="twitter"><a href="https://x.com/Momensarsour" target="_blank"
                                        title="X (Twitter)" class="fab fa-twitter"></a></li>
                                <li class="linkedin"><a href="https://linkedin.com/in/Momensarsour" target="_blank"
                                        title="LinkedIn" class="fab fa-linkedin-in"></a></li>
                                <li class="instagram"><a href="https://instagram.com/Momensarsour" target="_blank"
                                        title="Instagram" class="fab fa-instagram"></a></li>
                                <li class="youtube"><a href="https://wa.me/970567077179" target="_blank"
                                        title="WhatsApp" class="fab fa-youtube"></a></li>

                            </ul>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <!-- END sidebar widget item -->

    <!-- Color Palate / Color Switcher -->
    <div class="color-palate">
        <div class="color-trigger">
            <i class="fas fa-cog"></i>
        </div>
        <div class="color-palate-head">
            <h6>Choose Your Color</h6>
        </div>
        <div class="various-color clearfix">
            <div class="colors-list">
                <span class="palate default-color active" data-theme-file="css/color-themes/default-theme.css"></span>
                <span class="palate green-color" data-theme-file="css/color-themes/green-theme.css"></span>
                <span class="palate olive-color" data-theme-file="css/color-themes/olive-theme.css"></span>
                <span class="palate orange-color" data-theme-file="css/color-themes/orange-theme.css"></span>
                <span class="palate purple-color" data-theme-file="css/color-themes/purple-theme.css"></span>
                <span class="palate teal-color" data-theme-file="css/color-themes/teal-theme.css"></span>
                <span class="palate brown-color" data-theme-file="css/color-themes/brown-theme.css"></span>
                <span class="palate redd-color" data-theme-file="css/color-themes/redd-color.css"></span>
            </div>
        </div>

        <ul class="rtl-version option-box">
            <li class="rtl">RTL Version</li>
            <li>LTR Version</li>
        </ul>

        <a href="#" class="purchase-btn">Purchase now $17</a>

        <div class="palate-foo">
            <span>You will find much more options for colors and styling in admin panel. This color picker is used only
                for demonstation purposes.</span>
        </div>

    </div>

    @include('WebSite.layouts.scripts')
    @livewireScripts
</body>

</html>
