<?php
/**
*
* @package Cariera
*
* @since    1.4.8
* @version  1.4.8
* 
* ========================
* USER RELATED CLASS
* ========================
*     
**/


// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}




class Cariera_Core_User {


	/**
	 * Constructor function.
	 * 
	 * @since 1.4.8
	 */
    public function __construct() {
        // Register Login & Register script
        add_action( 'wp_enqueue_scripts', [ $this, 'login_register_script' ] );
        
        // Shortcodes
		add_shortcode( 'cariera_login_form', [ $this , 'login_form' ] );
		add_shortcode( 'cariera_registration_form', [ $this, 'registration_form' ] );
		add_shortcode( 'cariera_forgetpass_form', [ $this, 'forgetpass_form' ] );

		// Social Login Support
		add_action( 'cariera_social_login', [ $this, 'social_login_support'] );
        add_filter( 'wsl_render_auth_widget_alter_provider_icon_markup', [ $this, 'wsl_custom_markup' ], 10, 3 );
        
        // Login, Register, Foget Password AJAX Functions
		add_action( 'wp_ajax_nopriv_cariera_ajax_login', [ $this, 'login_process'] );
		add_action( 'wp_ajax_nopriv_cariera_ajax_register', [ $this, 'register_process'] );
		add_action( 'wp_ajax_nopriv_cariera_ajax_forgotpass', [ $this, 'forgot_pass_process'] );
		
		// User Columns
		add_filter( 'user_row_actions', [__CLASS__, 'user_table_actions' ], 10, 2 );
		add_filter( 'manage_users_columns', [ __CLASS__, 'add_column' ] );
		add_filter( 'manage_users_custom_column', [ __CLASS__, 'status_column' ], 10, 3 );

		// Status Action
		add_action( 'load-users.php', [ __CLASS__, 'process_update_user_action' ] );
		add_filter( 'cariera_new_user_approve_validate_status_update', [ __CLASS__, 'validate_status_update' ], 10, 3 );
		add_action( 'cariera_new_user_approve_approve_user', [ __CLASS__, 'approve_user' ] );
		add_action( 'cariera_new_user_approve_deny_user', [ __CLASS__, 'deny_user' ] );

		// Resent Approval Mail
		add_action( 'wp_ajax_cariera_resend_approval_mail', [ __CLASS__, 'resent_approval_mail' ] );
		add_action( 'wp_ajax_nopriv_cariera_resend_approval_mail', [ __CLASS__, 'resent_approval_mail' ] );

		// User Approval Frontend
		add_action( 'wp', [ __CLASS__, 'frontend_approve_user' ] );
		add_shortcode( 'cariera_approve_user', [ __CLASS__, 'approve_user_shortcode' ] );
    }





    /**
	 * Login & Register script
	 *
	 * @since 1.4.8
	 */
	public function login_register_script() {
		// Registering and enqueue the login/register script
        wp_register_script( 'cariera-user-ajax', CARIERA_URL . '/assets/dist/js/login-register.js', array('jquery') );

		// Redirection Settings
        $login_redirect     = get_option( 'cariera_login_redirection' );
        $dashboard_title    = get_page_by_title( 'Dashboard' );
        $dashboard_page     = get_option( 'cariera_dashboard_page');

        if ( $dashboard_title ) {
            $dashboard = get_permalink( $dashboard_title );
        } else {
            $dashboard = get_permalink( $dashboard_page );
        }

        // Redirection after login
        if( $login_redirect == 'dashboard' ) {                     
            $redirect = $dashboard;
        } elseif( $login_redirect == 'home' ) {
            $redirect = home_url( '/' );
        } else {
            $redirect = home_url( $_SERVER['REQUEST_URI'] );
		}
		
        wp_localize_script( 'cariera-user-ajax', 'cariera_user_ajax', array( 
            'ajaxurl'           => admin_url( 'admin-ajax.php', 'relative' ),
            'loadingmessage'    => '<span class="job-manager-message generic loading"><i></i>' . esc_html__( 'Please wait...', 'cariera' ) . '</span>',
            'auto_login'        => get_option( 'cariera_auto_login' ),
            'redirection'       => $redirect,
        ));
	}





