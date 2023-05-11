<template>
  <div class="container">
    <p>Create</p>
    <form @submit.prevent="create">

      <label>Name: </label>
      <input v-model="form.title" type="text" name="name" :class="{ 'is-invalid': errors.title }" placeholder="title">
      <div class="invalid-feedback" v-if="errors.title">
        {{ errors.title[0] }}
      </div>

      <label>Description: </label>
      <input v-model="form.description" type="textarea" name="description" :class="{ 'is-invalid': errors.text }" placeholder="description">
      <div class="invalid-feedback" v-if="errors.description">
        {{ errors.description[0] }}
      </div>

      <label>Address: </label>
      <input v-model="form.address" type="text" name="address" :class="{ 'is-invalid': errors.text }" placeholder="address">
      <div class="invalid-feedback" v-if="errors.address">
        {{ errors.address[0] }}
      </div>

      <label>Phone: </label>
      <input type="tel" v-mask="'+375 (##) ### ## ##'" v-model="form.phone.number">

      <label>Image:</label>
<!--      <input type="file" id="files" ref="files" accept="image/*" @change="handleImages($event)">-->
<!--      <div class="preview">-->
<!--        <draggable v-model="form.files" :animation="300" @start="drag=true" @end="drag=false">-->
<!--          <div class="img" v-for="(image, key) in form.files">-->
<!--            <img class="image_i" :src="image.src" alt="">-->
<!--            <span class="remove-file" v-on:click="removeFile( key )">✘</span>-->
<!--          </div>-->
<!--        </draggable>-->
<!--      </div>-->
      <input type="file" id="files" ref="files" accept="image/*" @change="handleImages($event)" multiple>

      <div class="preview">
        <draggable v-model="form.images" :animation="300" @start="drag=true" @end="drag=false">
          <div class="img" v-for="(image, key) in form.images">
            <img class="image_i" :src="image.src" alt="">
            <span class="remove-file" v-on:click="removeFile( key )">✘</span>
          </div>
        </draggable>
      </div>

      <input type="submit" value="Create">
    </form>

    <div v-if="error" class="err_r">
      {{ error }}
    </div>
  </div>
</template>

<script>

import _ from 'lodash'

export default {
  name: "create",
  middleware: 'auth',
  data() {
    return {
      form: {
        title: '',
        description: '',
        address: '',
        phone: {
          number: ''
        },
        author_id: this.$auth.user.id,
        images: [],
      },
      newFile: {
        name: undefined,
        file: undefined,
        src: undefined
      },
      error: this.$route.query.error,
      cte_id: '',
    }
  },
  methods: {
    async create() {
      // for (let i = 0; i < this.form.files.length; i++) {
      //   this.form.files['files' + i ] = this.form.files[i].file
      // }
      // let formData = new FormData();
      // console.log(this.files)
      // formData.append('file', this.files[0].file);

      let form = new FormData();
      for ( let i = 0; i < this.form.images.length; i++ ) {
        let file = this.form.images[i].file;
        form.append(`images[${i}][file]`, file)
      }
      _.each(this.form, (value, key) => {
        if (key === 'phone') {
            form.append(`phone[number]`, this.form.phone.number)
        } else {
          form.append(key, value)
        }
      });

      try {
        await this.$axios.post('/organization/' + this.cte_id, form, {})
      } catch (err) {
        console.log(err)
      }
    },
    async createTemporaryOrganization() {
      await this.$axios.post('/organization-cte', {}, {})
        .then(result => {
          this.cte_id = result.data.data
        })
        .catch(err => {
          console.log(err);
        });
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
  mounted() {
    this.createTemporaryOrganization()
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
    padding: 3px 2px 4px 6px;
    font-size: 14px;
    cursor: pointer;
  }
</style>
