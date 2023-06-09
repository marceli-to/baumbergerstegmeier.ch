import AwardIndex from '@/views/pages/award/Index.vue';
import AwardCreate from '@/views/pages/award/partials/Create.vue';
import AwardEdit from '@/views/pages/award/partials/Edit.vue';

const routes = [
  {
    name: 'awards-index',
    path: '/administration/award/index',
    component: AwardIndex,
  },
  {
    name: 'award-create',
    path: '/administration/award/create',
    component: AwardCreate,
  },
  {
    name: 'award-edit',
    path: '/administration/award/edit/:id',
    component: AwardEdit,
  },
];

export default routes;