    /**
	 * Login Form Shortcode
	 *
	 * @since 1.0.0
	 */
	public function login_form() {
        if ( is_user_logged_in() ) {
			return;
		}

        wp_enqueue_script('cariera-user-ajax');

        do_action( 'cariera_login_form_before' ); ?>
        
        <form id="cariera_login" method="post">
            <p class="status"></p>

            <div class="form-group">
                <label for="username"><?php esc_html_e('Username or Email','cariera'); ?></label>
                <input type="text" class="form-control" id="username" name="username" placeholder="<?php esc_html_e( 'Your Username or Email', 'cariera' ); ?>" />
            </div>

            <div class="form-group">
                <label for="password"><?php esc_html_e('Password','cariera'); ?></label>
                <div class="cariera-password">
                    <input type="password" class="form-control" id="password" name="password" placeholder="<?php esc_html_e( 'Your Password', 'cariera' ); ?>" />
                    <i class="far fa-eye"></i>
                </div>
            </div>

            <?php 
            $recaptcha_sitekey  = get_option( 'cariera_recaptcha_sitekey' );
            $login_captcha      = get_option( 'cariera_recaptcha_login' );

            if ( class_exists('Cariera_Recaptcha') && Cariera_Recaptcha::is_recaptcha_enabled() && $login_captcha ) { ?>
                <div class="form-group">
                    <div id="recaptcha-login-form" class="g-recaptcha" data-sitekey="<?php echo esc_attr( $recaptcha_sitekey ); ?>"></div>
                </div>
            <?php } ?>

            <div class="form-group">
                <div class="checkbox">
                    <input id="check1" type="checkbox" name="remember" value="yes">
                    <label for="check1"><?php esc_html_e( 'Keep me signed in', 'cariera' ); ?></label>
                </div>
            </div>

            <div class="form-group">
                <input type="submit" value="<?php esc_html_e( 'Sign in', 'cariera' ); ?>" class="btn btn-main btn-effect nomargin" /> 
            </div>

            <?php wp_nonce_field( 'cariera-ajax-login-nonce', 'login-security' ); ?>
        </form>

        <?php
        do_action( 'cariera_login_form_after' );
	}





    /**
	 * Registration Form Shortcode
	 *
	 * @since  1.4.8
	 */
	public function registration_form() {
		if ( is_user_logged_in() ) {
			return;
        }
        
        $registration   = get_option('cariera_registration');        
        $candidate_role = get_option('cariera_user_role_candidate');
        $employer_role  = get_option('cariera_user_role_employer');

		if ( $registration == true ) {
            wp_enqueue_script('cariera-user-ajax');

            do_action( 'cariera_register_form_before' ); ?>

            <form id="cariera_registration" action="" method="POST">
                <p class="status"></p>

                <div class="form-group">
                    <!-- User Roles Wrapper -->
                    <div class="user-roles-wrapper">
                        <?php if( class_exists( 'WP_Resume_Manager' ) ) { 
                            if( $candidate_role ) { ?>
                                <div class="user-role candidate-role">
                                    <input type="radio" name="cariera_user_role" id="candidate-input" value="candidate" class="user-role-radio" checked>
                                    <label for="candidate-input">
                                        <i class="icon-people"></i>
                                        <div>
                                            <h6><?php esc_html_e( 'Candidate', 'cariera' ); ?></h6>
                                            <span><?php esc_html_e( 'Register as a Candidate', 'cariera' ); ?></span>
                                        </div>
                                    </label>
                                </div>
                        <?php }
                        } ?>

                        <?php if( $employer_role ) { ?>
                            <div class="user-role employer-role">
                                <input type="radio" name="cariera_user_role" id="employer-input" value="employer" class="user-role-radio" checked>
                                <label for="employer-input">
                                    <i class="icon-briefcase"></i>
                                    <div>
                                        <h6><?php esc_html_e( 'Employer', 'cariera' ); ?></h6>
                                        <span><?php esc_html_e( 'Register as an Employer', 'cariera' ); ?></span>
                                    </div>
                                </label>
                            </div>
                        <?php } ?>
                    </div>
                </div>

                <div class="form-group">
                    <label for="register_username"><?php esc_html_e( 'Username', 'cariera' ); ?></label>
                    <input name="register_username" id="register_username" class="form-control" type="text" placeholder="<?php esc_html_e( 'Your Username', 'cariera' ); ?>" />
                </div>

                <div class="form-group">
                    <label for="register_email"><?php esc_html_e( 'Email', 'cariera' ); ?></label>
                    <input name="register_email" id="register_email" class="form-control" type="email" placeholder="<?php esc_html_e( 'Your Email', 'cariera' ); ?>" />
                </div>

                <div class="form-group">
                    <label for="register_password"><?php esc_html_e( 'Password', 'cariera' ); ?></label>
                    <div class="cariera-password">
                        <input name="register_password" id="register_password" class="form-control" type="password" placeholder="<?php esc_html_e( 'Your Password', 'cariera' ); ?>" />
                        <i class="far fa-eye"></i>
                    </div>        
                </div>

                <?php 
                $recaptcha_sitekey      = get_option( 'cariera_recaptcha_sitekey' );
                $registration_captcha   = get_option( 'cariera_recaptcha_register' );

                if ( class_exists('Cariera_Recaptcha') && Cariera_Recaptcha::is_recaptcha_enabled() && $registration_captcha ) { ?>
                    <div class="form-group">
                        <div id="recaptcha-register-form" class="g-recaptcha" data-sitekey="<?php echo esc_attr( $recaptcha_sitekey ); ?>"></div>
                    </div>
                <?php } ?>

                <?php if ( get_option('cariera_register_privacy_policy')) {
                    $gdpr_link = get_option('cariera_register_privacy_policy_page');
                    $gdpr_page = '<a href="' . esc_url( get_permalink($gdpr_link) ) . '">' . get_the_title( $gdpr_link ) . '</a>';
                    $gdpr_text = str_replace( '{gdpr_link}', $gdpr_page, get_option('cariera_register_privacy_policy_text') ); ?>

                    <div class="form-group gdpr-wrapper">
                        <div class="checkbox">
                            <input id="check-privacy" type="checkbox" name="privacy_policy">
                            <label for="check-privacy"><?php echo wp_kses_post( $gdpr_text ); ?></label>
                        </div>
                    </div>
                <?php } ?>

                <div class="form-group">
                    <input type="submit" class="btn btn-main btn-effect nomargin" id="cariera-user-register" value="<?php esc_attr_e( 'Register', 'cariera' ); ?>"/>
                </div>

                <?php wp_nonce_field( 'cariera-ajax-register-nonce', 'register-security' );  ?>
            </form>
        <?php
		} else { ?>
			<div class="job-manager-message generic"><?php esc_html_e( 'Registration is currently disabled.', 'cariera' ); ?></div>
		<?php }
	}





