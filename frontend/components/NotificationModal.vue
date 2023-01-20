<template>
  <transition name="modal-fade">
    <div class="modal-overlay" @click="$emit('close-modal')">
      <div class="modal" @click.stop>
        <div class="notification222" v-for="(notification) in notifications">
          <span @click="$emit('mark-as-read', notification.id)" v-if="notification.read_at" class="is_read">
            {{ notification.data.message }}
          </span>
          <span @click="$emit('mark-as-read', notification.id)" v-if="notification.read_at == null" class="is_not_read">
            {{ notification.data.message }}
          </span>
          <span @click="$emit('remove-notification', notification.id)">✘</span>
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
}
.modal {
  text-align: center;
  background-color: white;
  height: 500px;
  width: 500px;
  margin-top: 10%;
  padding: 60px 0;
  border-radius: 20px;
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
.is_read {
  color: gray;
}
</style>
