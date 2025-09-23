<?php

defined( 'ABSPATH' ) || exit;

use Automattic\WooCommerce\Admin\Notes\Note;

// Register a test REST route for adding custom notes
register_woocommerce_admin_test_helper_rest_route(
	'/admin-notes/create-note/v1',
	'wc_admin_create_note'
);

/**
 * REST callback to create a WooCommerce admin note.
 *
 * @param WP_REST_Request $request REST request object containing parameters.
 * @return bool
 */
function wc_admin_create_note( $request ) {
	$note      = new Note();
	$mock_data = wc_admin_mock_note_data();

	$type   = $request->get_param( 'type' );
	$layout = $request->get_param( 'layout' );

	$note->set_name( $request->get_param( 'name' ) );
	$note->set_title( $request->get_param( 'title' ) );
	$note->set_content( $mock_data['content'] ?? '' );
	$note->set_image( $mock_data[ $type ][ $layout ] ?? '' );
	$note->set_layout( $layout );
	$note->set_type( $type );

	wc_admin_maybe_add_action( $note );

	if ( 'email' === $type ) {
		wc_admin_add_email_params( $note );
	}

	$note->save();
	return true;
}

/**
 * Append extra parameters for email-type notes.
 *
 * @param Note $note The note instance.
 * @return void
 */
function wc_admin_add_email_params( $note ) {
	$email_data = array(
		'role' => 'administrator',
	);

	$note->set_content_data( (object) $email_data );
}

/**
 * Conditionally attach an action button to a note.
 *
 * @param Note $note The note to update.
 * @return void
 */
function wc_admin_maybe_add_action( $note ) {
	if ( 'info' === $note->get_type() ) {
		return;
	}

	$action_id = sprintf( 'wc-test-action-%s', $note->get_name() );
	$note->add_action( $action_id, __( 'Test Action', 'woocommerce' ), wc_admin_url() );
}

/**
 * Provide fake/mock note data for testing purposes.
 *
 * @return array
 */
function wc_admin_mock_note_data() {
	$plugin_assets = site_url( '/wp-content/plugins/woocommerce-admin-test-helper/' );

	return array(
		'content' => __( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin in lacus at nulla auctor aliquet.', 'woocommerce' ),
		'info'    => array(
			'thumbnail' => $plugin_assets . 'images/admin-notes/thumbnail.jpg',
			'plain'     => '',
		),
		'email'   => array(
			'plain' => $plugin_assets . 'images/admin-notes/woocommerce-logo-vector.png',
		),
		'update'  => array(
			'plain' => '',
		),
	);
}
