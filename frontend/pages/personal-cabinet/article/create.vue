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

      <div class="invalid-feedback" v-if="errors.description">
        {{ errors.description[0] }}
      </div>

      <div>
        <div>Можно выбрать несколько категорий, путём зажатия клавиши "ctrl" и клика левой кнопки мышки</div>
        <select multiple v-model="form.categoryIds">
          <option v-for="category in categories" :value="category.title">{{ category.title }}</option>
        </select>
        <div>Вы выбрали категории:</div>
          <span v-for="category in form.categoryIds">{{ category }}</span>
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
import VueEditorComponent from '~/components/VueEditor'

export default {
  name: "create",
  middleware: 'auth',
  components: {
    VueEditorComponent
  },
  data() {
    return {
      form: {
        title: '',
        description: '',
        author_id: this.$auth.user.id,
        categoryIds: [],
        // mainFile: [],
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
      categories: [],
    }
  },
  methods: {
    async create() {
      // this.form.file = this.$refs.files.files[0];
      // console.log(this.form.file)
      // if (this.form.mainFile.length > 0) {
      //   let form = new FormData();
      //   for (let i = 0; i < this.form.mainFile.length; i++) {
      //     let file = this.form.mainFile[i].file;
      //     form.append('files[' + i + ']', file)
      //   }
      //   _.each(this.form, (value, key) => {
      //     form.append(key, value)
      //   });
      // }
      // let form = new FormData();
      // if (this.form.mainFile.length > 0) {
      //   let file = this.form.mainFile[0].file;
      //   form.append('file', file)
      //   _.each(this.form, (value, key) => {
      //     form.append(key, value)
      //   });
      // }
      try {
        await this.$axios.post('/article/' + this.cte_id, this.form, {})
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
    // handleImages(e) {
    //   const files = e.target.files || e.dataTransfer.files
    //   let reader = new FileReader()
    //   reader.readAsDataURL(files[0])
    //   this.form.mainFile = [];
    //   for(let i = 0; i < files.length; i++) {
    //     let reader = new FileReader()
    //     reader.onload = (e) => {
    //       this.newFile = { name: files[i].name, file: files[i], src: e.target.result };
    //       this.form.mainFile.push(this.newFile)
    //     }
    //
    //     reader.readAsDataURL(files[i])
    //   }
    // },
    // removeFile( key ) {
    //   this.form.mainFile.splice( key, 1 );
    // },
    async createTemporaryArticle() {
      await this.$axios.post('/article-cte', {}, {})
        .then(result => {
          this.cte_id = result.data.data
        })
        .catch(err => {
          console.log(err);
        });
    },
    async getCategories() {
      await this.$axios.get('/get-article-categories?categoryId=' + 2)
        .then(result => {
          this.categories = result.data.categories
        })
    },
  },
  mounted() {
    this.createTemporaryArticle()
    this.getCategories()
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
