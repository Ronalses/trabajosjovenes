<?php

// don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}



$yes_no = array(
    esc_html__('Default', 'cariera') => '',
    esc_html__('Yes', 'cariera') => 'true',
    esc_html__('No', 'cariera') => 'false',
);


if (class_exists('WP_Resume_Manager')) {

    /*==================
        Submit Resume Form
    ==================*/

    vc_map(array(
        "name"                      => esc_html__("Submit Resume Form", 'cariera'),
        "base"                      => "submit_resume_form",
        "description"               => esc_html__("Frontend resume submission form", 'cariera'),
        "show_settings_on_create"   => false,
        "weight"                    => 1,
        "category"                  => 'Resume Manager',
        "group"                     => 'Resume Manager',
        "content_element"           => true,
    ));


    /*==================
        Candidate Dashboard
    ==================*/

    vc_map(array(
        "name"                      => esc_html__("Candidate Dashboard", 'cariera'),
        "base"                      => "candidate_dashboard",
        "description"               => esc_html__("Displays a users submitted resumes", 'cariera'),
        "show_settings_on_create"   => false,
        "weight"                    => 1,
        "category"                  => 'Resume Manager',
        "group"                     => 'Resume Manager',
        "content_element"           => true,
    ));


    /*==================
        Resumes
    ==================*/

    vc_map(array(
        "name"                      => esc_html__("Resumes", 'cariera'),
        "base"                      => "resumes",
        "description"               => esc_html__("Output resumes to a page", 'cariera'),
        "show_settings_on_create"   => true,
        "weight"                    => 1,
        "category"                  => 'Resume Manager',
        "group"                     => 'Resume Manager',
        "content_element"           => true,
        "params"                    => array(
            array(
                "type"          => "dropdown",
                "heading"       => esc_html__( 'Resume Layout', 'cariera'),
                "param_name"    => "resumes_layout",
                "value"         => array(
                    'List Layout'       => 'list',
                    'Grid Layout'       => 'grid',
                ),
                "description"   => esc_html__( "Choose the layout style for your companies.", 'cariera'),
            ),
            array(
                "type"          => "dropdown",
                "heading"       => esc_html__("Resume List Styles", 'cariera'),
                "param_name"    => "resumes_list_version",
                "value"         => array(
                    'Version 1'         => '1',
                    'Version 2'         => '2',
                ),
                "description"   => esc_html__("Choose a style for your resumes.", 'cariera'),
                'dependency'    => array( 
                    'element' => 'resumes_layout', 
                    'value' => array('list') 
                ),
            ),
            array(
                "type"          => "dropdown",
                "heading"       => esc_html__("Resumes Grid Styles", 'cariera'),
                "param_name"    => "resumes_grid_version",
                "value"         => array(
                    'Version 1'         => '1',
                    'Version 2'         => '2',
                ),
                "description"   => esc_html__("Choose a style for your resumes.", 'cariera'),
                'dependency'    => array( 
                    'element' => 'resumes_layout', 
                    'value' => array('grid') 
                ),
            ),
            array(
                "type"          => "textfield",
                "heading"       => esc_html__("Items Per Page", 'cariera'),
                "param_name"    => "per_page",
                "value"         => 5,
                "description"   => esc_html__("How many items to show in the resume list.", 'cariera'),
            ),
            array(
                "type"          => "dropdown",
                "class"         => "",
                "heading"       => esc_html__("Order By", 'cariera'),
                "param_name"    => "orderby",
                "value"         => array(
                    'Default'       => '',
                    'Title'         => 'title',
                    'Date'          => 'date',
                    'ID'            => 'id',
                    'Author'        => 'author',
                    'Modified'      => 'modified',
                    'Parent'        => 'parent',
                    'Rand'          => 'rand',
                ),
                "description"   => esc_html__("Choose order parameter.", 'cariera'),
            ),
            array(
                "type"          => "dropdown",
                "class"         => "",
                "heading"       => esc_html__("Order", 'cariera'),
                "param_name"    => "order",
                "value"         => array(
                    'Default'       => '',
                    'DESC'          => 'desc',
                    'ASC'           => 'asc',
                ),
                "description"   => esc_html__("Choose sorting order.", 'cariera'),
            ),
            array(
                "type"          => "dropdown",
                "class"         => "",
                "heading"       => esc_html__("Show Filters", 'cariera'),
                "param_name"    => "show_filters",
                "value"         => $yes_no,
                "description"   => esc_html__("Show filters above the resume list (to allow searching by location etc).", 'cariera'),
            ),
            array(
                "type"          => "dropdown",
                "class"         => "",
                "heading"       => esc_html__("Show Categories", 'cariera'),
                "param_name"    => "show_categories",
                "value"         => $yes_no,
                "description"   => esc_html__("Whether or not to show categories in the filters.", 'cariera'),
            ),
            array(
                "type"          => "exploded_textarea",
                "heading"       => esc_html__("Categories", 'cariera'),
                "param_name"    => "categories",
                "value"         => "",
                "description"   => esc_html__("List of category slugs (one per line). Only resumes in these categories will be displayed.", 'cariera'),
            ),
            array(
                "type"          => "dropdown",
                "heading"       => esc_html__("Show Pagination", 'cariera'),
                "param_name"    => "show_pagination",
                "value"         => $yes_no,
                "description"   => esc_html__("Enable this to show numbered pagination instead of the \"load more\" link.", 'cariera'),
            ),
            array(
                "type"          => "dropdown",
                "heading"       => esc_html__("Featured", 'cariera'),
                "param_name"    => "featured",
                "value"         => $yes_no,
                "description"   => esc_html__( "Set to Yes to show only featured resumes, No to show no featured resumes, or Both show both (featured first).", 'cariera'),
            ),
        ),
    ));
} /* End Resume */