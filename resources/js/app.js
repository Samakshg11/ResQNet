//

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allow your team to quickly build robust real-time web applications.
 */

import './echo';

// Lightweight debug hint when the frontend bundle initializes
if (typeof window !== 'undefined' && window.console && process.env.NODE_ENV !== 'production') {
	console.debug('ResQNet JS initialized');
}
