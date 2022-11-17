import Echo from 'laravel-echo';

export default (app) => {
  window.Pusher = require('pusher-js');
  window.Echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.pusherKey,
    cluster: process.env.pusherCluster,

    forceTLS: false,
    encrypted: false,
    wsHost: window.location.hostname,
    wsPort: 6001,
    wssPort: 6001,
    disableStats: true,
    enabledTransports: ['ws']
  });
}
