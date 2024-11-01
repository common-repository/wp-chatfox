<?php

	if( !defined('WP_UNINSTALL_PLUGIN') ) die;
	
	//	CLEANS DB WHEN UNINSTALLING PLUGIN
	//	----------------------------------------------------------------------------------------------------

		delete_option('wp-chatfox-enabled');
		delete_option('wp-chatfox-plugin-activation-redirect');

?>