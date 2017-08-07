<?php
/*
Plugin Name: Gugl
Version: 1.3.4.1
Description: Allow to display button with search phrase if user comes from Google.
Author: Kuba Mikita
Author URI: http://www.wpart.pl/
*/

defined( 'ABSPATH' ) OR exit;

define('GUGL_DEBUG', false); //display array with options

class Gugl_Options {

    public $options;

    public function __construct() {
        $this->options = get_option('gugl_plugin_options');
        $this->register_settings_and_fields();
        $this->reset_statistics();
        $this->send_statistics();
    }

    public function add_menu_page() {
        add_options_page('Gugl Options', __('Gugl Options','gugl'), 'administrator', 'gugl_option', array('Gugl_Options', 'display_gugl_options'));
    }

    public function display_gugl_options() {

        if (GUGL_DEBUG) {
            error_reporting(E_ALL ^ E_NOTICE);
            echo "<pre>";
                $o = get_option('gugl_plugin_options');
                print_r($o);
            echo "</pre>";
        }

        require_once('inc/options.php'); 

    }

    public function reset_statistics() {

        if (!empty($this->options['gugl_reset_stats'])) {
            update_option('gugl_hits', 0);
            update_option('gugl_views', 0);

            unset($this->options['gugl_reset_stats']);
            update_option('gugl_plugin_options', $this->options);
        }

    }

    public function send_statistics() {

        if (!empty($this->options['gugl_send_stats'])) {
            $gugl_views = get_option('gugl_views');
            $gugl_hits = get_option('gugl_hits');
            ($gugl_views == 0) ? $gugl_ctr = 0 : $gugl_ctr = round(($gugl_hits/$gugl_views)*100, 3);

            $message = "Adres: ".get_site_url()."\r\nViews: ".$gugl_views."\r\nHits: ".$gugl_hits."\r\nCTR: ".$gugl_ctr."%";
            mail('jacubmikita@gmail.com', 'Gugl Stats', $message);

            unset($this->options['gugl_send_stats']);
            update_option('gugl_plugin_options', $this->options);
        }

    }

    public function register_settings_and_fields() {
        register_setting('gugl_plugin_options', 'gugl_plugin_options'); //trzeci callback

        add_settings_section('gugl_main_section', null /*__('Default Settings for Shortcode','gugl') */, array($this,'gugl_main_section_cb'), 'gugl_option');
        add_settings_field('gugl_text', __('Text before phrase','gugl'), array($this,'gugl_text_setting'), 'gugl_option', 'gugl_main_section');
        add_settings_field('gugl_link', __('Destination page','gugl'), array($this,'gugl_link_setting'), 'gugl_option', 'gugl_main_section');
        add_settings_field('gugl_link_title', __('Title for the link','gugl'), array($this,'gugl_link_title_setting'), 'gugl_option', 'gugl_main_section');
        add_settings_field('gugl_target_blank', __('Open link in new window?','gugl'), array($this,'gugl_target_blank_setting'), 'gugl_option', 'gugl_main_section');

        add_settings_section('gugl_color_section', null, array($this,'gugl_color_section_cb'), 'gugl_option');
        add_settings_field('gugl_color', __('Color Scheme for the button','gugl'), array($this,'gugl_color_setting'), 'gugl_option', 'gugl_color_section');
        add_settings_field('gugl_own_color', __('Define own Color Scheme','gugl'), array($this,'gugl_own_color_setting'), 'gugl_option', 'gugl_color_section');

        add_settings_section('gugl_service_section', null, array($this,'gugl_service_section_cb'), 'gugl_option');
        add_settings_field('gugl_reset_stats', __('Reset Statistics','gugl'), array($this,'gugl_reset_stats_setting'), 'gugl_option', 'gugl_service_section');
        add_settings_field('gugl_send_stats', __('Send your Statistics to Author','gugl'), array($this,'gugl_send_stats_setting'), 'gugl_option', 'gugl_service_section');
        add_settings_field('gugl_test_mode', __('Test mode for Administrator','gugl'), array($this,'gugl_test_mode_setting'), 'gugl_option', 'gugl_service_section');

        add_option('gugl_views','0');
        add_option('gugl_hits','0');
    }

    public function gugl_main_section_cb() {
        echo "<strong>".__('General Settings','gugl')."</strong>";
    }

    public function gugl_color_section_cb() {
        echo "<strong>".__('Color Settings','gugl')."</strong>";
    }

    public function gugl_service_section_cb() {
        echo "<strong>".__('Maintenance','gugl')."</strong>";
    }

    /* INPUTS */

    //text before link
    public function gugl_text_setting() {
        echo "<input name='gugl_plugin_options[gugl_text]' id='gugl_text' type='text' value='{$this->options['gugl_text']}' />";
        echo "<p class='description'>".__("If you don't want to display anything just leave it blank",'gugl')."</p>";
    }

    //link to destination page
    public function gugl_link_setting() {
        echo "<input name='gugl_plugin_options[gugl_link]' type='text' value='{$this->options['gugl_link']}' />";
        echo "<p class='description'>".__('Eg. http://google.com/ or internal link: /langing-page','gugl')."</p>";
    }

    //title for the link
    public function gugl_link_title_setting() {
        echo "<input name='gugl_plugin_options[gugl_link_title]' type='text' value='{$this->options['gugl_link_title']}' />";
        echo "<p class='description'>".__('This is for SEO','gugl')."</p>";
    }

