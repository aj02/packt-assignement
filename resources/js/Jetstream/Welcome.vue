<script setup>
import JetApplicationLogo from '@/Jetstream/ApplicationLogo.vue';
</script>


<template>
    <div>
        <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
            <div>
                <img src="https://upload.wikimedia.org/wikipedia/commons/d/d7/Packt-Logo.png?20171012133106" alt="">
            </div>

            <div class="mt-8 text-2xl">
                Assignement 1
            </div>


        </div>


        <div class="bg-gray-200 bg-opacity-25 grid grid-cols-1 md:grid-cols-">


            <div class="bg-gray-200 bg-opacity-25 grid grid-cols-1 md:grid-cols-2">



                <div class="ml-12">
                    <div class="p-4 text-sm text-gray-700 bg-gray-100 rounded-lg dark:bg-gray-700 dark:text-gray-300" role="alert">

                        <div class="inline-flex">
                            <button @click="prevPage" class="prev bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-l">
                                Prev
                            </button>
                            <button @click="nextPage" class="next bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-r">
                                Next
                            </button>
                        </div>
                        <br>
                        <span class="font-medium">Showing {{ start_books }} - {{ end_books }}</span> out of total {{ total_books }} books
                    </div>

                    <ul class="divide-y divide-gray-200">
                        <li v-for="book in books" :key="book.title" class="py-4 flex">
                        <img class="h-10 w-10 rounded-full" src='https://cdn-icons-png.flaticon.com/128/3532/3532861.png' alt="" />
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">{{ book.title }}</p>
                            <p class="text-sm text-gray-500">{{ book.authors.join(", ") }}</p>
                        </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</template>
<script>

export default {
  setup() {
    return {
      people,
    }
  },
   data() {
        return {
            books: [],
            total_books: 0,
            start_books: 0,
            end_books: 0,
            current_page: 1,
        }
    },
    mounted() {
        window.axios.get('/api/books').then(res => {
            console.log(res.data);
            this.books = res.data.products;
            this.total_books = res.data.total;
            this.start_books = (res.data.current_page * res.data.per_page) - res.data.per_page;
            this.end_books = (res.data.current_page * res.data.per_page);
            this.current_page = res.data.current_page;
        })
    },
    methods: {
        prevPage(event) {
                // `this` inside methods points to the current active instance
                window.axios.get('/api/books').then(res => {
                console.log(res.data);
                this.books = res.data.products;
                this.total_books = res.data.total;
                this.start_books = (res.data.current_page * res.data.per_page) - res.data.per_page;
                this.end_books = (res.data.current_page * res.data.per_page);
                this.current_page = res.data.current_page;
            })
        },
        nextPage(event) {
                // `this` inside methods points to the current active instance
                window.axios.get('/api/books').then(res => {
                console.log(res.data);
                this.books = res.data.products;
                this.total_books = res.data.total;
                this.start_books = (res.data.current_page * res.data.per_page) - res.data.per_page;
                this.end_books = (res.data.current_page * res.data.per_page);
                this.current_page = res.data.current_page;
            })
        },

    },
}
</script>
