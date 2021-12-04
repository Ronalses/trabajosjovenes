<?php

// don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}



vc_map( array(
    'name'        => esc_html__( 'Google Maps', 'cariera' ),
    'description' => esc_html__( '', 'cariera' ),
    'base'        => 'cariera_googlemaps',
    'category' => 'Cariera Custom',
    'group' => 'Cariera Custom',        
    "params" => array(
        array(
            "type"			=> "textfield",
            "admin_label"	=> true,
            "class"			=> "",
            "heading"		=> esc_html__( "Address", 'cariera' ),
            "param_name"	=> "address",
            "value"			=> "",
            "description"	=> esc_html__( "Insert valid address string", 'cariera' ),
        ),
        array(
            "type"			=> "textfield",
            "admin_label"	=> true,
            "class"			=> "",
            "heading"		=> esc_html__( "Latitude", 'cariera' ),
            "param_name"	=> "lat",
            "value"			=> "",
            "description"	=> esc_html__( "For exact positioning you can use Lat & Long INSTEAD OF the address field.<br />Note: Does not work with a custom Marker Image", 'cariera' ),
        ),
        array(
            "type"			=> "textfield",
            "admin_label"	=> true,
            "class"			=> "",
            "heading"		=> esc_html__( "Longitude", 'cariera' ),
            "param_name"	=> "lon",
            "value"			=> "",
            "description"	=> esc_html__( "For exact positioning you can use Lat & Long INSTEAD OF the address field.<br />Note: Does not work with a custom Marker Image", 'cariera' ),
        ),
        array(
            "type"			=> "dropdown",
            "admin_label"	=> false,
            "class"			=> "",
            "heading"		=> esc_html__( "Map Style", 'cariera' ),
            "param_name"	=> "style",
            "value"			=> array(
                'With Border' => 'full',
                'Without Border' => 'fullsection',
            ),
        ),
        array(
            "type"			=> "textfield",
            "admin_label"	=> false,
            "class"			=> "",
            "heading"		=> esc_html__( "Height", 'cariera' ),
            "param_name"	=> "h",
            "value"			=> "300",
            "description"	=> esc_html__( "Height of the Map in px (leave the 'px' out)", 'cariera' ),
        ),
        array(
            "type"			=> "textfield",
            "admin_label"	=> false,
            "class"			=> "",
            "heading"		=> esc_html__( "Map ID", 'cariera' ),
            "param_name"	=> "id",
            "value"			=> "",
            "description"	=> esc_html__( "Unique Map ID (map1, map2,.. if you use multiple maps on one page)", 'cariera' ),
        ),
        array(
            "type"			=> "textfield",
            "admin_label"	=> false,
            "class"			=> "",
            "heading"		=> esc_html__( "Zoom Level", 'cariera' ),
            "param_name"	=> "z",
            "value"			=> "14",
            "description"	=> esc_html__( "Value between 1-21. Higher number = more zoomed in.", 'cariera' ),
            "group"	        => esc_html__( 'Options', 'cariera' ),
        ),
        array(
            "type"			=> "dropdown",
            "admin_label"	=> false,
            "class"			=> "",
            "heading"		=> esc_html__( "Map Type", 'cariera' ),
            "param_name"	=> "maptype",
            "value"			=> array(
                'Roadmap'       => 'ROADMAP',
                'Satellite'     => 'SATELLITE',
                'Hybrid'        => 'HYBRID',
                'Terrain'       => 'TERRAIN',
            ),
            "description"	=> esc_html__( "Desired map type", 'cariera' ),
            "group"	        => esc_html__( 'Options', 'cariera' ),
        ),
        array(
            "type"			=> "dropdown",
            "admin_label"	=> false,
            "class"			=> "",
            "heading"		=> esc_html__( "Marker", 'cariera' ),
            "param_name"	=> "marker",
            "value"			=> array(
                'Show Marker'   => 'yes',
                'Hide Marker'   => '',
            ),
            "description"	=> esc_html__( "Select if you want to show a marker", 'cariera' ),
            "group"	        => esc_html__( 'Marker', 'cariera' ),
        ),
        array(
            "type"			=> "attach_image",
            "admin_label"	=> false,
            "class"			=> "",
            "heading"		=> esc_html__( "Marker Image", 'cariera' ),
            "param_name"	=> "markerimage",
            "value"			=> "",
            "description"	=> esc_html__( "If you want to use a Marker Image you can upload it here.", 'cariera' ),
            "group"	        => esc_html__( 'Marker', 'cariera' ),
        ),
        array(
            "type"			=> "textarea",
            "admin_label"	=> false,
            "class"			=> "",
            "heading"		=> esc_html__( "Infowindow", 'cariera' ),
            "param_name"	=> "infowindow",
            "value"			=> "",
            "description"	=> esc_html__( "Text to add to the Infowindow", 'cariera' ),
            "group"	        => esc_html__( 'Marker', 'cariera' ),
        ),
        array(
            "type"			=> "dropdown",
            "admin_label"	=> false,
            "class"			=> "",
            "heading"		=> esc_html__( "Show Infowindow", 'cariera' ),
            "param_name"	=> "infowindowdefault",
            "value"			=> array(
                'Show Info Window on load' => 'yes',
                'Hide Info Window on load' => 'no',
            ),
            "description"	=> esc_html__( "Choose to have the infowindow show or not show automatically when the page loads", 'cariera' ),
            "group"	        => esc_html__( 'Marker', 'cariera' ),
        ),
        array(
            "type"			=> "dropdown",
            "admin_label"	=> false,
            "class"			=> "",
            "heading"		=> esc_html__( "Scrollwheel Zoom", 'cariera' ),
            "param_name"	=> "scrollwheel",
            "value"			=> array(
                'Enable Scrollwheel Zoom' => 'true',
                'Disable Scrollwheel Zoom' => 'false',
            ),
            "description"	=> esc_html__( "Allow scroll wheel zooming", 'cariera' ),
            "group"	        => esc_html__( 'Options', 'cariera' ),
        ),
        array(
            "type"			=> "dropdown",
            "admin_label"	=> false,
            "class"			=> "",
            "heading"		=> esc_html__( "Controls", 'cariera' ),
            "param_name"	=> "hidecontrols",
            "value"			=> array(
                'Show Controls' => 'false',
                'Hide Controls' => 'true',
            ),
            "description"	=> esc_html__( "Show / Hide Map Controls", 'cariera' ),
            "group"	        => esc_html__( 'Options', 'cariera' ),
        ),
        array(
            "type"			=> "textarea_raw_html",
            "admin_label"	=> false,
            "class"			=> "",
            "heading"		=> esc_html__( "Color Scheme Code", 'cariera' ),
            "param_name"	=> "colorscheme",
            "value"			=> "",
            "description"	=> esc_html__( "<strong>For advanced users:</strong> Paste your snazzymaps.com styles here.", 'cariera' ),
            "group"	        => esc_html__( 'Options', 'cariera' ),
        ),
    ),
) );

    
    
