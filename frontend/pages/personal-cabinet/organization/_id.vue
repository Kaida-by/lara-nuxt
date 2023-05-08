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

        <input type="file" id="files" ref="files" accept="image/*" @change="handleImages($event)">

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



        <vue-upload-multiple-image
            @upload-success="uploadImageSuccess"
            @before-remove="beforeRemove"
            @edit-image="editImage"
            :data-images="form.files"
        ></vue-upload-multiple-image>



      </form>
    </div>
    <div v-if="error" class="err_r">
      {{ error }}
    </div>
  </div>
</template>

<script>

export default {
  data() {
    return {
      form: {
        title: '',
        description: '',
        address: '',
        phone: '',
        files: [],
      },
      error: this.$route.query.error,
    }
  },
  methods: {
    async fetchData() {
      await this.$axios.get('/organization/edit/' + this.$route.params.id)
        .then((res) => {
          const organization = res.data
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
          if (key === 'files') {
            for (var i = 0; i < this.form.files.length; i++) {
              let file = this.form.files[i].file;
              if (file) {
                form.append(`files[${i}][file]`, file)
              } else {
                form.append(`files[${i}][file]`, JSON.stringify(this.form.files[i]))
              }
            }
          } else {
            if (key === 'phone') {
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
          this.form.files.push(this.newFile)
        }
        reader.readAsDataURL(files[i])
      }
    },
    removeFile( key ) {
      this.form.files.splice( key, 1 );
    },

    uploadImageSuccess(formData, index, fileList) {
      console.log('data', formData, index, fileList)
      // Upload image api
      // axios.post('http://your-url-upload', formData).then(response => {
      //   console.log(response)
      // })
    },
    beforeRemove (index, done, fileList) {
      console.log('index', index, fileList)
      var r = confirm("remove image")
      if (r === true) {
        done()
      } else {
      }
    },
    editImage (formData, index, fileList) {
      console.log('edit data', formData, index, fileList)
    }
  },
  mounted () {
    this.fetchData()
  }
}
</script>

<style scoped>

</style>
