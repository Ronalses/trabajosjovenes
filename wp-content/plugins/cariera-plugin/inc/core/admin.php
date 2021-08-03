<?php
/**
*
* @package Cariera
*
* @since    1.4.8
* @version  1.4.8
* 
* ========================
* CARIERA MAIN ADMIN SETTINGS
* ========================
*     
**/



// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}



class Cariera_Core_Admin {

    /**
     * The single instance of WordPress_Plugin_Template_Settings.
     * 
     * @since   1.4.8
     */
    private static $_instance = null;





    /**
     * Available settings for plugin.
     * 
     * @since   1.4.8
     */
    public $settings = array();





    /**
	 * Allows for accessing single instance of class. Class should only be constructed once per call.
	 *
	 * @since  1.4.8
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}





    /**
	 * Construct
	 *
	 * @since  1.4.8
	 */
    public function __construct() {

        $this->settings_group = 'cariera';

        // Register plugin settings
        add_action( 'admin_init' , [ $this, 'register_settings' ] );

        // Add settings page to menu
        add_action( 'admin_menu' , [ $this, 'add_menu_item' ], 11 );
    }



    

    /**
	 * Get Cariera Settings
	 *
	 * @since 1.4.8
	 */
	public function get_settings() {
		if ( 0 === count( $this->settings ) ) {
			$this->init_settings();
		}
		return $this->settings;
	}





    /**
     * Add settings page to admin menu
     * 
     * @since 1.4.8
     */
    public function add_menu_item () {
        add_submenu_page( 'cariera_theme', esc_html__( 'Settings', 'cariera' ), esc_html__( 'Settings', 'cariera' ), 'manage_options', 'cariera_settings', [ $this, 'output' ] );

        add_submenu_page( 'cariera_theme', esc_html__( 'Documentation', 'cariera' ), esc_html__( 'Documentation', 'cariera' ), 'manage_options', 'cariera_documentation',  function(){} );
    }





