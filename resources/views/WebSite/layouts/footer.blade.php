<footer class="main-footer style-two">
    <div class="auto-container">
        <!--Widgets Section-->
        <div class="widgets-section">
            <div class="row clearfix">

                <!--big column-->
                <div class="big-column col-lg-6 col-md-12 col-sm-12">
                    <div class="row clearfix">

                        <!--Footer Column-->
                        <div class="footer-column col-lg-7 col-md-6 col-sm-12">
                            <div class="footer-widget logo-widget">
                                <div class="logo">
                                    <a href="{{ route('home') }}"><img
                                            src="{{ URL::asset('Dashboard/img/brand/hospital-logo.png') }}"
                                            alt="شعار نظام إدارة المستشفيات" /></a>
                                    <!-- ^^^ عدل مسار واسم الشعار إذا لزم الأمر -->
                                </div>
                                <p style="color: #fff; margin-top: 15px; margin-bottom: 10px;">: تابعنا على</p>
                                <!-- تعديل بسيط للستايل المباشر إذا لم يكن هناك كلاس CSS -->
                                <ul class="social-icons">
                                    <li class="facebook"><a href="https://facebook.com/Momensarsour" target="_blank" title="Facebook" class="fab fa-facebook-f"></a></li>
                                    <li class="twitter"><a href="https://x.com/Momensarsour" target="_blank" title="X (Twitter)" class="fab fa-twitter"></a></li>
                                    <li class="linkedin"><a href="https://linkedin.com/in/Momensarsour" target="_blank" title="LinkedIn" class="fab fa-linkedin-in"></a></li>
                                    <li class="instagram"><a href="https://instagram.com/Momensarsour" target="_blank" title="Instagram" class="fab fa-instagram"></a></li>
                                    <li class="youtube"><a href="https://wa.me/970567077179" target="_blank" title="WhatsApp" class="fab fa-youtube"></a></li>
                                </ul>
                                {{-- <ul class="social-icons">
                                    <!-- !! تأكد من أن هذه الكلاسات تعمل مع الثيم الخاص بك !! -->
                                    <li><a href="https://facebook.com/Momensarsour" target="_blank" title="Facebook"><span class="fab fa-facebook-f"></span></a></li>
                                    <li><a href="https://x.com/Momensarsour" target="_blank" title="X (Twitter)"><span class="fab fa-twitter"></span></a></li>
                                    <li><a href="https://linkedin.com/in/Momensarsour" target="_blank" title="LinkedIn"><span class="fab fa-linkedin"></span></a></li>
                                    <!-- الأيقونات التالية قد تحتاج لتعديل الكلاس أو استخدام طريقة أخرى إذا لم تكن مدعومة -->
                                    <li><a href="https://instagram.com/Momensarsour" target="_blank" title="Instagram"><span class="fab fa-instagram"></span></a></li> <!-- قد يكون الكلاس مختلفاً -->
                                    <li><a href="https://wa.me/970567077179" target="_blank" title="WhatsApp"><span class="fab fa-whatsapp"></span></a></li> <!-- قد يكون الكلاس مختلفاً -->
                                </ul> --}}
                            </div>
                        </div>

                        <!--Footer Column-->
                        <div class="footer-column col-lg-5 col-md-6 col-sm-12">
                            <div class="footer-widget links-widget">
                                <div class="footer-title  clearfix">
                                    <h2>روابط سريعة</h2>
                                    <div class="separator"></div>
                                </div>
                                <ul class="footer-list">
                                    <!-- !!! عدل الراوتات لتناسب مشروعك !!! -->
                                    <li><a href="{{ route('home') }}">الرئيسية</a></li>
                                    <li><a href="{{ route('home') }}">من نحن</a></li>
                                    <li><a href="{{ route('home') }}">خدمات النظام</a></li>
                                    <li><a href="{{ route('home') }}">الأطباء</a></li>
                                    <li><a href="{{ route('home') }}">اتصل بنا</a></li>
                                    <li><a href="{{ route('home') }}">الأسئلة الشائعة</a></li>
                                </ul>
                            </div>
                        </div>

                    </div>
                </div>

                <!--big column-->
                <div class="big-column col-lg-6 col-md-12 col-sm-12">
                    <div class="row clearfix">

                        <!--Footer Column-->
                        <div class="footer-column col-lg-6 col-md-6 col-sm-12">
                            <div class="footer-widget news-widget">
                                <div class="footer-title  clearfix">
                                    <h2>آخر الأخبار</h2>
                                    <div class="separator"></div>
                                </div>

                                <!--News Widget Block-->
                                <div class="news-widget-block">
                                    <div class="widget-inner">
                                        <div class="image">
                                            <img src="{{ URL::asset('Dashboard/img/media/ps1.jpg') }}"
                                                alt="صورة خبر 1" />
                                            <!-- ^^^ عدل مسار الصورة إذا لزم الأمر -->
                                        </div>
                                        <h3><a href="#">نصائح للحفاظ على صحتك اليومية</a>
                                        </h3>
                                        <div class="post-date">25 أكتوبر 2023</div>
                                    </div>
                                </div>

                                <!--News Widget Block-->
                                <div class="news-widget-block">
                                    <div class="widget-inner">
                                        <div class="image">
                                            <img src="{{ URL::asset('Dashboard/img/media/cardback.jpeg') }}"
                                                alt="صورة خبر 2" />
                                            <!-- ^^^ عدل مسار الصورة إذا لزم الأمر -->
                                        </div>
                                        <h3><a href="#">تحديثات جديدة في نظام إدارة المستشفيات</a></h3>
                                        <div class="post-date">20 أكتوبر 2023</div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <!--Footer Column-->
                        <div class="footer-column col-lg-6 col-md-6 col-sm-12">
                            <div class="footer-widget contact-widget">
                                <div class="footer-title  clearfix">
                                    <h2>اتصل بنا</h2>
                                    <div class="separator"></div>
                                </div>

                                <ul class="contact-list">
                                    <li>
                                        <span class="icon flaticon-placeholder"></span>
                                        <!-- استخدام أيقونة flaticon الأصلية -->
                                        فلسطين
                                        <br> (غزة - النصيرات)
                                    </li>
                                    <li>
                                        <span class="icon flaticon-call"></span>
                                        <!-- استخدام أيقونة flaticon الأصلية -->
                                        من السبت إلى الخميس: من 9:00 صباحًا حتى 5:00 مساءً<br>
                                        <a href="tel:+970567077179"
                                            style="text-decoration: none; color: inherit; direction: ltr; display: inline-block;">+970
                                            56 707 7179</a>
                                        <!-- ^^^ أضفت direction: ltr للرقم لضمان عرضه صحيحاً -->
                                    </li>
                                    <li>
                                        <span class="icon flaticon-message"></span>
                                        <!-- استخدام أيقونة flaticon الأصلية -->
                                        هل لديك سؤال ؟ راسلنا عبر:<br class="mt-3"><br>
                                        <a href="mailto:momensarsour5@gmail.com"
                                            style="text-decoration: none; color: inherit;">momensarsour5@gmail.com</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--Footer Bottom-->
    <div class="footer-bottom" style="background-color: #1f2327; color: #a9a9a9; padding: 20px 0; text-align: center;">
        <!-- ستايل مقترح للفوتر السفلي -->
        <div class="auto-container">
            <div class="copyright-text">
                <p style="margin: 0; font-size: 14px;">© حقوق النشر {{ date('Y') }} ، جميع الحقوق محفوظة لـ <a
                        href="{{ route('home') }}" style="color: #00bcd4; text-decoration: none;">نظام إدارة
                        المستشفيات</a>.
                    <br class="d-sm-none"> <!-- فاصل سطر على الشاشات الصغيرة فقط -->
                    تصميم وتطوير <a href="https://www.facebook.com/Momensarsour" target="_blank"
                        style="color: #00bcd4; text-decoration: none;">Mo'men Sarsour & Yousef Rajab</a>
                </p>
            </div>
        </div>
    </div>
</footer>
