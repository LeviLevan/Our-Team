<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit;

class Widget_Our_Team_Slider extends Widget_Base {

    public function get_name() {
        return 'our-team-slider';
    }

    public function get_title() {
        return __( 'Our Team with Slider', 'wp_lfit' );
    }

    public function get_icon() { 
        return 'eicon-person';
    }

    protected function _register_controls() {

        // Controls for Our Team
        $this->start_controls_section(
            'section_our_team',
            [
                'label' => __( 'Our Team', 'wp_lfit' ),
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'team_member_image',
            [
                'label' => __( 'Choose Image', 'wp_lfit' ),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $repeater->add_control(
            'team_member_name',
            [
                'label' => __( 'Member Name', 'wp_lfit' ),
                'type' => Controls_Manager::TEXT,
                'default' => __( 'John Doe', 'wp_lfit' ),
            ]
        );

        $repeater->add_control(
            'team_member_position',
            [
                'label' => __( 'Member Position', 'wp_lfit' ),
                'type' => Controls_Manager::TEXT,
                'default' => __( 'CEO', 'wp_lfit' ),
            ]
        );

        $this->add_control(
            'team_members',
            [
                'label'   => esc_html__( 'Team Members', 'wp_lfit' ),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
            ]
        );

        $this->add_responsive_control(
            'blocks_per_row',
            [
                'label' => __( 'Blocks Per Row (Desktop)', 'wp_lfit' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '1' => __( '1 Block', 'wp_lfit' ),
                    '2' => __( '2 Blocks', 'wp_lfit' ),
                    '3' => __( '3 Blocks', 'wp_lfit' ),
                    '4' => __( '4 Blocks', 'wp_lfit' ),
                ],
                'default' => '3',
            ]
        );

        $this->add_responsive_control(
            'blocks_per_row_tablet',
            [
                'label' => __( 'Blocks Per Row (Tablet)', 'wp_lfit' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '1' => __( '1 Block', 'wp_lfit' ),
                    '2' => __( '2 Blocks', 'wp_lfit' ),
                    '3' => __( '3 Blocks', 'wp_lfit' ),
                ],
                'default' => '2',
            ]
        );

        $this->add_responsive_control(
            'blocks_per_row_mobile',
            [
                'label' => __( 'Blocks Per Row (Mobile)', 'wp_lfit' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '1' => __( '1 Block', 'wp_lfit' ),
                    '2' => __( '2 Blocks', 'wp_lfit' ),
                ],
                'default' => '2',
            ]
        );

        $this->end_controls_section();

        // Controls for Swiper Slider
        $this->start_controls_section(
            'section_swiper_slider',
            [
                'label' => __( 'Swiper Slider', 'wp_lfit' ),
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'slide_image',
            [
                'label' => __( 'Slide Image', 'wp_lfit' ),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $repeater->add_control(
            'slide_text',
            [
                'label' => __( 'Slide Text', 'wp_lfit' ),
                'type' => Controls_Manager::TEXT,
                'default' => __( 'Your Text Here', 'wp_lfit' ),
            ]
        );

        $this->add_control(
            'enable_slider',
            [
                'label' => __( 'Enable Slider', 'wp_lfit' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
            ]
        );

        $this->add_control(
            'slides_per_view',
            [
                'label' => __( 'Slides Per View', 'wp_lfit' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '1' => __( '1 Slide', 'wp_lfit' ),
                    '2' => __( '2 Slides', 'wp_lfit' ),
                    '3' => __( '3 Slides', 'wp_lfit' ),
                    '4' => __( '4 Slides', 'wp_lfit' ),
                ],
                'default' => '1',
                'condition' => [
                    'enable_slider' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'slide_items',
            [
                'label'   => esc_html__( 'Slides', 'wp_lfit' ),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'condition' => [
                    'enable_slider' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

    }

   protected function render( $instance = [] ) {
    $settings = $this->get_settings();

    if ( 'yes' === $settings['enable_slider'] ) {
        // Render Swiper Slider if enabled
        $this->render_swiper_slider( $settings );
    } else {
        // Render Our Team in a grid
        $this->render_our_team( $settings );
    }
}

    protected function get_blocks_count_per_row( $desktop, $tablet, $mobile ) {
    $is_tablet = wp_is_mobile() && !wp_is_mobile('Android') && !wp_is_mobile('iPhone') && !wp_is_mobile('Windows Phone');
    $is_mobile = wp_is_mobile();

    if ( $is_tablet ) {
        return intval( $tablet );
    } elseif ( $is_mobile ) {
        return intval( $mobile );
    } else {
        return intval( $desktop );
    }
}

    protected function render_our_team( $settings ) {

        $member_template = '<div class="team-member"><img src="%1$s" alt="%2$s"><h3>%2$s</h3><p>%3$s</p></div>';
        $team_template = '<div class="team-carousel" style="grid-template-columns: repeat(%1$d, 1fr);">%2$s</div>';

        $members = '';

        if ( ! empty( $settings['team_members'] ) ) {

            foreach ( $settings['team_members'] as $index => $member ) {
                $members .= sprintf( $member_template, esc_url( $member['team_member_image']['url'] ), esc_html( $member['team_member_name'] ), esc_html( $member['team_member_position'] ) );
            }

            $blocks_per_row = $this->get_settings('blocks_per_row');
            $blocks_per_row_tablet = $this->get_settings('blocks_per_row_tablet');
            $blocks_per_row_mobile = $this->get_settings('blocks_per_row_mobile');

            $blocks = $this->get_blocks_count_per_row( $blocks_per_row, $blocks_per_row_tablet, $blocks_per_row_mobile );

            printf( $team_template, $blocks, $members );
        }
    }

    protected function render_swiper_slider( $settings ) {
        $is_mobile = wp_is_mobile();
        $slides = '';

        if ( ! empty( $settings['slide_items'] ) ) {
            foreach ( $settings['slide_items'] as $slide ) {
                $slides .= '<div class="swiper-slide">';
                $slides .= '<figure class="swiper-slide-inner">';
                $slides .= '<img decoding="async" class="swiper-slide-image" src="' . esc_url( $slide['slide_image']['url'] ) . '" alt="' . esc_attr( $slide['slide_text'] ) . '">';
                $slides .= '</figure>';
                $slides .= '</div>';
            }

            // Swiper Container
            echo '<div class="swiper-container mySwiper" autoplay="true" data-slides-per-view="' . esc_attr( $settings['slides_per_view'] ) . '" data-space-between="10" data-loop="true">';

            // Swiper Wrapper
            echo '<div class="swiper-wrapper">';

            // Display the slides
            echo $slides;

            // Close Swiper Wrapper
            echo '</div>';
            // Close Swiper Container
            echo '</div>';
        }
    }



    protected function content_template() {}

    public function render_plain_content( $instance = [] ) {}

}

Plugin::instance()->widgets_manager->register_widget_type( new Widget_Our_Team_Slider );