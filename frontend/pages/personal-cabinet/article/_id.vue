<template>
  <div class="container">
    <div class="article">
      <form @submit.prevent="update">

        <label>Title: </label>
        <input v-model="form.title" type="text" name="title" :class="{ 'is-invalid': errors.text }" placeholder="title">
        <div class="invalid-feedback" v-if="errors.title">{{ errors.title[0] }}</div>

        <label>Description: </label>
<!--        <input v-model="form.description" type="text" name="description" :class="{ 'is-invalid': errors.description }" placeholder="description">-->
<!--        <div class="invalid-feedback" v-if="errors.description">{{ errors.description[0] }}</div>-->

        <vue-editor v-model="form.description"></vue-editor>

<!--        <input type="file" id="files" ref="files" accept="image/*" @change="handleImages($event)" multiple>-->

<!--        <div class="images">-->
<!--          <span>Images:</span>-->
<!--          <div class="preview">-->
<!--            <draggable v-model="form.images" :animation="300" @start="drag=true" @end="drag=false">-->
<!--              <div class="img" v-for="(image, key) in form.images">-->
<!--                <img class="image_i" :src="image.src" alt="">-->
<!--                <span class="remove-file" v-on:click="removeFile( key )">✘</span>-->
<!--              </div>-->
<!--            </draggable>-->
<!--          </div>-->
<!--        </div>-->

        <input type="submit" value="Update">
      </form>
    </div>
    <div v-if="error" class="err_r">
      {{ error }}
    </div>
  </div>
</template>

<script>
import _ from "lodash";
import VueEditorComponent from '~/components/VueEditor'

export default {
  // name: "article",
  components: {
    VueEditorComponent
  },
  data() {
    return {
      form: {
        title: '',
        description: '',
        images: [],
      },
      newFile: {},
      file: '',
      error: this.$route.query.error,
    }
  },
  methods: {
    async fetchData() {
      await this.$axios.get('/article/edit/' + this.$route.params.id)
        .then((res) => {
          const article = res.data.data[0]

          for (let key in this.form) {
              this.form[key] = article[key]
          }
        })
        .catch(err => console.log(err))
    },
    async update() {
      try {
        let form = new FormData();
        _.each(this.form, (value, key) => {
          if (key === 'images') {
            for (var i = 0; i < this.form.images.length; i++) {
              let file = this.form.images[i].file;
              if (file) {
                form.append('images[' + i + ']', file)
              } else {
                form.append('images[' + i + ']', JSON.stringify(this.form.images[i]))
              }
            }
          } else {
            form.append(key, value)
          }
        });

        // _.each(this.form, (value, key) => {
        //   form.append(key, value)
        // });

        // for (let value of form.values()) {
        //   console.log(value);
        // }
        await this.$axios.post('/article/' + this.$route.params.id, form, {})
      } catch(e) {
        return;
      }
    },
    handleImages(e) {
      const files = e.target.files || e.dataTransfer.files
      for(let i = 0; i < files.length; i++) {
        let reader = new FileReader()
        reader.onload = (e) => {
          this.newFile = { name: files[i].name, file: files[i], src: e.target.result };
          this.form.images.push(this.newFile)
        }
        reader.readAsDataURL(files[i])
      }
    },
    removeFile( key ) {
      this.form.images.splice( key, 1 );
    }
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
