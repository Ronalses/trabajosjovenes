<?php
/**
*
* @package Cariera
*
* @since    1.3.4
* @version  1.5.1
* 
* ========================
* MY ACCOUNT FUNTIONS
* ========================
*     
**/



// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}





/**
 * Extra field for the user Avatar on the Backend
 *
 * @since  1.3.4
 */

function  cariera_extra_profile_fields( $user ) { ?>

    <h3><?php esc_html_e( 'Cariera Avatar', 'cariera' ); ?></h3>
     <?php wp_enqueue_media(); ?>

    <table class="form-table">
        <tr>
            <th><label for="image"><?php esc_html_e( 'Avatar', 'cariera' ); ?></label></th>
            <td>
                <?php 
                $custom_avatar_id   = get_the_author_meta( 'cariera_avatar_id', $user->ID ) ;
                $custom_avatar      = wp_get_attachment_image_src( $custom_avatar_id, 'full' );
                if ($custom_avatar)  {
                    echo '<img src="' . $custom_avatar[0] . '" style="width:100px; height: auto;"/><br>';
                } ?>
                <input type="text" name="cariera_avatar_id" id="avatar" value="<?php echo esc_attr( get_the_author_meta( 'cariera_avatar_id', $user->ID ) ); ?>" class="regular-text" />
                <input type='button' class="cariera-user-avatar button-primary" value="<?php esc_html_e( 'Upload Image', 'cariera' ); ?>" id="uploadimage"/><br />
                <span class="description"><?php esc_html_e( 'This avatar will be displayed instead of default one', 'cariera' ); ?></span>
            </td>
        </tr>
    </table>
<?php }

add_action( 'show_user_profile', 'cariera_extra_profile_fields', 10 );
add_action( 'edit_user_profile', 'cariera_extra_profile_fields', 10 );





/**
 * Save the extra field
 *
 * @since  1.3.4
 */

function cariera_save_extra_profile_fields( $user_id ) {
    if ( !current_user_can( 'edit_user', $user_id ) ) {
        return false;  
    }
    
    if( isset($_POST['cariera_avatar_id']) ) {
        update_user_meta( $user_id, 'cariera_avatar_id', $_POST['cariera_avatar_id'] );	
    }
}


add_action( 'personal_options_update', 'cariera_save_extra_profile_fields' );
add_action( 'edit_user_profile_update', 'cariera_save_extra_profile_fields' );





/**
 * Submitting all the fields from the "Profile Details" form
 *
 * @since  1.3.4
 */
function cariera_submit_my_account_form() {
    global $wpdb;
    
    if ( isset( $_POST['my-account-submission'] ) && '1' == $_POST['my-account-submission'] ) {
        $current_user = wp_get_current_user();
		$error = array();  
        
        
        // Submitting the avatar
        if ( isset( $_POST['cariera_avatar_id'] ) ) {
            update_user_meta( $current_user->ID, 'cariera_avatar_id', sanitize_text_field( $_POST['cariera_avatar_id'] ) );
        }

        // Submitting the user role field
        if ( !empty( $_POST['cariera_user_role'] ) ) {
            wp_update_user( array ('ID' => $current_user->ID, 'role' => sanitize_text_field( $_POST['cariera_user_role'] ) ) );
        }
        
        // Submitting the first name field
        if ( isset( $_POST['first-name'] ) ) {
            update_user_meta( $current_user->ID, 'first_name', sanitize_text_field( $_POST['first-name'] ) );
        }
        
        // Submitting the last name field
        if ( isset( $_POST['last-name'] ) ){
            update_user_meta( $current_user->ID, 'last_name', sanitize_text_field( $_POST['last-name'] ) );
        }
        
        // Submitting the email field
        if ( isset( $_POST['email'] ) ) {
            if ( !is_email( $_POST['email'] ) ) {
                $error = 'error_1'; // Email not valid
            } else {
                if( email_exists( $_POST['email'] ) ) {
                    if( email_exists( $_POST['email'] ) != $current_user->ID) {
                        $error = 'error_2'; // Email is already used
                    }
                } else {
                    $user_id = wp_update_user( array (
                        'ID'         => $current_user->ID, 
                        'user_email' => sanitize_email( $_POST['email'] )
                    ));
                }
            }
        }
        
        // Redirect after submission
        if ( count($error) == 0 ) {
            wp_redirect( get_permalink() . '?updated=true' ); 
            exit;
        } else {
            wp_redirect( get_permalink() . '?user_err=' . $error ); 
            exit; 
        } 
    }
}

