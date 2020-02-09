<template>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="table-responsive">
                    <button type="button" class="btn btn-primary" style="margin: 1rem;" @click.prevent="exportActivityToXlsx()">Export <i class="far fa-file-excel"></i></button>
                    <button type="button" class="btn btn-primary" style="margin: 1rem;" @click.prevent="exportActivityToCsv()">Export <i class="fas fa-table"></i></i></button>
                    <router-link to="/customers" style="position: absolute; right: 1rem; top: 1.5rem;">Back to customers</router-link>
                    <table class="table table-hover table-bordered table-striped">
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Action</th>
                            <th scope="col">Name</th>
                            <th scope="col">Email</th>
                            <th scope="col">Phone</th>
                            <th scope="col">Website</th>
                            <th scope="col">Created at</th>
                            <th scope="col">Updated at</th>
                            <th scope="col">Date</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(activity, index) in activities" :key="activity.id">
                            <th  scope="row">{{index + 1}}</th>
                            <td>{{activity.description}}</td>
                            <td>
                                {{activity.properties.attributes.name}}
                                <br>
                                <span v-if="activity.properties.old &&
                                    (activity.properties.old.name !== activity.properties.attributes.name)" style="color: red;">
                                    {{activity.properties.old.name}}
                                </span>
                            </td>
                            <td>
                                {{activity.properties.attributes.email}}
                                <br>
                                <span v-if="activity.properties.old &&
                                    (activity.properties.old.email !== activity.properties.attributes.email)" style="color: red;">
                                    {{activity.properties.old.email}}
                                </span>
                            </td>
                            <td>
                                {{activity.properties.attributes.phone}}
                                <br>
                                <span v-if="activity.properties.old &&
                                    (activity.properties.old.phone !== activity.properties.attributes.phone)" style="color: red;">
                                    {{activity.properties.old.phone}}
                                </span>
                            </td>
                            <td>
                                {{activity.properties.attributes.website}}
                                <br>
                                <span v-if="activity.properties.old &&
                                    (activity.properties.old.website !== activity.properties.attributes.website)" style="color: red;">
                                    {{activity.properties.old.website}}
                                </span>
                            </td>
                            <td>{{activity.properties.attributes.created_at}}</td>
                            <td>{{activity.properties.attributes.updated_at}}</td>
                            <td>{{activity.created_at}}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'activity-log',
        data() {
            return {
                activities: [],
            }
        },
        created() {
            axios.get('/api/customers/log/show')
                .then(response => {
                    this.activities = response.data.log;
                    console.log(this.activities);
                })
                .catch(error => {
                    console.log(error);
                })
        },
        methods: {
            exportActivityToXlsx() {
                axios({
                    url: '/api/customers/log/export',
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
            exportActivityToCsv() {
                axios({
                    url: '/api/customers/log/export/csv',
                    method: 'GET',
                    responseType: 'blob',
                }).then((response) => {
                    let fileURL = window.URL.createObjectURL(new Blob([response.data]));
                    let fileLink = document.createElement('a');
                    console.log(response.data);
                    fileLink.href = fileURL;
                    fileLink.setAttribute('download', 'file.csv');
                    document.body.appendChild(fileLink);

                    fileLink.click();
                });
            },
        }
    }
</script>

<style>

</style>