    /**
	 * Forget Password Form Shortcode
	 *
	 * @since  1.4.8
	 */
	public function forgetpass_form() {
		if( is_user_logged_in() ) {
			return;	
		} ?>

		<form id="cariera_forget_pass" action="#"  method="post">
			<p class="status"></p>

			<div class="form-group">
				<label for="forgot_pass"><?php esc_html_e( 'Username or Email Address *', 'cariera' ); ?></label>
				<input id="forgot_pass" type="text" name="forgot_pass" class="form-control" placeholder="<?php esc_html_e( 'Your Username or Email Address', 'cariera' ); ?>" />
			</div>

			<div class="form-group">
				<input type="submit" name="submit" value="<?php esc_html_e( 'Reset Password', 'cariera' ); ?>" class="btn btn-main btn-effect nomargin" />
			</div>

			<?php wp_nonce_field( 'cariera-ajax-forgetpass-nonce', 'forgetpass-security' ); ?>
		</form>
	<?php
    }
    




    /**
	 * Social login support for third party plugins
	 *
	 * @since  1.4.8
	 */
	public function social_login_support() {
		
		// WordPress Social Login (miniorange) Support: https://wordpress.org/plugins/miniorange-login-openid/
		if ( function_exists('mo_openid_initialize_social_login') ) { ?>
			<div class="social-miniorange-container">
				<div class="social-login-separator"><span><?php esc_html_e( 'Or connect with', 'cariera' ); ?></span></div>
				<?php echo do_shortcode( '[miniorange_social_login  view="horizontal" heading=""]' ); ?>
			</div>
		<?php }


		// WordPress Social Login Support: https://wordpress.org/plugins/wordpress-social-login/
		if ( function_exists('_wsl_e') ) { ?>
			<div class="social-login-separator"><span><?php esc_html_e( 'Or connect with', 'cariera' ); ?></span></div>
			<?php do_action( 'wordpress_social_login' ); ?>
		<?php }
	}





	/**
	 * Customizing the markup for the WSL plugin
	 *
	 * @since  1.4.8
	 */
	public function wsl_custom_markup( $provider_id, $provider_name, $authenticate_url ) { ?>
		<a href="<?php echo $authenticate_url; ?>" rel="nofollow" data-provider="<?php echo $provider_id ?>" class="wp-social-login-provider wp-social-login-provider-<?php echo strtolower( $provider_id ); ?>">
		<span><i class="fab fa-<?php echo strtolower( $provider_id ); ?>"></i><?php echo $provider_name; ?></span>
		</a>
	<?php }





