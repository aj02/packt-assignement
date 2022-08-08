<template>
<!-- Latest compiled and minified CSS -->
<meta name="csrf-token" content="{{ csrf_token() }}" />
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
            Processing [ {{ processingMessage }} ]
            <span class="float-right"><b>{{ currentProcessingRecord }}</b>/{{ totalProcessingRecords }}</span>
            <div class="progress progress-sm">
                <div class="progress-bar bg-danger" :style="'width:'+ percProcessingProgress +'%;'"></div>
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
            currentProcessingRecord: 0,
            totalProcessingRecords: 0,
            percProcessingProgress: 0,
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
            this.processing = true;

            this.percProcessingProgress = 0;
            this.processingMessage = '';

            this.totalProcessingRecords = this.valid.length;
            this.currentProcessingRecord = 0;

            this.counter++;
            console.log(typeof this.valid);
            for (let i = 0; i < this.valid.length; i++) {
                this.currentProcessingRecord = this.currentProcessingRecord + 1;

                // POST request using axios with async/await
                axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                console.log(this.valid[i]);
                const user = { data: this.valid[i]};
                const response = await axios.post("/upload/record", user);

                this.percProcessingProgress = Math.round(this.currentProcessingRecord / this.totalProcessingRecords) * 100;
                this.processingMessage = 'Processed '+this.currentProcessingRecord+' / '+this.totalProcessingRecords+' records.';
            }

        }
    },
}
</script>
