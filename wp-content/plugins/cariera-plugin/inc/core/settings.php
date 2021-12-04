<?php

/**
*
* @package Cariera
*
* @since 1.4.3
* 
* ========================
* CARIERA CORE SETTINGS
* ========================
*     
**/



// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}



class Cariera_Core_Settings {

    /**
     * The single instance of WordPress_Plugin_Template_Settings.
     * 
     * @since   1.4.3
     */
    private static $_instance = null;


    /**
	 * Constructor function.
	 * 
	 * @since 1.4.3
	 */
    public function __construct() {
        add_filter( 'job_manager_settings', [ $this,'wpjm_settings' ] );
        add_filter( 'resume_manager_settings', [ $this,'wprm_settings' ] );
    }





    /*
    =====================================================
        WP JOB MANAGER
    =====================================================
    */
    
    /**
     * Adds Settings for Job Manager Options
     *
     * @since 1.0.0
     */
    public function wpjm_settings( $settings = array() ) {
        
        $settings['job_listings'][1][] = array(
            'name'    => 'cariera_currency_position',
            'std'     => 'before',
            'label'   => esc_html__( 'Currency Symbol positon', 'cariera' ),
            'desc'    => esc_html__( 'Select the positon of your selected currency symbol (before/after number)', 'cariera' ),
            'type'    => 'select',
            'options' => array(
                'before' 	=> esc_html__( 'Before Number', 'cariera' ),
                'after' 	=> esc_html__( 'After Number', 'cariera' )
            )
        );
        $settings['job_listings'][1][] = array(
            'name'    => 'cariera_currency_setting',
            'std'     => 'USD',
            'label'   => esc_html__('Currency Symbol', 'cariera'),
            'desc'    => esc_html__('Select the currency symbol that will be used in salary/rate fields', 'cariera'),
            'type'    => 'select',
            'options' => array(
                'none' => esc_html__( 'Disable Currency Symbol', 'cariera' ),
                'USD' => esc_html__( 'US Dollars', 'cariera' ),
                'AED' => esc_html__( 'United Arab Emirates Dirham', 'cariera' ),
                'ARS' => esc_html__( 'Argentine Peso', 'cariera' ),
                'AUD' => esc_html__( 'Australian Dollars', 'cariera' ),
                'BDT' => esc_html__( 'Bangladeshi Taka', 'cariera' ),
                'BHD' => esc_html__( 'Bahraini Dinar', 'cariera' ),
                'BRL' => esc_html__( 'Brazilian Real', 'cariera' ),
                'BGN' => esc_html__( 'Bulgarian Lev', 'cariera' ),
                'CAD' => esc_html__( 'Canadian Dollars', 'cariera' ),
                'CLP' => esc_html__( 'Chilean Peso', 'cariera' ),
                'CNY' => esc_html__( 'Chinese Yuan', 'cariera' ),
                'COP' => esc_html__( 'Colombian Peso', 'cariera' ),
                'CZK' => esc_html__( 'Czech Koruna', 'cariera' ),
                'DKK' => esc_html__( 'Danish Krone', 'cariera' ),
                'DOP' => esc_html__( 'Dominican Peso', 'cariera' ),
                'EUR' => esc_html__( 'Euros', 'cariera' ),
                'HKD' => esc_html__( 'Hong Kong Dollar', 'cariera' ),
                'HRK' => esc_html__( 'Croatia kuna', 'cariera' ),
                'HUF' => esc_html__( 'Hungarian Forint', 'cariera' ),
                'ISK' => esc_html__( 'Icelandic krona', 'cariera' ),
                'IDR' => esc_html__( 'Indonesia Rupiah', 'cariera' ),
                'INR' => esc_html__( 'Indian Rupee', 'cariera' ),
                'NPR' => esc_html__( 'Nepali Rupee', 'cariera' ),
                'ILS' => esc_html__( 'Israeli Shekel', 'cariera' ),
                'JPY' => esc_html__( 'Japanese Yen', 'cariera' ),
                'KIP' => esc_html__( 'Lao Kip', 'cariera' ),
                'KRW' => esc_html__( 'South Korean Won', 'cariera' ),
                'LKR' => esc_html__( 'Sri Lankan Rupee', 'cariera' ),
                'MYR' => esc_html__( 'Malaysian Ringgits', 'cariera' ),
                'MXN' => esc_html__( 'Mexican Peso', 'cariera' ),
                'NGN' => esc_html__( 'Nigerian Naira', 'cariera' ),
                'NOK' => esc_html__( 'Norwegian Krone', 'cariera' ),
                'NZD' => esc_html__( 'New Zealand Dollar', 'cariera' ),
                'PYG' => esc_html__( 'Paraguayan Guaraní', 'cariera' ),
                'PHP' => esc_html__( 'Philippine Pesos', 'cariera' ),
                'PLN' => esc_html__( 'Polish Zloty', 'cariera' ),
                'GBP' => esc_html__( 'Pounds Sterling', 'cariera' ),
                'RON' => esc_html__( 'Romanian Leu', 'cariera' ),
                'RUB' => esc_html__( 'Russian Ruble', 'cariera' ),
                'SGD' => esc_html__( 'Singapore Dollar', 'cariera' ),
                'ZAR' => esc_html__( 'South African rand', 'cariera' ),
                'SEK' => esc_html__( 'Swedish Krona', 'cariera' ),
                'CHF' => esc_html__( 'Swiss Franc', 'cariera' ),
                'TWD' => esc_html__( 'Taiwan New Dollars', 'cariera' ),
                'THB' => esc_html__( 'Thai Baht', 'cariera' ),
                'TRY' => esc_html__( 'Turkish Lira', 'cariera' ),
                'UAH' => esc_html__( 'Ukrainian Hryvnia', 'cariera' ),
                'USD' => esc_html__( 'US Dollars', 'cariera' ),
                'VND' => esc_html__( 'Vietnamese Dong', 'cariera' ),
                'EGP' => esc_html__( 'Egyptian Pound', 'cariera' )
            )
        );
        $settings['job_listings'][1][] = array(
            'name' 		=> 'cariera_enable_filter_salary',
            'std' 		=> '1',
            'label' 	=> esc_html__( 'Salary', 'cariera' ),
            'cb_label'  => esc_html__( 'Enable listing salary', 'cariera' ),
            'desc'		=> esc_html__( 'Enabling this option will show a salary range filter in sidebar on Jobs page and salary fields on Job posting.', 'cariera' ),
            'type'      => 'checkbox'
        );
        $settings['job_listings'][1][] = array(
            'name' 		=> 'cariera_enable_filter_rate',
            'std' 		=> '1',
            'label' 	=> esc_html__( 'Rates', 'cariera' ),
            'cb_label'  => esc_html__( 'Enable listing rates', 'cariera' ),
            'desc'		=> esc_html__( 'Enabling this option will show a rate range filter in sidebar on Jobs page and rate fields on Job posting.', 'cariera' ),
            'type'      => 'checkbox'
        );
        $settings['job_listings'][1][] = array(
            'name'       => 'cariera_job_manager_enable_career_level',
            'std'        => '1',
            'label'      => esc_html__( 'Career Level', 'cariera' ),
            'cb_label'   => esc_html__( 'Enable listing career level', 'cariera' ),
            'desc'       => esc_html__( 'This lets users select from a list of career level when submitting a job. Note: an admin has to create career level before site users can select them.', 'cariera' ),
            'type'       => 'checkbox',
            'attributes' => array(),
        );
        $settings['job_listings'][1][] = array(
            'name'       => 'cariera_job_manager_enable_experience',
            'std'        => '1',
            'label'      => esc_html__( 'Experience', 'cariera' ),
            'cb_label'   => esc_html__( 'Enable listing experience', 'cariera' ),
            'desc'       => esc_html__( 'This lets users select from a list of experience when submitting a job. Note: an admin has to create experience before site users can select them.', 'cariera' ),
            'type'       => 'checkbox',
            'attributes' => array(),
        );
        $settings['job_listings'][1][] = array(
            'name'       => 'cariera_job_manager_enable_qualification',
            'std'        => '1',
            'label'      => esc_html__( 'Qualification', 'cariera' ),
            'cb_label'   => esc_html__( 'Enable listing qualification', 'cariera' ),
            'desc'       => esc_html__( 'This lets users select from a list of qualification when submitting a job. Note: an admin has to create qualification before site users can select them.', 'cariera' ),
            'type'       => 'checkbox',
            'attributes' => array(),
        );
        // Email Setting
        $settings['email_notifications'][1][] = array(
            'name'       => 'cariera_job_manager_approved_job_notification',
            'std'        => '1',
            'label'      => esc_html__( 'Approved Job', 'cariera' ),
            'cb_label'   => esc_html__( 'Approved Job Notification', 'cariera' ),
            'desc'       => esc_html__( 'When enabled the Employer will receive an email notification when their job get\'s approved.', 'cariera' ),
            'type'       => 'checkbox',
            'attributes' => array(),
        );

        return $settings;
    }





