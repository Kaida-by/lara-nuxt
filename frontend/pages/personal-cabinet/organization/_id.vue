<template>
  <div class="container">
    <div class="organization">
      <form @submit.prevent="update">

        <label>Title: </label>
        <input v-model="form.title" type="text" name="title" :class="{ 'is-invalid': errors.text }" placeholder="title">
        <div class="invalid-feedback" v-if="errors.title">{{ errors.title[0] }}</div>

        <label>Description: </label>
        <input v-model="form.description" type="textarea" name="description" :class="{ 'is-invalid': errors.text }" placeholder="description">

        <label>Address: </label>
        <input v-model="form.address" type="text" name="address" :class="{ 'is-invalid': errors.text }" placeholder="address">

        <label>Phone: </label>
        <input type="tel" v-mask="'+375 (##) ### ## ##'" v-model="form.phone.number">
        <input type="submit" value="Update">

<!--        <input type="file" id="files" ref="files" accept="image/*" @change="handleImages($event)">-->

<!--        <div class="images">-->
<!--          <span>Images:</span>-->
<!--          <div class="preview">-->
<!--            <draggable v-model="form.files" :animation="300" @start="drag=true" @end="drag=false">-->
<!--              <div class="img" v-for="(image, key) in form.files">-->
<!--                <img class="image_i" :src="image.src" alt="">-->
<!--                <span class="remove-file" v-on:click="removeFile( key )">✘</span>-->
<!--              </div>-->
<!--            </draggable>-->
<!--          </div>-->
<!--        </div>-->



        <input type="file" id="files" ref="files" accept="image/*" @change="handleImages($event)">

        <div class="images">
          <span>Images:</span>
          <div class="preview">
            <draggable v-model="form.images" :animation="300" @start="drag=true" @end="drag=false">
              <div class="img" v-for="(image, key) in form.images">
                <img class="image_i" :src="image.src" alt="">
                <span class="remove-file" v-on:click="removeFile( key )">✘</span>
              </div>
            </draggable>
          </div>
        </div>


      </form>
    </div>
    <div v-if="error" class="err_r">
      {{ error }}
    </div>
  </div>
</template>

<script>

import _ from 'lodash'

export default {
  data() {
    return {
      form: {
        title: '',
        description: '',
        address: '',
        phone: '',
        images: [],
      },
      newFile: {},
      error: this.$route.query.error,
    }
  },
  methods: {
    async fetchData() {
      await this.$axios.get('/organization/edit/' + this.$route.params.id)
        .then((res) => {
          const organization = res.data
          console.log(res.data)
          for (let key in this.form) {
            this.form[key] = organization[key]
          }
        })
        .catch(err => console.log(err))
    },
    async update() {


      try {
        let form = new FormData();
        _.each(this.form, (value, key) => {
          if (key === 'images') {
            for (let i = 0; i < this.form.images.length; i++) {
              let file = this.form.images[i].file;
              if (file) {
                form.append(`images[${i}][file]`, file)
              } else {
                form.append(`images[${i}][file]`, JSON.stringify(this.form.images[i]))
              }
            }
          } else {
            if (key === 'phone') {
              form.append(`phone[id]`, this.form.phone.id)
              form.append(`phone[number]`, this.form.phone.number)
            } else {
              form.append(key, value)
            }
          }
        });

        await this.$axios.post('/organization/' + this.$route.params.id, form, {})
      } catch(e) {
        console.log(e)
        return;
      }






      // try {
      //   await this.$axios.post('/organization/' + this.$route.params.id, this.form, {})
      // } catch(e) {
      //   return;
      // }
    },
    handleImages(e) {
      const files = e.target.files || e.dataTransfer.files
      for(let i = 0; i < files.length; i++) {
        let reader = new FileReader()
        reader.onload = (e) => {
          this.newFile = { name: files[i].name, file: files[i], src: e.target.result };
          if (this.form.images.length === 0) {
            this.form.images.push(this.newFile)
          } else {
            this.form.images.shift()
            this.form.images.push(this.newFile)
          }
        }
        reader.readAsDataURL(files[i])
      }
    },
    removeFile( key ) {
      this.form.images.splice( key, 1 );
    },
  },
  mounted () {
    this.fetchData()
  }
}
</script>

<style scoped>
  .preview {
    display: flex;
  }
  .preview > div {
    display: flex;
  }
  .img {
    width: 200px;
    height: 200px;
    overflow: hidden;
    display: flex;
    justify-content: center;
    align-items: center;
    position: relative;
  }
  .image_i {
    position: relative;
    height: 100%;
    width: 100%;
    object-fit: cover;
  }
  .img span {
    position: absolute;
    top: 5px;
    right: 5px;
    width: 15px;
    height: 15px;
    border-radius: 50%;
    background-color: white;
    font-family: sans-serif;
    padding: 0 0 0 2px;
    font-size: 12px;
    cursor: pointer;
  }
</style>
