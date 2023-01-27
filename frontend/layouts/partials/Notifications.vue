<template>
  <div class="container">
    <div class="notifications" @click="showModal = true" v-if="notifications">
      notifications - {{notifications.length}}
    </div>
    <NotificationModal
      v-show="showModal"
      @close-modal="showModal = false"
      :notifications=notifications
      @remove-notification="removeNotification"
    />
  </div>
</template>

<script>

import NotificationModal from "~/components/NotificationModal";

export default {
  name: "Notifications",
  components: {NotificationModal},
  data () {
    return {
      notifications: [],
      showModal: false
    }
  },
  computed: {
    channel() {
      return window.Echo.private(`user.` + this.$auth.user.id);
    }
  },
  methods: {
    async getNotifications() {
      if (this.$auth.loggedIn) {
        await this.$axios.get('/get-notifications')
          .then((res) => {
            this.notifications = res.data.notifications
          })
          .catch(err => console.log(err))
      }
    },
    connectToPrivateChannel() {
      this.channel.listen('Notifications', () => {
        this.getNotifications();
      })
    },
    removeNotification(uuid) {
      this.notifications = this.notifications.filter( (value) => value.id !== uuid);
      try {
        this.$axios.delete('/remove-notification/' + uuid).then(response => {
          this.getNotifications();
        });
      } catch(e) {
        return;
      }
    },
  },
  mounted() {
    this.getNotifications();
    if (this.$auth.loggedIn) {
      this.connectToPrivateChannel();
    }
  }
}
</script>

<style scoped>

</style>
