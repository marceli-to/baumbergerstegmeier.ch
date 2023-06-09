import JobDashboard from '@/views/pages/job/Index.vue';
import JobIndex from '@/views/pages/job/page/Index.vue';
import JobCreate from '@/views/pages/job/page/partials/Create.vue';
import JobEdit from '@/views/pages/job/page/partials/Edit.vue';
import JobArticleIndex from '@/views/pages/job/article/Index.vue';
import JobArticleCreate from '@/views/pages/job/article/partials/Create.vue';
import JobArticleEdit from '@/views/pages/job/article/partials/Edit.vue';

const routes = [
  {
    name: 'job-dashboard',
    path: '/administration/job/dashboard',
    component: JobDashboard,
  },

  {
    name: 'job-index',
    path: '/administration/job/index',
    component: JobIndex,
  },
  {
    name: 'job-create',
    path: '/administration/job/create',
    component: JobCreate,
  },
  {
    name: 'job-edit',
    path: '/administration/job/edit/:id',
    component: JobEdit,
  },

  {
    name: 'job-article-index',
    path: '/administration/job/article/index',
    component: JobArticleIndex,
  },
  {
    name: 'job-article-create',
    path: '/administration/job/article/create',
    component: JobArticleCreate,
  },
  {
    name: 'job-article-edit',
    path: '/administration/job/article/edit/:id',
    component: JobArticleEdit,
  },
];

export default routes;