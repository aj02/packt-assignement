<template>
  <div class="container">
    <div class="large-12 medium-12 small-12 cell">
      <label>File
        <input type="file" id="file" ref="file" v-on:change="handleFileUpload()"/>
      </label>
      <br>
      <progress max="100" :value.prop="uploadPercentage"></progress>
      <br>
      <button v-on:click="submitFile()">Submit</button>
    </div>
  </div>
</template>

<script>
import Papa from 'papaparse';
import axios from 'axios';

export default {
    data(){
        return {
            file: '',
            uploadPercentage: 0
        }
    },

    methods: {
        handleFileUpload(){
            this.file = this.$refs.file.files[0];
        },
        submitFile(){
            /*
                Initialize the form data
            */
            let formData = new FormData();

            /*
                Add the form data we need to submit
            */
            formData.append('file', this.file);

            /*
                Make the request to the POST /single-file URL
            */
            axios.post( '/file-progress',
                formData,
                {
                headers: {
                    'Content-Type': 'multipart/form-data'
                },
                onUploadProgress: function( progressEvent ) {
                    this.uploadPercentage = parseInt( Math.round( ( progressEvent.loaded / progressEvent.total ) * 100 ) );
                }.bind(this)
                }
            ).then(function(){
                console.log('SUCCESS!!');
            })
            .catch(function(){
                console.log('FAILURE!!');
            });
        },

    },
}
</script>
