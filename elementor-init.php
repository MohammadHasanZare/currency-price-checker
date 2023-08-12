<?php
function currency_price_checker_elementor_init() {
    if (class_exists('\Elementor\Plugin')) {
        require_once('currency-price-checker-widget.php');
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Currency_Price_Checker_Widget());
    }
}
add_action('init', 'currency_price_checker_elementor_init');