    /**
	 * AJAX Login function
	 *
	 * @since 1.4.8
	 */
	public function login_process() {

		$captcha_login = false;
        $process_login = true;
        
        if( isset( $_POST['g-recaptcha-response'] ) ) {
            if( !empty( $_POST['g-recaptcha-response'] ) ) {
                $captcha_login = true;
            } else {
                $process_login = false;
            }
        } else {
            $captcha_login = false;
            $process_login = true;
        }
        
        $response = '';

        if( $captcha_login == true ){
            if ( class_exists( 'Cariera_Recaptcha' ) ) {
                $response = Cariera_Recaptcha::is_recaptcha_valid(sanitize_text_field($_POST['g-recaptcha-response']));
                if( $response == false ){
                    $process_login = false;
                } else {
                    $process_login = true;
                }
            }
        }
        
        if( $process_login == true ) {
			// First check the nonce, if it fails the function will break
			check_ajax_referer( 'cariera-ajax-login-nonce', 'login-security' );

			$creds                  = [];
			$creds['user_login']    = isset($_POST['username']) ? sanitize_text_field($_POST['username']) : '';
			$creds['user_password'] = isset($_POST['password']) ? sanitize_text_field($_POST['password']) : '';
			$creds['remember']      = isset($_POST['remember']) ? true : false;


			if ( filter_var( $creds['user_login'], FILTER_VALIDATE_EMAIL ) ) {
				$user_obj = get_user_by( 'email', $creds['user_login'] );
			} else {
				$user_obj = get_user_by( 'login', $creds['user_login'] );
			}
			$user_id = isset($user_obj->ID) ? $user_obj->ID : '0';


			// Login notification if user is "pending" or "denied" else it will continue
			$user_login_auth = self::get_user_status($user_id);
			if ( $user_login_auth == 'pending' && isset($user_obj->ID) ) {
				echo json_encode( [
					'loggedin' 	=> false,
					'message' 	=> '<span class="job-manager-message error">' . self::login_message($user_obj) . ' </span>'
				] );
				die();
			} elseif ( $user_login_auth == 'denied' && isset($user_obj->ID) ) {
				echo json_encode(array(
					'loggedin' 	=> false,
					'message' 	=> '<span class="job-manager-message error">' . esc_html__( 'Your account has been denied and you can not login.', 'cariera' ) . ' </span>'
				));
				die();
			}

			
			// Sign user in with the given credentials
			if ( is_ssl() ) {
				$user_signon = wp_signon( $creds, true );
			} else {
				$user_signon = wp_signon( $creds, false );
			}

			if ( is_wp_error($user_signon) ) {
				$result = json_encode( [
					'loggedin' 	=> false, 
					'message' 	=> '<span class="job-manager-message error">' . esc_html__( 'Wrong username or password.', 'cariera' ) . ' </span>'
				] );
			} else {
				wp_set_current_user($user_signon->ID); 
				$result = json_encode( [
					'loggedin' 	=> true, 
					'message' 	=> '<span class="job-manager-message success">' . esc_html__( 'Login successful, redirecting...', 'cariera' ) . '</span>'
				] );
			}

		} else {
            $result = json_encode( [
                'loggedin' => false,
                'message' => '<span class="job-manager-message error">' . esc_html__( 'Please provide captcha information', 'cariera' ) . ' </span>'
			] );
        }
		
   		echo trim($result);
   		die();
	}





	/**
	 * Login Message regarding the user's status
	 *
	 * @since 1.4.8
	 */
	public static function login_message( $user ) {
		$approval = get_option('cariera_moderate_new_user');
		
		if ( $approval == 'email' ) {
			return sprintf( __( 'Your account has not been verified yet, you must active your account with the link sent to your email address. If you did not receive an email, please check your junk/spam folder or you can <a href="javascript:void(0);" class="cariera-resend-approval-mail" data-login="%s">click here</a> to resend the activation email.', 'cariera' ), $user->user_login );
		} elseif ( $approval == 'admin' ) {
			return esc_html__( 'Your account has not been activate yet, please be patient until an admin activates your account.', 'cariera' );
		} else {
			return esc_html__( 'Your account has to been activated yet.', 'cariera' );
		}
	}





	/**
	 * Registration validation
	 *
	 * @since 1.4.8
	 */
	public function registration_validation( $username, $email, $password, $privacy_policy ) {
		global $reg_errors;

		$reg_errors = new WP_Error;

		$registration_captcha = get_option( 'cariera_recaptcha_register' );
		if ( class_exists('Cariera_Recaptcha') && Cariera_Recaptcha::is_recaptcha_enabled() && $registration_captcha ) {
			$is_recaptcha_valid = array_key_exists( 'g-recaptcha-response', $_POST ) ? Cariera_Recaptcha::is_recaptcha_valid( sanitize_text_field( $_POST['g-recaptcha-response'] ) ) : false;
			if ( !$is_recaptcha_valid ) {
				$reg_errors->add( 'field', esc_html__( 'reCAPTCHA is a required field', 'cariera' ) );
			}
		}

		if ( empty( $username ) || empty( $password ) || empty( $email ) || empty( $privacy_policy ) ) {
		    $reg_errors->add( 'field', esc_html__( 'Required form field is missing', 'cariera' ) );
		}

		if ( 4 > strlen( $username ) ) {
		    $reg_errors->add( 'username_length', esc_html__( 'Username too short, it should be at least 4 characters.', 'cariera' ) );
		}

		if ( username_exists( $username ) ) {
	    	$reg_errors->add( 'user_name', esc_html__( 'This Username already exists', 'cariera' ) );
		}

		if ( ! validate_username( $username ) ) {
		    $reg_errors->add( 'username_invalid', esc_html__( 'The Username you entered is not valid', 'cariera' ) );
		}

		if ( 4 > strlen( $password ) ) {
	        $reg_errors->add( 'password', esc_html__( 'Password length must be greater than 4', 'cariera' ) );
	    }

	    if ( !is_email( $email ) ) {
		    $reg_errors->add( 'email_invalid', esc_html__( 'Email is not valid, please provide a correct email address.', 'cariera' ) );
		}

		if ( email_exists( $email ) ) {
		    $reg_errors->add( 'email', esc_html__( 'This Email already exists.', 'cariera' ) );
		}

		if ( empty( $privacy_policy ) ) {
			$reg_errors->add( 'privacy_policy', esc_html__( 'Please accept our Privacy Policy.', 'cariera' ) );
		}
	}





