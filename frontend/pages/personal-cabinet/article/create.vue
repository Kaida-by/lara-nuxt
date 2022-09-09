<template>
  <div class="container">
    <p>Create</p>
    <form @submit.prevent="create">
      <label>Email: </label>
      <input v-model="form.title" type="text" name="name" :class="{ 'is-invalid': errors.title }" placeholder="title">
      <input v-model="form.description" type="text" name="description" :class="{ 'is-invalid': errors.description }" placeholder="description">

      <input type="file" id="files" ref="files" accept="image/*" @change="handleFilesUpload()" multiple>
      <div class="large-12 medium-12 small-12 cell">
        <div v-for="(file, key) in files" class="file-listing">{{ file.name }}
          <span class="remove-file" v-on:click="removeFile( key )">Remove</span>
        </div>
      </div>

      <div class="preview"></div>

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
      error: this.$route.query.error
    }
  },
  methods: {
    async create() {
      if (this.files.length > 0) {
        let form = new FormData();
        for( var i = 0; i < this.files.length; i++ ){
          let file = this.files[i];
          form.append('files[' + i + ']', file)
          _.each(this.form, (value, key) => {
            form.append(key, value)});
        }
        await this.$axios.post('/article/store', form, {})
      } else {
        await this.$axios.post('/article/store', this.form, {})
      }
    },
    handleFilesUpload() {
      let uploadedFiles = this.$refs.files.files;
      let div = document.querySelector('.preview');

      for( let i = 0; i < uploadedFiles.length; i++ ){

        let reader = new FileReader();
        reader.addEventListener("load", function () {
          this.showPreview = true;
          this.imagePreview.push(reader.result);
          this.img = document.createElement('img');
          this.img.setAttribute('src', this.imagePreview[i]);
          div.append(this.img);
        }.bind(this), false);

        if( uploadedFiles[i] ){
          if ( /\.(jpe?g|png|gif)$/i.test( uploadedFiles[i].name ) ) {
            reader.readAsDataURL( uploadedFiles[i] );

          }
        }
        this.imagePreview = [];
        this.files.push(uploadedFiles[i]);
      }
    },
    removeFile( key ) {
      let blockImg = document.querySelectorAll('.preview > img')
      blockImg[key].remove()
      this.files.splice( key, 1 );
    }
  }
}
</script>

<style scoped>
  img {
    width: 40px;
    height: 40px;
  }
</style>
