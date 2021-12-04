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
    Job List
==================*/
vc_map(array(
    "name"                      => esc_html__("Job Board", 'cariera'),
    "base"                      => "jobs",
    "description"               => esc_html__("Adds a job board", 'cariera'),
    "show_settings_on_create"   => true,
    "weight"                    => 1,
    "category"                  => 'Job Manager',
    "group"                     => 'Job Manager',
    "content_element"           => true,
    "params"                    => array(
        array(
            "type"          => "dropdown",
            "heading"       => esc_html__("Job Layout", 'cariera'),
            "param_name"    => "jobs_layout",
            "value"         => array(
                'List Layout'       => 'list',
                'Grid Layout'       => 'grid',
            ),
            "description"   => esc_html__("Choose the layout style for your jobs.", 'cariera'),
        ),
        array(
            "type"          => "dropdown",
            "heading"       => esc_html__("Job List Styles", 'cariera'),
            "param_name"    => "jobs_list_version",
            "value"         => array(
                'Version 1'         => '1',
                'Version 2'         => '2',
                'Version 3'         => '3',
                'Version 4'         => '4',                
                'Version 5'         => '5',
            ),
            "description"   => esc_html__("Choose a style for your jobs.", 'cariera'),
            'dependency'    => array( 
                'element' => 'jobs_layout', 
                'value' => array('list') 
            ),
        ),
        array(
            "type"          => "dropdown",
            "heading"       => esc_html__("Job Grid Styles", 'cariera'),
            "param_name"    => "jobs_grid_version",
            "value"         => array(
                'Version 1'         => '1',
                'Version 2'         => '2',
                'Version 3'         => '3',
            ),
            "description"   => esc_html__("Choose a style for your jobs.", 'cariera'),
            'dependency'    => array( 
                'element' => 'jobs_layout', 
                'value' => array('grid') 
            ),
        ),
        array(
            "type"          => "textfield",
            "heading"       => esc_html__("Items Per Page", 'cariera'),
            "param_name"    => "per_page",
            "value"         => 5,
            "description"   => esc_html__("How many items to show in the job board.", 'cariera'),
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
            "description"   => esc_html__("Whether show filters by keyword, region, category, type or not...", 'cariera'),
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
            "heading"       => esc_html__("Show Categories", 'cariera'),
            "param_name"    => "show_categories",
            "value"         => $yes_no,
            "description"   => esc_html__("If enabled, the filters will also show a dropdown letting the user choose a job category to filter by.", 'cariera'),
        ),
        array(
            "type"          => "exploded_textarea",
            "heading"       => esc_html__("Categories", 'cariera'),
            "param_name"    => "categories",
            "value"         => "",
            "description"   => esc_html__("Put one category slug per line to limit the jobs to certain categories. This option overrides \"show_categories\" if both are set.", 'cariera'),
        ),
        array(
            "type"          => "exploded_textarea",
            "heading"       => esc_html__("Job Types", 'cariera'),
            "param_name"    => "job_types",
            "value"         => "",
            "description"   => esc_html__("List of job type slugs (one per line) to limit the jobs to certain job types.", 'cariera'),
        ),
        array(
            "type"          => "exploded_textarea",
            "heading"       => esc_html__("Selected Job Types", 'cariera'),
            "param_name"    => "selected_job_types",
            "value"         => "",
            "description"   => esc_html__("List of job type slugs (one per line) to select by default.", 'cariera'),
        ),
        array(
            "type"          => "textfield",
            "heading"       => esc_html__("Location", 'cariera'),
            "param_name"    => "location",
            "value"         => "",
            "description"   => esc_html__("Enter a location keyword to search by default.", 'cariera'),
        ),
        array(
            "type"          => "textfield",
            "heading"       => esc_html__("Keywords", 'cariera'),
            "param_name"    => "keywords",
            "value"         => "",
            "description"   => esc_html__("Enter a keyword to search by default.", 'cariera'),
        ),
        array(
            "type"          => "dropdown",
            "heading"       => esc_html__("Featured", 'cariera'),
            "param_name"    => "featured",
            "value"         => $yes_no,
            "description"   => esc_html__("Set to Yes to show only featured jobs, No to show no featured jobs, or Both show both (featured first).", 'cariera'),
        ),
        array(
            "type"          => "dropdown",
            "heading"       => esc_html__("Filled", 'cariera'),
            "param_name"    => "filled",
            "value"         => $yes_no,
            "description"   => esc_html__("Set to true to show only filled jobs, false to show no filled jobs, or leave out entirely to respect the default settings.", 'cariera'),
        ),
    ),
));


/*==================
    Single Job
==================*/

vc_map(array(
    "name"                      => esc_html__("Job", 'cariera'),
    "base"                      => "job",
    "description"               => esc_html__("Single job with details", 'cariera'),
    "show_settings_on_create"   => true,
    "weight"                    => 1,
    "category"                  => 'Job Manager',
    "group"                     => 'Job Manager',
    "content_element"           => true,
    "params"                    => array(
        array(
            "type"          => "textfield",
            "heading"       => esc_html__("Job ID", 'cariera'),
            "param_name"    => "id",
            "value"         => "",
            "description"   => esc_html__("Outputs a single job by ID. You can find the id by viewing the list of jobs in admin.", 'cariera'),
        ),
    )
));


/*==================
    Job Submit Form
==================*/

vc_map(array(
    "name"                      => esc_html__("Submit Job Form", 'cariera'),
    "base"                      => "submit_job_form",
    "description"               => esc_html__("Form to post a new job", 'cariera'),
    "show_settings_on_create"   => false,
    "weight"                    => 1,
    "category"                  => 'Job Manager',
    "group"                     => 'Job Manager',
    "content_element"           => true,
));


/*==================
    Job Dashboard
==================*/

vc_map(array(
    "name"                      => esc_html__("Job Dashboard", 'cariera'),
    "base"                      => "job_dashboard",
    "description"               => esc_html__("Job dashboard used by logged in users", 'cariera'),
    "show_settings_on_create"   => false,
    "weight"                    => 1,
    "category"                  => 'Job Manager',
    "group"                     => 'Job Manager',
    "content_element"           => true,
));