	/**
	 * Complete the registration and add the user in the DB
	 *
	 * @since 1.4.8
	 */
	public function registration_complete( $username, $password, $email, $user_role ) {
        $userdata = [
	        'user_login' 	=> $username,
	        'user_email' 	=> $email,
	        'user_pass' 	=> $password,
            'role'          => $user_role,
		];

        return wp_insert_user( $userdata );
	}





	/**
	 * AJAX Register function
	 *
	 * @since 1.4.8
	 */
	public function register_process() {
		global $reg_errors;

		// First check the nonce, if it fails the function will break	
		check_ajax_referer( 'cariera-ajax-register-nonce', 'register-security' );

		// Check Privacy Policy if enabled
		if ( get_option('cariera_register_privacy_policy') == 1 ) {
			$privacy_policy = isset($_POST['privacy_policy'] ) ? sanitize_text_field( $_POST['privacy_policy'] ) : '';
		} else {
			$privacy_policy = 1;
		}

		// Validate Registration fields
		$this->registration_validation( $_POST['register_username'], $_POST['register_email'], $_POST['register_password'], $privacy_policy );


		// If there are no errors during registration
		if ( 1 > count( $reg_errors->get_error_messages() ) ) {

			$username 	= sanitize_user( $_POST['register_username'] );
	        $email 		= sanitize_email( $_POST['register_email'] );
            $password 	= esc_attr( $_POST['register_password'] );
            $user_role  = sanitize_text_field($_POST['cariera_user_role']);
			
			$user_id = $this->registration_complete( $username, $password, $email, $user_role );
			
			// When user is registered successfully
			if ( !is_wp_error( $user_id ) ) {
				$user_obj = get_user_by('ID', $user_id);

				// If account requires approval
				if ( get_option('cariera_moderate_new_user') != 'auto') {
					$code = cariera_random_key();
	                update_user_meta( $user_id, 'account_approve_key', $code );
	            	update_user_meta( $user_id, 'user_account_status', 'pending' );


					$approval_url 	= get_permalink( get_option('cariera_moderate_new_user_page') );
		            $code 			= get_user_meta( $user_id, 'account_approve_key', true );
					$approval_url	= add_query_arg( [ 'user_id' => $user_id, 'approve-key' => $code ], $approval_url);

					$user = get_userdata( $user_id );

					if ( get_option('cariera_moderate_new_user') == 'email' ) {
						$recipent_mail = $user->user_email;
					} else {
						$recipent_mail = get_option('admin_email');
					}

					$mail_args = [
						'send_to'		=> $recipent_mail,
						'email'    		=> $user->user_email,
						'display_name' 	=> $user->user_login,
						'approval_url'	=> $approval_url
					];

					do_action( 'cariera_new_user_approval_notification', $mail_args );


					$user_data = get_userdata($user_id);
					$final = [
	            		'status' 	=> true,
						'register' 	=> true, 
	            		'message' 	=> '<span class="job-manager-message success">' . self::register_message($user_data) . '</span>',
					];
				
				// Account doesn't require approval
				} else {
					if ( get_option('cariera_auto_login') ) {
						//signing in
						$info = [];
						$info['user_login']     = $username;
						$info['user_password']  = $password;
						$info['remember']       = 1;
						
						$note = esc_html__( 'You have been successfully registered, you will be logged in shortly.', 'cariera' );
	
						if ( is_ssl() ) {
							wp_signon( $info, true );
						} else {
							wp_signon( $info, false );
						}
					} else {
						$note = esc_html__( 'You have been successfully registered, you can login now.', 'cariera' );
					}

					// Send a welcome email to user/admin
					$user = get_userdata( $user_id );
					$mail_args = [
						'email'         => $user->user_email,
						'display_name' 	=> $user->user_login,
						'password'      => $password,
					];
					do_action( 'cariera_new_user_notification', $mail_args );
	
					$final = [
						'register' 	=> true, 
						'message'	=> '<span class="job-manager-message success">' . $note . '</span>'
					];
				}

			} else {
				$final = [
					'register' 	=> false, 
					'message' 	=> '<span class="job-manager-message error">' . esc_html__( 'Registration Error!', 'cariera' ) . '</span>'
				];
			}
		
		// There are errors during registration
		} else {
			$final = [
				'register' 	=> false,
				'message'	=> '<span class="job-manager-message error"><ul><li>' . implode( '</li><li>', $reg_errors->get_error_messages() ) . '</li></ul></span>',
			];
		}

		echo json_encode($final);
	    exit;
	}





