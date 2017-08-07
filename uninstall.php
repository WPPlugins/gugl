<?php

if (!defined('WP_UNINSTALL_PLUGIN')) exit ();

delete_option('gugl_plugin_options');
delete_option('gugl_views');
delete_option('gugl_hits');

?>