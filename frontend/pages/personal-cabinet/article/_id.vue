<template>
  <div class="container">
    <div class="article">
      <form @submit.prevent="update">

        <label>Title: </label>
        <input
          v-model="article.title"
          type="text"
          name="title"
          :class="{ 'is-invalid': errors.text }"
          placeholder="title"
        >
        <div class="invalid-feedback" v-if="errors.title">
          {{ errors.title[0] }}
        </div>

        <label>Description: </label>
        <input
          v-model="article.description"
          type="text"
          name="description"
          :class="{ 'is-invalid': errors.description }"
          placeholder="description"
        >
        <div class="invalid-feedback" v-if="errors.description">
          {{ errors.description[0] }}
        </div>

        <input type="file" id="files" ref="files" accept="image/*" @change="handleImages($event)" multiple>

        <div class="images" @change="handleImages($event)">
          <span>Images:</span>
          <div class="preview">
            <draggable v-model="files" :animation="300" @start="drag=true" @end="drag=false">
              <div class="img" v-for="(image, key) in files">
                <img class="image_i" :src="image.src" alt="">
                <span class="remove-file" v-on:click="removeFile( key )">✘</span>
              </div>
            </draggable>
          </div>
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
export default {
  name: "_id",
  data() {
    return {
      article: [],
      files: [],
      error: this.$route.query.error,
    }
  },
  methods: {
    async fetchData() {
      await this.$axios.get('/article/edit/' + this.$route.params.id)
        .then((res) => {
          this.article = res.data.data[0]
          this.files = res.data.data[0].images
        })
        .catch(err => console.log(err))
    },
    async update() {
      this.article.images = this.files;

      try {
        await this.$axios.patch('/article/' + this.$route.params.id, this.article);
      } catch(e) {
        return;
      }

      // this.$router.push({name: 'admin'});
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
