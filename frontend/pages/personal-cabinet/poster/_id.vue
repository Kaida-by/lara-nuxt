<template>
  <div class="container">
    <div class="poster">
      <form @submit.prevent="update">

        <label>Title: </label>
        <input v-model="form.title" type="text" name="title" :class="{ 'is-invalid': errors.text }" placeholder="title">
        <div class="invalid-feedback" v-if="errors.title">{{ errors.title[0] }}</div>

        <label>Description: </label>
        <vue-editor
          id="editor"
          useCustomImageHandler
          @image-added="handleImageAdded"
          v-model="form.description"
        >
        </vue-editor>

        <div>When?</div>
        <VueCtkDateTimePicker v-model="form.date" />

        <div>
          <div>How much is it?</div>
          <input type="number" step="0.01" v-model="form.price">
        </div>

        <div v-if="!isShow">
          <div>Вы выбрали категории:</div>
          <span v-for="category in form.categoryIds">{{ category.title }}</span>
        </div>
        <div @click="isShow = !isShow">Изменить?</div>

        <div v-if="isShow">
          <div>Можно выбрать несколько категорий, путём зажатия клавиши "ctrl" и клика левой кнопки мышки</div>
          <select multiple v-model="form.categoryIds">
            <option v-for="category in categories" :value="category.title">{{ category.title }}</option>
          </select>
          <div>Вы выбрали категории:</div>
          <span v-for="category in form.categoryIds" v-if="!category.title">{{ category }}</span>
        </div>

        <input type="submit" value="Update">
      </form>
    </div>
    <div v-if="error" class="err_r">
      {{ error }}
    </div>
  </div>
</template>

<script>

import VueEditorComponent from '~/components/VueEditor'

export default {
  components: {
    VueEditorComponent
  },
  data() {
    return {
      form: {
        title: '',
        description: '',
        date: '',
        price: '',
        categoryIds: [],
      },
      error: this.$route.query.error,
      categories: [],
      isShow: false,
    }
  },
  methods: {
    async fetchData() {
      await this.$axios.get('/poster/edit/' + this.$route.params.id)
        .then((res) => {
          const poster = res.data
          for (let key in this.form) {
            this.form[key] = poster[key]
          }
        })
        .catch(err => console.log(err))
    },
    async update() {
      try {
        await this.$axios.post('/poster/' + this.$route.params.id, this.form, {})
      } catch(e) {
        return;
      }
    },
    async handleImageAdded(file, Editor, cursorLocation, resetUploader) {
      let formDataI = new FormData();

      formDataI.append('file', file);
      formDataI.append('entity_id', this.$route.params.id);
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
    async getCategories() {
      await this.$axios.get('/get-article-categories?categoryId=' + 2)
        .then(result => {
          this.categories = result.data.categories
        })
    },
  },
  mounted () {
    this.fetchData()
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
    padding: 0 0 0 2px;
    font-size: 12px;
    cursor: pointer;
  }
</style>
