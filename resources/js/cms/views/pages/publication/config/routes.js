import PublicationIndex from '@/views/pages/publication/Index.vue';
import PublicationCreate from '@/views/pages/publication/partials/Create.vue';
import PublicationEdit from '@/views/pages/publication/partials/Edit.vue';

const routes = [
  {
    name: 'publications-index',
    path: '/administration/publication/index',
    component: PublicationIndex,
  },
  {
    name: 'publication-create',
    path: '/administration/publication/create',
    component: PublicationCreate,
  },
  {
    name: 'publication-edit',
    path: '/administration/publication/edit/:id',
    component: PublicationEdit,
  },
];

export default routes;