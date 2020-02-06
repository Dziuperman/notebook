<template>
    <div id="customer">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="curd-title">
                            Customers
                        </h4>
                        <div class="card-tools" style="position: absolute; right: 1rem; top: 0.5rem">
                            <button type="button" class="btn btn-primary" @click="reload">Reload</button>
                        </div>
                        <button type="button" class="btn btn-primary" @click.prevent="exportXlsx">Download</button>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
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
                                    </select>
                                </div>
                                <div class="col-md-7">
                                    <input v-model="query" type="text" class="form-control" placeholder="Search">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

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
                            <router-link :to="`/customers/${customer.id}`">
                                <div class="btn btn-primary"><i class="far fa-eye"></i></div>
                            </router-link>
                            <router-link :to="`/customers/update/${customer.id}`">
                                <div class="btn btn-primary"><i class="fas fa-edit"></i></div>
                            </router-link>
                            <button class="btn btn-primary" @click.prevent="deleteCustomer(customer.id)">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                </template>
                <tr v-show="customers.data && !customers.data.length">
                    <td>
                        <div class="alert alert-danger" role="alert">
                            Sorry. No data found.
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        <pagination-component v-if="pagination.last_page > 1"
                    :pagination="pagination"
                    :offset="5"
                    @paginate="query === '' ? getData() : searchData()">
        </pagination-component>
    </div>
</template>

<script>
    import PaginationComponent from "../partial/PaginationComponent";

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
                options: {
                    responseType: 'blob',
                },
                settings: {
                    headers: {
                        'content-type': 'multipart/form-data'
                    }
                }
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
                        console.log(this.customers.data.length);
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
            },
            reload() {
                this.getData();
                this.query = '';
                this.queryField = 'name';
            },
            deleteCustomer(id) {
                axios.delete(`api/customers/delete/${id}`, this.$data.customers)
                    .then(response => {
                        this.getData();
                    })
            },
            exportXlsx() {
                // axios.get('api/customers/export', this.settings)
                //     .then(response => {
                //         console.log(response.headers)
                //     })
                //     .catch(error => {
                //         console.log(error);
                //     })
                axios({
                    url: 'api/customers/export',
                    method: 'GET',
                    responseType: 'blob',
                }).then((response) => {
                    let fileURL = window.URL.createObjectURL(new Blob([response.data]));
                    let fileLink = document.createElement('a');

                    fileLink.href = fileURL;
                    fileLink.setAttribute('download', 'file.xlsx');
                    document.body.appendChild(fileLink);

                    fileLink.click();
                });

            }
        },
        computed: {
            // customers() {
            //     return this.$store.getters.customers;
            // }
        },
        components: {
            PaginationComponent,
        }
    }
</script>

<style scoped>
.btn-wrapper {
    text-align: right;
    margin-bottom: 20px;
}
</style>
