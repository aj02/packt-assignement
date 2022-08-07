<template>
    <div>
        <div class="row">
            <div class="col-lg-9 col-md-9 col-sm-12">
                <div class="form-inline mb-3 pt-3">
                    <label for="date">Date:</label>
                    <input type="date" name="date" id="date" min='2020-05-08' max="" value="" class="form-control my-2 mx-2">
                </div>
                <div class="custom-file mb-3">
                    <input type="file" class="custom-file-input" id="file_upload" name="upfile[]" multiple  @change="showFileNames">
                    <label class="custom-file-label" for="file_upload">Upload</label>
                </div>
                <div id="uploaded_files">
                    <p v-for="(file,index) in files" :key="index">{{file.name}}</p>
                </div>
                <button class="form-control btn btn-outline-primary" id="btn_add" v-on:click="uploadFiles">Submit</button>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            files:[],
        }
    },
    methods: {
        showFileNames(evt){
            this.files = evt.target.files;
        },
        uploadFiles(){
            let formData = new FormData();
            formData.append('files_upload', this.files);
            axios.post('/uploadfile',
                formData,
                {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }
            ).then(function(res){
            });
        }
    },
}
</script>