add_action( 'init', 'cariera_submit_my_account_form', 10 );





/**
 * Submitting all the fields from the "Change Password" form
 *
 * @since  1.3.4
 */

function cariera_submit_change_password_form() {
    $error = '';
    
    if ( isset( $_POST['cariera-password-change'] ) && '1' == $_POST['cariera-password-change'] ) {
        $current_user = wp_get_current_user();

        if ( !empty($_POST['current_pass']) && !empty($_POST['pass1'] ) && !empty( $_POST['pass2'] ) ) {
            if ( !wp_check_password( $_POST['current_pass'], $current_user->user_pass, $current_user->ID) ) {
                // Current Password doesn't match
                $error = 'error_1';
            } elseif ( $_POST['pass1'] != $_POST['pass2'] ) {
                // Passwords do not match
                $error = 'error_2';
            } elseif ( strlen($_POST['pass1']) < 4 ) {
                // Password is too short
                $error = 'error_3';
            } elseif ( false !== strpos( wp_unslash($_POST['pass1']), "\\" ) ) {
                // Password contains "\\" (backslash) character
                $error = 'error_4';
            } else {
                $user_id  = wp_update_user( array( 'ID' => $current_user->ID, 'user_pass' => sanitize_text_field( $_POST['pass1'] ) ) );

                if ( is_wp_error( $user_id ) ) {
                    // Error while updating the profile
                    $error = 'error_5';
                } else {
                    $error = false;
                    do_action( 'edit_user_profile_update', $current_user->ID);
                    wp_redirect( get_permalink().'?updated_pass=true' ); 
                    exit;
                }
            }
            
            // Redirect after submission
            if ( count($error) == 0 ) {
                do_action( 'edit_user_profile_update', $current_user->ID );
                wp_redirect( get_permalink() . '?updated_pass=true' ); 
                exit;
            } else {
                wp_redirect( get_permalink() . '?err_pass=' . $error ); 
                exit;
            }

        }
    }
}

add_action( 'init', 'cariera_submit_change_password_form', 10 );





/**
 * Delete Account
 *
 * @since  1.3.4
 */
function cariera_delete_account() {
    
    // Require user file to delete account
    require_once(ABSPATH.'wp-admin/includes/user.php');
    
    $error = '';
    
    if ( isset( $_POST['cariera-delete-account'] ) && '1' == $_POST['cariera-delete-account'] ) {
        $current_user = wp_get_current_user();
        
        if ( !empty($_POST['current_pass']) ) {
            if ( !wp_check_password( $_POST['current_pass'], $current_user->user_pass, $current_user->ID) ) {
                // Current Password doesn't match
                $error = 'error_1';
            } else {
                $error = false;
                
                $nonce = ( isset( $_REQUEST['_wpnonce'] ) ) ? $_REQUEST['_wpnonce'] : false;
                if ( ! wp_verify_nonce( $nonce, 'cariera_delete_account' ) ) {
                    return;
                }

                // Mail args for the Send email notification
                $user = get_userdata( $current_user->ID );
                $mail_args = [
                    'email'         => $user->user_email,
                    'first_name' 	=> $user->first_name,
                    'last_name' 	=> $user->last_name,
                    'display_name' 	=> $user->display_name,
                ];

                do_action( 'cariera_delete_account_email', $mail_args );
                
                wp_delete_user( $current_user->ID );
                wp_redirect( home_url() );
                exit;
            }
            
            // Redirect after submission
            if ( count($error) == 0 ) {
                wp_delete_user( $current_user->ID );
                wp_redirect( home_url() );
                exit;
            } else {
                wp_redirect( get_permalink() . '?user_err_pass=' . $error ); 
                exit;
            }  
        }
    } 
}

add_action( 'init', 'cariera_delete_account', 99 );





/**
 * Modifying the Avatar function
 *
 * @since   1.3.4
 * @version 1.5.1
 */
