<template>
  <div>
    <!-- Small screens: logo and a button that opens the sidebar as a drawer -->
    <header class="page-header">
      <div>
        <router-link :to="{ name: 'projects' }" class="brand" title="Baumberger Stegmeier Architekten">
          <Logo />
        </router-link>
        <button type="button" class="sidebar-toggle" title="Menü" aria-controls="sidebar" :aria-expanded="menuVisible" @click="menuVisible = true">
          <PhList :size="24" weight="light" />
        </button>
      </div>
    </header>

    <div class="sidebar-backdrop" v-if="menuVisible" @click="menuVisible = false"></div>

    <nav id="sidebar" :class="['sidebar', { 'is-open': menuVisible }]" aria-label="Hauptmenü">
      <div class="sidebar__head">
        <router-link :to="{ name: 'projects' }" class="sidebar__brand" title="Baumberger Stegmeier Architekten">
          <Logo />
        </router-link>
        <button type="button" class="sidebar-toggle sidebar__close" title="Schliessen" @click="menuVisible = false">
          <PhX :size="24" weight="light" />
        </button>
      </div>

      <div class="sidebar__nav">
        <section v-for="group in menu" :key="group.label" class="sidebar__group">
          <h2 class="sidebar__label">{{ group.label }}</h2>
          <ul role="list">
            <li v-for="item in group.items" :key="item.route">
              <router-link :to="{ name: item.route }" :class="['sidebar__link', { 'is-active': isActive(item) }]" :aria-current="isActive(item) ? 'page' : undefined">{{ item.label }}</router-link>
            </li>
          </ul>
        </section>
      </div>

      <footer class="sidebar__user">
        <div class="sidebar__name">{{ user }}</div>
        <button type="button" class="sidebar__logout" @click="logout.submit()">
          <PhSignOut :size="16" weight="light" />
          <span>Logout</span>
        </button>
        <form method="POST" action="/logout" ref="logout" hidden>
          <input type="hidden" name="_token" :value="csrfToken">
        </form>
      </footer>
    </nav>
  </div>
</template>
<script setup>
import { ref, watch, useTemplateRef, onMounted, onBeforeUnmount } from 'vue';
import { useRoute } from 'vue-router';
import { PhList, PhX, PhSignOut } from '@phosphor-icons/vue';
import Logo from '@/components/ui/Logo.vue';
import http from '@/lib/http';

// The sidebar: always there from md up, a drawer below. Every group has a
// label; a menu item is active on its list and on the pages below it
// (forms, teasers, CVs): match is tested against the route name
const menu = [
  { label: 'Startseite', items: [
    { label: 'Teaser', route: 'home-teasers', match: /^home-/ },
    { label: 'Artikel', route: 'articles', match: /^articles?(-|$)/ },
  ] },
  { label: 'Projekte', items: [
    { label: 'Projekte', route: 'projects', match: /^projects?(-|$)/ },
    { label: 'Übersicht', route: 'landings', match: /^landings?(-|$)/ },
    { label: 'Kategorien', route: 'categories', match: /^categor(y|ies)(-|$)/ },
    { label: 'Status', route: 'states', match: /^states?(-|$)/ },
  ] },
  { label: 'Team', items: [
    { label: 'Mitarbeitende', route: 'employees', match: /^(employees|employee-(create|edit)|cvs|cv-(create|edit))$/ },
    { label: 'Teams', route: 'teams', match: /^teams?(-|$)/ },
    { label: 'Kategorien Mitarbeitende', route: 'employee-categories', match: /^employee-categor/ },
    { label: 'Kategorien CV', route: 'cv-categories', match: /^cv-categor/ },
  ] },
  { label: 'Büro', items: [
    { label: 'Profil', route: 'profile', match: /^profile(-|$)/ },
    { label: 'Publikationen', route: 'publications', match: /^publications?(-|$)/ },
    { label: 'Auszeichnungen', route: 'awards', match: /^awards?(-|$)/ },
    { label: 'Jobs', route: 'jobs', match: /^jobs?(-(create|edit)|$)/ },
    { label: 'Inserate', route: 'job-articles', match: /^job-articles?(-|$)/ },
  ] },
  { label: 'Kontakt', items: [
    { label: 'Kontakt', route: 'contact', match: /^contact(-|$)/ },
  ] },
];

const route = useRoute();
const menuVisible = ref(false);
const logout = useTemplateRef('logout');
const csrfToken = document.head.querySelector('meta[name="csrf-token"]').content;
const user = ref('');

function isActive(item) {
  return item.match.test(route.name ?? '');
}

// Close the drawer on navigation and with Escape; the page doesn't scroll
// behind it
watch(() => route.fullPath, () => menuVisible.value = false);
watch(menuVisible, open => document.documentElement.classList.toggle('has-drawer', open));

function onKeydown(event) {
  if (event.key === 'Escape') menuVisible.value = false;
}

onMounted(() => document.addEventListener('keydown', onKeydown));
onBeforeUnmount(() => document.removeEventListener('keydown', onKeydown));

http.get('/api/user')
  .then(({ data }) => user.value = `${data.firstname} ${data.name}`)
  .catch(() => {});
</script>
