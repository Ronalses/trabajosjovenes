<?php

/**
*
* @package Cariera
*
* @since 1.0
* 
* ========================
* DYNAMIC CSS FILE- FOR ALL STYLES THAT ARE CREATED ON THE BACKEND
* ========================
*     
**/



function cariera_stylesheet_content() {
    wp_add_inline_style( 'cariera-frontend', cariera_main_style() );    
}
add_action( 'wp_enqueue_scripts', 'cariera_stylesheet_content' );




function cariera_main_style() {
    $bodycolor          = cariera_get_option('cariera_body_color');
    $bodywrapper        = cariera_get_option('cariera_wrapper_color');
    $maincolor          = cariera_get_option('cariera_main_color');
    $secondcolor        = cariera_get_option('cariera_secondary_color');
    $footer_bg          = cariera_get_option('cariera_footer_bg');    
    
    $inline_css = '';   
    
    //Logo
	$logo_size_width = intval( cariera_get_option( 'logo_width' ) );
	$logo_css        = $logo_size_width ? 'width:' . $logo_size_width . 'px; ' : '';

	$logo_size_height = intval( cariera_get_option( 'logo_height' ) );
	$logo_css .= $logo_size_height ? 'height:' . $logo_size_height . 'px; ' : '';

	$logo_margin = cariera_get_option( 'logo_margins' );
	$logo_css .= $logo_margin['top'] ? 'margin-top:' . $logo_margin['top'] . ' !important; ' : '';
	$logo_css .= $logo_margin['right'] ? 'margin-right:' . $logo_margin['right'] . ' !important; ' : '';
	$logo_css .= $logo_margin['bottom'] ? 'margin-bottom:' . $logo_margin['bottom'] . ' !important; ' : '';
	$logo_css .= $logo_margin['left'] ? 'margin-left:' . $logo_margin['left'] . ' !important; ' : '';

	if ( ! empty( $logo_css ) ) {
		$inline_css .= 'header .navbar-brand img {' . esc_html( $logo_css ) . ';}';
	}

    //Home Page Background Image
    $home_image  = cariera_get_option('home_page_image');
    $home2_image = cariera_get_option('home_page2_image');
    
    if ( ! empty( $home_image ) ) {
		$inline_css .= 'section.home-search { background-image: url("' . esc_url( $home_image ) . '"); }';
	}
    
    if ( ! empty( $home2_image ) ) {
		$inline_css .= 'section.home-search2 { background-image: url("' . esc_url( $home2_image ) . '"); }';
	}
    
    $inline_css .= 'body {background:' . esc_attr($bodycolor) . ';}';
    $inline_css .= 'body > .wrapper {background:' . esc_attr($bodywrapper) . ';}';
    
    
    if ( cariera_get_option( 'cariera_body_style' ) == 'boxed' ) {
		$body_bg = cariera_get_option('cariera_body_bg');
        
		if ( ! empty( $body_bg ) ) {
                        
			$bg_horizontal = cariera_get_option( 'cariera_body_bg_horizontal' );
			$bg_vertical   = cariera_get_option( 'cariera_body_bg_vertical' );
            $bg_repeats = cariera_get_option( 'cariera_body_bg_repeats' );
            $bg_attachments = cariera_get_option( 'cariera_body_bg_attachments' );
            $bg_size = cariera_get_option( 'cariera_body_bg_size' );
            
			$inline_css .= 'body { 
                background-image: url(' . $body_bg . '); 
                background-position:' . $bg_horizontal . ' ' . $bg_vertical . ';
                background-repeat:' . $bg_repeats . ';
                background-attachment:' . $bg_attachments . ';
                background-size:' . $bg_size . ';
            }';

		}
	}
    
    /*****************
        HEADER
    ******************/
    $navbar_bg          = cariera_get_option('cariera_navbar_bg');
    $menu_hover_color   = cariera_get_option('cariera_menu_hover_color');

    $inline_css .= 'header.header1,
    header.header2 {
        background:' . $navbar_bg . ';
    }';
    
    $inline_css .= 'ul.main-nav .menu-item a:hover,
    ul.main-nav .menu-item.active > a,
    header.main-header ul.main-nav .dropdown-menu > li > a:focus,
    header.main-header ul.main-nav .dropdown-menu > li > a:hover,
    ul.main-nav .mega-menu .dropdown-menu .mega-menu-inner .menu-item-mega a:hover,    
    header.header1.header-white ul.main-nav > li > a:hover {
        color:' . $menu_hover_color . ';
    }';


    /*****************
        FOOTER
    ******************/
    $footer_bg           = cariera_get_option('cariera_footer_bg');
    $footer_title_color  = cariera_get_option('cariera_footer_title_color');
    
    $inline_css .= 'footer.main-footer {
        background:' . $footer_bg . ';
    }';

    $inline_css .= 'footer.main-footer .footer-info .widget-title {
        color:' . $footer_title_color . ';
    }';


    /*****************
        JOB & RESUME
    ******************/
    $auto_location  = cariera_get_option('cariera_job_auto_location');
    
    if ( $auto_location == false ) {
        $inline_css .= '.geolocation { 
            display: none !important;
        }';
    }

    // Radius Scale
    $search_radius = cariera_get_option( 'cariera_search_radius' );
    if ( $search_radius ) {
        $radius_scale = cariera_get_option( 'cariera_radius_unit' );
        $inline_css .= ".range-output:after {
            content: '$radius_scale';
        }";
    }
    
    
    /* ------------------------------------------------------------------- */
    /* Blue #303af7
    ---------------------------------------------------------------------- */
    /* Color */
    $inline_css .= 'a, a:hover,
    header.main-header .extra-menu-item > a:hover,
    .woocommerce nav.woocommerce-pagination ul li span.current,
    .woocommerce nav.woocommerce-pagination ul li a:hover,
    .pagination .current,
    .pagination ul li a:hover,
    .pagination-next-prev ul li a:hover,
    .text-primary,
    ul.main-nav .open a,
    a#vc_load-inline-editor:hover,
    .navbar-default .navbar-nav>.open>a:hover,
    .navbar-default .navbar-nav >.active > a:focus,
    .navbar-default .navbar-nav >.active > a:hover,
    article.blog-post .blog-desc .meta-tags a:hover,
    article.blog-post-content .blog-desc .meta-tags a:hover,
    .widget_recent_entries .widget-blog-post .post-info a:hover,
    .job-sidebar .single-job-overview-detail .icon i,
    article.blog-post .blog-desc h5 a:hover,
    article.blog-post .meta-tags span i,
    article.blog-post-content .meta-tags span i,
    .widget_archive li a:hover,
    .widget_categories li a:hover,
    .widget_product_categories li a:hover,
    .widget_recent_comments li a:hover,
    .widget_pages li a:hover,
    .widget_nav_menu li a:hover,
    .widget_nav_menu .menu ul > li > a:hover,
    .widget_meta li a:hover,
    .company-letters a:hover,
    .company-group li a:hover,
    .woocommerce div.product p.price,
    .woocommerce div.product span.price,
    .woocommerce-MyAccount-content a,
    .woocommerce-message a,
    .woocommerce div.product div.summary .product_meta span.posted_in a,
    .cart .cart_item a:hover,
    .woocommerce-checkout table.shop_table .cart-subtotal .woocommerce-Price-amount,
    .woocommerce-checkout table.shop_table .order-total .woocommerce-Price-amount,
    .woocommerce table.shop_table td .product-quantity,
    .woocommerce-account .woocommerce-MyAccount-navigation ul li.is-active,
    .woocommerce nav.woocommerce-pagination ul li a:focus,
    .woocommerce ul.cart_list li a:hover,
    .woocommerce ul.product_list_widget li a:hover,
    .woocommerce-account .woocommerce-MyAccount-navigation ul li a:hover,
    .contact-candidate-popup a,
    .testimonials-carousel-style1 .testimonial-item .testimonial .customer h4,
    .testimonials-carousel-style2 .testimonial .customer cite,
    .testimonials-carousel-style3 .testimonial i,
    footer .copyright h6 a,
    article.blog-post .blog-desc .blog-post-title a:hover,
    .single-job_listing .company-info .job-company-info .single-job-listing-company-name a:hover,
    .single-job_listing .company-info .job-company-info .single-job-listing-company-contact a:hover,
    .single-job_listing .company-info .job-company-info .single-job-listing-company-contact i,
    .single_job_listing_1  .job-content-wrapper .job-content-main .job-title .title:hover,
    ul.resumes li.resume.resume-list.single_resume_1 .candidate-title h5:hover,
    ul.resumes li.resume.resume-list.single_resume_2 .resume-content-body .title:hover,
    .job_listing_preview .job-sidebar .job-overview h5 i,
    .job-sidebar .widget-job-overview a:hover,
    .category-groups ul li a:hover,
    .loading_effect3,
    #preloader .loading-container .object,
    .cariera-job-applications .application-content h4 a:hover,
    .cariera-job-applications .application-content .info ul li a:hover,
    .blogslider-post-holder .blogslider-title a:hover,
    .blog-post-layout .bloglist-title a:hover,
    .category-groups .job-category-bg a:hover h4,
    .job-search-form-box .geolocation i.geolocate:hover,
    .vc_job_search .geolocation i.geolocate:hover,
    .company_listings .company:hover .company-details .company-title h5,
    .company-page .company-info .company-details i,
    .company-page .company-info .company-details a:hover,
    .company-sidebar .single-company-overview-detail .icon i,
    .company-sidebar .widget-company-overview a:hover,
    .iconbox-style-1.icon-color-primary i.boxicon,
    .iconbox-style-2.icon-color-primary i.boxicon,
    .iconbox-style-3.icon-color-primary i.boxicon,
    .iconbox-style-6.icon-color-primary i.boxicon,
    .iconbox-style-7.icon-color-primary i.boxicon,
    .iconbox-style-8.icon-color-primary i.boxicon,
    .iconbox-style-8:hover h3,
    section.page-header .breadcrumb li a:hover,
    section.comments .comment-form .logged-in-as a:hover,
    .candidate-extra-info ul.candidate-categories li a:hover,
    .candidate-extra-info .candidate-resume a:hover,
    .resume-page .candidate-info-wrapper .candidate-links span a:hover,
    .candidate-education .education-title .location,
    .candidate-education .education-item:before,
    .candidate-experience .experience-title .employer,
    .candidate-experience .experience-item:before,
    .candidate-skills .skills span:before,
    .resume-sidebar .single-resume-overview-detail .icon i,
    .featured-jobs .featured-job-info a:hover,
    .job-carousel .job-info a:hover,
    .resume-carousel .candidate-info > span i,
    .job-resume-tab-search ul li.active a,
    .job-resume-tab-search.dark-skin .tabs-nav li.active a,
    .job-manager-single-alert-link a:hover,
    .company-letters a.chosen,
    .company_listings .company .company-inner .company-details .company-jobs span,
    .company_listings .company .company-details .company-jobs span,
    #submit-job-form .save_draft:hover,
    .submission-flow ul li.active,
    #job_package_selection .job_listing_packages ul.job_packages li.job-package .package-footer .price,
    #job_package_selection .job_listing_packages ul.job_packages li.user-job-package .package-footer .price,
    #job_package_selection .job_listing_packages ul.resume_packages li.resume-package .package-footer .price,
    #job_package_selection .job_listing_packages ul.resume_packages li.user-resume-package .package-footer .price,
    #dashboard ul.listing-packages li.package i.list-icon,
    .widget ul.resumes li.resume > a:hover .candidate h3,
    .extra-menu .header-account-widget .main-content ul a:hover,
    .single-related-job ul li i,
    ul.job_listings .job_listing.job-grid.single_job_listing_3 .job-content-body .company a:hover,
    ul.job_listings .job_listing.job-list.single_job_listing_5 .job-content-body .company a:hover,
    .pricing-table3 .pricing-body ul li span,
    #login-register-popup .bottom-links a:hover,
    .signin-wrapper .bottom-links a:hover, 
    .signup-wrapper .bottom-links a:hover, 
    .forgetpassword-wrapper .bottom-links a:hover,
    .cariera-uploader.cariera-dropzone:hover,
    form#cariera_login .cariera-password i:hover, 
    form#cariera_registration .cariera-password i:hover,
    .header-notifications-widget ul.cariera-notifications span.notification-content strong,
    .promo-packages-wrapper ul.promo-packages .promo-package .package-details p.promo-desc,
    .cariera-countdown .value,
    .leaflet-container .cariera-infoBox a {
        color:' . esc_attr($maincolor) . ';
    }';

    $inline_css .= '.nav > li > a:focus,
    .job-manager-pagination .current,
    .job-manager-pagination a:hover,
    .job-manager-pagination a:focus,
    .btn-border.btn-main:hover,
    .btn-border.btn-main:focus,
    aside.widget ul.job_listings li.job_listing > a:hover .position h3 {
        color:' . esc_attr($maincolor) . '!important;
    }';
    
    
    
    /* Background */
    $inline_css .= '
    .slick-dots li.slick-active,
    .slick-arrow,
    .section-title h2:after,
    .list li:before,
    .button,
    body .woocommerce a.button,
    body .woocommerce a.button:hover,
    .woocommerce-page ul.products li.product .mediaholder .product-overlay .add_to_cart_button:hover,
    .woocommerce-pagination ul li a,
    .pagination ul li a,
    .nav-links a,    
    .checkbox input[type="checkbox"]:checked ~ label:before,
    .radio input[type="radio"]:checked ~ label:before,
    .job-listings-main .job-list.single_job_listing_1.job_position_featured:before,
    .job-listings-main .job-list.single_job_listing_2.job_position_featured:before,
    ul.job_listings .job_listing.job-grid.single_job_listing_3.job_position_featured .job-content-wrapper:before,
    ul.job_listings .job_listing.job-list.single_job_listing_5.job_position_featured .job-content-wrapper:before,
    ul.resumes li.resume.resume-list.single_resume_1.resume_featured:before,
    ul.resumes li.resume.resume-list.single_resume_2.resume_featured .resume-content-wrapper:before,
    ul.resumes .resume.resume-grid.single_resume_1.resume_featured .resume-content-wrapper:before,
    ul.resumes .resume.resume-grid.single_resume_2.resume_featured .resume-content-wrapper:before,
    .resume-skills .skills span,
    .job-manager-pagination a,
    .page-links a,
    .pagination-next-prev ul li a,
    header.main-header .extra-menu-item .notification-count,
    .header-notifications-widget ul.cariera-notifications .notification-icon,
    .header-notifications-widget ul.cariera-notifications li.notification-active:after,
    .header-notifications-widget .notifications-footer a,
    article.sticky:before,
    article figure.post-quote,
    .post-navigation .nav-links .nav-next a,
    .post-navigation .nav-links .nav-previous a,
    .sidebar aside.widget .widget-title:after,
    .woocommerce .widget_price_filter .ui-slider .ui-slider-range,
    .pricing-table-featured:before,
    .entry-content .job_listing_packages_title .button,
    .entry-content .resume_packages_title .button,
    .job_listing_packages_title .button,
    .job_filters .filter_by_tag .filter_by_tag_cloud a.active,
    .resume_filters .filter_by_tag .filter_by_tag_cloud a.active,
    .resume_packages_title .button,
    .entry-content .resume_preview_title .button,
    .resume_preview_title .button,
    article.blog-post-content .date,
    .widget_tag_cloud .tagcloud a:hover,
    .widget_product_tag_cloud .tagcloud a:hover,
    .woocommerce #reviews #comments .commentlist .comment .commenter:before,
    .woocommerce #reviews #comments .commentlist .comment .commenter:after,
    .woocommerce button.button.alt, .woocommerce button.button.alt:hover,
    .woocommerce-account .woocommerce-MyAccount-navigation ul li.is-active,
    .woocommerce-account .woocommerce-MyAccount-navigation ul li.is-active:hover,
    .testimonials-carousel .testimonial-item .testimonial .review:before,
    .job-search-form-box .form-title,
    input.wp_job_manager_send_application_button,
    .loading_effect .object,
    .loading_effect2 .object,
    .loading_effect3 .object,
    .cariera-job-applications .application-tabs .job-application-note-add input[type="button"],
    .small-dialog-headline,
    .small-dialog-headline:before,
    .iconbox-style-4.icon-color-primary i.boxicon,
    .iconbox-style-5.icon-color-primary i.boxicon,
    .pricing-table .pricing-body ul li:before,
    .flip .card .back,
    .category-slider-layout .job-cat-slider1 .cat-item:hover,
    .pricing-table2 .pricing-footer a,
    .pricing-table3 .pricing-header,
    .pricing-table3 .pricing-body ul li span:after,
    .company-letters ul li:first-child a,
    .companies-listing-a-z .company-group-inner .company-letter,
    ul.company_listings .company.company-list.single_company_1.company_featured:before,
    ul.company_listings .company.company-list.single_company_2.company_featured .company-content-wrapper:before,
    ul.company_listings .company.company-grid.single_company_1.company_featured .company-content-wrapper:before,
    ul.company_listings .company.company-grid.single_company_2.company_featured .company-content-wrapper:before,
    .job-listings-main .job-actions .job-quickview,
    .submission-flow ul li.active::before,
    #job_package_selection .job_listing_packages ul.job_packages li.job-package .package-button:after,
    #job_package_selection .job_listing_packages ul.job_packages li.user-job-package .package-button:after,
    #job_package_selection .job_listing_packages ul.resume_packages li.resume-package .package-button:after,
    #job_package_selection .job_listing_packages ul.resume_packages li.user-resume-package .package-button:after,
    .select2-container--default .select2-results__option--highlighted[aria-selected],
    .select2-container--default .select2-results__option--highlighted[data-selected],
    .select2-container--default .select2-selection--multiple .select2-selection__choice,
    input.submit-bookmark-button,
    form.woocommerce-EditAccountForm button.button,
    form.woocommerce-EditAccountForm button.button:hover,
    .woocommerce-address-fields button.button,
    .woocommerce-address-fields button.button:hover,
    .woocommerce button.button,
    .woocommerce button.button:hover,
    .woocommerce button.button.alt,
    .woocommerce button.button.alt:hover,
    .woocommerce button.button:disabled[disabled],
    .woocommerce button.button:disabled[disabled]:hover,
    .woocommerce a.button.alt,
    .woocommerce a.button.alt:hover,
    .woocommerce .widget_price_filter .price_slider_amount .button,
    .woocommerce .widget_price_filter .price_slider_amount .button:hover,
    .woocommerce .return-to-shop a.button,
    .woocommerce .return-to-shop a.button:hover,
    .woocommerce .widget_price_filter .ui-slider .ui-slider-range,
    .woocommerce .widget_shopping_cart .buttons a.checkout,
    .woocommerce table.shop_table td .product-quantity:before,
    div.resumes .load_more_resumes,
    div.resumes .load_more_resumes:hover,
    .dashboard-nav ul li.active,
    .mm-counter,
    .marker-cluster span,
    .marker-cluster span:after,
    #cariera-map .cariera-infoBox a.leaflet-popup-close-button:hover,
    .cariera-infoBox .wrapper .company-jobs,
    .marker .marker-img.featured:before,
    .header-account-widget .title-bar,
    .header-notifications-widget .title-bar,
    #company-selection .fieldset input.company-selection-radio:checked ~ label,
    #company-selection .fieldset input.company-selection-radio ~ label:hover,
    div.resumes .load_more_resumes:focus,
    div.resumes .load_more_resumes.loading,
    .user-roles-wrapper input.user-role-radio:checked ~ label,
    .company_listings .company .company-inner .company-details .company-jobs span:before,
    .company_listings .company .company-details .company-jobs span:before,
    .rangeslider__fill,
    .blog-post-layout3 .blog-grid-item .item-cat,
    .listing-categories.grid-layout2 .listing-category a:hover,
    .listing-categories.grid-layout3 .listing-category a:hover,
    form.apply_with_resume input[type=submit],
    #job-manager-job-applications .job-applications-download-csv,
    .cariera-dropzone .dz-preview .dz-remove,
    .cariera-uploader.cariera-dropzone:before,
    ul.listing-categories.list-layout3 li:hover .category-count,
    .woocommerce-MyAccount-content ins, 
    .woocommerce-MyAccount-content mark,
    .promo-packages-wrapper ul.promo-packages .promo-package .package-icon i,
    input#submit-resume-alert {
        background:' . esc_attr($maincolor) . ';
    }';
    
    $inline_css .= '.btn-main, .btn-main:hover, .btn-main:focus, .btn-main:active, form.post-password-form input[type=submit]{
        background:' . esc_attr($maincolor) . '!important;
    }';
    
    
    
    /* Gradient Background */
    $inline_css .= '.overlay-gradient:after,
    .back-top,
    article.blog-post .blog-thumbnail,
    .widget_recent_entries .widget-blog-post .post-thumbnail,
    .blogslider-post-thumbnail:after,
    .bloglist-post-thumbnail:after {
        background: -moz-linear-gradient(left, ' . esc_attr($maincolor) . ' -20%, ' . esc_attr($secondcolor) . ' 120%);
        background: -webkit-linear-gradient(left, ' . esc_attr($maincolor) . ' -20%, ' . esc_attr($secondcolor) . ' 120%);
        background: linear-gradient(to right, ' . esc_attr($maincolor) . ' -20%, ' . esc_attr($secondcolor) . ' 120%);
    }';
    
    
    
    /* Border Color */
    $inline_css .= '.woocommerce-pagination ul li a,
    .pagination ul li a,
    .nav-links a,
    blockquote,
    .job-manager-pagination .current,
    .woocommerce-pagination .current,
    .pagination .current,
    .page-links a,
    .pagination-next-prev ul li a,
    .checkbox [type="checkbox"]:checked ~ label:before,
    .radio input[type="radio"]:checked ~ label:before,
    .woocommerce nav.woocommerce-pagination ul li span.current,
    .woocommerce nav.woocommerce-pagination ul li a:hover,
    .woocommerce nav.woocommerce-pagination ul li a:focus,
    .pagination .current,
    .pagination ul li a:hover,
    .pagination-next-prev ul li a:hover,
    #job_package_selection .job_listing_packages ul.job_packages li.job-package.active .package-button,
    #job_package_selection .job_listing_packages ul.job_packages li.user-job-package.active .package-button,
    #job_package_selection .job_listing_packages ul.resume_packages li.resume-package.active .package-button,
    #job_package_selection .job_listing_packages ul.resume_packages li.user-resume-package.active .package-button,
    .company_listings .company .company-inner .company-details .company-jobs span,
    .company_listings .company .company-details .company-jobs span,
    .rangeslider__handle {
        border-color:' . esc_attr($maincolor) . ';
    }';
        
        
    $inline_css .= '.job-manager-pagination a,
    .job-manager-pagination a:hover,
    .woocommerce .widget_price_filter .ui-slider .ui-slider-handle,
    .user-roles-wrapper label,
    .slick-dots li,
    ul.company_listings .company.company-grid.single_company_2:hover .company-content-wrapper,
    ul.resumes .resume.resume-grid.single_resume_2:hover .resume-content-wrapper,
    .cariera-uploader.cariera-dropzone:hover {
         border-color:' . esc_attr($maincolor) . '!important;
    }';
    
        
    $inline_css .= 'ul.main-nav .dropdown .dropdown-menu,
    .woocommerce div.product .woocommerce-tabs ul.tabs li.active a,
    .no_job_listings_found,
    .no_resumes_found,
    .no_companies_found,
    .job-manager-form fieldset .account-sign-in,
    .job-manager-message,
    .job_listing_preview_title,
    .resume_preview_title,
    .company_preview_title {
        border-top-color:' . esc_attr($maincolor) . ' !important;
    }';
    
    
    
    /* ------------------------------------------------------------------- */
    /* Purple #443088
    ---------------------------------------------------------------------- */
    
    $inline_css .= '.btn-secondary, .btn-secondary:hover,
    .woocommerce-page ul.products li.product .mediaholder .product-overlay .add_to_cart_button {
        background:' . esc_attr($secondcolor) . ';
    }';
    
    $inline_css .= '.btn-border.btn-secondary:hover,
    .btn-border.btn-secondary:focus {
        color:' . esc_attr($secondcolor) . '!important;
    }';
    

    /*** Footer Background ***/
    $inline_css .= '.footer1 .footer-info{
        background-image: url(' . esc_attr($footer_bg) . ');
    }';
        
    
    /*** Media Query ***/
    $inline_css .= '@media (max-width: 992px) {
        .navbar-toggle {
            border-color:' . esc_attr($secondcolor) . '!important;
        }
        .navbar-default .navbar-toggle .icon-bar {
            background:' . esc_attr($secondcolor) . ';
        }
    }';    
    
    return $inline_css;
}