function cariera_gravatar_filter($avatar, $id_or_email, $size, $default, $alt, $args) {
    if ( is_object($id_or_email) ) {	        
        $avatar_id = get_the_author_meta( 'cariera_avatar_id', $id_or_email->ID );

        if ( !empty($avatar_id) ) {
            $avatar_url = wp_get_attachment_image_src( $avatar_id, 'thumbnail' );
            if ( !empty($avatar_url[0]) ) {
                $avatar = '<img src="' . esc_url($avatar_url[0]) . '" class="avatar avatar-' . esc_attr($size) . ' wp-user-avatar wp-user-avatar-' . esc_attr($size) . ' photo avatar-default cariera-avatar" width="' . esc_attr($size) . '" height="' . esc_attr($size) . '" alt="' . esc_attr($alt) . '" />';
            }
        }
    } else {
        $avatar_id = get_the_author_meta( 'cariera_avatar_id', $id_or_email );

        if ( !empty($avatar_id) ) {
            $avatar_url = wp_get_attachment_image_src( $avatar_id, 'thumbnail' );
            if ( !empty($avatar_url[0]) ) {
                $avatar = '<img src="' . esc_url($avatar_url[0]) . '" class="avatar avatar-' . esc_attr($size) . ' wp-user-avatar wp-user-avatar-' . esc_attr($size) . ' photo avatar-default cariera-avatar" width="' . esc_attr($size) . '" height="' . esc_attr($size) . '" alt="' . esc_attr($alt) . '" />';
            }
        }
    }

    return $avatar;
}

add_filter( 'get_avatar', 'cariera_gravatar_filter', 10, 6 );





/**
 * My Account shortcode
 * Usage: [cariera_my_account]
 *
 * @since   1.3.4
 * @version 1.5.1
 */
