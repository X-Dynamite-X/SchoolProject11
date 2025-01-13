import Echo from 'laravel-echo';

import Pusher from 'pusher-js';
window.Pusher = Pusher;
// Pusher.logToConsole = true;

window.Echo = new Echo({
    broadcaster: import.meta.env.VITE_PUSHER_BROADCAST_DRIVER,
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: (import.meta.env.VITE_PUSHER_SCHEME ?? 'https') === 'https',
    enabledTransports: ['ws', 'wss'],
    authEndpoint: "/broadcasting/auth",


});
