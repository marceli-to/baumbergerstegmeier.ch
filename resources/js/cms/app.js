require('@/bootstrap');

// Vue
window.Vue = require('vue');

// Axios Interceptors
require('vue-axios-interceptors');

// Axios, Vue-Axios
import VueAxios from 'vue-axios';
import axios from 'axios';
window.axios = require('axios');
Vue.use(VueAxios, axios);

// Filters
require('@/mixins/Filters');

// Vue-Axios defaults
Vue.axios.defaults.withCredentials = true;

// Vue-Notifications
import Notifications from 'vue-notification';
Vue.use(Notifications);

// Vue-Router
import VueRouter from 'vue-router';
Vue.use(VueRouter);

// Loading indicator
import LoadingIndicator from "@/components/ui/LoadingIndicator";
Vue.component('LoadingIndicator', LoadingIndicator);

// Separator
import Separator from "@/components/ui/Separator";
Vue.component('Separator', Separator);

// Store
import store from '@/config/store';

// Routes
import baseRoutes from '@/config/routes';
import awardRoutes from '@/views/pages/award/config/routes';
import contactRoutes from '@/views/pages/contact/config/routes';
import jobRoutes from '@/views/pages/job/config/routes';
import profileRoutes from '@/views/pages/profile/config/routes';
import publicationRoutes from '@/views/pages/publication/config/routes';
import teamRoutes from '@/views/pages/team/config/routes';
import projectRoutes from '@/views/pages/project/config/routes';
import imageRoutes from '@/modules/images/config/routes';

const router = new VueRouter(
  { 
    mode: 'history', 
    routes: [
      ...baseRoutes,
      ...awardRoutes,
      ...contactRoutes,
      ...jobRoutes,
      ...publicationRoutes,
      ...profileRoutes,
      ...teamRoutes,
      ...projectRoutes,
      ...imageRoutes,
    ]
  }
);

// App component
import AppComponent from '@/App.vue';

// Mount App
const app = new Vue({
  mixins: [],
  components: { 
    AppComponent
  },
  router,
  store
}).$mount('#app');