if ( !function_exists('cariera_my_account') ) {
    function cariera_my_account() {
        global $wp_roles;
        
        //wp_enqueue_media();
        do_action( 'cariera_my_account_start' );
        
        if ( isset($_GET['updated']) && $_GET['updated'] == 'true' ) { ?>
            <p class="job-manager-message success">
                <?php esc_html_e('Your profile has been updated.', 'cariera'); ?>
            </p>
        <?php } ?>


        <?php  if ( isset($_GET['user_err']) && !empty($_GET['user_err'])  ) { ?> 
            <p class="job-manager-message error">
                <?php
                switch ($_GET['user_err']) {
                    case 'error_1':
                        echo esc_html_e( 'The Email you entered is not valid or empty. Please try again...', 'cariera' );
                        break;
                    case 'error_2':
                        echo esc_html_e( 'This email is already used by another user, please try a different one.', 'cariera' );
                        break;					 	
                    default:
                        # code...
                        break;
                 } ?>
            </p> 
        <?php } ?>

        <?php if ( !is_user_logged_in() ) { ?>
            <p><?php esc_html_e( 'You must be logged in to edit your profile.', 'cariera'); ?></p>

            <?php
            $login_registration = get_option('cariera_login_register_layout');

            if ( $login_registration == 'popup' ) { ?>
                <a href="#login-register-popup" class="btn btn-main btn-effect popup-with-zoom-anim">
            <?php } else {
                $login_registration_page     = get_option('cariera_login_register_page');
                $login_registration_page_url = get_permalink( $login_registration_page );?>

                <a href="<?php echo esc_url( $login_registration_page_url ); ?>" class="btn btn-main btn-effect">
            <?php }
                esc_html_e( 'Sign in', 'cariera' ); ?>
            </a>
        <?php } else { 
            $current_user   = wp_get_current_user();
            $user_id        = get_current_user_id();
            $user_img       = get_avatar( get_the_author_meta( 'ID', $user_id ), 120 );
            $user_role      = $current_user->roles[0];            
            ?>

            <div class="row">
                
                <!-- Start of Edit My Profile -->
                <div class="col-lg-6 col-md-12">
                    <div class="dashboard-card-box">
                        <h4 class="title"><?php esc_html_e( 'Profile Details', 'cariera' ); ?></h4>
                        
                        <div class="dashboard-card-box-inner">
                            <form method="post" id="edit_user" action="<?php the_permalink(); ?>">
                                
                                <!-- Details -->
                                <div class="my-profile">
                                    
                                    <div class="user-avatar-upload">
                                        <?php                                        
                                        $custom_avatar = $current_user->cariera_avatar_id;
                                        $custom_avatar = wp_get_attachment_url($custom_avatar); 
                                        if( !empty($custom_avatar) ) { ?>
                                            <div data-photo="<?php echo $custom_avatar; ?>" data-name="<?php esc_html_e('Your Avatar', 'cariera'); ?>" data-size="<?php echo filesize( get_attached_file( $current_user->cariera_avatar_id ) ); ?>" class="edit-profile-photo">
                                        <?php } else { ?>
                                            <div class="edit-profile-photo">
                                        <?php } ?>
                                                <div id="cariera-avatar-uploader" class="cariera-uploader cariera-dropzone">
                                                    <div class="dz-message" data-dz-message><span><i class="fas fa-cloud-upload-alt"></i></span></div>
                                                </div>
                                                <input type="hidden" name="cariera_avatar_id" id="avatar-uploader-id" value="<?php echo $current_user->cariera_avatar_id; ?>" />
                                            </div>
                        
                                        <div class="user-avatar-description">
                                            <p><?php echo apply_filters( 'cariera_my_account_avatar_description', esc_html__( 'Update your photo manually, if the photo is not set the default Gravatar will be the same as your login email account. Please make sure that your uploaded image is a square size image.', 'cariera' ) ); ?></p>
                                        </div>
                                    </div>

                                    

                                    <?php if ( $user_role == 'employer' || $user_role == 'candidate' ) { ?>
                                        <div class="form-group">
                                            <!-- User Roles Wrapper -->
                                            <div class="user-roles-wrapper">
                                                <?php if( class_exists( 'WP_Resume_Manager' ) ) { ?>
                                                    <div class="user-role candidate-role">
                                                        <input type="radio" name="cariera_user_role" id="candidate-input" value="candidate" class="user-role-radio" <?php echo $user_role == 'candidate' ? 'checked' : ''; ?>>
                                                        <label for="candidate-input">
                                                            <i class="icon-people"></i>
                                                            <div>
                                                                <span><?php esc_html_e( 'Registered as a', 'cariera' ); ?></span>
                                                                <h6><?php esc_html_e( 'Candidate', 'cariera' ); ?></h6>
                                                            </div>
                                                        </label>
                                                    </div>
                                                <?php } ?>

                                                <div class="user-role employer-role">
                                                    <input type="radio" name="cariera_user_role" id="employer-input" value="employer" class="user-role-radio" <?php echo $user_role == 'employer' ? 'checked' : ''; ?>>
                                                    <label for="employer-input">
                                                        <i class="icon-briefcase"></i>
                                                        <div>
                                                            <span><?php esc_html_e( 'Registered as an', 'cariera' ); ?></span>
                                                            <h6><?php esc_html_e( 'Employer', 'cariera' ); ?></h6>
                                                        </div>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                        
                                    <div class="form-group">
                                        <label for="first-name"><?php esc_html_e( 'First Name', 'cariera' ); ?></label>
                                        <input name="first-name" type="text" id="first-name" value="<?php  echo $current_user->user_firstname; ?>" />
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="last-name"><?php esc_html_e( 'Last Name', 'cariera' ); ?></label>
                                        <input name="last-name" type="text" id="last-name" value="<?php echo $current_user->user_lastname; ?>" />
                                    </div>
                                        
                                    <div class="form-group">
                                        <label for="email"><?php esc_html_e( 'E-mail', 'cariera' ); ?></label>
                                        <input name="email" type="text" id="email" value="<?php the_author_meta( 'user_email', $current_user->ID ); ?>" />
                                    </div>
                                    
                                    <div class="form-group">
                                        <input type="hidden" name="my-account-submission" value="1" />
                                        <button type="submit" form="edit_user" value="<?php esc_html_e( 'Submit', 'cariera' ); ?>" class="btn btn-main btn-effect"><?php esc_html_e( 'Save Changes', 'cariera' ); ?></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- End of Edit My Profile -->

                
                
                <!-- 2nd Column -->
                <div class="col-lg-6 col-md-12">
                    
                    <!-- Start of Change Password Form -->
                    <div class="dashboard-card-box">
                        <h4 class="title"><?php esc_html_e( 'Change Password', 'cariera' ); ?></h4>
                        
                        <div class="dashboard-card-box-inner">
                            <?php if ( isset($_GET['updated_pass']) && $_GET['updated_pass'] == 'true' ) { ?> 
								<span class="job-manager-message success"><?php esc_html_e( 'Your password has been updated.', 'cariera'); ?></span> 
							<?php } ?>
                            
                            <?php  if ( isset($_GET['err_pass']) && !empty($_GET['err_pass'])  ) { ?> 
                                <p class="job-manager-message error">
                                    <?php
                                    switch ($_GET['err_pass']) {
                                        case 'error_1':
                                            echo esc_html_e( 'Your current password does not match. Please try again...', 'cariera' );
                                            break;
                                        case 'error_2':
                                            echo esc_html_e( 'The passwords do not match. Please try again...', 'cariera' );
                                            break;					 	
                                        case 'error_3':
                                            echo esc_html_e( 'The password is too short. Please try a longer password.', 'cariera' );
                                            break;					 	
                                        case 'error_4':
                                            echo esc_html_e( 'Password may not contain the character "\\" (backslash).', 'cariera' );
                                            break;
                                        case 'error_5':
                                            echo esc_html_e( 'An error occurred while updating your profile. Please try again...', 'cariera' );
                                            break;
                                        default:
                                            # code...
                                            break;
                                     } ?>
                                </p> 
							<?php } ?>
                            
                            <form name="resetpasswordform" action="" method="post">
                                <div class="form-group">
                                    <label><?php esc_html_e( 'Current Password', 'cariera' ); ?></label>
                                    <input type="password" name="current_pass">
                                </div>
                                
                                <div class="form-group">
								    <label for="pass1"><?php esc_html_e( 'New Password', 'cariera' ); ?></label>
								    <input name="pass1" type="password">
                                </div>
                                
                                <div class="form-group">
								    <label for="pass2"><?php esc_html_e( 'Confirm New Password', 'cariera' ); ?></label>
								    <input name="pass2" type="password">
                                </div>
                                
                                <div class="form-group">
								    <input type="submit" name="wp-submit" id="wp-submit" class="btn btn-main btn-effect" value="<?php esc_html_e( 'Save Changes', 'cariera'); ?>" />
                                </div>
                                
								<input type="hidden" name="cariera-password-change" value="1" />
							</form>
                        </div>
                    </div>
                    <!-- End of Change Password Form -->
                    
                    
                    
                    <?php if ( ! current_user_can('administrator') ) { ?>
                        <!-- Start of Delete Account -->
                        <div class="dashboard-card-box delete-account">
                            <h4 class="title"><?php esc_html_e( 'Delete Account', 'cariera' ); ?></h4>

                            <div class="dashboard-card-box-inner">
                                <?php if ( isset($_GET['user_err_pass']) && !empty($_GET['user_err_pass']) ) { ?> 
                                    <p class="job-manager-message error">
                                        <?php
                                        switch ($_GET['user_err_pass']) {
                                            case 'error_1':
                                                echo esc_html_e( 'Your current password does not match. Please try again...', 'cariera' );
                                                break;
                                            default:
                                                # code...
                                                break;
                                         } ?>
                                    </p> 
                                <?php } ?>

                                <form id="delete-account" name="delete-account" action="" method="post">
                                    <p><?php esc_html_e( 'Before you delete your account, remember that all of your data will also be deleted. This action can not be undone!', 'cariera' ); ?></p>

                                    <div class="form-group">
                                        <label><?php esc_html_e( 'Current Password', 'cariera'); ?></label>
                                        <input type="password" name="current_pass" id="current_pass">
                                    </div>

                                    <div class="form-group">
                                        <?php wp_nonce_field( 'cariera_delete_account' ); ?>
                                        <input type="hidden" name="cariera-delete-account" value="1" />
                                        <input type="submit" name="delete-submit" id="delete-submit" class="btn btn-main btn-effect" value="<?php esc_html_e( 'Delete Account', 'cariera' ); ?>" />
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- End of Delete Account -->
                    <?php } ?>
                    
                </div>
            </div>
        <?php }  
    }
}

add_shortcode( 'cariera_my_account', 'cariera_my_account' );