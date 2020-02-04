<template>
    <div>
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
        <div class="card-footer">
            <pagination :data="customers" @pagination-change-page="getResults"></pagination>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'list',
        data() {
            return {
                customers: {},
            }
        },
        mounted() {
            // if(this.customers.length) {
            //     return;
            // }

            // this.$store.dispatch('getCustomers');

            this.getResults();
        },
        methods: {
            getResults(page = 1) {
                axios.get('/api/customers?page=' + page)
                    .then(response => {
                        this.customers = response.data.customers;
                        console.log(this.customers.current_page);
                    });
            },
        },
        computed: {
            // customers() {
            //     return this.$store.getters.customers;
            // }
        }
    }
</script>

<style scoped>
.btn-wrapper {
    text-align: right;
    margin-bottom: 20px;
}
</style>
