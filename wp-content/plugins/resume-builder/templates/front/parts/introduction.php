<?php

global $resume, $compact;

if ( isset( $resume['introduction']['content'] ) ):
	
	do_action( 'rb_resume_before_resume_header' );
	$rand_rbt = rand( 100,999 );
	
	?><style type="text/css">
		<?php echo '#rb-template-' . $rand_rbt; ?> .rbt-introduction { color:<?php echo $resume['display']['text_color']; ?>; }
	</style>
	
	<div id="<?php echo 'rb-template-' . $rand_rbt; ?>" class="<?php echo 'rb-template-' . $resume['display']['template']; ?>">
		<?php echo '<div class="rbt-introduction">' . $resume['introduction']['content'] . '</div>'; ?>
	</div><?php
	
	do_action( 'rb_resume_after_resume_header' );
	
endif;