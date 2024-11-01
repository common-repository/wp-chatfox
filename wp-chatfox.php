<?php

	//
	//	Plugin Name:	WP Chat Fox
	//	Plugin URI:
	//	Description:	Chat Fox is a 100% FREE live chat application for your website. Get all the premium features others are paying for, absolutely free!
	//	Version:		1.0.0
	//	Author:			ChatFox.co
	//	Author URI:		https://chatfox.co/
	//	Text Domain:	wp-chatfox
	//	License:
	//
		
		
	//	STYLES AND SCRIPTS
	//	----------------------------------------------------------------------------------------------------
		
		//  ADMIN STYLES
		function wp_chatfox_admin_styles() {
			
			wp_enqueue_style('wp-chatfox', plugins_url('/css/wp-chatfox-admin.css', __FILE__));

		} add_action('admin_print_styles', 'wp_chatfox_admin_styles');
		
	
	//	NAVIGATION
	//	----------------------------------------------------------------------------------------------------
		
		function wp_chatfox_admin_menu() {

			add_menu_page('WP Chat Fox', 'WP Chat Fox', 'edit_posts', 'wp-chatfox', null, plugins_url('/img/menu-icon.png', __FILE__));
			add_submenu_page('wp-chatfox', __('Settings', 'wp-chatfox'), __('Settings', 'wp-chatfox'), 'edit_posts', 'wp-chatfox', 'wp_chatfox_settings');
			add_submenu_page('wp-chatfox', __('Dashboard', 'wp-chatfox'), __('Dashboard', 'wp-chatfox'), 'edit_posts', 'wp-chatfox-dashboard', 'wp_chatfox_dashboard');

		} add_action('admin_menu', 'wp_chatfox_admin_menu');
	
        function wp_chatfox_admin_menu_external_url() {
    
            global $submenu;
            
            $submenu['wp-chatfox'][] = array(__('<span id="wp-chatfox-register">Register for FREE!</span>', 'wp-chatfox'), 'manage_options', 'http://chatfox.co/');

        } add_action('admin_menu', 'wp_chatfox_admin_menu_external_url');
        
        function wp_chatfox_admin_menu_external_url_target() { ?>
    
            <script type="text/javascript">
                jQuery(document).ready(function($) {
                    $('#wp-chatfox-register').parent().attr('target','_blank');
                });
            </script> <?php

        } add_action('admin_footer', 'wp_chatfox_admin_menu_external_url_target');    


	//	PLUGIN INIT
	//	----------------------------------------------------------------------------------------------------

        function wp_chatfox_plugin_init() {

			//  SETTINGS
			register_setting('wp-chatfox-settings-group', 'wp-chatfox-enabled');
			
			//  REDIRECTS USER AFTER INSTALL
			if( get_option('wp-chatfox-plugin-activation-redirect', false) ):
                
                delete_option('wp-chatfox-plugin-activation-redirect');
                if( !isset($_GET['activate-multi']) ) wp_redirect('options-general.php?page=wp-chatfox');
                
            endif;

		} add_action('admin_init', 'wp_chatfox_plugin_init');
		
		function wp_chatfox_plugin_activate() {
		    
            add_option('wp-chatfox-plugin-activation-redirect', true);
            
		}
		    
		register_activation_hook(__FILE__, 'wp_chatfox_plugin_activate');
		
		
	//	PLUGINS PAGE LINKS
	//	----------------------------------------------------------------------------------------------------

		function wp_chatfox_plugin_action_links($links) {

			unset($links['edit']);

			return array_merge($links, array(
				'<a href="' . esc_url(get_admin_url(null, 'admin.php?page=wp-chatfox')) . '">' . __('Settings', 'wp-chatfox') . '</a>',
				'<a href="http://chatfox.co/" target="_blank">' . __('Register for FREE!', 'wp-chatfox') . '</a>'
			));

		} add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'wp_chatfox_plugin_action_links');
        

	//	SETTINGS SCREEN
	//	----------------------------------------------------------------------------------------------------

		function wp_chatfox_settings() {

			if( !current_user_can('manage_options') ) wp_die(__('You do not have sufficient permissions to access this page.', 'wp-chatfox')); ?>

			<div class="wrap">

				<p><img src="<?php echo plugins_url('/img/logo-chatfox.png', __FILE__); ?>" alt="Chat Fox"></p>
				
				<form method="post" action="options.php">
				    
				    <?php settings_fields('wp-chatfox-settings-group'); ?>
				    
				    <h3><?php _e('Step 1', 'wp-chatfox'); ?></h3>
				    <p><?php _e('Sign up for a <strong>FREE</strong> account on Chat Fox (<a href="http://chatfox.co/" target="_blank">http://chatfox.co/</a>), <strong>VERIFY</strong> and <strong>CONFIRM</strong> your email address, then <strong>COME BACK</strong> to this screen.', 'wp-chatfox'); ?></p>
				    
				    <h3><?php _e('Step 2', 'wp-chatfox'); ?></h3>
				    <p><?php _e('<a href="https://live.chatfox.co/operator/index.php" target="_blank">Log in</a> to your account and add your domain(s) to the white list (under "Chat Widget" on your dashboard).', 'wp-chat-fox'); ?></p>
				    <p><?php _e('<strong>NOTE</strong>: In order for Chat Fox to work properly, you must add EVERY domain it will be used on.<br>The domains must be separated with commas and shouldn\'t include a trailing slash.', 'wp-chatfox'); ?></p>
				    
				    <h3><?php _e('Step 3', 'wp-chatfox'); ?></h3>
				    <p><?php _e('Awesome! You can now <a href="https://live.chatfox.co/operator/index.php?p=widget" target="_blank">start customizing</a> your widget. When you\'re done activate the chat widget below.', 'wp-chatfox'); ?></p>

				    <table class="form-table">
                        <tbody>
                            <tr>
		                        <th><label for="wp-chatfox-enabled"><?php _e('Chat widget enabled?', 'wp-chatfox'); ?></label></th>
		                        <td><input name="wp-chatfox-enabled" type="checkbox" id="wp-chatfox-enabled" value="1" <?php echo get_option('wp-chatfox-enabled') ? 'checked="checked"' : ''; ?>></td>
                            </tr>
                        </tbody>
                    </table>

					<?php submit_button(); ?>

				</form>

			</div>
			
		<?php }
        
        
    //	DASHBOARD SCREEN
	//	----------------------------------------------------------------------------------------------------

		function wp_chatfox_dashboard() { ?>
		    
		    <iframe style="border:0;height:800px;width:100%;" src="https://live.chatfox.co/operator/index.php"></iframe>
		    
        <?php }
		
		
	//	JAVASCRIPT WIDGET
	//	----------------------------------------------------------------------------------------------------
	
    	add_action('wp_footer', function() {
    	
    	    if( get_option('wp-chatfox-enabled') ): ?>
    
                <!-- Start Chat Fox widget -->
                <script type="text/javascript">
            	    !function(e,c,t,a){e.id=7,e.lang="",e.cName="",e.cEmail="",e.cMessage="",e.lcjUrl="https://live.chatfox.co/";var s=c.getElementsByTagName(t)[0],n=c.createElement(t);n.async=!0,n.src="https://live.chatfox.co/js/jaklcpchat.js",s.parentNode.insertBefore(n,s)}(window,document,"script");
                </script>
                <div id="jaklcp-chat-container"></div>
                <!-- End Chat Fox widget -->
        
            <?php endif;
    	    
    	});

?>