/*
Shortcode logic how it should be rendered
*/

if ( !function_exists( 'cariera_googlemaps' ) ) {
	function cariera_googlemaps($attr) {
        $attr = shortcode_atts(array(	
            'lat'               => '0', 
            'lon'               => '0',
            'id'                => 'map',
            'z'                 => '14',
            'w'                 => '400',
            'h'                 => '300',
            'maptype'           => 'ROADMAP',
            'address'           => '',
            'kml'               => '',
            'kmlautofit'        => 'yes',
            'marker'            => 'yes',
            'markerimage'       => '',
            'traffic'           => 'no',
            'bike'              => 'no',
            'fusion'            => '',
            'start'             => '',
            'end'               => '',
            'infowindow'        => '',
            'infowindowdefault' => 'yes',
            'directions'        => '',
            'hidecontrols'      => 'false',
            'scale'             => 'false',
            'scrollwheel'       => 'true',
            'style'             => 'full',
            'colorscheme'       => ''
        ), $attr);

	   wp_print_scripts( 'google-maps' );

        $returnme = '<div id="' .esc_attr($attr['id']) . '" style="height:' . esc_attr($attr['h']) . 'px;" class="google_map ' . esc_attr($attr['style']) . ' wpb_content_element"></div>';

        //directions panel
        if($attr['start'] != '' && $attr['end'] != '') {
            $panelwidth = $attr['w']-20;
            $returnme .= '
            <div id="directionsPanel" style="width:' . esc_attr($panelwidth) . 'px;height:' . esc_attr($attr['h']) . 'px;border:1px solid gray;padding:10px;overflow:auto;"></div><br>
            ';
        }

        $getScheme = NULL;
        if($attr['colorscheme'] != '') {
            $getScheme = 'styles: '.rawurldecode(base64_decode($attr['colorscheme'])).','; // ignore theme check
        }

        $returnme .= '
        <script type="text/javascript">

            var latlng = new google.maps.LatLng(' . esc_js($attr['lat']) . ', ' . esc_js($attr['lon']) . ');
            var myOptions = {
                zoom: ' . esc_js($attr['z']) . ',
                center: latlng,
                scrollwheel: ' . esc_js($attr['scrollwheel']) .',
                disableDefaultUI: ' . esc_js($attr['hidecontrols']) .',
                scaleControl: ' . esc_js($attr['scale']) .',
                // mapTypeControl: false,
                //rotateControl: false,
                panControl: false,
                //scaleControl: false,
                streetViewControl: false,
                '. $getScheme .'
                //overviewMapControl: false,
                mapTypeId: google.maps.MapTypeId.' . esc_js($attr['maptype']) . '
            };
            var ' . esc_js($attr['id']) . ' = new google.maps.Map(document.getElementById("' . esc_js($attr['id']) . '"),
            myOptions);
            ';
				
		//kml
		if($attr['kml'] != '') {
			if($attr['kmlautofit'] == 'no') {
				$returnme .= '
				var kmlLayerOptions = {preserveViewport:true};
				';
			}
			else {
				$returnme .= '
				var kmlLayerOptions = {preserveViewport:false};
				';
			}
			$returnme .= '
			var kmllayer = new google.maps.KmlLayer(\'' . html_entity_decode($attr['kml']) . '\',kmlLayerOptions);
			kmllayer.setMap(' . esc_js($attr['id']) . ');
			';
		}

		//directions
		if($attr['start'] != '' && $attr['end'] != '') {
			$returnme .= '
			var directionDisplay;
			var directionsService = new google.maps.DirectionsService();
		    directionsDisplay = new google.maps.DirectionsRenderer();
		    directionsDisplay.setMap(' . esc_js($attr['id']) . ');
    		directionsDisplay.setPanel(document.getElementById("directionsPanel"));

				var start = \'' . esc_js($attr['start']) . '\';
				var end = \'' . esc_js($attr['end']) . '\';
				var request = {
					origin:start, 
					destination:end,
					travelMode: google.maps.DirectionsTravelMode.DRIVING
				};
				directionsService.route(request, function(response, status) {
					if (status == google.maps.DirectionsStatus.OK) {
						directionsDisplay.setDirections(response);
					}
				});


			';
		}
		
		//traffic
		if($attr['traffic'] == 'yes') {
			$returnme .= '
			var trafficLayer = new google.maps.TrafficLayer();
			trafficLayer.setMap(' . esc_js($attr['id']) . ');
			';
		}
	
		//bike
		if($attr['bike'] == 'yes') {
			$returnme .= '			
			var bikeLayer = new google.maps.BicyclingLayer();
			bikeLayer.setMap(' . esc_js($attr['id']) . ');
			';
		}
		
		//fusion tables
		if($attr['fusion'] != '') {
			$returnme .= '			
			var fusionLayer = new google.maps.FusionTablesLayer(' . esc_js($attr['fusion']) . ');
			fusionLayer.setMap(' . esc_js($attr['id']) . ');
			';
		}
	
		//address
		if($attr['address'] != '') {
			$returnme .= '
		    var geocoder_' . esc_js($attr['id']) . ' = new google.maps.Geocoder();
			var address = \'' . esc_js($attr['address']) . '\';
			geocoder_' . $attr['id'] . '.geocode( { \'address\': address}, function(results, status) {
				if (status == google.maps.GeocoderStatus.OK) {
					' . esc_js($attr['id']) . '.setCenter(results[0].geometry.location);
					';
					
					if ($attr['marker'] !='') {
						//add custom image
						if ($attr['markerimage'] !='') {
							$image_src = wp_get_attachment_image_src($attr['markerimage'], 'full');
							$returnme .= 'var image = "'. esc_url($image_src[0]) .'";';
						}
						$returnme .= '
						var marker = new google.maps.Marker({
							map: ' . esc_js($attr['id']) . ', 
							';
							if ($attr['markerimage'] !='')
							{
								$returnme .= 'icon: image,';
							}
						$returnme .= '
							position: ' . esc_js($attr['id']) . '.getCenter()
						});
						';

						//infowindow
						if($attr['infowindow'] != '') {
                            
							//first convert and decode html chars
							$thiscontent = htmlspecialchars_decode(preg_replace( "/\r|\n/", "", $attr['infowindow'])); // HTML allowed, no escaping
							$returnme .= '
							var contentString = \'' . $thiscontent . '\';
							var infowindow = new google.maps.InfoWindow({
								content: contentString
							});
										
							google.maps.event.addListener(marker, \'click\', function() {
							  infowindow.open(' . esc_js($attr['id']) . ',marker);
							});
							';

							//infowindow default
							if ($attr['infowindowdefault'] == 'yes') {
								$returnme .= '
									infowindow.open(' . esc_js($attr['id']) . ',marker);
								';
							}
						}
					}
			$returnme .= '
				} else {
				    alert("Geocode was not successful for the following reason: " + status);
			    }
			});
			';
		}

		//marker: show if address is not specified
		if ($attr['marker'] != '' && $attr['address'] == '') {
			//add custom image
			if ($attr['markerimage'] !='') {
				$returnme .= 'var image = "'. esc_url($attr['markerimage']) .'";';
			}

			$returnme .= '
				var marker = new google.maps.Marker({
				map: ' . esc_js($attr['id']) . ', 
				';
				if ($attr['markerimage'] !='')
				{
					$returnme .= 'icon: image,';
				}
			$returnme .= '
				position: ' . esc_js($attr['id']) . '.getCenter()
			});
			';

			//infowindow
			if($attr['infowindow'] != '') {
				$returnme .= '
				var contentString = \'' . esc_js($attr['infowindow']) . '\';
				var infowindow = new google.maps.InfoWindow({
					content: contentString
				});
							
				google.maps.event.addListener(marker, \'click\', function() {
				  infowindow.open(' . esc_js($attr['id']) . ',marker);
				});
				';
				//infowindow default
				if ($attr['infowindowdefault'] == 'yes') {
					$returnme .= '
						infowindow.open(' . esc_js($attr['id']) . ',marker);
					';
				}				
			}
		}

		$returnme .= '
		jQuery(document).ready(function($){
			$(".wpb_accordion_section, .wpb_tabs").click(function(){
	        	var center = '.esc_js($attr['id']).'.getCenter();
	       		google.maps.event.trigger('.esc_js($attr['id']).', "resize"); 
	        	'.esc_js($attr['id']).'.setCenter(center);
	    	});
		});';

		$returnme .= '</script>';
		
		return $returnme;
    }
}

add_shortcode('cariera_googlemaps', 'cariera_googlemaps');