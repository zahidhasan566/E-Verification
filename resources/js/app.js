require('./bootstrap');
require('./validation/index');

import Vue from 'vue'
import App from './views/App'
import router from './router/index';
import store from './store/index'
import {baseurl} from './base_url'

// main origin
Vue.prototype.mainOrigin = baseurl
//import v-form
import { Form } from 'vform'
window.Form = Form;


Vue.component('pagination', require('./components/partial/PaginationComponent.vue').default);
Vue.component('report-pagination', require('./components/partial/ReportPaginationComponent.vue').default);
//Multi select
//Vue Multiselect
import Multiselect from 'vue-multiselect'
Vue.component('multiselect', Multiselect)
//Bus to transfer data
export const bus = new Vue();

//Toaster
import Toaster from 'v-toaster'
import 'v-toaster/dist/v-toaster.css'
Vue.use(Toaster, {timeout: 5000})
//import sweetalert
import Swal from 'sweetalert2';
window.Swal = Swal;
//moment
import moment from 'moment'


//Component
Vue.component('skeleton-loader', require('./components/loaders/Straight').default);
Vue.component('submit-form', require('./components/buttons/Submit').default);
Vue.component('advanced-datatable', require('./components/datatable/Advanced').default);
Vue.component('breadcrumb', require('./components/layouts/Breadcrumb').default);
Vue.component('data-export', require('./components/datatable/Export').default);
Vue.component('add-edit-user',require('./components/users/AddEditModal').default);
Vue.component('reset-password',require('./components/users/Editpassword').default);
Vue.component('submit-form', require('./components/buttons/Submit').default);

Vue.component('add-edit-assign-vro', require('./components/vro/assignVroAddEdit.vue').default);



const app = new Vue({
    el: '#app',
    store: store,
    components: {App},
    router,
});
