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
  mounted() {
    this.getNotifications()
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