	/**
	 * Message regarding the user status after registration
	 *
	 * @since 1.4.8
	 */
	public static function register_message($user) {
		$approval = get_option('cariera_moderate_new_user');

		if ( $approval == 'email' ) {
			return esc_html__( 'Registration complete! Before you can login you must activate your account via the email sent to you.', 'cariera' );
		} elseif ( $approval == 'admin' ) {
			return esc_html__( 'Registration complete! Your account has to be activated by an admin before you can login.', 'cariera' );
		} else {
			return esc_html__( 'Your account has to be activated.', 'cariera' );
		}
	}





	/**
	 * AJAX Forgot Password function
	 *
	 * @since 1.4.8
	 */
	public function forgot_pass_process() {
		// First check the nonce, if it fails the function will break
		check_ajax_referer( 'cariera-ajax-forgetpass-nonce', 'forgetpass-security' );

		global $wpdb;

		$account = isset($_POST['forgot_pass']) ? $_POST['forgot_pass'] : '';
	
		// Account checks
		if( empty( $account ) ) {
			$error = esc_html__( 'Enter a Username or Email address.', 'cariera' );
		} else {
			if(is_email( $account )) {
				if( email_exists($account) ) {
					$get_by = 'email';
				} else {
					$error = esc_html__( 'There is no user registered with that Email address.', 'cariera' );
				}
			} elseif ( validate_username( $account ) ) {
				if( username_exists($account) ) {
					$get_by = 'login';
				} else {
					$error = esc_html__( 'There is no user registered with that Username.', 'cariera' );
				}
			} else {
				$error = esc_html__( 'Invalid username or e-mail address.', 'cariera' );		
			}
		}


		// If no error
		if ( empty ($error) ) {
			$random_password = wp_generate_password();
			$user 		     = get_user_by( $get_by, $account );
			$update_user     = wp_update_user( [ 'ID' => $user->ID, 'user_pass' => $random_password ] );
				
			if ( $update_user ) {

				/***** Mail Content *****/
                $subject = esc_html__( 'Password Reset', 'cariera' );

                ob_start();
                get_template_part('/templates/emails/header'); ?>

                <tr><td class="h2"><?php printf( esc_html__( 'Hello %s,', 'cariera' ), $user->user_login ); ?></td></tr>
                <tr><td><?php esc_html_e( 'Your password has been resetted successfully. You can log in on your account with the newly generated password provided below.', 'cariera' ); ?></td></tr>
                <tr><td style="padding-top: 15px;"><?php printf( esc_html__( 'Your new password is: %s', 'cariera' ), $random_password ); ?></td></tr>

                <?php
                get_template_part('/templates/emails/footer');
                $content = ob_get_clean();

                $headers[] = 'Content-Type: text/html; charset=UTF-8';
                wp_mail( $user->user_email, $subject, $content, $headers );
				
				$success = esc_html__( 'Go to your inbox or spam/junk and get your new generated password.', 'cariera' );
			} else {
				$error = esc_html__( 'Something went wrong while updating your account.', 'cariera' );
			}
		}
	
		if ( !empty( $error ) ) {
			echo json_encode( [ 
				'loggedin'	=> false, 
				'message'	=> '<span class="job-manager-message error">' . $error . '</span>', 
			] );
		}
				
		if ( !empty( $success ) ) {
			echo json_encode( [
				'loggedin' 	=> true, 
				'message'	=> '<span class="job-manager-message success">' . $success . '</span>',
			] );	
		}

		die();
	}





	/**
	 * Add the "approve" or "deny" link.
	 *
	 * @since 1.4.8
	 */
	public static function user_table_actions( $actions, $user ) {
		if ( $user->ID == get_current_user_id() ) {
			return $actions;
		}

		if ( is_super_admin( $user->ID ) ) {
			return $actions;
		}

		$user_status = self::get_user_status( $user->ID );

		$approve_link = add_query_arg( array( 'action' => 'approve', 'user' => $user->ID ) );
		$approve_link = remove_query_arg( array( 'new_role' ), $approve_link );
		$approve_link = wp_nonce_url( $approve_link, 'cariera' );

		$deny_link = add_query_arg( array( 'action' => 'deny', 'user' => $user->ID ) );
		$deny_link = remove_query_arg( array( 'new_role' ), $deny_link );
		$deny_link = wp_nonce_url( $deny_link, 'cariera' );

		$approve_action = '<a href="' . esc_url( $approve_link ) . '">' . esc_html__( 'Approve', 'cariera' ) . '</a>';
		$deny_action 	= '<a href="' . esc_url( $deny_link ) . '">' . esc_html__( 'Deny', 'cariera' ) . '</a>';

		if ( $user_status == 'pending' ) {
			$actions[] = $approve_action;
			$actions[] = $deny_action;
		} else if ( $user_status == 'approved' ) {
			$actions[] = $deny_action;
		} else if ( $user_status == 'denied' ) {
			$actions[] = $approve_action;
		}

		return $actions;
	}





