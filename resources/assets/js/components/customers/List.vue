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

        <div class="btn-wrapper" style="margin-top: 1rem;">
             <button type="button" class="btn btn-info" @click="create">Create <i class="fas fa-plus"></i></button>
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
                            <button type="button" @click="show(customer)" class="btn btn-info btn-sm">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button type="button" @click="edit(customer)" class="btn btn-primary btn-sm">
                                <i class="fas fa-edit"></i>
                            </button>

                            <button
                                type="button"
                                @click="destroy(customer)"
                                class="btn btn-danger btn-sm">
                                <i class="fas fa-trash-alt"></i>
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

        <!-- Modal -->
        <div
            class="modal fade"
            id="customerModalLong"
            tabindex="-1"
            role="dialog"
            aria-labelledby="customerModalLongTitle"
            aria-hidden="true"
        >
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5
                            class="modal-title"
                            id="customerModalLongTitle">
                            {{ editMode ? "Edit" : "Add New" }} Customer</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="editMode ? update() : store()" @keydown="form.onKeydown($event)">
                        <div class="modal-body">
                            <alert-error :form="form"></alert-error>

                            <div class="form-group">
                                <label>Name</label>
                                <input
                                    v-model="form.name"
                                    type="text"
                                    name="name"
                                    class="form-control"
                                    :class="{ 'is-invalid': form.errors.has('name') }"
                                >
                                <has-error :form="form" field="name"></has-error>
                            </div>

                            <div class="form-group">
                                <label>Email</label>
                                <input
                                    v-model="form.email"
                                    type="email"
                                    name="email"
                                    class="form-control"
                                    :class="{ 'is-invalid': form.errors.has('email') }"
                                >
                                <has-error :form="form" field="email"></has-error>
                            </div>

                            <div class="form-group">
                                <label>Phone</label>
                                <input
                                    v-model="form.phone"
                                    type="tel"
                                    name="phone"
                                    class="form-control"
                                    :class="{ 'is-invalid': form.errors.has('phone') }"
                                >
                                <has-error :form="form" field="phone"></has-error>
                            </div>

                            <div class="form-group">
                                <label>Website</label>
                                <input
                                    v-model="form.website"
                                    name="website"
                                    class="form-control"
                                    :class="{ 'is-invalid': form.errors.has('website') }"
                                >
                                <has-error :form="form" field="website"></has-error>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button :disabled="form.busy" type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div
            class="modal fade"
            id="showModal"
            tabindex="-1"
            role="dialog"
            aria-labelledby="showModalLabel"
            aria-hidden="true"
        >
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="showModalLabel">{{ form.name }}</h5>

                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <strong>Email : {{ form.email }}</strong>
                        <br>
                        <strong>Phone : {{ form.phone }}</strong>
                        <br>
                        <strong>Website : {{ form.website }}</strong>
                        <br>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import PaginationComponent from "../partial/PaginationComponent";
    import validate from 'validate.js';

    export default {

        name: 'list',
        data() {
            return {
                editMode: false,
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
                },
                errors: null,
                form: new Form({
                    id: '',
                    name: '',
                    email: '',
                    phone: '',
                    website: '',
                }),
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
                this.$snotify.success('Data saccessfully Refresh', 'success');
            },
            deleteCustomer(id) {
                axios.delete(`api/customers/delete/${id}`, this.$data.customers)
                    .then(response => {
                        this.getData();
                    })
            },
            exportXlsx() {
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

            },
            create() {
                this.editMode = false;
                this.form.reset();
                this.form.clear();
                $('#customerModalLong').modal('show');
            },
            store() {
                this.errors = null;
                const constraints = this.getConstraints();
                const errors = validate(this.form, constraints);

                if(errors) {
                    this.errors = errors;
                    return;
                }

                this.form.busy = true;
                this.form.post('/api/customers/new')
                    .then(response => {
                        this.getData();
                        $('#customerModalLong').modal('hide');
                        if(this.form.successful) {
                            this.$snotify.success('Customer successfully saved', 'Success')
                        } else {
                            this.$snotify.error('Something went wrong. Try again later', 'Error')
                        }
                    })
                    .catch(error => {
                        console.log(error);
                    })
            },
            show(customer) {
                this.form.reset();
                this.form.fill(customer);
                $("#showModal").modal("show");
                console.log(customer);
            },
            edit(customer) {
                this.editMode = true;
                this.form.reset();
                this.form.clear();
                this.form.fill(customer);
                $("#customerModalLong").modal("show");
            },
            update() {
                this.form.busy = true;
                this.form
                    .post("/api/customers/update/" + this.form.id)
                    .then(response => {
                        this.getData();
                        $("#customerModalLong").modal("hide");
                        if (this.form.successful) {
                            this.$snotify.success("Customer successfully updated", "Success");
                        } else {
                            this.$snotify.error(
                                "Something went wrong. Try again later.",
                                "Error"
                            );
                        }
                    })
                    .catch(error => {
                        console.log(error);
                    });
            },
            destroy(customer) {
                this.$snotify.clear();
                this.$snotify.confirm(
                    "You will not be able to recover this data!",
                    "Are you sure?",
                    {
                        showProgressBar: false,
                        closeOnClick: false,
                        pauseOnHover: true,
                        buttons: [
                            {
                                text: "Yes",
                                action: toast => {
                                    this.$snotify.remove(toast.id);
                                    axios.delete("/api/customers/delete/" + customer.id)
                                        .then(response => {
                                            this.getData();
                                            this.$snotify.success(
                                                "Customer successfully deleted",
                                                "Success"
                                            );
                                        })
                                        .catch(error => {
                                            console.log(error);
                                        });
                                },
                                bold: true
                            },
                            {
                                text: "No",
                                action: toast => {
                                    this.$snotify.remove(toast.id);
                                },
                                bold: true
                            }
                        ]
                    }
                );
            },
            getConstraints() {
                return {
                    name: {
                        presence: true,
                        length: {
                            minimum: 3,
                            message: 'Must be at least 3 characters long'
                        }
                    },
                    email: {
                        presence: true,
                        email: true
                    },
                    phone: {
                        presence: true,
                        numericality: true,
                        length: {
                            minimum: 10,
                            message: 'Must be at least 10 digits long'
                        }
                    },
                    website: {
                        presence: true,
                        url: true
                    }
                };
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
.errors {
    background: lightcoral;
    border-radius:5px;
    padding: 21px 0 2px 0;
}
</style>
