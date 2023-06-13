import ProjectDashboard from '@/views/pages/project/Index.vue';

import ProjectIndex from '@/views/pages/project/project/Index.vue';
import ProjectCreate from '@/views/pages/project/project/partials/Create.vue';
import ProjectEdit from '@/views/pages/project/project/partials/Edit.vue';

import CategoryIndex from '@/views/pages/project/category/Index.vue';
import CategoryCreate from '@/views/pages/project/category/partials/Create.vue';
import CategoryEdit from '@/views/pages/project/category/partials/Edit.vue';

import StateIndex from '@/views/pages/project/state/Index.vue';
import StateCreate from '@/views/pages/project/state/partials/Create.vue';
import StateEdit from '@/views/pages/project/state/partials/Edit.vue';

import TypeIndex from '@/views/pages/project/type/Index.vue';
import TypeCreate from '@/views/pages/project/type/partials/Create.vue';
import TypeEdit from '@/views/pages/project/type/partials/Edit.vue';

const routes = [
  {
    name: 'project-dashboard',
    path: '/administration/project/dashboard',
    component: ProjectDashboard,
  },

  {
    name: 'project-index',
    path: '/administration/project/index',
    component: ProjectIndex,
  },
  {
    name: 'project-create',
    path: '/administration/project/create',
    component: ProjectCreate,
  },
  {
    name: 'project-edit',
    path: '/administration/project/edit/:id',
    component: ProjectEdit,
  },

  {
    name: 'project-category-index',
    path: '/administration/project/category/index',
    component: CategoryIndex,
  },
  {
    name: 'project-category-create',
    path: '/administration/project/category/create',
    component: CategoryCreate,
  },
  {
    name: 'project-category-edit',
    path: '/administration/project/category/edit/:id',
    component: CategoryEdit,
  },

  {
    name: 'project-state-index',
    path: '/administration/project/state/index',
    component: StateIndex,
  },
  {
    name: 'project-state-create',
    path: '/administration/project/state/create',
    component: StateCreate,
  },
  {
    name: 'project-state-edit',
    path: '/administration/project/state/edit/:id',
    component: StateEdit,
  },

  {
    name: 'project-type-index',
    path: '/administration/project/type/index',
    component: TypeIndex,
  },
  {
    name: 'project-type-create',
    path: '/administration/project/type/create',
    component: TypeCreate,
  },
  {
    name: 'project-type-edit',
    path: '/administration/project/type/edit/:id',
    component: TypeEdit,
  },

];

export default routes;