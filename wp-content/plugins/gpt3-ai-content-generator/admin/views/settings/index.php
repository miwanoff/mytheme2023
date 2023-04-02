<?php
if ( ! defined( 'ABSPATH' ) ) exit;
$flag = true;
$errors = '';


if ( isset( $_POST['wpaicg_submit'] ) ) {
    global  $wpdb ;
    $table = $wpdb->prefix . 'wpaicg';
    $result = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$table} WHERE name = %s", 'wpaicg_settings' ) );
    $newData = [];
    extract( \WPAICG\wpaicg_util_core()->sanitize_text_or_array_field($_POST['wpaicg_settings']) );

    if ( !is_numeric( $temperature ) || floatval( $temperature ) < 0 || floatval( $temperature ) > 1 ) {
        $errors = 'Please enter a valid temperature value between 0 and 1.';
        $flag = false;
    }


    if ( !is_numeric( $max_tokens ) || floatval( $max_tokens ) < 64 || floatval( $max_tokens ) > 8000 ) {
        $errors = 'Please enter a valid max token value between 64 and 8000.';
        $flag = false;
    }


    if ( !is_numeric( $top_p ) || floatval( $top_p ) < 0 || floatval( $top_p ) > 1 ) {
        $errors = 'Please enter a valid top p value between 0 and 1.';
        $flag = false;
    }


    if ( !is_numeric( $best_of ) || floatval( $best_of ) < 1 || floatval( $best_of ) > 20 ) {
        $errors = 'Please enter a valid best of value between 1 and 20.';
        $flag = false;
    }


    if ( !is_numeric( $frequency_penalty ) || floatval( $frequency_penalty ) < 0 || floatval( $frequency_penalty ) > 2 ) {
        $errors = 'Please enter a valid frequency penalty value between 0 and 2.';
        $flag = false;
    }


    if ( !is_numeric( $presence_penalty ) || floatval( $presence_penalty ) < 0 || floatval( $presence_penalty ) > 2 ) {
        $errors = 'Please enter a valid presence penalty value between 0 and 2.';
        $flag = false;
    }

    if ( empty($api_key) ) {
        $errors = 'Please enter a valid API key.';
        $flag = false;
    }

    $data = [
        'name'                   => 'wpaicg_settings',
        'temperature'            => $temperature,
        'max_tokens'             => $max_tokens,
        'top_p'                  => $top_p,
        'best_of'                => $best_of,
        'frequency_penalty'      => $frequency_penalty,
        'presence_penalty'       => $presence_penalty,
        'img_size'               => $img_size,
        'api_key'                => $api_key,
        'wpai_language'          => $wpai_language,
        'wpai_modify_headings'   => ( isset( $wpai_modify_headings ) ? 1 : 0 ),
        'wpai_add_img'           => ( isset( $wpai_add_img ) ? 1 : 0 ),
        'wpai_add_tagline'       => ( isset( $wpai_add_tagline ) ? 1 : 0 ),
        'wpai_add_intro'         => ( isset( $wpai_add_intro ) ? 1 : 0 ),
        'wpai_add_faq'           => ( isset( $wpai_add_faq ) ? 1 : 0 ),
        'wpai_add_conclusion'    => ( isset( $wpai_add_conclusion ) ? 1 : 0 ),
        'wpai_add_keywords_bold' => ( isset( $wpai_add_keywords_bold ) ? 1 : 0 ),
        'wpai_number_of_heading' => $wpai_number_of_heading,
        'wpai_heading_tag'       => $wpai_heading_tag,
        'wpai_writing_style'     => $wpai_writing_style,
        'wpai_writing_tone'      => $wpai_writing_tone,
        'wpai_cta_pos'           => $wpai_cta_pos,
        'added_date'             => date( 'Y-m-d H:i:s' ),
        'modified_date'          => date( 'Y-m-d H:i:s' ),
    ];

    if ( $flag == true ) {

        if ( !empty($result->name) ) {
            $wpdb->update(
                $table,
                $data,
                [
                    'name' => 'wpaicg_settings',
                ],
                [
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s'
                ],
                [ '%s' ]
            );
        } else {
            $wpdb->insert( $table, $data, [
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s'
            ] );
        }
        $wpaicg_keys = array(
            '_wpaicg_image_featured',
            '_wpaicg_seo_meta_desc',
            'rank_math_description',
            '_wpaicg_image_style',
            'wpaicg_woo_generate_title',
            'wpaicg_woo_generate_description',
            'wpaicg_woo_generate_short',
            'wpaicg_woo_generate_tags',
            'wpaicg_ai_model',
            '_wpaicg_seo_meta_tag',
            'wpaicg_toc',
            'wpaicg_toc_title',
            'wpaicg_intro_title_tag',
            'wpaicg_conclusion_title_tag',
            'wpaicg_sd_api_key',
            'wpaicg_sd_api_version',
            '_yoast_wpseo_metadesc',
            '_aioseo_description',
            'wpaicg_search_language',
            'wpaicg_search_placeholder',
            'wpaicg_search_no_result',
            'wpaicg_search_font_size',
            'wpaicg_search_font_color',
            'wpaicg_search_bg_color',
            'wpaicg_search_width',
            'wpaicg_search_height',
            'wpaicg_search_border_color',
            'wpaicg_search_loading_color',
            'wpaicg_search_result_font_size',
            'wpaicg_search_result_font_color',
            'wpaicg_search_result_bg_color',
            'wpaicg_image_source',
            'wpaicg_featured_image_source',
            'wpaicg_pexels_orientation',
            'wpaicg_pexels_size',
            'wpaicg_sleep_time',
            'wpaicg_pexels_api',
            'wpaicg_woo_meta_description',
            'wpaicg_woo_custom_prompt',
            'wpaicg_woo_custom_prompt_title',
            'wpaicg_woo_custom_prompt_short',
            'wpaicg_woo_custom_prompt_description',
            'wpaicg_woo_custom_prompt_keywords',
            'wpaicg_woo_custom_prompt_meta',
            'wpaicg_custom_image_settings',
            'wpaicg_editor_change_action',
            'wpaicg_editor_button_menus',
        );
        foreach($wpaicg_keys as $wpaicg_key){
            if(isset($_POST[$wpaicg_key]) && !empty($_POST[$wpaicg_key])){
                if($wpaicg_key == 'wpaicg_editor_button_menus'){
                    $wpaicg_editor_button_menus = array();
                    $wpaicg_list_menus = \WPAICG\wpaicg_util_core()->sanitize_text_or_array_field($_POST[$wpaicg_key]);
                    if($wpaicg_list_menus && is_array($wpaicg_list_menus) && count($wpaicg_list_menus)){
                        foreach($wpaicg_list_menus as $wpaicg_list_menu){
                            if(isset($wpaicg_list_menu['name']) && isset($wpaicg_list_menu['prompt']) && $wpaicg_list_menu['name'] != '' && $wpaicg_list_menu['prompt'] != ''){
                                $wpaicg_editor_button_menus[] = $wpaicg_list_menu;
                            }
                        }
                    }
                    update_option($wpaicg_key, $wpaicg_editor_button_menus);
                }
                else{
                    update_option($wpaicg_key, \WPAICG\wpaicg_util_core()->sanitize_text_or_array_field($_POST[$wpaicg_key]));
                }
            }
            else{
                delete_option($wpaicg_key);
                if($wpaicg_key == 'wpaicg_pexels_api'){
                    delete_option( 'wpaicg_pexels_size' );
                    delete_option( 'wpaicg_pexels_orientation' );
                    if ( isset( $_POST['wpaicg_featured_image_source'] ) && !empty( $_POST['wpaicg_featured_image_source'] ) && $_POST['wpaicg_featured_image_source'] == 'pexels') {
                        delete_option('wpaicg_featured_image_source');
                    }
                    if ( isset( $_POST['wpaicg_image_source'] ) && !empty( $_POST['wpaicg_image_source'] ) && $_POST['wpaicg_image_source'] == 'pexels') {
                        delete_option('wpaicg_image_source');
                    }
                }
            }
        }
        $message = "Records successfully updated!";
    }

}

