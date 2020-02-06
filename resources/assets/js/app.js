require('./bootstrap');
import Vue from 'vue';
import VueRouter from 'vue-router';
import Vuex from 'vuex';
import {routes} from './routes';
import StoreData from './store';
import MainApp from './components/MainApp.vue';
import {initialize} from './helpers/general';
import VueExcelXlsx from "vue-excel-xlsx";
import { Form, HasError, AlertError , AlertSuccess } from 'vform';

window.Form = Form;

import Snotify, {SnotifyPosition} from "vue-snotify";

const snotifyOptions = {
    toast: {
        position: SnotifyPosition.rightTop
    }
};


Vue.use(VueRouter);
Vue.use(Vuex);
Vue.use(Snotify, snotifyOptions);
Vue.use(VueExcelXlsx);

const store = new Vuex.Store(StoreData);

const router = new VueRouter({
    routes,
    mode: 'history'
});

initialize(store, router);

Vue.component(HasError.name, HasError);
Vue.component(AlertError.name, AlertError);
Vue.component(AlertSuccess.name, AlertSuccess);

const app = new Vue({
    el: '#app',
    router,
    store,
    components: {
        MainApp
    }
});
