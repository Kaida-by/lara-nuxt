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

      <vue-editor
        id="editor"
        useCustomImageHandler
        @image-added="handleImageAdded"
        v-model="form.description"
      >
      </vue-editor>

      <VueCtkDateTimePicker v-model="form.date" />

      <div class="invalid-feedback" v-if="errors.description">
        {{ errors.description[0] }}
      </div>
      <input type="submit" value="Create">
    </form>

    <div v-if="error" class="err_r">
      {{ error }}
    </div>
  </div>
</template>

<script>

import VueEditorComponent from '~/components/VueEditor'

export default {
  name: "create",
  middleware: 'auth',
  components: {
    VueEditorComponent,
  },
  data() {
    return {
      form: {
        title: '',
        description: '',
        date: '',
        price: '',
        author_id: this.$auth.user.id,
      },
      file: '',
      showPreview: false,
      imagePreview: [],
      img: '',
      error: this.$route.query.error,
      newFiles: [],
      newFile: {
        name: undefined,
        file: undefined,
        src: undefined
      },
      cte_id: '',
    }
  },
  methods: {
    async create() {
      try {
        await this.$axios.post('/poster/' + this.cte_id, this.form, {})
      } catch (e) {
        console.log(this.errors)
      }
    },
    async handleImageAdded(file, Editor, cursorLocation, resetUploader) {
      let formDataI = new FormData();

      formDataI.append('file', file);
      formDataI.append('entity_id', this.cte_id);
      await this.$axios.post('/upload-image', formDataI)
        .then(result => {
          const url = result.data[0].src; // Get url from response
          Editor.insertEmbed(cursorLocation, 'image', process.env.API_URL_PUBLIC + url);
          resetUploader();
        })
        .catch(err => {
          console.log(err);
        });
    },
    async createTemporaryPoster() {
      await this.$axios.post('/poster-cte', {}, {})
        .then(result => {
          this.cte_id = result.data.data
        })
        .catch(err => {
          console.log(err);
        });
    }
  },
  mounted() {
    this.createTemporaryPoster()
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