global  $wpdb ;
$table = $wpdb->prefix . 'wpaicg';
$existingValue = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$table} WHERE name = %s", 'wpaicg_settings' ), ARRAY_A );
function help_img()
{
}

?>
<script>
    jQuery( function() {
        jQuery( "#wpcgai_tabs" ).tabs();
    } );
</script>

<h3>Settings</h3>
<?php

if ( !empty($message) && $flag == true ) {
    echo  "<h4 id='setting_message' style='color: green;'>" . esc_html( $message ) . "</h4>" ;
} else {
    echo  "<h4 id='setting_message' style='color: red;'>" . esc_html( $errors ) . "</h4>" ;
}
$wpaicg_custom_models = get_option('wpaicg_custom_models',array());
$wpaicg_custom_models = array_merge(array('text-davinci-003','text-curie-001','text-babbage-001','text-ada-001'),$wpaicg_custom_models);
?>
<script type="text/javascript">
    jQuery(document).ready(function($)
    {
        if(jQuery("#wpcgai_setting_message").text() != '')
        {
            jQuery("#wpcgai_setting_message").delay(4000).slideUp(300);
        }
    });
</script>
<div class="wpcgai_container">
    <div id="wpcgai_tabs">
        <form action="<?php
        echo  esc_url( $_SERVER['REQUEST_URI'] ) ;
        ?>" method="post">
            <ul>
                <li><a href="#tabs-1">AI Engine</a></li>
                <li><a href="#tabs-2">Content</a></li>
                <li><a href="#tabs-6">SEO</a></li>
                <?php
                if ( class_exists( 'woocommerce' ) ){
                    ?>
                    <li><a href="#tabs-7">WooCommerce</a></li>
                    <?php
                }
                ?>
                <li><a href="#tabs-5">Image</a></li>
                <li><a href="#tabs-8">SearchGPT</a></li>
                <li><a href="#tabs-9">AI Assistant</a></li>
                <li><a href="#tabs-3">How to Use?</a></li>
            </ul>
            <?php
            include WPAICG_PLUGIN_DIR.'admin/views/settings/ai.php';
            include WPAICG_PLUGIN_DIR.'admin/views/settings/content.php';
            include WPAICG_PLUGIN_DIR.'admin/views/settings/seo.php';
            include WPAICG_PLUGIN_DIR.'admin/views/settings/woocommerce.php';
            include WPAICG_PLUGIN_DIR.'admin/views/settings/image.php';
            include WPAICG_PLUGIN_DIR.'admin/views/settings/how-to.php';
            include WPAICG_PLUGIN_DIR.'admin/views/settings/search.php';
            include WPAICG_PLUGIN_DIR.'admin/views/settings/editor.php';
            ?>
            <div style="padding: 1em 1.4em;"><input type="submit" value="Save" name="wpaicg_submit" class="button button-primary button-large"></div>
        </form>
    </div>
</div>
