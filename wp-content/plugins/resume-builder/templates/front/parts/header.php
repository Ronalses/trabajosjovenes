<?php

global $resume, $compact;

do_action( 'rb_resume_before_resume_header' );
$rand_rbt = rand( 100,999 );

?><style type="text/css">
	<?php echo '#rb-template-' . $rand_rbt; ?> .rbt-header .rbt-name,
	<?php echo '#rb-template-' . $rand_rbt; ?> .rbt-header .rbt-title { color:<?php echo $resume['display']['text_color']; ?>; }
	<?php echo '#rb-template-' . $rand_rbt; ?> .rbt-header > .rbt-photo { width: <?php echo $resume['display']['photo_size']; ?>px; height: <?php echo $resume['display']['photo_size']; ?>px; }
</style>

<div id="<?php echo 'rb-template-' . $rand_rbt; ?>" class="<?php echo 'rb-template-' . $resume['display']['template']; ?>">
	<section class="rbt-header<?php echo ( has_post_thumbnail( $resume['id'] ) ? ' rb-resume-has-photo' : '' ); ?>">

		<?php do_action( 'rb_resume_header_start' ); ?>
		
		<?php if ( has_post_thumbnail( $resume['id'] ) ): ?>
			<div class="rbt-photo" style="background-image: url( '<?php echo get_the_post_thumbnail_url( $resume['id'], 'large' ); ?>' )">&nbsp;</div>
		<?php endif; ?>
	
		<?php do_action( 'rb_resume_after_photo' ); ?>
	
		<div class="rbt-name"><?php echo ( isset( $resume['introduction']['title'] ) ? esc_html( $resume['introduction']['title'] ) : esc_html( $resume['title'] ) ); ?></div>
	
		<?php do_action( 'rb_resume_after_title' ); ?>
	
		<?php echo ( isset( $resume['introduction']['subtitle'] ) ? '<div class="rbt-title">' . ( esc_html( $resume['introduction']['subtitle'] ) ) . '</div>' : '' ); ?>
	
		<?php do_action( 'rb_resume_header_end' ); ?>
	
	</section>
</div><?php

do_action( 'rb_resume_after_resume_header' );