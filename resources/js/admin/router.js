import { createRouter, createWebHistory } from 'vue-router';

// Forms get type 'create' or 'edit' as a prop
const create = { type: 'create' };
const edit = { type: 'edit' };

// The login is a Blade page (/login); routes/web.php serves this app for
// every /administration URL to a logged-in admin. The paths are the ones the
// Vue 2 admin had, so bookmarks keep working.
const routes = [
  // Land on the most used list
  { path: '/administration', redirect: { name: 'projects' } },

  // Home
  { name: 'home-teasers', path: '/administration/homepage/teasers', component: () => import('@/views/home/Teasers.vue') },
  { name: 'articles', path: '/administration/article/index', component: () => import('@/views/articles/Index.vue') },
  { name: 'article-create', path: '/administration/article/create', component: () => import('@/views/articles/Form.vue'), props: create },
  { name: 'article-edit', path: '/administration/article/edit/:id', component: () => import('@/views/articles/Form.vue'), props: edit },

  // Projects
  { name: 'projects', path: '/administration/project/index', component: () => import('@/views/projects/Index.vue') },
  { name: 'project-create', path: '/administration/project/create', component: () => import('@/views/projects/Form.vue'), props: create },
  { name: 'project-edit', path: '/administration/project/edit/:id', component: () => import('@/views/projects/Form.vue'), props: edit },
  { name: 'project-teasers', path: '/administration/project/teaser/:id', component: () => import('@/views/projects/Teasers.vue') },
  { name: 'landings', path: '/administration/project/landing', component: () => import('@/views/landings/Index.vue') },
  { name: 'landing-edit', path: '/administration/project/landing/edit/:id/:view(state|category)', component: () => import('@/views/landings/Edit.vue') },
  { name: 'categories', path: '/administration/project/category/index', component: () => import('@/views/categories/Index.vue') },
  { name: 'category-create', path: '/administration/project/category/create', component: () => import('@/views/categories/Form.vue'), props: create },
  { name: 'category-edit', path: '/administration/project/category/edit/:id', component: () => import('@/views/categories/Form.vue'), props: edit },
  { name: 'states', path: '/administration/project/state/index', component: () => import('@/views/states/Index.vue') },
  { name: 'state-create', path: '/administration/project/state/create', component: () => import('@/views/states/Form.vue'), props: create },
  { name: 'state-edit', path: '/administration/project/state/edit/:id', component: () => import('@/views/states/Form.vue'), props: edit },

  // Team
  { name: 'employees', path: '/administration/employee/index', component: () => import('@/views/employees/Index.vue') },
  { name: 'employee-create', path: '/administration/employee/create', component: () => import('@/views/employees/Form.vue'), props: create },
  { name: 'employee-edit', path: '/administration/employee/edit/:id', component: () => import('@/views/employees/Form.vue'), props: edit },
  { name: 'cvs', path: '/administration/team/cv/index/:id', component: () => import('@/views/cvs/Index.vue') },
  { name: 'cv-create', path: '/administration/team/cv/create/:employeeId', component: () => import('@/views/cvs/Form.vue'), props: create },
  { name: 'cv-edit', path: '/administration/team/cv/edit/:id', component: () => import('@/views/cvs/Form.vue'), props: edit },
  { name: 'teams', path: '/administration/team/index', component: () => import('@/views/teams/Index.vue') },
  { name: 'team-create', path: '/administration/team/create', component: () => import('@/views/teams/Form.vue'), props: create },
  { name: 'team-edit', path: '/administration/team/edit/:id', component: () => import('@/views/teams/Form.vue'), props: edit },
  { name: 'employee-categories', path: '/administration/team/employeeCategory/index', component: () => import('@/views/employee-categories/Index.vue') },
  { name: 'employee-category-create', path: '/administration/team/employeeCategory/create', component: () => import('@/views/employee-categories/Form.vue'), props: create },
  { name: 'employee-category-edit', path: '/administration/team/employeeCategory/edit/:id', component: () => import('@/views/employee-categories/Form.vue'), props: edit },
  { name: 'cv-categories', path: '/administration/team/cvCategory/index', component: () => import('@/views/cv-categories/Index.vue') },
  { name: 'cv-category-create', path: '/administration/team/cvCategory/create', component: () => import('@/views/cv-categories/Form.vue'), props: create },
  { name: 'cv-category-edit', path: '/administration/team/cvCategory/edit/:id', component: () => import('@/views/cv-categories/Form.vue'), props: edit },

  // Pages
  { name: 'profile', path: '/administration/profile/index', component: () => import('@/views/profile/Index.vue') },
  { name: 'profile-create', path: '/administration/profile/create', component: () => import('@/views/profile/Form.vue'), props: create },
  { name: 'profile-edit', path: '/administration/profile/edit/:id', component: () => import('@/views/profile/Form.vue'), props: edit },
  { name: 'publications', path: '/administration/publication/index', component: () => import('@/views/publications/Index.vue') },
  { name: 'publication-create', path: '/administration/publication/create', component: () => import('@/views/publications/Form.vue'), props: create },
  { name: 'publication-edit', path: '/administration/publication/edit/:id', component: () => import('@/views/publications/Form.vue'), props: edit },
  { name: 'awards', path: '/administration/award/index', component: () => import('@/views/awards/Index.vue') },
  { name: 'award-create', path: '/administration/award/create', component: () => import('@/views/awards/Form.vue'), props: create },
  { name: 'award-edit', path: '/administration/award/edit/:id', component: () => import('@/views/awards/Form.vue'), props: edit },
  { name: 'jobs', path: '/administration/job/index', component: () => import('@/views/jobs/Index.vue') },
  { name: 'job-create', path: '/administration/job/create', component: () => import('@/views/jobs/Form.vue'), props: create },
  { name: 'job-edit', path: '/administration/job/edit/:id', component: () => import('@/views/jobs/Form.vue'), props: edit },
  { name: 'job-articles', path: '/administration/job/article/index', component: () => import('@/views/job-articles/Index.vue') },
  { name: 'job-article-create', path: '/administration/job/article/create', component: () => import('@/views/job-articles/Form.vue'), props: create },
  { name: 'job-article-edit', path: '/administration/job/article/edit/:id', component: () => import('@/views/job-articles/Form.vue'), props: edit },
  { name: 'contact', path: '/administration/contact/index', component: () => import('@/views/contact/Index.vue') },
  { name: 'contact-create', path: '/administration/contact/create', component: () => import('@/views/contact/Form.vue'), props: create },
  { name: 'contact-edit', path: '/administration/contact/edit/:id', component: () => import('@/views/contact/Form.vue'), props: edit },

  { path: '/administration/:any(.*)', redirect: { name: 'projects' } },
];

export default createRouter({
  history: createWebHistory(),
  routes,
  scrollBehavior: () => ({ top: 0 }),
});
