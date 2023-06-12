import TeamDashboard from '@/views/pages/team/Index.vue';
import TeamIndex from '@/views/pages/team/team/Index.vue';
import TeamCreate from '@/views/pages/team/team/partials/Create.vue';
import TeamEdit from '@/views/pages/team/team/partials/Edit.vue';

import EmployeeCategoryIndex from '@/views/pages/team/employeeCategory/Index.vue';
import EmployeeCategoryCreate from '@/views/pages/team/employeeCategory/partials/Create.vue';
import EmployeeCategoryEdit from '@/views/pages/team/employeeCategory/partials/Edit.vue';

const routes = [
  {
    name: 'team-dashboard',
    path: '/administration/team/dashboard',
    component: TeamDashboard,
  },

  {
    name: 'team-index',
    path: '/administration/team/index',
    component: TeamIndex,
  },
  {
    name: 'team-create',
    path: '/administration/team/create',
    component: TeamCreate,
  },
  {
    name: 'team-edit',
    path: '/administration/team/edit/:id',
    component: TeamEdit,
  },

  {
    name: 'employee-category-index',
    path: '/administration/team/employeeCategory/index',
    component: EmployeeCategoryIndex,
  },
  {
    name: 'employee-category-create',
    path: '/administration/team/employeeCategory/create',
    component: EmployeeCategoryCreate,
  },
  {
    name: 'employee-category-edit',
    path: '/administration/team/employeeCategory/edit/:id',
    component: EmployeeCategoryEdit,
  },

  // {
  //   name: 'team-article-index',
  //   path: '/administration/team/article/index',
  //   component: TeamArticleIndex,
  // },
  // {
  //   name: 'team-article-create',
  //   path: '/administration/team/article/create',
  //   component: TeamArticleCreate,
  // },
  // {
  //   name: 'team-article-edit',
  //   path: '/administration/team/article/edit/:id',
  //   component: TeamArticleEdit,
  // },
];

export default routes;