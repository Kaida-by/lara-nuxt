<template>
  <div class="container">
    <p>Create</p>
    <form @submit.prevent="create">
      <label>Name: </label>
      <input v-model="form.title" type="text" name="name" :class="{ 'is-invalid': errors.title }" placeholder="title">
      <label>Description: </label>
      <input v-model="form.description" type="text" name="description" :class="{ 'is-invalid': errors.description }" placeholder="description">

      <input type="file" id="files" ref="files" accept="image/*" @change="handleImages($event)" multiple>

      <div class="preview">
        <draggable v-model="files" :animation="300" @start="drag=true" @end="drag=false">
          <div class="img" v-for="(image, key) in files">
            <img class="image_i" :src="image.src" alt="">
            <span class="remove-file" v-on:click="removeFile( key )">✘</span>
          </div>
        </draggable>
      </div>
      <div class="invalid-feedback" v-if="errors.text">
        {{ errors.text[0] }}
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
        author_id: this.$auth.user.id,
      },
      file: '',
      showPreview: false,
      imagePreview: [],
      files: [],
      img: '',
      error: this.$route.query.error,
      newFiles: [],
      newFile: {
        name: undefined,
        file: undefined,
        src: undefined
      }
    }
  },
  methods: {
    async create() {
      if (this.files.length > 0) {
        let form = new FormData();
        for( var i = 0; i < this.files.length; i++ ){
          let file = this.files[i].file;
          form.append('files[' + i + ']', file)
          _.each(this.form, (value, key) => {
            form.append(key, value)});
        }
        await this.$axios.post('/article/store', form, {})
      } else {
        await this.$axios.post('/article/store', this.form, {})
      }
    },
    handleImages(e) {
      const files = e.target.files || e.dataTransfer.files

      for(let i = 0; i < files.length; i++) {
        let reader = new FileReader()
        reader.onload = (e) => {
          this.newFile = { name: files[i].name, file: files[i], src: e.target.result };
          this.files.push(this.newFile)
        }

        reader.readAsDataURL(files[i])
      }
    },
    removeFile( key ) {
      this.files.splice( key, 1 );
    }
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
