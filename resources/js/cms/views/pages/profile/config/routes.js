import ProfileIndex from '@/views/pages/profile/Index.vue';
import ProfileCreate from '@/views/pages/profile/partials/Create.vue';
import ProfileEdit from '@/views/pages/profile/partials/Edit.vue';

const routes = [
  {
    name: 'profile-index',
    path: '/administration/profile/index',
    component: ProfileIndex,
  },
  {
    name: 'profile-create',
    path: '/administration/profile/create',
    component: ProfileCreate,
  },
  {
    name: 'profile-edit',
    path: '/administration/profile/edit/:id',
    component: ProfileEdit,
  },
];

export default routes;