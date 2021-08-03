<?php

global $resume, $compact;

if ( !empty( $resume['experience'] ) ):

	$rand_rbt = rand( 100,999 );
	
	?><style type="text/css">
		<?php echo '#rb-template-' . $rand_rbt; ?>,
		<?php echo '#rb-template-' . $rand_rbt; ?> .rbt-history-blocks .rbt-history-block { color: <?php echo $resume['display']['text_color']; ?>; }
		<?php echo '#rb-template-' . $rand_rbt; ?> .rbt-section-heading { color: <?php echo $resume['display']['highlight_color']; ?>; }
		<?php echo '#rb-template-' . $rand_rbt; ?> .rbt-history-blocks .rbt-history-block > div::before { background: <?php echo $resume['display']['text_color']; ?>; }
		<?php echo '#rb-template-' . $rand_rbt; ?> .rbt-history-blocks .rbt-history-place-time > span::after { background: <?php echo $resume['display']['text_color']; ?>; }
		<?php echo '#rb-template-' . $rand_rbt; ?> .rbt-history-blocks .rbt-history-block .rbt-history-job-degree:after { background: <?php echo $resume['display']['highlight_color']; ?>; }
	</style><?php

	do_action( 'rb_resume_before_resume_body' );
	
	echo '<div id="rb-template-' . $rand_rbt . '" class="rb-template-' . $resume['display']['template'] . '">';
	
		do_action( 'rb_resume_experience_start' );
		
		$started_history_block = false;
	
		foreach( $resume['experience'] as $exp ):
		
			if ( $started_history_block && isset( $exp['section_heading_name'] ) && $exp['section_heading_name'] || $started_history_block && isset( $exp['section_text_content'] ) && $exp['section_text_content'] ):
				$started_history_block = false;
				echo '</div>';
			endif;
		
			if ( isset( $exp['section_heading_name'] ) && $exp['section_heading_name'] ):    
				echo '<div class="rbt-section-heading">' . esc_html( $exp['section_heading_name'] ) . '</div>';
			elseif ( isset( $exp['section_text_content'] ) && $exp['section_text_content'] ):
				echo '<div class="rbt-text-content">' . wpautop( $exp['section_text_content'] ) . '</div>';
			else:
			
				if ( !$started_history_block ):
					$started_history_block = true;
					echo '<div class="rbt-history-blocks">';
				endif;
				
				echo '<div class="rbt-history-block">';
				
					echo ( isset( $exp['short_description'] ) && $exp['short_description'] ? '<div class="rbt-history-job-degree">' . esc_html( $exp['short_description'] ) . '</div>' : '' );
					
					if ( isset( $exp['title'] ) || isset( $exp['date_range'] ) ): echo '<div class="rbt-history-place-time">'; endif;
						echo ( isset( $exp['title'] ) && $exp['title'] ? '<span class="rbt-history-company-school">' . esc_html( $exp['title'] ) . '</span>' : '' );
						echo ( isset( $exp['date_range'] ) && $exp['date_range'] ? '<span class="rbt-history-dates">' . esc_html( $exp['date_range'] ) . '</span>' : '' );
					if ( isset( $exp['title'] ) || isset( $exp['date_range'] ) ): echo '</div>'; endif;
					
					echo ( isset( $exp['long_description'] ) && $exp['long_description'] ? '<div class="rbt-history-description">' . $exp['long_description'] . '</div>' : '' );
					
				echo '</div>';
			endif;
		endforeach;
		
		if ( $started_history_block ):
			$started_history_block = false;
			echo '</div>';
		endif;
		
		do_action( 'rb_resume_experience_end' );
		
	echo '</div>';

endif;