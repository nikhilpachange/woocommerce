<?php
defined('ABSPATH') || exit;

const OPTION_NAME_PREFIX = 'wc_admin_helper_feature_values';

foreach ([
	['/features/(?P<feature_name>[a-z0-9_\-]+)/toggle', 'toggle_feature', 'POST'],
	['/features', 'get_features', 'GET'],
	['/features/reset', 'reset_features', 'POST']
] as [$route, $callback, $method]) {
	register_woocommerce_admin_test_helper_rest_route($route, $callback, ['methods' => $method]);
}

function toggle_feature($request) {
	$features = get_features();
	$values   = get_option(OPTION_NAME_PREFIX, []);
	$name     = $request->get_param('feature_name');

	if (!isset($features[$name])) return new WP_REST_Response($features, 204);

	isset($values[$name]) ? unset($values[$name]) : $values[$name] = !$features[$name];
	update_option(OPTION_NAME_PREFIX, $values);

	return new WP_REST_Response(get_features(), 200);
}

function reset_features() {
	delete_option(OPTION_NAME_PREFIX);
	return new WP_REST_Response(get_features(), 200);
}

function get_features() {
	return function_exists('wc_admin_get_feature_config')
		? apply_filters('woocommerce_admin_get_feature_config', wc_admin_get_feature_config())
		: [];
}
