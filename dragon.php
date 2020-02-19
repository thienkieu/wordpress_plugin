<?php
use Elementor\Plugin as Plugin;

/**
 * Plugin Name:       Dragon Plugin
 * Plugin URI:        https://lamwebnhanhgiare.com/plugins/
 * Description:       Custom wordpress and add more widget for elementor
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Thien Kieu
 * Author URI:        https://author.lamwebnhanhgiare.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       dragon-plugin
 * Domain Path:       /languages
 */

function dragon_install() {

}

register_activation_hook( __FILE__, 'dragon_install');

function dragon_deactivation() {
}

register_deactivation_hook( __FILE__, 'dragon_deactivation');

function dragon_initPlugin()
{
    include_once(plugin_dir_path( __FILE__ ).'/plugins/elementor/image.php');
    Plugin::instance()->widgets_manager->register_widget_type( new Dragon\Plugins\Elementor\Responsive_Widget_Image() );   
}

add_action('init', 'dragon_initPlugin');