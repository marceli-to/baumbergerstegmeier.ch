require('@/bootstrap');

// Vue
import * as Vue from 'vue';
window.Vue = Vue.default;

// Axios, Vue-Axios
import VueAxios from 'vue-axios';
import axios from 'axios';
window.axios = require('axios');
window.Vue.use(VueAxios, axios);

// Axios Interceptors
window.Vue.use(require('vue-axios-interceptors'));

// Filters
require('@/mixins/Filters');

// Vue-Axios defaults
window.Vue.axios.defaults.withCredentials = true;

// Vue-Notifications
import Notifications from 'vue-notification';
window.Vue.use(Notifications);

// Vue-Router
import VueRouter from 'vue-router';
window.Vue.use(VueRouter);

// Loading indicator
import LoadingIndicator from "@/components/ui/LoadingIndicator";
window.Vue.component('LoadingIndicator', LoadingIndicator);

// Separator
import Separator from "@/components/ui/Separator";
window.Vue.component('Separator', Separator);

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
import homepageRoutes from '@/views/pages/homepage/config/routes';
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
      ...homepageRoutes,
      ...imageRoutes,
    ]
  }
);

// App component
import AppComponent from '@/App.vue';

// Mount App
const app = new window.Vue({
  mixins: [],
  components: { 
    AppComponent
  },
  router,
  store
}).$mount('#app');