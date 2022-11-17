import Echo from 'laravel-echo';

export default (app) => {
  window.Pusher = require('pusher-js');

  window.Echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.PUSHER_APP_KEY,
    cluster: process.env.PUSHER_APP_CLUSTER,

    forceTLS: true,
    encrypted: false,
    wsHost: window.location.hostname,
    wsPort: 6001,
    wssPort: 6001,
    disableStats: true,
    enabledTransports: ['ws', 'wss']
  });
}
