<?php
/**
 * ChurchPress Genesis Starter.
 *
 * This file adds the Customizer additions to the ChurchPress Genesis Starter Theme.
 *
 * @package ChurchPress Genesis Starter
 * @author  ChurchPress
 * @license GPL-3.0+
 * @link    https://ChurchPress.co
 */

class cp_initialise_customizer_settings {
	// Get our default values
	private $defaults;

	public function __construct() {

		//* Register our default controls
		add_action( 'customize_register', array( $this, 'genesis_sample_default_controls' ) );

	}

	public function genesis_sample_default_controls( $wp_customize ) {
		$wp_customize->add_setting(
			'genesis_sample_link_color',
			array(
				'default'           => genesis_sample_customizer_get_default_link_color(),
				'sanitize_callback' => 'sanitize_hex_color',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'genesis_sample_link_color',
				array(
					'description' => __( 'Change the color of post info links, hover color of linked titles, hover color of menu items, and more.', 'cp-genesis-starter' ),
					'label'       => __( 'Link Color', 'cp-genesis-starter' ),
					'section'     => 'colors',
					'settings'    => 'genesis_sample_link_color',
				)
			)
		);

		$wp_customize->add_setting(
			'genesis_sample_accent_color',
			array(
				'default'           => genesis_sample_customizer_get_default_accent_color(),
				'sanitize_callback' => 'sanitize_hex_color',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'genesis_sample_accent_color',
				array(
					'description' => __( 'Change the default hovers color for button.', 'cp-genesis-starter' ),
					'label'       => __( 'Accent Color', 'cp-genesis-starter' ),
					'section'     => 'colors',
					'settings'    => 'genesis_sample_accent_color',
				)
			)
		);

		$wp_customize->add_setting(
			'genesis_sample_logo_width',
			array(
				'default'           => 350,
				'sanitize_callback' => 'absint',
			)
		);

		// Add a control for the logo size.
		$wp_customize->add_control(
			'genesis_sample_logo_width',
			array(
				'label'       => __( 'Logo Width', 'cp-genesis-starter' ),
				'description' => __( 'The maximum width of the logo in pixels.', 'cp-genesis-starter' ),
				'priority'    => 9,
				'section'     => 'title_tagline',
				'settings'    => 'genesis_sample_logo_width',
				'type'        => 'number',
				'input_attrs' => array(
					'min' => 100,
				),
			)
		);
	}

}

// Initialize our Customizer settings
$cp_settings = new cp_initialise_customizer_settings();