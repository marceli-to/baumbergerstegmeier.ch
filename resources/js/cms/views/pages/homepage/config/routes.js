import HomepageDashboard from '@/views/pages/homepage/Index.vue';

import TeaserIndex from '@/views/pages/homepage/teaser/Index.vue';

import ArticleIndex from '@/views/pages/homepage/article/Index.vue';
import ArticleCreate from '@/views/pages/homepage/article/partials/Create.vue';
import ArticleEdit from '@/views/pages/homepage/article/partials/Edit.vue';

const routes = [
  {
    name: 'homepage-dashboard',
    path: '/administration/homepage/dashboard',
    component: HomepageDashboard,
  },

  {
    name: 'teaser-index',
    path: '/administration/homepage/teasers',
    component: TeaserIndex,
  },

  {
    name: 'article-index',
    path: '/administration/article/index',
    component: ArticleIndex,
  },
  {
    name: 'article-create',
    path: '/administration/article/create',
    component: ArticleCreate,
  },
  {
    name: 'article-edit',
    path: '/administration/article/edit/:id',
    component: ArticleEdit,
  },


];

export default routes;