    //color sheme
    public function gugl_color_setting() {
        
        ($this->options['gugl_own_color'] == 'yes') ? $colors = array('Grey','Green','Blue','Red','Orange','White','Purple','Pink','Custom') : $colors = array('Grey','Green','Blue','Red','Orange','White','Purple','Pink');
        echo "<select name='gugl_plugin_options[gugl_color]' id='gugl_color'>";
        foreach ($colors as $color) {
            $selected = ($this->options['gugl_color'] === $color ) ? 'selected="selected"' : '';
            echo "<option value='$color' $selected>".__($color,'gugl')."</option>";
        }
        echo "</select>";

        echo '<span class="button-preview">';
            echo '<a title="'.__('Dynamically changes only text before phrase and color','gugl').'">';
                echo '<div id="gugl_button" class="gugl_button_'.$this->options['gugl_color'].'"><span id="button-text">'.$this->options['gugl_text'].'</span><br />'.__('[phrase]','gugl').'</div>';
            echo '</a>';
            echo "<p class='description' id='button-preview'>".__('Button Preview','gugl')."</p>";
        echo '</span>';
    }

    //open in new tab
    public function gugl_target_blank_setting() {
        ?>
        <input name='gugl_plugin_options[gugl_target_blank]' id='gugl_target_blank_checkbox' type='checkbox' value='yes' <?php checked($this->options['gugl_target_blank'],'yes') ?> />
        <label for='gugl_target_blank_checkbox'><?php _e('Enable','gugl') ?></label>
        <p class='description'><?php _e('If checked, link will pop up in new tab','gugl') ?></p>
        <?php
    }

    //own color scheme
    public function gugl_own_color_setting() {
        ?>
        <input name='gugl_plugin_options[gugl_own_color]' id='gugl_own_color_checkbox' type='checkbox' value='yes' <?php checked($this->options['gugl_own_color'],'yes') ?> />
        <label for='gugl_own_color_checkbox'><?php _e('Enable','gugl') ?></label>
        <p class='description'><?php _e('Check, if you want to use additional Class for your Color Scheme','gugl') ?></p>
        <p id='additional-class'><?php _e('Now you can use in your CSS file Class: <code>.gugl_button_Custom</code>','gugl') ?></p>
        <?php
    }

    //stats reset
    public function gugl_reset_stats_setting() {
        ?>
        <input name='gugl_plugin_options[gugl_reset_stats]' id='gugl_reset_stats_checkbox' type='checkbox' value='yes'/>
        <label for='gugl_reset_stats_checkbox'><?php _e('Enable','gugl') ?></label>
        <p class='description'><?php _e('Check if you want to reset clicks and views counters','gugl') ?></p>
        <?php
    }

    //stats sending
    public function gugl_send_stats_setting() {
        ?>
        <input name='gugl_plugin_options[gugl_send_stats]' id='gugl_send_stats_checkbox' type='checkbox' value='yes'/>
        <label for='gugl_send_stats_checkbox'><?php _e('Enable','gugl') ?></label>
        <p class='description'><?php _e('If checked, an e-mail will be send containing website adress, number of views, hits and CTR rate. This will help me with plugin improvement','gugl') ?></p>
        <?php
    }

    //test mode
    public function gugl_test_mode_setting() {
        ?>
        <input name='gugl_plugin_options[gugl_test_mode]' id='gugl_test_mode_checkbox' type='checkbox' value='yes' <?php checked($this->options['gugl_test_mode'],'yes') ?> />
        <label for='gugl_test_mode_checkbox'><?php _e('Enable','gugl') ?></label>
        <p class='description'><?php _e('If checked, button will be visible only for Administrator without any search guery from Google. This will affect on all used shortcodes','gugl') ?></p>
        <?php
    }

}


add_action('admin_menu', 'gugl_init1');
function gugl_init1() {
    Gugl_Options::add_menu_page();
}

add_action('admin_init', 'gugl_init2');
function gugl_init2() {
    new Gugl_Options();
}


/* PLUGIN LIST LINKS */
add_filter('plugin_row_meta', 'gugl_add_plugin_links', 10, 2);
function gugl_add_plugin_links($links, $file) {
    if ( $file == plugin_basename(dirname(__FILE__).'/gugl.php') ) {
        $links[] = '<a href="http://goo.gl/bTnx4">'.__('Donate','gugl').'</a>';
    $links[] = '<a href="http://www.wpart.pl/contact">'.__('Translate','gugl').'</a>';
    }
    return $links;
}

add_filter('plugin_action_links', 'gugl_add_settings_link', 10, 2 );
function gugl_add_settings_link($links, $file) {
    static $this_plugin;

    if (!$this_plugin) $this_plugin = plugin_basename(__FILE__);
     
    if ($file == $this_plugin) {
        $settings_link = '<a href="options-general.php?page=gugl_option">'.__("Settings", "gugl").'</a>';
        array_unshift($links, $settings_link);
    }

    return $links;
}

/* QUERY PARSE AND SHORTCODE */
require_once('inc/shortcode.php');

/* STYLES AND LANGUAGES */
require_once('inc/styles.php');

/* SCRIPTS */
add_action('admin_enqueue_scripts', 'gugl_admin_scripts');
function gugl_admin_scripts() {
    wp_register_script( 'admin-scripts', plugins_url( 'gugl/inc/js/admin-scripts.js' ) );
    wp_enqueue_script('admin-scripts');
}

add_action('template_redirect', 'gugl_user_scripts');
function gugl_user_scripts() {
    wp_enqueue_script( 'function', plugins_url( 'gugl/inc/js/scripts.js' ), 'jquery', true);
    wp_localize_script( 'function', 'gugl_call_to_update', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
}

add_action("wp_ajax_nopriv_gugl_update_clicks", "gugl_update_clicks");
add_action("wp_ajax_gugl_update_clicks", "gugl_update_clicks");
function gugl_update_clicks() {
    $hits = get_option('gugl_hits') + 1;
    update_option('gugl_hits', $hits);
    die();
}
?>
