<template>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<form>
  <!-- Content here -->


  <div class="form-group">
    <label for="exampleFormControlFile1">User File</label>
    <input type="file" accept=".csv" @change="handleFileUpload( $event )"/>
  </div>
</form>



    <table v-if="parsed" style="width: 100%;border: 1px solid black;">
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
                        <input v-model="content.data[rowKey][column]"/>
                </td>
        </tr>

    </tbody>
</table>

</template>

<script>
import Papa from 'papaparse';
import axios from 'axios';

export default {
    data(){
        return {
            file: '',
            content: [],
            parsed: false
        }
    },

    methods: {
        handleFileUpload( event ){
            this.file = event.target.files[0];
            this.parseFile();
            // this.submitUpdates();
        },
        parseFile(){
            Papa.parse( this.file, {
                header: true,
                skipEmptyLines: true,
                complete: function( results ){

                    console.log("parsing started");
                    console.log(results.data);
                    for (let i = 0; i < results.data.length; i++) {
                        results.data[i]['status'] = 'Valid';

                        if(results.data[i]['email'] == '')
                        {
                            console.log("email");
                            results.data[i]['status'] = 'Invalid';
                        }
                        if(results.data[i]['first_name'] === '')
                        {
                            console.log("fname");

                            results.data[i]['status'] = 'Invalid';
                        }
                        if(results.data[i]['last_name'] == '')
                        {
                            console.log("lname");

                            results.data[i]['status'] = 'Invalid';
                        }

                    }
                    this.content = results;
                    this.parsed = true;
                }.bind(this)
            } );
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