    /**
     * Initializes the configuration for the plugin's setting fields.
     * 
     * @since 1.0.0
     */
    protected function init_settings () {

        $prefix = 'cariera_';

        $this->settings = apply_filters(
			'cariera_settings',
			array(
                /********** GENERAL OPTIONS **********/
                'general'        => array(
					esc_html__( 'General', 'cariera' ),
					array(

                        array(
                            'id'            => $prefix . 'header_emp_cta_link',
                            'label'         => esc_html__( 'Main Header CTA Link', 'cariera' ),
                            'description'   => esc_html__( 'This link will be added to the Header CTA for non loggedin, employers and admins.', 'cariera' ),
                            'type'          => 'select',
                            'options'       => cariera_get_pages_options(),
                            'default'       => '',
                        ),
                        array(
                            'id'            => $prefix . 'header_candidate_cta_link',
                            'label'         => esc_html__( 'Candidate Header CTA Link', 'cariera' ),
                            'description'   => esc_html__( 'This link will be added to the Header CTA for loggedin Candidate users.', 'cariera' ),
                            'type'          => 'select',
                            'options'       => cariera_get_pages_options(),
                            'default'       => '',
                        ),
                        array(
                            'id'            => $prefix . 'notifications',
                            'label'         => esc_html__( 'Notifications', 'cariera' ),
                            'description'   => esc_html__( 'Notifications will be disabled if this option is turned off.', 'cariera' ),
                            'type'          => 'switch',
                            'default'       => 1,
                        ),
                        array(
                            'id'            => $prefix . 'job_promotions',
                            'label'         => esc_html__( 'Job Promotions', 'cariera' ),
                            'description'   => esc_html__( 'Job Promotions will be disabled if this option is turned off.', 'cariera' ),
                            'type'          => 'switch',
                            'default'       => 1,
                        ),
                        array(
                            'id'            => $prefix . 'company_promotions',
                            'label'         => esc_html__( 'Company Promotions', 'cariera' ),
                            'description'   => esc_html__( 'Company Promotions will be disabled if this option is turned off.', 'cariera' ),
                            'type'          => 'switch',
                            'default'       => 1,
                        ),
                        array(
                            'id'            => $prefix . 'resume_promotions',
                            'label'         => esc_html__( 'Resume Promotions', 'cariera' ),
                            'description'   => esc_html__( 'Resume Promotions will be disabled if this option is turned off.', 'cariera' ),
                            'type'          => 'switch',
                            'default'       => 1,
                        ),
                        array(
                            'id'            => $prefix . 'font_iconsmind',
                            'label'         => esc_html__( 'Additional Font Icons', 'cariera' ),
                            'description'   => esc_html__( 'You can disable Iconsmind font icon library by turning the switch off if you do not use any icons from this library to improve performance.', 'cariera' ),
                            'type'          => 'switch',
                            'default'       => 1,
                        ),

					),
                ),

                /********** reCAPTCHA OPTIONS **********/
                'recaptcha'        => array(
					esc_html__( 'reCAPTCHA', 'cariera' ),
					array(
                        array(
                            'id'            => $prefix . 'recaptcha_sitekey',
                            'label'         => esc_html__( 'reCAPTCHA Site Key', 'cariera' ),
                            'description'   => esc_html__( 'Get the sitekey from https://www.google.com/recaptcha/admin#list - use reCAPTCHA v2', 'cariera' ),
                            'type'          => 'text',
                        ),
                        array(
                            'id'            => $prefix . 'recaptcha_secretkey',
                            'label'         => esc_html__( 'reCAPTCHA Secret Key', 'cariera' ),
                            'description'   => esc_html__( 'Get the sitekey from https://www.google.com/recaptcha/admin#list - use reCAPTCHA v2', 'cariera' ),
                            'type'          => 'text',
                        ),
                        array(
                            'id'            => $prefix . 'recaptcha_login',
                            'label'         => esc_html__( 'Login form', 'cariera' ),
                            'description'   => esc_html__( 'Display reCAPTCHA field to the login form. You must have entered a valid site key and secret key above.', 'cariera' ),
                            'type'          => 'switch',
                        ),
                        array(
                            'id'            => $prefix . 'recaptcha_register',
                            'label'         => esc_html__( 'Registration form', 'cariera' ),
                            'description'   => esc_html__( 'Display reCAPTCHA field to the registration form. You must have entered a valid site key and secret key above.', 'cariera' ),
                            'type'          => 'switch',
                        ),
                        
					),
                ),



                /********** REGISTRATION OPTIONS **********/
                'registration'  => array(
					esc_html__( 'Login & Register', 'cariera' ),
					array(
                        array(
                            'id'            => $prefix . 'login_register_layout',
                            'options'       => array( 
                                'popup'             => esc_html__( 'Popup', 'cariera' ),
                                'page'              => esc_html__( 'Custom Page', 'cariera' ),
                            ),
                            'default'       => 'popup',
                            'label'         => esc_html__( 'Login & Registration Layout' , 'cariera' ),
                            'description'   => esc_html__( 'You can set your login & register to "popup" or to redirect to a "custom page".', 'cariera' ),
                            'type'          => 'select',
                        ),
                        array(
                            'id'            => $prefix . 'login_register_page',
                            'options'       => cariera_get_pages_options(),
                            'default'       => '',
                            'label'         => esc_html__( 'Login & Registration Custom Page' , 'cariera' ),
                            'description'   => esc_html__( 'Choose page that uses "Page Template Login".', 'cariera' ),
                            'type'          => 'select',
                            'class'         => 'login-page'
                        ),
                        array(
                            'id'            => $prefix . 'login_redirection',
                            'options'       => array( 
                                'dashboard'         => esc_html__( 'Dashboard', 'cariera' ),
                                'home'              => esc_html__( 'Home Page', 'cariera' ),
                                'no_redirection'    => esc_html__( 'No Redirection', 'cariera' ),
                            ),
                            'default'       => 'dashboard',
                            'label'         => esc_html__( 'Login Redirection' , 'cariera' ),
                            'description'   => esc_html__( 'Select your prefered login redirection method.', 'cariera' ),
                            'type'          => 'select',
                        ),

                        /***** WELCOME USER *****/
                        array(
                            'id'            => $prefix . 'header_registration',
                            'label'         => '',
                            'title'         => esc_html__( 'Registration', 'cariera' ),
                            'description'   => '',
                            'type'          => 'title',
                        ),
                        array(
                            'id'            => $prefix . 'registration',
                            'label'         => esc_html__( 'Registration', 'cariera' ),
                            'description'   => esc_html__( 'Turn the switch "off" if you want to disable registration.', 'cariera' ),
                            'type'          => 'switch',
                            'default'       => 1,
                        ),                        
                        array(
                            'id'            => $prefix . 'user_role_candidate',
                            'label'         => esc_html__( 'Candidate Role Selection', 'cariera' ),
                            'description'   => esc_html__( 'Turn the switch "off" if you want to disable the "Candidate" role from the registration form.', 'cariera' ),
                            'type'          => 'switch',
                            'default'       => 1,
                            'class'         => 'cariera-registration'
                        ),
                        array(
                            'id'            => $prefix . 'user_role_employer',
                            'label'         => esc_html__( 'Employer Role Selection', 'cariera' ),
                            'description'   => esc_html__( 'Turn the switch "off" if you want to disable the "Employer" role from the registration form.', 'cariera' ),
                            'type'          => 'switch',
                            'default'       => 1,
                            'class'         => 'cariera-registration'
                        ),
                        array(
                            'id'            => $prefix . 'moderate_new_user',
                            'options'       => array( 
                                'auto'              => esc_html__( 'Auto Approval', 'cariera' ),
                                'email'             => esc_html__( 'Email Approval', 'cariera' ),
                                'admin'             => esc_html__( 'Admin Approval', 'cariera' ),
                            ),
                            'default'       => 'auto',
                            'label'         => esc_html__( 'Moderate New User' , 'cariera' ),
                            'description'   => esc_html__( 'Users are automatically approved once registered and there is no need to activate their account. You can setup so that they can activate their account by email or that admin has to approve them manually.', 'cariera' ),
                            'type'          => 'select',
                            'class'         => 'cariera-registration'
                        ),
                        array(
                            'id'            => $prefix . 'moderate_new_user_page',
                            'options'       => cariera_get_pages_options(),
                            'label'         => esc_html__( 'Approve User Page', 'cariera' ),
                            'description'   => esc_html__( 'Approve pending user page. The page needs to have [cariera_approve_user] shortcode.', 'cariera' ),
                            'type'          => 'select',
                            'default'       => '',
                            'class'         => 'cariera-registration approve-user-page'
                        ),
                        array(
                            'id'            => $prefix . 'auto_login',
                            'label'         => esc_html__( 'Auto Login after Registration', 'cariera' ),
                            'description'   => esc_html__( 'If enabled the user will automatically login after registration.', 'cariera' ),
                            'type'          => 'switch',
                            'default'       => 1,
                            'class'         => 'cariera-registration'
                        ),
                        array(
                            'id'            => $prefix . 'register_privacy_policy',
                            'label'         => esc_html__( 'Privacy Policy', 'cariera' ),
                            'description'   => esc_html__( 'Turn the switch to "off" if you want to disable privacy policy checkbox.', 'cariera' ),
                            'type'          => 'switch',
                            'default'       => 1,
                            'class'         => 'cariera-registration'
                        ),
                        array(
                            'id'            => $prefix . 'register_privacy_policy_text',
                            'label'         => esc_html__( 'Privacy Policy Text', 'cariera' ),
                            'description'   => esc_html__( 'Make sure to add "{gdpr_link}" in the input below if you want to add a link. The {gdpr_link} will get replace with the page set on the next option.', 'cariera' ),
                            'type'          => 'text',
                            'default'       => esc_html__('By signing up, you agree to our {gdpr_link}.', 'cariera'),
                            'class'         => 'cariera-registration'
                        ),
                        array(
                            'id'            => $prefix . 'register_privacy_policy_page',
                            'options'       => cariera_get_pages_options(),
                            'label'         => esc_html__( 'Privacy Policy Page', 'cariera' ),
                            'description'   => esc_html__( 'Choose page that will contain detailed information about the Privacy Policy of your website.', 'cariera' ),
                            'type'          => 'select',
                            'default'       => '',
                            'class'         => 'cariera-registration'
                        ),

                        
                        /***** WELCOME USER *****/
                        array(
                            'id'            => $prefix . 'header_user_welcome',
                            'label'         => '',
                            'title'         => esc_html__( 'New User Welcome Email - Auto Approve', 'cariera' ),
                            'description'   => esc_html__( 'Available tags are: ', 'cariera' ) . '<strong>{user_name}, {user_mail}, {site_name}, {password}</strong>',
                            'type'          => 'title',
                            'class'         => 'cariera-registration no-approval-required'
                        ),
                        array(
                            'id'            => $prefix . 'user_welcome_email',
                            'label'         => esc_html__( 'User Welcome Email', 'cariera' ),
                            'description'   => esc_html__( 'Enable/Disable email notification when a user registers on the website.', 'cariera' ),
                            'default'       => 1,
                            'type'          => 'switch',
                            'class'         => 'cariera-registration no-approval-required'
                        ),
                        array(
                            'id'            => $prefix . 'user_welcome_email_admin',
                            'label'         => esc_html__( 'Admin Notification - New User', 'cariera' ),
                            'description'   => esc_html__( 'Enable/Disable email notification to notify the admin that a new user has registered.', 'cariera' ),
                            'default'       => 1,
                            'type'          => 'switch',
                            'class'         => 'cariera-registration no-approval-required'
                        ),
                        array(
                            'id'            => $prefix . 'user_welcome_email_subject',
                            'label'         => esc_html__( 'Email Subject', 'cariera' ),
                            'default'       => esc_html__('Welcome to {site_name}', 'cariera'),
                            'type'          => 'text',
                            'class'         => 'cariera-registration no-approval-required'
                        ),
                         array(
                            'id'            => $prefix . 'user_welcome_email_content',
                            'label'         => esc_html__( 'Email Content', 'cariera' ),
                            'default'       => trim(preg_replace('/\t+/', '', "Hi {user_name},<br>
Welcome and thank you for signing up. You can login to your account with the details below:<br>
<ul>
<li>Username: {user_name}</li>
<li>Email: {user_mail}</li>
<li>Password: {password}</li>
</ul>")),
                            'type'      => 'editor',
                            'class'         => 'cariera-registration no-approval-required'
                        ),


                        /***** APPROVAL EMAIL *****/
                        array(
                            'id'            => $prefix . 'header_new_user_approve',
                            'label'         => '',
                            'title'         => esc_html__( 'Approve new registered User', 'cariera' ),
                            'description'   => esc_html__( 'This email will be sent to the user or the admin depening on your approval settings. Available tags are: ', 'cariera' ) . '<strong>{user_name}, {user_mail}, {site_name}, {password}, {approval_url}</strong>',
                            'type'          => 'title',
                            'class'         => 'cariera-registration approval-required'
                        ),
                        array(
                            'id'            => $prefix . 'new_user_approve_email_subject',
                            'label'         => esc_html__( 'Email Subject', 'cariera' ),
                            'default'       => esc_html__( 'Approve new Registered user: {user_name}', 'cariera' ),
                            'type'          => 'text',
                            'class'         => 'cariera-registration approval-required'
                        ),
                         array(
                            'id'            => $prefix . 'new_user_approve_email_content',
                            'label'         => esc_html__( 'Email Content', 'cariera' ),
                            'default'       => trim(preg_replace('/\t+/', '', "Hi {user_name},<br>
Welcome and thank you for signing up. You can verify your account by clicking the link below:<br>
<a href='{approval_url}' target='_blank'>Verify Account</a>")),
                            'type'          => 'editor',
                            'class'         => 'cariera-registration approval-required'
                        ),


                        /***** USER APPROVED EMAIL *****/
                        array(
                            'id'            => $prefix . 'header_new_user_approved',
                            'label'         => '',
                            'title'         => esc_html__( 'User Approved', 'cariera' ),
                            'description'   => esc_html__( 'This email will be sent to the user once their user status changes to "Approved"', 'cariera' ),
                            'type'          => 'title',
                            'class'         => 'cariera-registration approval-required'
                        ),
                        array(
                            'id'            => $prefix . 'new_user_approved_email_subject',
                            'label'         => esc_html__( 'Email Subject', 'cariera' ),
                            'default'       => esc_html__( 'Your Account has been Approved', 'cariera' ),
                            'type'          => 'text',
                            'class'         => 'cariera-registration approval-required'
                        ),
                         array(
                            'id'            => $prefix . 'new_user_approved_email_content',
                            'label'         => esc_html__( 'Email Content', 'cariera' ),
                            'default'       => trim(preg_replace('/\t+/', '', "Hi {user_name},<br>
Your account has been approved. You can login via the link below:<br>
<a href='{site_url}' target='_blank'>Login</a>")),
                            'type'          => 'editor',
                            'class'         => 'cariera-registration approval-required'
                        ),


                        /***** USER DENIED EMAIL *****/
                        array(
                            'id'            => $prefix . 'header_new_user_denied',
                            'label'         => '',
                            'title'         => esc_html__( 'User Denied', 'cariera' ),
                            'description'   => esc_html__( 'This email will be sent to the user once their user status changes to "Denied"', 'cariera' ),
                            'type'          => 'title',
                            'class'         => 'cariera-registration approval-required'
                        ),
                        array(
                            'id'            => $prefix . 'new_user_denied_email_subject',
                            'label'         => esc_html__( 'Email Subject', 'cariera' ),
                            'default'       => esc_html__( 'Your Account has been Denied', 'cariera' ),
                            'type'          => 'text',
                            'class'         => 'cariera-registration approval-required'
                        ),
                         array(
                            'id'            => $prefix . 'new_user_denied_email_content',
                            'label'         => esc_html__( 'Email Content', 'cariera' ),
                            'default'       => trim(preg_replace('/\t+/', '', "Hi {user_name},<br>
We are sorry to say but your account has been denied.")),
                            'type'          => 'editor',
                            'class'         => 'cariera-registration approval-required'
                        ),

                        
					),
                ),



                /********** PAGES OPTIONS **********/
                'pages'        => array(
					esc_html__( 'Pages', 'cariera' ),
					array(
                        array(
                            'id'            => $prefix . 'dashboard_page',
                            'options'       => cariera_get_pages_options(),
                            'label'         => esc_html__( 'Dashboard Page' , 'cariera' ),
                            'description'   => esc_html__( 'Main User Dashboard page. The page needs to have [cariera_dashboard] shortcode (optional).', 'cariera' ),
                            'type'          => 'select',
                        ),
                        array(
                            'id'            => $prefix . 'bookmarks_page',
                            'options'       => cariera_get_pages_options(),
                            'label'         => esc_html__( 'Bookmarks Page' , 'cariera' ),
                            'description'   => esc_html__( 'The page needs to have [my_bookmarks] shortcode.', 'cariera' ),
                            'type'          => 'select',
                        ),
                        array(
                            'id'            => $prefix . 'past_applications_page',
                            'options'       => cariera_get_pages_options(),
                            'label'         => esc_html__( 'Applied Jobs Page' , 'cariera' ),
                            'description'   => esc_html__( 'The page needs to have [past_applications] shortcode.', 'cariera' ),
                            'type'          => 'select',
                        ),
                        array(
                            'id'            => $prefix . 'listing_reports_page',
                            'options'       => cariera_get_pages_options(),
                            'label'         => esc_html__( 'Listing Reports Page' , 'cariera' ),
                            'description'   => esc_html__( 'The page needs to have [cariera_listing_reports] shortcode.', 'cariera' ),
                            'type'          => 'select',
                        ),
                        array(
                            'id'            => $prefix . 'dashboard_profile_page',
                            'options'       => cariera_get_pages_options(),
                            'label'         => esc_html__( 'My Profile Page' , 'cariera' ),
                            'description'   => esc_html__( 'Profile customization page. The page needs to have [cariera_my_account] shortcode.', 'cariera' ),
                            'type'          => 'select',
                        ),

					),
                ),



                /********** EMAILS OPTIONS **********/
                'emails'        => array(
					esc_html__( 'Emails', 'cariera' ),
					array(
                        array(
                            'id'            => $prefix . 'emails_name',
                            'label'         => esc_html__('"From name" in email', 'cariera'),
                            'description'   => esc_html__('The name from who the email is received, by default it is your site name.', 'cariera'),
                            'default'       =>  get_bloginfo( 'name' ),                
                            'type'          => 'text',
                        ),
                        array(
                            'id'            => $prefix . 'emails_from_email',
                            'label'         => esc_html__('"From" email ', 'cariera'),
                            'description'   => esc_html__('This will act as the "from" and "reply-to" address. This emails should match your domain address', 'cariera'),
                            'default'       =>  get_bloginfo( 'admin_email' ),               
                            'type'          => 'text',
                        ),


                        /***** ACCOUNT DELETED *****/
                        array(
                            'id'            => $prefix . 'header_delete_account',
                            'label'         => '',
                            'title'         => esc_html__( 'Delete Account Email', 'cariera' ),
                            'description'   => esc_html__( 'Available tags are: ', 'cariera' ) . '<strong>{user_name}, {user_mail}, {first_name}, {last_name}</strong>',
                            'type'          => 'title',
                        ),
                        array(
                            'id'            => $prefix . 'delete_account_email',
                            'label'         => esc_html__( 'Delete Account Notification', 'cariera' ),
                            'description'   => esc_html__( 'Enable/Disable email notification when a user deletes their account.', 'cariera' ),
                            'default'       => 1,
                            'type'          => 'switch',
                        ),
                        array(
                            'id'            => $prefix . 'delete_account_email_subject',
                            'label'         => esc_html__( 'Email Subject', 'cariera' ),
                            'description'   => '',
                            'default'       => esc_html__( 'Your account has been deleted!', 'cariera' ),
                            'type'          => 'text',
                        ),
                        array(
                            'id'            => $prefix . 'delete_account_email_content',
                            'label'         => esc_html__( 'Email Content', 'cariera' ),
                            'description'   => '',
                            'default'      => trim(preg_replace('/\t+/', '', 'Hi {user_name},<br>
We are sorry to see you go! If you change your mind feel free to register on our website again anytime.')),
                            'type'          => 'editor',
                        ),


                        /***** LISTING PROMOTED *****/
                        array(
                            'id'            => $prefix . 'header_listing_promoted',
                            'label'         => '',
                            'title'         => esc_html__( 'Listing Promotion', 'cariera' ),
                            'description'   => esc_html__( 'Available tags that can be used in the mail content: ', 'cariera' ) . '<strong>{user_name}, {user_mail}, {listing_name}, {listing_url}</strong>',
                            'type'          => 'title',
                        ),
                        array(
                            'id'            => $prefix . 'listing_promoted_email',
                            'label'         => esc_html__( 'Listing Promotion', 'cariera' ),
                            'description'   => esc_html__( 'Enable/Disable email notifications to notify the author when their listing get\'s promoted.', 'cariera' ),
                            'default'       => 1,
                            'type'          => 'switch',
                        ),
                        array(
                            'id'            => $prefix . 'listing_promoted_email_subject',
                            'label'         => esc_html__( 'Email Subject', 'cariera' ),
                            'description'   => '',
                            'default'       => esc_html__( 'Listing Promoted Successfully!', 'cariera' ),
                            'type'          => 'text',
                        ),
                        array(
                            'id'            => $prefix . 'listing_promoted_email_content',
                            'label'         => esc_html__( 'Email Content', 'cariera' ),
                            'description'   => '',
                            'default'      => trim(preg_replace('/\t+/', '', 'Hi {user_name},<br>
Your listing <strong>"{listing_name}"</strong> has been promoted successfully.<br>')),
                            'type'          => 'editor',
                        ),


                        /***** LISTING PROMOTION EXPIRED *****/
                        array(
                            'id'            => $prefix . 'header_promotion_expired_email',
                            'label'         => '',
                            'title'         => esc_html__( 'Listing Promotion Expired', 'cariera' ),
                            'description'   => esc_html__( 'Available tags that can be used in the mail content: ', 'cariera' ) . '<strong>{user_name}, {user_mail}, {listing_name}, {listing_url}</strong>',
                            'type'          => 'title',
                        ),
                        array(
                            'id'            => $prefix . 'promotion_expired_email',
                            'label'         => esc_html__( 'Promotion Expired', 'cariera' ),
                            'description'   => esc_html__( 'Enable/Disable email notifications to notify the author when their listing get\'s promoted.', 'cariera' ),
                            'default'       => 1,
                            'type'          => 'switch',
                        ),
                        array(
                            'id'            => $prefix . 'promotion_expired_email_subject',
                            'label'         => esc_html__( 'Email Subject', 'cariera' ),
                            'description'   => '',
                            'default'       => esc_html__( 'Promotion has Expired!', 'cariera' ),
                            'type'          => 'text',
                        ),
                        array(
                            'id'            => $prefix . 'promotion_expired_email_content',
                            'label'         => esc_html__( 'Email Content', 'cariera' ),
                            'description'   => '',
                            'default'      => trim(preg_replace('/\t+/', '', 'Hi {user_name},<br>
Your promotion for <strong>"{listing_name}"</strong> has expired.<br>')),
                            'type'          => 'editor',
                        ),

					),
                ),

            // END
            )
        );

    }





    /**
     * Register plugin settings with WordPress's Settings API.
     * 
     * @since 1.4.8
     */
	public function register_settings() {
		$this->init_settings();

		foreach ( $this->settings as $section ) {
			foreach ( $section[1] as $option ) {
				if ( isset( $option['default'] ) ) {
					add_option( $option['id'], $option['default'] );
				}
				register_setting( $this->settings_group, $option['id'] );
			}
		}
    }





    /**
     * Load settings page content
     * 
     * @since 1.4.8
     */
    public function output() {
        $this->init_settings(); ?>

        <!-- Build Settings Page -->
        <div class="wrap cariera-settings-wrap">
            <h2><?php esc_html_e( 'Cariera Core Settings' , 'cariera' ); ?></h2>

            <form class="cariera-options" method="post" action="options.php">
                <?php settings_fields( $this->settings_group ); ?>
                <h2 class="nav-tab-wrapper">
                    <?php
                    foreach ( $this->settings as $key => $section ) {
                        echo '<a href="#settings-' . esc_attr( sanitize_title( $key ) ) . '" class="nav-tab">' . esc_html( $section[0] ) . '</a>';
                    } ?>
                </h2>

                <?php
                if ( ! empty( $_GET['settings-updated'] ) ) {
                    flush_rewrite_rules();
                    echo '<div class="updated fade cariera-updated"><p>' . esc_html__( 'Settings successfully saved', 'cariera' ) . '</p></div>';
                }

                foreach ( $this->settings as $key => $section ) {
					$section_args = isset( $section[2] ) ? (array) $section[2] : array();
                    
                    echo '<div id="settings-' . esc_attr( sanitize_title( $key ) ) . '" class="settings_panel">';
                        if ( ! empty( $section_args['before'] ) ) {
                            echo '<p class="before-settings">' . wp_kses_post( $section_args['before'] ) . '</p>';
                        }
                        echo '<table class="form-table settings parent-settings">';
                            foreach ( $section[1] as $option ) {
                                $value = get_option( $option['id'] );
                                $this->output_field( $option, $value );
                            }
                        echo '</table>';
                        if ( ! empty( $section_args['after'] ) ) {
                            echo '<p class="after-settings">' . wp_kses_post( $section_args['after'] ) . '</p>';
                        }
					echo '</div>';
				} ?>

                <p class="submit">
					<input type="submit" class="button-primary" value="<?php esc_attr_e( 'Save Changes', 'cariera' ); ?>" />
				</p>
            </form>
        </div>
        
        <script type="text/javascript"></script>
    <?php
    }





    /**
	 * Checkbox input field.
	 *
	 * @since 1.4.8
	 */
	protected function input_checkbox( $option, $attributes, $value, $ignored_placeholder ) { ?>
		<label>
		<input type="hidden" name="<?php echo esc_attr( $option['id'] ); ?>" value="0" />
		<input
			id="setting-<?php echo esc_attr( $option['id'] ); ?>"
			name="<?php echo esc_attr( $option['id'] ); ?>"
			type="checkbox"
			value="1"
			<?php
			echo implode( ' ', $attributes ) . ' '; // WPCS: XSS ok.
			checked( '1', $value );
			?>
		/> <?php echo wp_kses_post( $option['cb_label'] ); ?></label>
		<?php
		if ( ! empty( $option['description'] ) ) {
			echo ' <p class="description">' . wp_kses_post( $option['description'] ) . '</p>';
		}
    }





    /**
	 * Checkbox input switch.
	 *
	 * @since 1.4.8
	 */
	protected function input_switch( $option, $attributes, $value, $ignored_placeholder ) { ?>
        <div class="switch-container">
            <label class="switch">
                <input type="hidden" name="<?php echo esc_attr( $option['id'] ); ?>" value="0" />
                <input id="setting-<?php echo esc_attr( $option['id'] ); ?>" name="<?php echo esc_attr( $option['id'] ); ?>" type="checkbox" value="1" <?php echo implode( ' ', $attributes ) . ' '; checked( '1', $value ); ?>	/>
                <span class="switch-btn"><span data-on="<?php esc_html_e( 'on', 'cariera' ); ?>" data-off="<?php esc_html_e( 'off', 'cariera' ); ?>"></span></span>
            </label>
            <?php
            if ( ! empty( $option['description'] ) ) {
                echo ' <p class="description">' . wp_kses_post( $option['description'] ) . '</p>';
            } ?>
        </div>
		<?php
    }
    




    /**
	 * Text area input field.
	 *
	 * @since 1.4.8
	 */
	protected function input_textarea( $option, $attributes, $value, $placeholder ) { ?>
		<textarea
			id="setting-<?php echo esc_attr( $option['id'] ); ?>"
			class="large-text"
			cols="50"
			rows="3"
			name="<?php echo esc_attr( $option['id'] ); ?>"
			<?php
			echo implode( ' ', $attributes ) . ' '; // WPCS: XSS ok.
			echo $placeholder; // WPCS: XSS ok.
			?>
		>
			<?php echo esc_textarea( $value ); ?>
		</textarea>
		<?php

		if ( ! empty( $option['description'] ) ) {
			echo ' <p class="description">' . wp_kses_post( $option['description'] ) . '</p>';
		}
	}
   




    /**
	 * Select input field.
	 *
	 * @since 1.4.8
	 */
	protected function input_select( $option, $attributes, $value, $ignored_placeholder ) { ?>
		<select
			id="setting-<?php echo esc_attr( $option['id'] ); ?>"
			class="regular-text"
			name="<?php echo esc_attr( $option['id'] ); ?>"
			<?php
			echo implode( ' ', $attributes ); // WPCS: XSS ok.
			?>
		>
		<?php
		foreach ( $option['options'] as $key => $name ) {
			echo '<option value="' . esc_attr( $key ) . '" ' . selected( $value, $key, false ) . '>' . esc_html( $name ) . '</option>';
		}
		?>
		</select>
		<?php

		if ( ! empty( $option['description'] ) ) {
			echo ' <p class="description">' . wp_kses_post( $option['description'] ) . '</p>';
		}
	}





    /**
	 * Radio input field.
	 *
	 * @since 1.4.8
	 */
	protected function input_radio( $option, $ignored_attributes, $value, $ignored_placeholder ) { ?>
		<fieldset>
            <legend class="screen-reader-text">
                <span><?php echo esc_html( $option['label'] ); ?></span>
            </legend>
            <?php
            if ( ! empty( $option['description'] ) ) {
                echo ' <p class="description">' . wp_kses_post( $option['description'] ) . '</p>';
            }

            foreach ( $option['options'] as $key => $name ) {
                echo '<label><input name="' . esc_attr( $option['id'] ) . '" type="radio" value="' . esc_attr( $key ) . '" ' . checked( $value, $key, false ) . ' />' . esc_html( $name ) . '</label><br>';
            } ?>
		</fieldset>
		<?php
    }
    




    /**
	 * Editor input field.
	 *
	 * @since 1.4.8
	 */
    protected function input_editor( $option, $ignored_attributes, $value, $ignored_placeholder ) {
        wp_editor( $value, $option['id'], array(
            'textarea_name' => $option['id'],
            'editor_height' => 200
        ) );
    }




    
    /**
	 * Editor input field.
	 *
	 * @since 1.4.8
	 */
    protected function input_title( $option, $ignored_attributes, $value, $ignored_placeholder ) {
        echo '<div class="settings-title ' . $option['id'] . '">';
            echo '<h3>' . $option['title'] . '</h3>';
            echo '<span>' . $option['description'] . '</span>';
        echo '</div>';
    }





    /**
	 * Page input field.
	 *
	 * @since 1.4.8
	 */
	protected function input_page( $option, $ignored_attributes, $value, $ignored_placeholder ) {
		$args = array(
			'name'             => $option['id'],
			'id'               => $option['id'],
			'sort_column'      => 'menu_order',
			'sort_order'       => 'ASC',
			'show_option_none' => esc_html__( '--no page--', 'cariera' ),
			'echo'             => false,
			'selected'         => absint( $value ),
		);

		echo str_replace( ' id=', " data-placeholder='" . esc_attr__( 'Select a page&hellip;', 'cariera' ) . "' id=", wp_dropdown_pages( $args ) ); // WPCS: XSS ok.

		if ( ! empty( $option['description'] ) ) {
			echo ' <p class="description">' . wp_kses_post( $option['description'] ) . '</p>';
		}
    }
    




    /**
	 * Hidden input field.
	 *
	 * @since 1.4.8
	 */
	protected function input_hidden( $option, $attributes, $value, $ignored_placeholder ) {
		$human_value = $value;
		if ( $option['human_value'] ) {
			$human_value = $option['human_value'];
		} ?>
		<input
			id="setting-<?php echo esc_attr( $option['id'] ); ?>"
			type="hidden"
			name="<?php echo esc_attr( $option['id'] ); ?>"
			value="<?php echo esc_attr( $value ); ?>"
			<?php
			echo implode( ' ', $attributes ); // WPCS: XSS ok.
			?>
		/><strong><?php echo esc_html( $human_value ); ?></strong>
		<?php

		if ( ! empty( $option['description'] ) ) {
			echo ' <p class="description">' . wp_kses_post( $option['description'] ) . '</p>';
		}
	}





	/**
	 * Password input field.
	 *
	 * @since 1.4.8
	 */
	protected function input_password( $option, $attributes, $value, $placeholder ) { ?>
		<input
			id="setting-<?php echo esc_attr( $option['id'] ); ?>"
			class="regular-text"
			type="password"
			name="<?php echo esc_attr( $option['id'] ); ?>"
			value="<?php echo esc_attr( $value ); ?>"
			<?php
			echo implode( ' ', $attributes ) . ' '; // WPCS: XSS ok.
			echo $placeholder; // WPCS: XSS ok.
			?>
		/>
		<?php

		if ( ! empty( $option['description'] ) ) {
			echo ' <p class="description">' . wp_kses_post( $option['description'] ) . '</p>';
		}
    }
    




    /**
	 * Number input field.
	 *
	 * @since 1.4.8
	 */
	protected function input_number( $option, $attributes, $value, $placeholder ) {
		echo isset( $option['before'] ) ? wp_kses_post( $option['before'] ) : '';
		?>
		<input
			id="setting-<?php echo esc_attr( $option['id'] ); ?>"
			class="small-text"
			type="number"
			name="<?php echo esc_attr( $option['id'] ); ?>"
			value="<?php echo esc_attr( $value ); ?>"
			<?php
			echo implode( ' ', $attributes ) . ' '; // WPCS: XSS ok.
			echo $placeholder; // WPCS: XSS ok.
			?>
		/>
		<?php
		echo isset( $option['after'] ) ? wp_kses_post( $option['after'] ) : '';
		if ( ! empty( $option['description'] ) ) {
			echo ' <p class="description">' . wp_kses_post( $option['description'] ) . '</p>';
		}
	}





	/**
	 * Text input field.
	 *
	 * @since 1.4.8
	 */
	protected function input_text( $option, $attributes, $value, $placeholder ) { ?>
		<input
			id="setting-<?php echo esc_attr( $option['id'] ); ?>"
			class="regular-text"
			type="text"
			name="<?php echo esc_attr( $option['id'] ); ?>"
			value="<?php echo esc_attr( $value ); ?>"
			<?php
			echo implode( ' ', $attributes ) . ' '; // WPCS: XSS ok.
			echo $placeholder; // WPCS: XSS ok.
			?>
		/>
		<?php

		if ( ! empty( $option['description'] ) ) {
			echo ' <p class="description">' . wp_kses_post( $option['description'] ) . '</p>';
		}
	}





    /**
	 * Outputs the field row.
	 *
	 * @since 1.4.8
	 */
	protected function output_field( $option, $value ) {
		$placeholder    = ( ! empty( $option['placeholder'] ) ) ? 'placeholder="' . esc_attr( $option['placeholder'] ) . '"' : '';
		$class          = ! empty( $option['class'] ) ? $option['class'] : '';
		$option['type'] = ! empty( $option['type'] ) ? $option['type'] : 'text';
		$attributes     = array();
		if ( ! empty( $option['attributes'] ) && is_array( $option['attributes'] ) ) {
			foreach ( $option['attributes'] as $attribute_name => $attribute_value ) {
				$attributes[] = esc_attr( $attribute_name ) . '="' . esc_attr( $attribute_value ) . '"';
			}
		}

		echo '<tr valign="top" class="' . esc_attr( $class ) . '">';

		if ( ! empty( $option['label'] ) ) {
			echo '<th scope="row"><label for="setting-' . esc_attr( $option['id'] ) . '">' . esc_html( $option['label'] ) . '</a></th><td>';
		} else {
			echo '<td colspan="2">';
		}

		$method_name = 'input_' . $option['type'];
		if ( method_exists( $this, $method_name ) ) {
			$this->$method_name( $option, $attributes, $value, $placeholder );
		} else {
			// Allows for custom fields in admin setting panes.
			do_action( 'cariera_admin_field_' . $option['type'], $option, $attributes, $value, $placeholder );
		}
		echo '</td></tr>';
    }
    




    /**
	 * Multiple settings stored in one setting array that are shown when the `enable` setting is checked.
	 *
	 * @since 1.4.8
	 */
	protected function input_multi_enable_expand( $option, $attributes, $values, $placeholder ) {
		echo '<div class="setting-enable-expand">';
		$enable_option               = $option['enable_field'];
		$enable_option['id']         = $option['id'] . '[' . $enable_option['id'] . ']';
		$enable_option['type']       = 'checkbox';
		$enable_option['attributes'] = array( 'class="sub-settings-expander"' );
		$this->input_checkbox( $enable_option, $enable_option['attributes'], $values[ $option['enable_field']['id'] ], null );

		echo '<div class="sub-settings-expandable">';
		$this->input_multi( $option, $attributes, $values, $placeholder );
		echo '</div>';
		echo '</div>';
	}





	/**
	 * Multiple settings stored in one setting array.
	 *
	 * @since 1.4.8
	 */
	protected function input_multi( $option, $ignored_attributes, $values, $ignored_placeholder ) {
		echo '<table class="form-table settings child-settings">';
		foreach ( $option['settings'] as $sub_option ) {
			$value              = isset( $values[ $sub_option['id'] ] ) ? $values[ $sub_option['id'] ] : $sub_option['default'];
			$sub_option['id']   = $option['id'] . '[' . $sub_option['id'] . ']';
			$this->output_field( $sub_option, $value );
		}
		echo '</table>';
	}





	/**
	 * Proxy for text input field.
	 *
	 * @since 1.4.8
	 */
	protected function input_input( $option, $attributes, $value, $placeholder ) {
		$this->input_text( $option, $attributes, $value, $placeholder );
	}
}