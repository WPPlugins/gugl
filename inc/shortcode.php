<?php

defined( 'ABSPATH' ) OR exit;

function count_impression() {
    $impressions = get_option('gugl_views') + 1;
    update_option('gugl_views', $impressions);
}

add_shortcode('gugl','gugl_shortcode' );
function gugl_shortcode( $atts ) {

    $options = get_option("gugl_plugin_options");

    extract( shortcode_atts( array(
        'before' => $options['gugl_text'],
        'link' => $options['gugl_link'],
        'title' => $options['gugl_link_title'],
        'newtab' => $options['gugl_target_blank'],
        'color' => $options['gugl_color'],
    ), $atts ) );

    (current_user_can('manage_options') && $options['gugl_test_mode'] == 'yes') ? $keyword=__('[phrase]','gugl') : $keyword = search_query();

    if (!empty($keyword)) {
        empty($before) ? null : $before.='<br />';
        empty($newtab) ? null : $newtab ='target="_blank"';
        $code = '<a href="'.$link.'" title="'.$title.'" '.$newtab.' onClick="gugl_hits_counter()" ><div class="gugl_button_'.$color.' gugl">'.$before.$keyword.'</div></a>';

        count_impression();
    }

    return $code;
}


function search_query() {

	$referrer='';

    $referrer = $_SERVER['HTTP_REFERER'];
    if (!empty($referrer))
    {
        $parts_url = parse_url($referrer);
 
        $query = isset($parts_url['query']) ? $parts_url['query'] : '';
        if($query)
        {
            parse_str($query, $parts_query);
            $ref_keywords = isset($parts_query['q']) ? $parts_query['q'] : (isset($parts_query['query']) ? $parts_query['query'] : '' );
        }
    }

    return $ref_keywords;
}

?>