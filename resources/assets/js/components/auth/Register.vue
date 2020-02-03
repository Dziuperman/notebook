<template>
    <div class="login row justify-content-center">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    Register
                </div>
                <div class="card-body">
                    <form @submit.prevent="registerUser">
                        <div class="form-group row">
                            <label for="name">Name:</label>
                            <input type="text" v-model="form.name" class="form-control" placeholder="Name">
                        </div>
                        <div class="form-group row">
                            <label for="email">Email:</label>
                            <input type="email" v-model="form.email" class="form-control" placeholder="Email Address">
                        </div>
                        <div class="form-group row">
                            <label for="password">Password:</label>
                            <input type="password" v-model="form.password" class="form-control" placeholder="Password">
                        </div>
                        <div class="form-group row">
                            <input type="submit" value="Register">
                        </div>
                        <div class="form-group row">
                            <p class="error">
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "register",
        data() {
            return {
                form: {
                    name: '',
                    email: '',
                    password: ''
                },
                error: null
            }
        },
        methods: {
            registerUser() {
                axios.post('/api/auth/register', this.$data.form)
                    .then(response => {
                        console.log(response);
                        this.$router.push({path: '/login'});
                    })
                    .catch(error => {
                        console.log(error.response);
                    })
            }
        }
    }
</script>