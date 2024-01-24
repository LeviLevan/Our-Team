<?php
/**
 * Plugin Name: Our Team
 * Description: Custom Elementor widgets Our Team
 * Version: 1.0
 * Author: LL
 */

if (!defined('ABSPATH')) exit;

class Custom_Elementor_Extensions {

    private static $instance = null;

    public static function get_instance() {
        if (!self::$instance)
            self::$instance = new self;
        return self::$instance;
    }

    public function init() {
        add_action('elementor/widgets/widgets_registered', array($this, 'widgets_registered'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts_styles'));
    }

    private function widget_register($widgetPath) {
        $widget_file = plugin_dir_path(__FILE__) . $widgetPath;

        $template_file = locate_template($widget_file);
        if (!$template_file || !is_readable($template_file)) {
            $template_file = plugin_dir_path(__FILE__) . $widgetPath;
        }
        if ($template_file && is_readable($template_file)) {
            require_once $template_file;
        }
    }

    public function widgets_registered() {
        if (defined('ELEMENTOR_PATH') && class_exists('Elementor\Widget_Base')) {
            $this->widget_register('/elementor-ext/widgets/class-widget-our-team-slider.php');
        }
    }

    public function enqueue_scripts_styles() {
        wp_enqueue_style('widget-css', plugin_dir_url(__FILE__) . 'elementor-ext/widgets/css/widget.css');
        wp_enqueue_style('swiper', 'https://unpkg.com/swiper/swiper-bundle.min.css', array(), '6.5.5');
        wp_enqueue_script('swiper', 'https://unpkg.com/swiper/swiper-bundle.min.js', array('jquery'), '6.5.5', true);
    }
}

Custom_Elementor_Extensions::get_instance()->init();