    /*
    =====================================================
        WP RESUME MANAGER
    =====================================================
    */

    /*
    * Add extra settings to Resume Options
    *
    * @since 1.3.0
    */
    public function wprm_settings($settings = array()) {

        $settings['resume_listings'][1][] = array(
            'name'       => 'cariera_resume_manager_enable_education',
            'std'        => '1',
            'label'      => esc_html__( 'Education', 'cariera' ),
            'cb_label'   => esc_html__( 'Enable listing education', 'cariera' ),
            'desc'       => esc_html__( 'This lets users select their education when submitting a resume. Note: an admin has to create experience before site users can select them.', 'cariera' ),
            'type'       => 'checkbox',
            'attributes' => array(),
        );
        $settings['resume_listings'][1][] = array(
            'name'       => 'cariera_resume_manager_enable_experience',
            'std'        => '1',
            'label'      => esc_html__( 'Experience', 'cariera' ),
            'cb_label'   => esc_html__( 'Enable listing experience', 'cariera' ),
            'desc'       => esc_html__( 'This lets users select their experience when submitting a resume. Note: an admin has to create experience before site users can select them.', 'cariera' ),
            'type'       => 'checkbox',
            'attributes' => array(),
        );
        $settings['resume_listings'][1][] = array(
            'name'      => 'resume_manager_single_resume_contact_form',
            'std'       => '',
            'label'     => esc_html__( 'Single Resume Contact Form', 'cariera' ),
            'desc'      => esc_html__( 'Select the contact form that you want to show on a single resume page.', 'cariera' ),
            'type'      => 'select',
            'options'   => cariera_get_forms()
        );
        $settings['resume_listings'][1][] = array(
            'name'       => 'cariera_resume_manager_contact_owner',
            'std'        => '0',
            'label'      => esc_html__( 'Owner Contact', 'cariera' ),
            'cb_label'   => esc_html__( 'Hide Contact to Owner', 'cariera' ),
            'desc'       => esc_html__( 'When enabled the "contact button & form" of the Resume will be hidden from the owner of the Resume. This will avoid Candidates being able to send emails to themselves via their own Resume.', 'cariera' ),
            'type'       => 'checkbox',
            'attributes' => array(),
        );

        // Email Setting
        $settings['email_notifications'][1][] = array(
            'name'       => 'cariera_resume_manager_approved_resume_notification',
            'std'        => '1',
            'label'      => esc_html__( 'Approved Resume', 'cariera' ),
            'cb_label'   => esc_html__( 'Approved Resume Notification', 'cariera' ),
            'desc'       => esc_html__( 'When enabled the Candidate will receive an email notification when their resume get\'s approved.', 'cariera' ),
            'type'       => 'checkbox',
            'attributes' => array(),
        );
        $settings['email_notifications'][1][] = array(
            'name'       => 'cariera_resume_manager_expired_resume_notification',
            'std'        => '1',
            'label'      => esc_html__( 'Expired Resume', 'cariera' ),
            'cb_label'   => esc_html__( 'Expired Resume Notification', 'cariera' ),
            'desc'       => esc_html__( 'When enabled the Candidate will receive an email notification when their resume get\'s expired.', 'cariera' ),
            'type'       => 'checkbox',
            'attributes' => array(),
        );

        return $settings;
    }

}