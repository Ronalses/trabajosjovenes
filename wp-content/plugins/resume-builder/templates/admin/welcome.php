<div id="rbuilder-welcome-screen">
	<div class="wrap about-wrap">
		<div id="welcome-panel" class="welcome-panel">
			
			<div class="rbuilder-welcome-banner" style="background-image: url( '<?php echo apply_filters( 'rbuilder_welcome_banner_img', RBUILDER_URL . '/assets/admin/images/welcome-banner.jpg' ); ?>' );">
				<div class="rbuilder-badge">
					<img src="<?php echo apply_filters( 'rbuilder_welcome_badge_img', RBUILDER_URL . '/assets/admin/images/logo.png' ); ?>">
				</div>
			</div>
			
			<div class="rbuilder-intro">
				<h1><?php echo sprintf( __( 'Thanks for using %s.', 'resume-builder'), 'Resume Builder' ); ?></h1>
				<div class="about-text">
					<?php echo sprintf(__('If this is your first time using %s, have a look at the %s. If you just recently updated, you can find out what\'s new below. Go and %s!','resume-builder'),'Resume Builder','<a target="_blank" href="https://resumebuilder.studio/examples/">' . esc_html__( 'Examples', 'resume-builder' ) . '</a>', '<a href="' . untrailingslashit( admin_url('admin.php?page=rbuilder_main') ) . '">' . esc_html__( 'create a resume', 'resume-builder' ) . '</a>' ); ?>
				</div>
			</div>
			
			<div class="welcome-panel-content">
				<div class="welcome-panel-column">
					<h3><?php esc_html_e( 'Quick Links', 'resume-builder' ); ?></h3>
					<ul>
						<li>
							<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M11.25 19.25H7.75C6.64543 19.25 5.75 18.3546 5.75 17.25V6.75C5.75 5.64543 6.64543 4.75 7.75 4.75H14L18.25 9V11.25" stroke="#141414" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
								<path d="M17 14.75V19.25" stroke="#141414" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
								<path d="M19.25 17H14.75" stroke="#141414" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
								<path d="M18 9.25H13.75V5" stroke="#141414" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
							</svg>
							<a href="<?php echo admin_url('admin.php?page=rbuilder_main'); ?>"><?php esc_html_e('Create a Resume','resume-builder'); ?></a>
						</li>
						<li>
							<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M17.25 15.25V6.75H8.75" stroke="#141414" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
								<path d="M17 7L6.75 17.25" stroke="#141414" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
							</svg>	
							<a href="https://resumebuilder.studio/examples" target="_blank"><?php esc_html_e( 'Resume Examples','resume-builder' ); ?></a>
						</li>
					</ul>
				</div>
				<div class="welcome-panel-column welcome-panel-last">
					<?php do_action( 'rbuilder_welcome_before_changelog' ); ?>
					<?php echo Resume_Builder_Functions::parse_readme_changelog(); ?>
					<?php do_action( 'rbuilder_welcome_after_changelog' ); ?>
				</div>
			</div>
		</div>
	</div>
</div>
