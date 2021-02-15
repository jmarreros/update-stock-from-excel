<?php

namespace dcms\update\includes;

/**
 * Class to enqueue all files javascript and css
 */
class Enqueue{

    // hooks for enqueue in the administrator
    public function __construct(){
        add_action('admin_enqueue_scripts', [$this, 'register_scripts']);
    }

    // Register javascript and css
    public function register_scripts(){
        global $pagenow;

        // Register javascript
        wp_register_script(
            'dcms-update-stock-script',
            DCMS_UPDATE_URL.'assets/script.js',
            ['jquery'],
            DCMS_UPDATE_VERSION,
            true
        );

        // Register css
        wp_register_style(
            'dcms-update-stock-css',
            DCMS_UPDATE_URL.'assets/style.css',
            [],
            DCMS_UPDATE_VERSION
        );

        // Load only in tools page
        if ( $pagenow === 'tools.php'){
            // wp_enqueue_script('dcms-update-stock-script');
            // wp_localize_script('dcms-update-stock-script','dcms_vars',['ajaxurl'=>admin_url('admin-ajax.php')]);

            wp_enqueue_style('dcms-update-stock-css');
        }
    }

}