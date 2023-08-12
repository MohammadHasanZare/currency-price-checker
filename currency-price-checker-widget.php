<?php
class Currency_Price_Checker_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'currency_price_checker_widget';
    }

    public function get_title() {
        return __( 'Currency Price Checker', 'text-domain' );
    }

    public function get_icon() {
        return 'eicon-post-list';
    }

    public function get_categories() {
        return [ 'general' ];
    }

    protected function _register_controls() {
        $this->start_controls_section(
            'section_content',
            [
                'label' => __( 'Content', 'text-domain' ),
            ]
        );

        $this->add_control(
            'show_settings',
            [
                'label' => __( 'Show Settings', 'text-domain' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __( 'Show', 'text-domain' ),
                'label_off' => __( 'Hide', 'text-domain' ),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_bitcoin',
            [
                'label' => __( 'Show Bitcoin Price', 'text-domain' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __( 'Show', 'text-domain' ),
                'label_off' => __( 'Hide', 'text-domain' ),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_ethereum',
            [
                'label' => __( 'Show Ethereum Price', 'text-domain' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __( 'Show', 'text-domain' ),
                'label_off' => __( 'Hide', 'text-domain' ),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_bitcoincash',
            [
                'label' => __( 'Show Bitcoin Cash Price', 'text-domain' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __( 'Show', 'text-domain' ),
                'label_off' => __( 'Hide', 'text-domain' ),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_litecoin',
            [
                'label' => __( 'Show Litecoin Price', 'text-domain' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __( 'Show', 'text-domain' ),
                'label_off' => __( 'Hide', 'text-domain' ),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style',
            [
                'label' => __( 'Style', 'text-domain' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
    
        $this->add_control(
            'font_color',
            [
                'label' => __( 'Font Color', 'text-domain' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#000000', // Default font color
                'selectors' => [
                    '{{WRAPPER}} .currency-price-checker-widget' => 'color: {{VALUE}};',
                ],
            ]
        );
    
        $this->add_control(
            'font_size',
            [
                'label' => __( 'Font Size', 'text-domain' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', '%' ],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .currency-price-checker-widget' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
    
        $this->end_controls_section();
    }
  
     
    protected function render() {
        $settings = $this->get_settings_for_display();
        $font_color = $settings['font_color'];
        $font_size = $settings['font_size'];
        $show_settings = $settings['show_settings'];
        $show_bitcoin = $settings['show_bitcoin'];
        $show_ethereum = $settings['show_ethereum'];
        $show_bitcoincash = $settings['show_bitcoincash'];
        $show_litecoin = $settings['show_litecoin'];

        echo '<div class="currency-price-checker-widget">';
        if ($show_settings) {
            echo do_shortcode('[currency_prices]');
        }
        if ($show_bitcoin) {
            echo do_shortcode('[currency_prices currency="Bitcoin"]');
        }
        if ($show_ethereum) {
            echo do_shortcode('[currency_prices currency="Ethereum"]');
        }
        if ($show_bitcoincash) {
            echo do_shortcode('[currency_prices currency="Bitcoin Cash"]');
        }
        if ($show_litecoin) {
            echo do_shortcode('[currency_prices currency="LiteCoin"]');
        }
        echo '</div>';
    }

}
