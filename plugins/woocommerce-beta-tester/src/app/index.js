/**
 * Vendor imports
 */
import { createRoot } from '@wordpress/element';

/**
 * Local imports
 */
import { App } from './app';
import '../index.scss';

// Target container for mounting the React app
const rootEl = document.getElementById(
	'wc-admin-test-helper-root'
);

if ( rootEl ) {
	const root = createRoot( rootEl );
	root.render( <App /> );
}
