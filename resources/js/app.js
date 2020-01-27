import './bootstrap';
import Vue from 'vue';
import Vuetify from 'vuetify';
import VueResource from 'vue-resource';

// Route information from Vue Router
import Routes from '@/js/routes.js';

// Component File
import App from '@/js/views/App';

Vue.use(Vuetify);
Vue.use(VueResource);

const app = new Vue({
    el: '#app',
    vuetify: new Vuetify(),
    router: Routes,
    render: h => h(App)
});

export default app;
