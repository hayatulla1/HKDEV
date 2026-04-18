<?php
/**
 * Mini Cart class.
 *
 * Registers Customizer controls for social links and contact info
 * that appear in the footer, and adds the WooCommerce cart widget fragment.
 *
 * @package HKDEV
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class HKDEV_Mini_Cart
 */
class HKDEV_Mini_Cart {

	/** @var HKDEV_Mini_Cart|null */
	private static $instance = null;

	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() {
		add_action( 'customize_register', array( $this, 'customizer_settings' ) );
	}

	public function customizer_settings( $wp_customize ) {

		// ── Social Links ──────────────────────────────────────────────────────
		$wp_customize->add_section(
			'hkdev_social',
			array(
				'title'    => esc_html__( 'Social Media Links', 'hkdev' ),
				'priority' => 50,
			)
		);

		$socials = array(
			'hkdev_social_facebook'  => esc_html__( 'Facebook URL', 'hkdev' ),
			'hkdev_social_twitter'   => esc_html__( 'Twitter / X URL', 'hkdev' ),
			'hkdev_social_instagram' => esc_html__( 'Instagram URL', 'hkdev' ),
			'hkdev_social_youtube'   => esc_html__( 'YouTube URL', 'hkdev' ),
			'hkdev_social_tiktok'    => esc_html__( 'TikTok URL', 'hkdev' ),
			'hkdev_social_linkedin'  => esc_html__( 'LinkedIn URL', 'hkdev' ),
		);

		foreach ( $socials as $setting_id => $label ) {
			$wp_customize->add_setting(
				$setting_id,
				array(
					'default'           => '',
					'sanitize_callback' => 'esc_url_raw',
				)
			);
			$wp_customize->add_control(
				$setting_id,
				array(
					'label'   => $label,
					'section' => 'hkdev_social',
					'type'    => 'url',
				)
			);
		}

		// ── Contact Info ──────────────────────────────────────────────────────
		$wp_customize->add_section(
			'hkdev_contact',
			array(
				'title'    => esc_html__( 'Contact Information', 'hkdev' ),
				'priority' => 55,
			)
		);

		$contacts = array(
			'hkdev_contact_address' => array(
				'label' => esc_html__( 'Address', 'hkdev' ),
				'type'  => 'textarea',
			),
			'hkdev_contact_phone' => array(
				'label' => esc_html__( 'Phone Number', 'hkdev' ),
				'type'  => 'text',
			),
			'hkdev_contact_email' => array(
				'label' => esc_html__( 'Email Address', 'hkdev' ),
				'type'  => 'email',
			),
		);

		foreach ( $contacts as $setting_id => $control ) {
			$wp_customize->add_setting(
				$setting_id,
				array(
					'default'           => '',
					'sanitize_callback' => 'sanitize_text_field',
				)
			);
			$wp_customize->add_control(
				$setting_id,
				array(
					'label'   => $control['label'],
					'section' => 'hkdev_contact',
					'type'    => $control['type'],
				)
			);
		}
	}
}
