import ProjectDashboard from '@/views/pages/project/Index.vue';

import ProjectIndex from '@/views/pages/project/project/Index.vue';
import ProjectCreate from '@/views/pages/project/project/partials/Create.vue';
import ProjectEdit from '@/views/pages/project/project/partials/Edit.vue';
import ProjectTeaser from '@/views/pages/project/project/Teaser.vue';

import ProjectLandingIndex from '@/views/pages/project/landing/Index.vue';
import ProjectLandingEdit from '@/views/pages/project/landing/Edit.vue';

import CategoryIndex from '@/views/pages/project/category/Index.vue';
import CategoryCreate from '@/views/pages/project/category/partials/Create.vue';
import CategoryEdit from '@/views/pages/project/category/partials/Edit.vue';

import StateIndex from '@/views/pages/project/state/Index.vue';
import StateCreate from '@/views/pages/project/state/partials/Create.vue';
import StateEdit from '@/views/pages/project/state/partials/Edit.vue';

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
    name: 'project-landing-index',
    path: '/administration/project/landing',
    component: ProjectLandingIndex,
  },
  {
    name: 'project-landing-category-edit',
    path: '/administration/project/landing/edit/:id/:view?/:title?',
    component: ProjectLandingEdit,
  },
  {
    name: 'project-landing-state-edit',
    path: '/administration/project/landing/edit/:id/:view?/:title?',
    component: ProjectLandingEdit,
  },
  {
    name: 'project-teaser',
    path: '/administration/project/teaser/:id',
    component: ProjectTeaser,
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

];

export default routes;