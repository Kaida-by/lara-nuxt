import Echo from 'laravel-echo';

export default (app, inject) => {
  window.Pusher = require('pusher-js');

  window.Echo = new Echo({
    broadcaster: process.env.MIX_BROADCAST_DRIVER,
    key: process.env.MIX_PUSHER_APP_KEY,
    cluster: process.env.MIX_PUSHER_APP_CLUSTER,

    forceTLS: false,
    encrypted: false,
    wsHost: process.env.MIX_PUSHER_APP_HOST,
    wsPort: 6001,
    disableStats: true,
    enabledTransports: ['ws', 'wss'],
    authorizer: (channel, options) => {
      return {
        authorize: (socketId, callback) => {
          app.$axios.$post('/broadcasting/auth', {
            socket_id: socketId,
            channel_name: channel.name
          })
            .then(response => {
              callback(false, response);

            })
            .catch(error => {
              callback(true, error);
            });
        }
      };
    }
  });

  // echo.private(`user.1`).listen('user.1', () => console.log(123))
}
