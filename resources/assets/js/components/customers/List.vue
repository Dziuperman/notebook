<template>
    <div>
        <form class="mb-3">
            <div class="row">
                <div class="col-md-2">
                    Search By:
                </div>
                <div class="col-md-3">
                    <select v-model="queryField" name="" id="fields" class="form-control">
                        <option value="name">Name</option>
                        <option value="email">Email</option>
                        <option value="phone">Phone</option>
                        <option value="website">Website</option>
                        <option value="total">Total</option>
                    </select>
                </div>
                <div class="col-md-7">
                    <input v-model="query" type="text" class="form-control" placeholder="Search">
                </div>
            </div>
        </form>
        <div class="btn-wrapper">
            <router-link to="/customers/new" class="btn btn-primary btn-sm">New</router-link>
        </div>
        <table class="table">
            <thead>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Actions</th>
            </thead>
            <tbody>
                <template v-if="customers.data && !customers.data.length">
                    <tr>
                        <td colspan="4" class="text-center">No Customers Available</td>
                    </tr>
                </template>
                <template>
                    <tr v-for="customer in customers.data" :key="customer.id">
                        <td>{{ customer.name }}</td>
                        <td>{{ customer.email }}</td>
                        <td>{{ customer.phone }}</td>
                        <td>
                            <router-link :to="`/customers/${customer.id}`">View</router-link>
                            <router-link :to="`/customers/update/${customer.id}`">Edit</router-link>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
        <pagination v-if="pagination.last_page > 1"
                    :pagination="pagination"
                    :offset="5"
                    @paginate="getData()">
        </pagination>
    </div>
</template>

<script>
    export default {
        name: 'list',
        data() {
            return {
                customers: [],
                pagination: {
                    current_page: 1,
                },
                query: '',
                queryField: 'name',
                currentPage: null,
            }
        },
        watch: {
            query(newQ, old) {
                if(newQ === '') {
                    this.getData();
                } else {
                    this.searchData();
                }
            }
        },
        mounted() {
            if(this.customers.length) {
                return;
            }

            // this.$store.dispatch('getCustomers');

            // this.getResults();
            this.getData();
        },
        methods: {
            getData() {
                axios.get('api/customers?page=' + this.pagination.current_page)
                    .then(response => {
                        this.customers = response.data.customers;
                        this.pagination = response.data.customers;
                        console.log(this.pagination.current_page);
                    })
            },
            searchData() {
                axios.get('api/search/customers/'
                    + this.queryField + '/' + this.query
                    + '?page=' + this.pagination.current_page)
                    .then(response => {
                        this.customers = response.data.customers;
                        this.pagination = response.data.customers;
                    })
            }
        },
        computed: {
            // customers() {
            //     return this.$store.getters.customers;
            // }
        },
        components: {
            // PaginationComponent,
        }
    }
</script>

<style scoped>
.btn-wrapper {
    text-align: right;
    margin-bottom: 20px;
}
</style>