	/**
	 * Add the status column to the user table
	 *
	 * @since 1.4.8
	 */
	public static function add_column( $columns ) {
		$the_columns['user_status'] = esc_html__( 'Status', 'cariera' );

		$newcol 	= array_slice( $columns, 0, -1 );
		$newcol 	= array_merge( $newcol, $the_columns );
		$columns 	= array_merge( $newcol, array_slice( $columns, 1 ) );

		return $columns;
	}





	/**
	 * Show the status of the user in the status column
	 *
	 * @since 1.4.8
	 */
	public static function status_column( $val, $column_name, $user_id ) {
		switch ( $column_name ) {
			case 'user_status' :
				$status = self::get_user_status( $user_id );
				if ( $status == 'approved' ) {
					$status_i18n = esc_html__( 'approved', 'cariera' );
				} else if ( $status == 'denied' ) {
					$status_i18n = esc_html__( 'denied', 'cariera' );
				} else if ( $status == 'pending' ) {
					$status_i18n = esc_html__( 'pending', 'cariera' );
				}
				return $status_i18n;
				break;

			default:
		}

		return $val;
	}





	/**
	 * Get user status
	 *
	 * @since 1.4.8
	 */
	public static function get_user_status( $user_id ) {
		$user_status = get_user_meta( $user_id, 'user_account_status', true );

		if ( empty( $user_status ) ) {
			$user_status = 'approved';
		}

		return $user_status;
	}





	/**
	 * Get user status
	 *
	 * @since 1.4.8
	 */
	public static function validate_status_update( $do_update, $user_id, $status ) {
		$current_status = self::get_user_status( $user_id );

		if ( $status == 'approve' ) {
			$new_status = 'approved';
		} else {
			$new_status = 'denied';
		}

		if ( $current_status == $new_status ) {
			$do_update = false;
		}

		return $do_update;
	}
	




	/**
	 * Get user status
	 *
	 * @since 1.4.8
	 */
	public static function update_user_status( $user, $status ) {
		$user_id = absint( $user );
		if ( !$user_id ) {
			return false;
		}

		if ( !in_array( $status, [ 'approve', 'deny' ] ) ) {
			return false;
		}

		$do_update = apply_filters( 'cariera_new_user_approve_validate_status_update', true, $user_id, $status );
		if ( !$do_update ) {
			return false;
		}

		// where it all happens
		do_action( 'cariera_new_user_approve_' . $status . '_user', $user_id );
		do_action( 'cariera_new_user_approve_user_status_update', $user_id, $status );

		return true;
	}





	/**
	 * Process the user status update
	 *
	 * @since 1.4.8
	 */
	public static function process_update_user_action() {
		if ( isset( $_GET['action'] ) && in_array( $_GET['action'], [ 'approve', 'deny' ] ) && !isset( $_GET['new_role'] ) ) {
			check_admin_referer( 'cariera' );

			$sendback = remove_query_arg( [ 'approved', 'denied', 'deleted', 'ids', 'cariera-status-query-submit', 'new_role' ], wp_get_referer() );
			if ( !$sendback ) {
				$sendback = admin_url( 'users.php' );
			}

			$wp_list_table 	= _get_list_table( 'WP_Users_List_Table' );
			$pagenum 		= $wp_list_table->get_pagenum();
			$sendback 		= add_query_arg( 'paged', $pagenum, $sendback );

			$status 		= sanitize_key( $_GET['action'] );
			$user 			= absint( $_GET['user'] );

			self::update_user_status( $user, $status );

			if ( $_GET['action'] == 'approve' ) {
				$sendback = add_query_arg( array( 'approved' => 1, 'ids' => $user ), $sendback );
			} else {
				$sendback = add_query_arg( array( 'denied' => 1, 'ids' => $user ), $sendback );
			}

			wp_redirect( $sendback );
			exit;
		}
	}





	/**
	 * Approve User
	 *
	 * @since 1.4.8
	 */
	public static function approve_user( $user_id ) {
		$user = get_user_by('ID', $user_id);

		wp_cache_delete( $user->ID, 'users' );
		wp_cache_delete( $user->data->user_login, 'userlogins' );


		// Send mail when user gets approved
		$mail_args = [
			'email'    		=> stripslashes( $user->data->user_email ),
			'display_name' 	=> $user->data->user_login,
			'site_url'		=> home_url(),
		];
		do_action( 'cariera_new_user_approved_notification', $mail_args );


		// change usermeta tag in database to approved
		update_user_meta( $user->ID, 'user_account_status', 'approved' );
		update_user_meta( $user->ID, 'account_approve_key', '' );

		do_action( 'cariera_new_user_approve_user_approved', $user );
	}





