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
                                    <a href="<?php echo e(route('home')); ?>"><img
                                            src="<?php echo e(URL::asset('Dashboard/img/brand/hospital-logo.png')); ?>"
                                            alt="<?php echo e(trans('patients.footer_logo_alt')); ?>" /></a>
                                </div>
                                <p style="color: #fff; margin-top: 15px; margin-bottom: 10px;"><?php echo e(trans('patients.footer_follow_us')); ?>:</p>
                                <ul class="social-icons">
                                    <li class="facebook"><a href="https://facebook.com/Momensarsour" target="_blank" title="Facebook" class="fab fa-facebook-f"></a></li>
                                    <li class="twitter"><a href="https://x.com/Momensarsour" target="_blank" title="X (Twitter)" class="fab fa-twitter"></a></li>
                                    <li class="linkedin"><a href="https://linkedin.com/in/Momensarsour" target="_blank" title="LinkedIn" class="fab fa-linkedin-in"></a></li>
                                    <li class="instagram"><a href="https://instagram.com/Momensarsour" target="_blank" title="Instagram" class="fab fa-instagram"></a></li>
                                    <li class="youtube"><a href="https://wa.me/970567077179" target="_blank" title="WhatsApp" class="fab fa-youtube"></a></li>
                                </ul>
                            </div>
                        </div>

                        <!--Footer Column-->
                        <div class="footer-column col-lg-5 col-md-6 col-sm-12">
                            <div class="footer-widget links-widget">
                                <div class="footer-title  clearfix">
                                    <h2><?php echo e(trans('patients.footer_quick_links')); ?></h2>
                                    <div class="separator"></div>
                                </div>
                                <ul class="footer-list">
                                    <li><a href="<?php echo e(route('home')); ?>"><?php echo e(trans('patients.nav_home')); ?></a></li>
                                    <li><a href="<?php echo e(route('home')); ?>"><?php echo e(trans('patients.footer_about_us')); ?></a></li>
                                    <li><a href="<?php echo e(route('home')); ?>"><?php echo e(trans('patients.footer_system_services')); ?></a></li>
                                    <li><a href="<?php echo e(route('home')); ?>"><?php echo e(trans('patients.footer_doctors')); ?></a></li>
                                    <li><a href="<?php echo e(route('home')); ?>"><?php echo e(trans('patients.footer_contact_us')); ?></a></li>
                                    <li><a href="<?php echo e(route('home')); ?>"><?php echo e(trans('patients.footer_faq')); ?></a></li>
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
                                    <h2><?php echo e(trans('patients.footer_latest_news')); ?></h2>
                                    <div class="separator"></div>
                                </div>

                                <!--News Widget Block-->
                                <div class="news-widget-block">
                                    <div class="widget-inner">
                                        <div class="image">
                                            <img src="<?php echo e(URL::asset('Dashboard/img/media/ps1.jpg')); ?>"
                                                alt="صورة خبر 1" />
                                        </div>
                                        <h3><a href="#"><?php echo e(trans('patients.footer_news1_title')); ?></a>
                                        </h3>
                                        <div class="post-date">25 أكتوبر 2023</div>
                                    </div>
                                </div>

                                <!--News Widget Block-->
                                <div class="news-widget-block">
                                    <div class="widget-inner">
                                        <div class="image">
                                            <img src="<?php echo e(URL::asset('Dashboard/img/media/cardback.jpeg')); ?>"
                                                alt="صورة خبر 2" />
                                        </div>
                                        <h3><a href="#"><?php echo e(trans('patients.footer_news2_title')); ?></a></h3>
                                        <div class="post-date">20 أكتوبر 2023</div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <!--Footer Column-->
                        <div class="footer-column col-lg-6 col-md-6 col-sm-12">
                            <div class="footer-widget contact-widget">
                                <div class="footer-title  clearfix">
                                    <h2><?php echo e(trans('patients.footer_contact_us')); ?></h2>
                                    <div class="separator"></div>
                                </div>

                                <ul class="contact-list">
                                    <li>
                                        <span class="icon flaticon-placeholder"></span>
                                        <?php echo e(trans('patients.footer_address_palestine')); ?>

                                        <br> <?php echo e(trans('patients.footer_address_gaza')); ?>

                                    </li>
                                    <li>
                                        <span class="icon flaticon-call"></span>
                                        <?php echo e(trans('patients.footer_working_hours')); ?><br>
                                        <a href="tel:+970567077179"
                                            style="text-decoration: none; color: inherit; direction: ltr; display: inline-block;">+970
                                            56 707 7179</a>
                                    </li>
                                    <li>
                                        <span class="icon flaticon-message"></span>
                                        <?php echo e(trans('patients.footer_ask_a_question')); ?><br class="mt-3"><br>
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
        <div class="auto-container">
            <div class="copyright-text">
                <p style="margin: 0; font-size: 14px;"><?php echo e(trans('patients.footer_copyright', ['year' => date('Y')])); ?> <a
                        href="<?php echo e(route('home')); ?>" style="color: #00bcd4; text-decoration: none;"><?php echo e(trans('patients.footer_system_name')); ?></a>.
                    <br class="d-sm-none">
                    <?php echo e(trans('patients.footer_designed_by')); ?> <a href="https://www.facebook.com/Momensarsour" target="_blank"
                        style="color: #00bcd4; text-decoration: none;">Mo'men Sarsour & Yousef Rajab</a>
                </p>
            </div>
        </div>
    </div>
</footer>
<?php /**PATH H:\Hospital_Sys\resources\views/WebSite/layouts/footer.blade.php ENDPATH**/ ?>