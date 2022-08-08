<template>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://adminlte.io/themes/v3/plugins/fontawesome-free/css/all.min.css">
<link rel="stylesheet" href="https://adminlte.io/themes/v3/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
<link rel="stylesheet" href="https://adminlte.io/themes/v3/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css">
<link rel="stylesheet" href="https://adminlte.io/themes/v3/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
<link rel="stylesheet" href="https://adminlte.io/themes/v3/plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css">
<link rel="stylesheet" href="https://adminlte.io/themes/v3/dist/css/adminlte.min.css?v=3.2.0">
<!-- Content here -->

<div class="col-md-12">
    <p class="text-center">
        <strong>Upload Status</strong>
    </p>
    <div class="progress-group" v-show="validating">
        Validation [ {{ validationMessage }} ]
        <span class="float-right"><b>{{ currentValidationRecord }}</b>/{{ totalValidationRecords }}</span>
        <div class="progress progress-sm">
            <div class="progress-bar bg-primary" :style="'width:'+ percValidationProgress +'%;'"></div>
        </div>
    </div>

    <div class="progress-group" v-show="processing" >
        Processing [ {{ validationMessage }} ]
        <span class="float-right"><b>310</b>/400</span>
        <div class="progress progress-sm">
            <div class="progress-bar bg-danger" style="width: 75%"></div>
        </div>
    </div>

</div>
<form onsubmit="return false;">
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Scilife - Upload User File</h3>
        </div>
        <div class="card-body">
            <div class="form-group" >
                <div class="input-group">
                    <div class="custom-file">
                        <input type="file" accept=".csv" @change="handleFileUpload( $event )"/>
                    </div>
                </div>
            </div>

            <div class="card" v-show="validating">
                <div class="card-header">
                <h3 class="card-title">Uploaded User file data</h3>
                </div>

                <div class="card-body p-0">
            <table v-if="parsed" class="table table-striped">

                <thead>
                    <tr>
                        <th v-for="(header, key) in ['email', 'person_prefix', 'first_name', 'last_name', 'active', 'status']"
                            v-bind:key="'header-'+key">
                            {{ header }}
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(row, rowKey) in content.data"
                        v-bind:key="'row-'+rowKey">
                            <td v-for="(column, columnKey) in ['email', 'person_prefix', 'first_name', 'last_name', 'active', 'status']"
                                v-bind:key="'row-'+rowKey+'-column-'+columnKey">
                                {{ content.data[rowKey][column] }}
                            </td>
                    </tr>

                </tbody>
            </table>
                </div>

                </div>


            <div class="form-check afterParse"  v-show="afterParse" >
                <input type="checkbox" class="form-check-input" id="exampleCheck1" v-on:click="isHidden = false">
                <label class="form-check-label" for="exampleCheck1"> Proceed with import for only successfully validated records.</label>
            </div>
        </div>

        <div class="card-footer afterParse" v-show="afterParse" v-if="!isHidden">
            <button class="btn btn-primary col start" @click="processFile">
            <i class="fas fa-upload"></i>
                <span> Upload {{ valid.length }} valid {{ valid.length <= 1 ? 'user':'users' }} only</span>
            </button>
        </div>

    </div>

</form>




</template>

<script>
import Papa from 'papaparse';
import axios from 'axios';
import $ from 'jquery';

export default {
    data(){
        return {
            file: '',
            content: [],
            valid: [],
            invalid: [],
            parsed: false,
            currentValidationRecord: 0,
            totalValidationRecords: 0,
            percValidationProgress: 0,
            validationMessage: '',
            processingMessage: '',
            isHidden : true,
            counter: 0
        }
    },

    methods: {
        handleFileUpload( event ){
            this.file = event.target.files[0];
            this.parseFile();
            // this.submitUpdates();
        },
        parseFile(){
            this.validating = true;
            Papa.parse( this.file, {
                header: true,
                skipEmptyLines: true,
                complete: function( results ){
                    this.percValidationProgress = 0;
                    this.validationMessage = '';
                    this.processingMessage = '';

                    this.totalValidationRecords = results.data.length;
                    this.currentValidationRecord = 0;


                    console.log("parsing started");
                    console.log(results.data);
                    for (let i = 0; i < results.data.length; i++) {
                        this.currentValidationRecord = this.currentValidationRecord + 1;
                        this.percValidationProgress = Math.round(this.currentValidationRecord / this.totalValidationRecords) * 100;
                        console.log(this.percValidationProgress);

                        this.validationMessage = 'Validated '+this.currentValidationRecord+' / '+this.totalValidationRecords+' records.';

                        // console.log(typeof results.data[i]);
                        // console.log(Object.keys(results.data[i]).length);

                        results.data[i]['status'] = 'Valid';

                        Object.entries(results.data[i]).forEach(item => {
                            console.log(item[0]);
                            this.validationMessage = 'Validated '+this.currentValidationRecord+' / '+this.totalValidationRecords+' records => ';
                            this.validationMessage += 'Now validating '+item[0].toString();
                            if(item[0] == 'email' && results.data[i]['email'] == '')
                            {
                                results.data[i]['status'] = 'Invalid';
                            }
                            if(results.data[i]['first_name'] === '')
                            {
                                results.data[i]['status'] = 'Invalid';
                            }
                            if(results.data[i]['last_name'] == '')
                            {
                                results.data[i]['status'] = 'Invalid';
                            }
                        });
                        if(results.data[i]['status'] == 'Invalid')
                        {
                            //push to invalid array
                            this.invalid.push(results.data[i]);

                        }
                        else
                        {
                            //push to valid array
                            this.valid.push(results.data[i]);
                        }
                    }
                    this.content = results;
                    this.parsed = true;
                    // this.validationMessage = 'Validated '+this.currentValidationRecord+' / '+this.totalValidationRecords+' records.';
                    this.afterParse = true;

                }.bind(this)
            } );
        },

        async processFile() {
            this.counter++;
            console.log(typeof this.invalid);
            Object.entries(this.invalid).forEach(item => {
console.log(json.parse(json.stringify(item)));
            });
            // POST request using axios with async/await
            const article = { title: "Vue POST Request Example" };
            const response = await axios.post("https://reqres.in/api/articles", article);
            this.articleId = response.data.id;
        },
        showFileNames(evt){
            this.files = evt.target.files;
        },
        submitUpdates(){
            axios.post( '/preview-file-changes',
                this.content.data
            ).then(function(){
                console.log('SUCCESS!!');
            })
            .catch(function(){
                console.log('FAILURE!!');
            });
        },
        uploadFiles(){
            this.processing = true;
            // let formData = new FormData();
            // formData.append('files', this.files);
            let formData = new FormData();
            for (let i = 0; i < this.files.length; i++) {
                formData.append('files[]', this.files[i]);
            }

            for (let i = 0; i < this.files.length; i++) {
                let formData = new FormData();
                formData.append('record', this.files[i]);

                axios.post('/upload/record',
                    formData,
                    {
                        headers: {
                        }
                    }
                ).then(function(res){
                });
            }

        }
    },
}
</script>
