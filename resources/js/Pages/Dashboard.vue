<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Welcome from '@/Jetstream/Welcome.vue';
</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Assignement 1
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <Welcome />
                    <FileUploadComponent />
                </div>
            </div>
        </div>
    </AppLayout>
</template>
<script>
    export default {
        data() {
            return {
                name: '',
                file: '',
                success: ''
            };
        },
        methods: {
            getBooksList(e) {

            },
            onChange(e) {
                this.file = e.target.files[0];
            },

        }
    }
</script>
