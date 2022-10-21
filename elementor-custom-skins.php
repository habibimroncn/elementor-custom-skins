<?php
/**
 * Elementor CustomSkins WordPress Plugin
 *
 * @package CustomSkins
 *
 * Plugin Name: Elementor CustomSkins
 * Description: Custom Skins for Posts Widget
 * Plugin URI:  https://github.com/habibimroncn/elementor-custom-skins
 * Version:     1.0.0
 * Author:      Habib Nugroho
 * Author URI:  https://habibimroncn.github.io
 * Text Domain: elementor-customskins
 */
define( 'ELEMENTOR_CUSTOMSKINS_FILE', __FILE__ );
define( 'ELEMENTOR_CUSTOMSKINS_DIR', __DIR__ );

function register_new_widgets_skins( $widget ) {

    require_once( plugin_dir_path( ELEMENTOR_CUSTOMSKINS_FILE ) . '/widgets/skins/custom-skins.php' );

    if(class_exists('Hn_Custom_Skin')):
        $widget->add_skin( new \Hn_Custom_Skin($widget) );
    endif;
}
add_action( 'elementor/widget/posts/skins_init', 'register_new_widgets_skins' );