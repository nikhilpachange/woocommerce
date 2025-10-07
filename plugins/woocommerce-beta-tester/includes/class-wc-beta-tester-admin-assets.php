<?php
defined('ABSPATH') || exit;

class WC_Beta_Tester_Admin_Assets {

	public function __construct() {
		add_action('admin_enqueue_scripts', [$this, 'admin_scripts']);
	}

	public function admin_scripts() {
		$screen_id = get_current_screen()->id ?? '';
		$suffix    = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';
		$base_url  = WC_Beta_Tester::instance()->plugin_url() . '/assets';
		$ver       = WC_BETA_TESTER_VERSION;
		$wc_ver    = WC_VERSION;

		wp_register_style('wc-beta-tester-admin', "$base_url/css/admin.css", ['woocommerce_admin_styles'], $ver);
		wp_register_script('wc-beta-tester-version-info', "$base_url/js/version-information$suffix.js", ['wc-backbone-modal'], $ver, false);
		wp_register_script('wc-beta-tester-version-picker', "$base_url/js/version-picker$suffix.js", ['wc-backbone-modal'], $ver, false);

		wp_localize_script('wc-beta-tester-version-info', 'wc_beta_tester_version_info_params', [
			'version' => $wc_ver,
			'description' => sprintf(__('Release of version %s', 'woocommerce-beta-tester'), $wc_ver)
		]);

		wp_localize_script('wc-beta-tester-version-picker', 'wc_beta_tester_version_picker_params', [
			'i18n_pick_version' => __('Please pick a WooCommerce version.', 'woocommerce-beta-tester')
		]);

		if (in_array($screen_id, ['plugins_page_wc-beta-tester', 'plugins_page_wc-beta-tester-version-picker'], true)) {
			wp_enqueue_style('wc-beta-tester-admin');
			wp_enqueue_script('wc-beta-tester-version-info');
			wp_enqueue_script('wc-beta-tester-version-picker');
		}
	}
}

return new WC_Beta_Tester_Admin_Assets();
