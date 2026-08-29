<?php
// WooCommerce Beta Tester Plugin

add_action('admin_menu', function() {
	add_management_page(
		'WooCommerce Admin Test Helper',
		'WCA Test Helper',
		'install_plugins',
		'woocommerce-admin-test-helper',
		fn() => print('<div id="woocommerce-admin-test-helper-app-root"></div>')
	);
});

add_action('wp_loaded', function() {
	require_once __DIR__ . '/vendor/autoload.php';
	require 'api/api.php';
});

add_filter('woocommerce_admin_get_feature_config', function($config) {
	$config['beta-tester-slotfill-examples'] = false;
	foreach (get_option('wc_admin_helper_feature_values', []) as $f => $v) {
		if (isset($config[$f])) $config[$f] = $v;
	}
	return $config;
});

function enqueue_beta_tester_app_script() {
	if (!defined('WC_ADMIN_APP')) return;
	$screen = get_current_screen();
	if (!$screen || $screen->id !== 'tools_page_woocommerce-admin-test-helper') return;

	$dir = dirname(__FILE__);
	$script_path = '/build/app.js';
	$asset_path  = "$dir/build/app.asset.php";
	$asset = file_exists($asset_path) ? require $asset_path : ['dependencies' => [], 'version' => filemtime($dir . $script_path)];
	$asset['dependencies'][] = WC_ADMIN_APP;

	wp_enqueue_script('woocommerce-admin-test-helper-app', plugins_url($script_path, __FILE__), $asset['dependencies'], $asset['version'], true);

	$css_ver = filemtime("$dir/build/app.css");
	wp_enqueue_style('woocommerce-admin-test-helper-app', plugins_url('/build/app.css', __FILE__), ['wp-components'], $css_ver);
}

add_action('admin_enqueue_scripts', 'enqueue_beta_tester_app_script');
