<template>
    <input type="file" accept=".csv" @change="handleFileUpload( $event )"/>
    <table v-if="parsed" style="width: 100%;">
    <thead>
        <tr>
            <th v-for="(header, key) in content.meta.fields"
                v-bind:key="'header-'+key">
                {{ header }}
            </th>
        </tr>
    </thead>
    <tbody>
        <tr v-for="(row, rowKey) in content.data"
            v-bind:key="'row-'+rowKey">
                <td v-for="(column, columnKey) in content.meta.fields"
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
                    this.content = results;
                    console.log("parsing started");
                    console.log(results.data);
                    for (let i = 0; i < results.data.length; i++) {
                        console.log(results.data[i]);
                        let emailValid = true;
                        let firstNameValid = true;
                        let lastNameValid = true;

                        console.log(typeof results.data[i]['first_name']);

                        if(results.data[i]['email'] == null)
                        {
                            emailValid = false;
                        }
                        if(results.data[i]['first_name'] === '')
                        {
                            firstNameValid = false;
                        }
                        if(results.data[i]['last_name'] == null)
                        {
                            lastNameValid = false;
                        }
                        console.log(firstNameValid);
                    }
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
