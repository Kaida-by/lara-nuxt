import Echo from 'laravel-echo';
import auth from "~/plugins/auth";

export default (app, inject) => {
  window.Pusher = require('pusher-js');
  // const echo = new Echo({
  window.Echo = new Echo({
    broadcaster: process.env.MIX_BROADCAST_DRIVER,
    key: process.env.MIX_PUSHER_APP_KEY,
    cluster: process.env.MIX_PUSHER_APP_CLUSTER,

    forceTLS: false,
    encrypted: false,
    wsHost: process.env.MIX_PUSHER_APP_HOST,
    wsPort: 6001,
    wssPort: 6001,
    disableStats: true,
    enabledTransports: ['ws', 'wss']
  });

  // echo.channel(`articles`).listen('ApproveEvent', () => console.log(123))

  // inject('echo', echo)
}
