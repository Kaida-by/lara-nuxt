<template>
  <transition name="modal-fade">
    <div class="modal-overlay" @click="$emit('close-modal')">
      <div class="modal" @click.stop>
        <div class="notification" v-for="(notification) in notifications">
          <span>
            {{ notification.data.message }}
          </span>
          <span class="bnt-cl" @click="$emit('remove-notification', notification.id)">✘</span>
          <div>
            <span class="text-gray-300">{{ notification.created_at }}</span>
          </div>
        </div>
      </div>
      <div class="close" @click="$emit('close-modal')">
        <img class="close-img" src="~/assets/icons/check-icon.png" alt="" />
      </div>
    </div>
  </transition>
</template>

<script>
export default {
  name: "NotificationModal",
  props: [
    'notifications'
  ],
  emits: ['mark-as-read', 'remove-notification']
}
</script>

<style scoped>
.modal-overlay {
  position: fixed;
  top: 0;
  bottom: 0;
  left: 0;
  right: 0;
  display: flex;
  justify-content: center;
  background-color: #000000da;
  z-index: 9;
}
.modal {
  text-align: left;
  background-color: white;
  height: 500px;
  width: 500px;
  margin-top: 10%;
  padding: 60px 20px;
  border-radius: 20px;
  overflow-y: auto;
}
.close {
  margin: 10% 0 0 16px;
  cursor: pointer;
}
.close-img {
  width: 25px;
}
.check {
  width: 150px;
}
h6 {
  font-weight: 500;
  font-size: 28px;
  margin: 20px 0;
}
p {
  /* font-weight: 500; */
  font-size: 16px;
  margin: 20px 0;
}
button {
  background-color: #ac003e;
  width: 150px;
  height: 40px;
  color: white;
  font-size: 14px;
  border-radius: 16px;
  margin-top: 50px;
}
.modal-fade-enter,
.modal-fade-leave-to {
  opacity: 0;
}
.modal-fade-enter-active,
.modal-fade-leave-active {
  transition: opacity 0.5s ease;
}
.notification {
  position: relative;
}
.notification span {
  font-family: monospace;
}
.bnt-cl {
  position: absolute;
  top: 0;
  right: 0;
}
</style>
