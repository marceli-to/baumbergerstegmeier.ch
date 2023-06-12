import TeamDashboard from '@/views/pages/team/Index.vue';
import TeamSettings from '@/views/pages/team/Settings.vue';

import TeamIndex from '@/views/pages/team/team/Index.vue';
import TeamCreate from '@/views/pages/team/team/partials/Create.vue';
import TeamEdit from '@/views/pages/team/team/partials/Edit.vue';

import EmployeeIndex from '@/views/pages/team/employee/Index.vue';
import EmployeeCreate from '@/views/pages/team/employee/partials/Create.vue';
import EmployeeEdit from '@/views/pages/team/employee/partials/Edit.vue';

import EmployeeCategoryIndex from '@/views/pages/team/employeeCategory/Index.vue';
import EmployeeCategoryCreate from '@/views/pages/team/employeeCategory/partials/Create.vue';
import EmployeeCategoryEdit from '@/views/pages/team/employeeCategory/partials/Edit.vue';

import CvCategoryIndex from '@/views/pages/team/cvCategory/Index.vue';
import CvCategoryCreate from '@/views/pages/team/cvCategory/partials/Create.vue';
import CvCategoryEdit from '@/views/pages/team/cvCategory/partials/Edit.vue';

import CvIndex from '@/views/pages/team/cv/Index.vue';
import CvCreate from '@/views/pages/team/cv/partials/Create.vue';
import CvEdit from '@/views/pages/team/cv/partials/Edit.vue';

const routes = [
  {
    name: 'team-dashboard',
    path: '/administration/team/dashboard',
    component: TeamDashboard,
  },

  {
    name: 'team-settings',
    path: '/administration/team/settings',
    component: TeamSettings,
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
    name: 'employee-index',
    path: '/administration/employee/index',
    component: EmployeeIndex,
  },
  {
    name: 'employee-create',
    path: '/administration/employee/create',
    component: EmployeeCreate,
  },
  {
    name: 'employee-edit',
    path: '/administration/employee/edit/:id',
    component: EmployeeEdit,
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

  {
    name: 'cv-category-index',
    path: '/administration/team/cvCategory/index',
    component: CvCategoryIndex,
  },
  {
    name: 'cv-category-create',
    path: '/administration/team/cvCategory/create',
    component: CvCategoryCreate,
  },
  {
    name: 'cv-category-edit',
    path: '/administration/team/cvCategory/edit/:id',
    component: CvCategoryEdit,
  },

  {
    name: 'cv-index',
    path: '/administration/team/cv/index/:id',
    component: CvIndex,
  },
  {
    name: 'cv-create',
    path: '/administration/team/cv/create/:employee_id',
    component: CvCreate,
  },
  {
    name: 'cv-edit',
    path: '/administration/team/cv/edit/:id',
    component: CvEdit,
  },

];

export default routes;