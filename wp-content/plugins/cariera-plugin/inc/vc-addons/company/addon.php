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





/*==================
    Company Board
==================*/
vc_map( array(
    'name'                      => esc_html__( 'Company Board', 'cariera' ),
    "base"                      => "companies",
    "description"               => esc_html__("Adds a company board", 'cariera'),
    "show_settings_on_create"   => true,
    "weight"                    => 1,
    "category"                  => 'Job Manager',
    "group"                     => 'Job Manager',
    "params"                    => array(
        array(
            "type"          => "dropdown",
            "heading"       => esc_html__( 'Company Layout', 'cariera'),
            "param_name"    => "companies_layout",
            "value"         => array(
                'List Layout'       => 'list',
                'Grid Layout'       => 'grid',
            ),
            "description"   => esc_html__( "Choose the layout style for your companies.", 'cariera'),
        ),
        array(
            "type"          => "dropdown",
            "heading"       => esc_html__("Companies List Styles", 'cariera'),
            "param_name"    => "companies_list_version",
            "value"         => array(
                'Version 1'         => '1',
                'Version 2'         => '2',
            ),
            "description"   => esc_html__("Choose a style for your companies.", 'cariera'),
            'dependency'    => array( 
                'element' => 'companies_layout', 
                'value' => array('list') 
            ),
        ),
        array(
            "type"          => "dropdown",
            "heading"       => esc_html__("Companies Grid Styles", 'cariera'),
            "param_name"    => "companies_grid_version",
            "value"         => array(
                'Version 1'         => '1',
                'Version 2'         => '2',
            ),
            "description"   => esc_html__("Choose a style for your companies.", 'cariera'),
            'dependency'    => array( 
                'element' => 'companies_layout', 
                'value' => array('grid') 
            ),
        ),
        array(
            "type"          => "textfield",
            "heading"       => esc_html__("Items Per Page", 'cariera'),
            "param_name"    => "per_page",
            "value"         => 5,
            "description"   => esc_html__("How many companies will be shown per page.", 'cariera'),
        ),
        array(
            "type"          => "dropdown",
            "heading"       => esc_html__("Order By", 'cariera'),
            "param_name"    => "orderby",
            "value"         => array(
                'Default'       => '',
                'Date'          => 'date',
                'Title'         => 'title',
                'ID'            => 'ID',
                'Name'          => 'name',
                'Modified'      => 'modified',
                'Parent'        => 'parent',
                'Rand'          => 'rand',
            ),
            "description"   => esc_html__("Choose order parameter.", 'cariera'),
        ),
        array(
            "type"          => "dropdown",
            "heading"       => esc_html__("Order", 'cariera'),
            "param_name"    => "order",
            "value"         => array(
                'Default'       => '',
                'DESC'          => 'DESC',
                'ASC'           => 'ASC',
            ),
            "description"   => esc_html__("Choose sorting order.", 'cariera'),
        ),
        array(
            "type"          => "dropdown",
            "heading"       => esc_html__("Show Filters", 'cariera'),
            "param_name"    => "show_filters",
            "value"         => $yes_no,
            "description"   => esc_html__("Whether show filters by keyword, location, category, type or not...", 'cariera'),
        ),
        array(
            "type"          => "dropdown",
            "heading"       => esc_html__("Featured", 'cariera'),
            "param_name"    => "featured",
            "value"         => $yes_no,
            "description"   => esc_html__("Set to Yes to show only featured companies, No to not show featured companies, or Both show both (featured first).", 'cariera'),
        ),
    ),
) );





/*==================
    Company Submit Form
==================*/
vc_map(array(
    "name"                      => esc_html__("Submit Company Form", 'cariera'),
    "base"                      => "submit_company",
    "description"               => esc_html__( "Form to post a new company", 'cariera' ),
    "show_settings_on_create"   => false,
    "weight"                    => 1,
    "category"                  => 'Job Manager',
    "group"                     => 'Job Manager',
    "content_element"           => true,
));





/*==================
    Company Dashboard
==================*/
vc_map(array(
    "name"                      => esc_html__("Company Dashboard", 'cariera'),
    "base"                      => "company_dashboard",
    "description"               => esc_html__( "Company dashboard used by logged in users", 'cariera'),
    "show_settings_on_create"   => false,
    "weight"                    => 1,
    "category"                  => 'Job Manager',
    "group"                     => 'Job Manager',
    "content_element"           => true,
));




/*==================
    Company List
==================*/
vc_map( array(
    'name'                      => esc_html__( 'Companies List', 'cariera' ),
    "base"                      => "cariera_companies_list",
    "description"               => esc_html__("Adds a list of all companies", 'cariera'),
    "show_settings_on_create"   => true,
    "weight"                    => 1,
    "category"                  => 'Job Manager',
    "group"                     => 'Job Manager',
) );