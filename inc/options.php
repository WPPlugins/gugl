<?php defined( 'ABSPATH' ) OR exit; ?>

<div class="wrap">

    <?php screen_icon(); ?>
    <?php echo "<h2>".__('Gugl Settings','gugl')."</h2>"; ?>

    <div class="metabox-holder has-right-sidebar">
        <div id="post-body">
            <div id="post-body-content">

                <!-- Default Settings -->
                <div class="postbox">
                    <h3><span><?php _e('Settings','gugl') ?></span></h3>
                    <div class="inside">
                        <form method="post" action="options.php" enctype="multipart/form-data">

                            <?php 
                                settings_fields('gugl_plugin_options');
                                do_settings_sections('gugl_option');
                            ?>

                            <p class="submit">
                                <input name="submit" type="submit" class="button-primary" value="<?php _e('Save Changes','gugl'); ?>" />
                            </p>

                        </form>
                    </div>
                </div>


                <!-- Usage -->
                <div class="postbox">
                    <h3><span><?php _e('Usage','gugl') ?></span></h3>
                    <div class="inside">
                        <?php 
                            _e('Shortcode will use default values you specified in Default Settings Section. If you want override those settings just enter optional attributes in shortcode.<br /><code>[gugl before="text shown before query phrase" link="url to your destination page" title="title of this anchor" color="One of color scheme"]</code>', 'gugl');

                            echo '<p>';
                                _e('Example 1: If you want to ovveride just link url and title, shortcode will look like:<br /><code>[gugl link="http://new-adress.com/" title="New title"]</code>', 'gugl');
                            echo '</p>';

                            echo '<p>';
                                _e('Example 2: If you want to show button with your defaul settings:', 'gugl');
                            echo '<br /><code>[gugl]</code></p>';

                            echo "<br />";

                            $code = '<code>'.htmlspecialchars('<?php echo do_shortcode(\'[gugl]\'); ?>').'</code>';

                            printf(__('If you wan\'t to put button to your theme file, you can just execute shortcode in PHP tags like this:<br />%s. ', 'gugl' ), $code);

                            _e('Of course in that case you can also use attributes.', 'gugl');

                            echo '<p class="description">';
                                _e('See the <a href="http://codex.wordpress.org/Function_Reference/do_shortcode" title="Function do_shortcode">WordPress Function Reference</a> for more information.', 'gugl');
                            echo '</p>';
                        ?>
                    </div>
                </div>

            </div> <!-- #post-body-content -->
        </div> <!-- #post-body -->



        <div class="inner-sidebar">
 
            <!-- Dontation -->
            <div class="postbox">
                <h3><span><?php _e('Give me some sunshine','gugl') ?></span></h3>
                <div class="inside">
                    <?php
                        echo get_avatar( 'jacubmikita@gmail.com', $size = '35' );
                        printf(__('If you like this plugin, please <strong><a href="%s" target="_blank" title="Make me happy :)">give me reason</a></strong> to make it even better!', 'gugl' ), 'http://goo.gl/bTnx4');
                    ?>


                </div>
            </div>


            <!-- Statistics -->
            <div class="postbox">
                <h3><span><?php _e('Statistics','gugl') ?></span></h3>
                <div class="inside">
                    <?php
                    $vi = get_option('gugl_views');
                    $hi = get_option('gugl_hits');
                    ($vi == 0) ? $ctr = 0 : $ctr = ($hi/$vi)*100;

                    switch(true) {
                        case ($ctr == 0): _e("Don't give up! It's always hope!","gugl"); break;
                        case ((0 < $ctr) && ($ctr <= 0.05)): _e("You should improve something.","gugl"); break;
                        case ((0.05 < $ctr) && ($ctr <= 0.1)): _e("It could be better.","gugl"); break;
                        case ((0.1 < $ctr) && ($ctr <= 0.5)): _e("A good start","gugl"); break;
                        case ((0.5 < $ctr) && ($ctr <= 1)): _e("It's OK :)","gugl"); break;
                        case ((1 < $ctr) && ($ctr <= 3)): _e("Boom! This is CTR!","gugl"); break;
                        case ((3 < $ctr) && ($ctr <= 5)): _e("Whoa! This is really good!","gugl"); break;
                        case ((5 < $ctr) && ($ctr <= 10)): _e("Tell me what you do!","gugl"); break;
                        case (10 < $ctr): _e("Watch out! We have a Badass over here!","gugl"); break;
                    }

                    printf('<div id="ctr-rate">%.3f&#37; CTR</div>', $ctr);
                    echo "<div class='ctr-stats'>";
                        printf(__('Views: %d, Clicks: %d', 'gugl' ), $vi, $hi);
                    echo "</div>";

                    ?>
                </div>
            </div>


            <!-- Important informations -->
            <div class="postbox">
                <h3><span><?php _e('Important information','gugl') ?></span></h3>
                <div class="inside">
                    <?php 
                        _e('If Test mode is disabled a button will not be shown on page. Only if a visitor load page with shortcode from the Google <acronym title="Search Engine Results Page">SERP</acronym> <strong>without SSL</strong> button will be visible. If visitor search for anything while hi is logged to his Google Account he use SSL. In that case plugin will be working improperly. Google just doesn\'t pass query string because of "security reasons".', 'gugl'); 
                    ?>
                </div>
            </div>


            <!-- Contact -->
            <div class="postbox">
                <h3><span><?php _e('Contact','gugl') ?></span></h3>
                <div class="inside">
                    <?php 
                        $contact_adress = 'http://www.wpart.pl/contact/?utm_source=gugl&utm_medium=link&utm_campaign=plugin';    
                        printf(__('If you have any questions about this plugin or you want to <strong>translate it</strong> you can use this simple <a href="%s" title="Contact me">contact form</a> to get in touch with me.', 'gugl' ), $contact_adress);

                        if (WPLANG == 'pl_PL') {
                            echo '<a href="http://www.wpart.pl/?utm_source=gugl&utm_medium=baner&utm_campaign=plugin" title="Sztuka WordPress" style="width: 27px"><img class="wpart-banner" src="'.plugins_url('gugl/images/wpart.png').'" /></a>';
                        }
                    ?>
                </div>
            </div>
 
        </div> <!-- .inner-sidebar -->

    </div>
    
</div>