	/**
	 * Deny User
	 *
	 * @since 1.4.8
	 */
	public static function deny_user( $user_id ) {
		$user = get_user_by('ID', $user_id);

		// Send mail when user gets approved
		$mail_args = [
			'email'    		=> stripslashes( $user->data->user_email ),
			'display_name' 	=> $user->data->user_login,
			'site_url'		=> home_url(),
		];
		do_action( 'cariera_new_user_denied_notification', $mail_args );

		update_user_meta( $user->ID, 'user_account_status', 'denied' );

		do_action( 'cariera_new_user_approve_user_denied', $user );
	}





	/**
	 * Resent Approval Mail
	 *
	 * @since 1.4.8
	 */
	public static function resent_approval_mail() {

		$user_login = isset($_POST['login']) ? $_POST['login'] : '';
		
		if ( empty($user_login) ) {
            echo json_encode( [
            	'status'	=> false,
            	'message' 	=> '<span class="job-manager-message error">' . esc_html__( 'Username or Email not correct.', 'cariera' ) . '</span>'
			] );

            die();
        }

		if ( filter_var( $user_login, FILTER_VALIDATE_EMAIL ) ) {
            $user_obj = get_user_by( 'email', $user_login );
        } else {
            $user_obj = get_user_by( 'login', $user_login );
		}
		
        if ( !empty($user_obj->ID) ) {
			$user_login_auth = self::get_user_status($user_obj->ID);
			
	        if ( $user_login_auth == 'pending' ) {
	        	if ( get_option('cariera_moderate_new_user') == 'email' ) {
	        		$recipent_mail = stripslashes( $user_obj->data->user_email );
	        	} else {
	        		$recipent_mail = get_option( 'admin_email' );
	        	}

				$approval_url 	= get_permalink( get_option('cariera_moderate_new_user_page') );
				$code 			= get_user_meta( $user_obj->data->ID, 'account_approve_key', true );
				$approval_url	= add_query_arg( [ 'user_id' => $user_obj->data->ID, 'approve-key' => $code ], $approval_url);
				
				// Send Email
				$mail_args = [
					'send_to'		=> $recipent_mail,
					'email'    		=> $user_obj->data->user_email,
					'display_name' 	=> $user_obj->data->user_login,
					'approval_url'	=> $approval_url
				];

				do_action( 'cariera_new_user_approval_notification', $mail_args );

				echo json_encode( [
					'status' 	=> true,
					'message' 	=> '<span class="job-manager-message success">' . esc_html__( 'Email has been sent successfully.', 'cariera' ) . '</span>'
				] );

		        die();
	        }
		}
		
        echo json_encode( [
        	'status' 	=> false,
        	'message' 	=> '<span class="job-manager-message error">' . esc_html__( 'Your account is not available.', 'cariera' ) . '</span>'
		] );
		
        die();
	}





	/**
	 * Approve user via the frontend
	 *
	 * @since 1.4.8
	 */
	public static function frontend_approve_user() {
		$post = get_post();

		if ( is_object( $post ) ) {
			if ( strpos( $post->post_content, '[cariera_approve_user]' ) !== false ) {
				
				$user_id 	= isset($_GET['user_id']) ? $_GET['user_id'] : 0;
				$code 		= isset($_GET['approve-key']) ? $_GET['approve-key'] : 0;

				if ( !$user_id ) {
					$error = [
						'error' 	=> true,
						'message' 	=> esc_html__( 'The user does not exist.', 'cariera' )
					];
				}

				$user = get_user_by( 'ID', $user_id );
				if ( empty($user) ) {
					$error = [
						'error' 	=> true,
						'message' 	=> esc_html__( 'The user does not exist.', 'cariera' )
					];
				} else {
					$user_code = get_user_meta( $user_id, 'account_approve_key', true );
					if ( $code != $user_code ) {
						$error = [
							'error' 	=> true,
							'message'	 => esc_html__( 'Activation code is not the same.', 'cariera' )
						];
					}
				}

				if ( empty($error) ) {
					$return = self::update_user_status( $user_id, 'approve' );
					$error  = [
						'error' 	=> false,
						'message' 	=> esc_html__( 'Congratulations, your account has been approved!', 'cariera' )
					];
					$_SESSION['approve_user_msg'] = $error;
				} else {
					$_SESSION['approve_user_msg'] = $error;
				}
			}
		}
	}





	/**
	 * Approve user via the frontend
	 *
	 * @since 1.4.8
	 */
	public static function approve_user_shortcode($atts) { ?>
		<div class="approve-user-wrapper">
			<?php if ( isset($_SESSION['approve_user_msg']) ) { ?>
				<div class="job-manager-message <?php echo esc_attr($_SESSION['approve_user_msg']['error'] ? 'error' : 'success'); ?>">
					<h3><?php echo trim($_SESSION['approve_user_msg']['message']); ?></h3>
				</div>
				<?php
				unset($_SESSION['approve_user_msg']);
			} ?>
		</div>
	<?php
    }
    
}

new Cariera_Core_User();