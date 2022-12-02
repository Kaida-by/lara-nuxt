<template>
  <div class="container header-container">
    <div class="header_flex">
      <div class="logo">
        <nuxt-link to="/">JWT-Auth</nuxt-link>
      </div>
      <div class="header-sdk" v-if="$auth.loggedIn">
        <div class="notifications">
          <div class="notification" v-for="(notification, key) in this.notifications">
            <span>{{ notification }}</span>
            <span @click="removeNotification(key)">✘</span>
          </div>
        </div>
        <div>
          {{ $auth.user.name }}
        </div>
        <div class="auth-div">
          <nuxt-link to="/personal-cabinet">personal-cabinet</nuxt-link>
        </div>
        <div @click.prevent="logOut">logout</div>
      </div>
      <div class="header-sdk" v-else>
        <div class="auth-div">
          <nuxt-link to="/auth/login">Login</nuxt-link>
        </div>
        <div class="auth-div">
          <nuxt-link to="/auth/register">Register</nuxt-link>
        </div>
      </div>
    </div>
    <div class="bot_header_flex">
      <div>
        <div>
          <nuxt-link to="/articles">Articles</nuxt-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import echo from "~/plugins/echo";
import Echo from "~/plugins/echo";

export default {
  name: "TopNav",
  data () {
    return {
      notifications: [],
    }
  },
  methods: {
    logOut() {
      this.$auth.logout();
    },
    async getNotifications() {
      if (this.$auth.loggedIn) {
        await this.$axios.get('/get-notifications')
          .then((res) => {
            this.notifications = res.data.notifications
          })
          .catch(err => console.log(err))
      }
    },
    removeNotification( id ) {
      this.notifications.splice( id, 1 );
      try {
        this.$axios.delete('/remove-notification/' + id).then(response => {
          this.getNotifications();
        });
      } catch(e) {
        return;
      }
    }
  },
  // created() {
  //   this.$echo.channel(`articles`).listen('ApproveEvent', () => console.log(123))
  // },
  mounted() {
    // console.log(Echo)
    // window.Echo.channel(`articles`).listen('ApproveEvent', () => console.log(123))
    window.Echo.private(`user.1`).listen('ApproveEvent', () => console.log(123))
    console.log(window.Echo)
    this.getNotifications()
    // this.$echo.private(`articles.1`)
    //   .listen('ApproveEvent', ({article}) => {
    //     console.log(article)
    //     // this.notifications.push(notifications)
    //   })
  }
}
</script>

<style scoped>
  .header-sdk {
    display: flex;
    flex-direction: column;
  }
  .header-container {
    display: flex;
    flex-direction: column;
    width: 100%;
    margin: 0 auto;
  }
  .header_flex {
    display: flex;
    justify-content: space-between;
  }
</style>
