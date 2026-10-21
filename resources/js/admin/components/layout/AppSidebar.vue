<template>
  <aside class="fixed top-0 bottom-0 left-0 z-30 flex w-240 flex-col bg-gray-100 dark:bg-warm-950">
    <div class="px-24 pt-24 pb-24 text-gray-900 dark:text-warm-100">
      <router-link :to="{ name: 'projects' }" aria-label="Baumberger Stegmeier Administration" class="block w-128">
        <Logo />
      </router-link>
    </div>

    <nav class="min-h-0 flex-1 overflow-y-auto px-16 py-8" aria-label="Hauptmenü">
      <div class="space-y-20">
        <div v-for="(group, index) in navigation" :key="index">
          <p v-if="group.title" class="mb-6 px-12 text-[0.6875rem] tracking-[0.08em] text-gray-400 uppercase dark:text-warm-500">{{ group.title }}</p>
          <ul role="list">
            <li v-for="item in group.items" :key="item.route">
              <router-link
                :to="{ name: item.route }"
                :aria-current="isActive(item) ? 'page' : undefined"
                class="flex items-center gap-12 rounded-md px-12 py-6 text-sm transition-colors duration-150 focus-visible:ring-2 focus-visible:ring-gray-200 focus-visible:outline-none dark:focus-visible:ring-warm-700"
                :class="isActive(item)
                  ? 'text-gray-900 dark:text-warm-100'
                  : 'text-gray-400 hover:text-gray-900 dark:text-warm-500 dark:hover:text-warm-100'"
              >
                <component :is="item.icon" :size="18" weight="light" />
                <span>{{ item.name }}</span>
              </router-link>
            </li>
          </ul>
        </div>
      </div>
    </nav>

    <div class="border-t border-gray-900/6 px-12 pt-8 pb-16 dark:border-warm-700/40">
      <!-- The login is a Blade page; logging out is a POST -->
      <form method="POST" action="/logout">
        <input type="hidden" name="_token" :value="csrfToken">
        <button
          type="submit"
          :title="user ? `${user} abmelden` : null"
          class="flex w-full cursor-pointer items-center gap-8 rounded-md px-12 py-10 text-xs text-gray-400 transition-colors duration-150 hover:text-gray-900 focus-visible:ring-2 focus-visible:ring-gray-200 focus-visible:outline-none dark:text-warm-500 dark:hover:text-warm-100 dark:focus-visible:ring-warm-700"
        >
          <PhSignOut :size="18" weight="light" />
          <span>Abmelden</span>
        </button>
      </form>
    </div>
  </aside>
</template>
<script setup>
import { ref } from 'vue';
import { useRoute } from 'vue-router';
import {
  PhSquaresFour, PhArticle, PhBuildings, PhLayout, PhTag, PhFlag, PhUsers, PhUsersThree,
  PhIdentificationCard, PhBooks, PhTrophy, PhBriefcase, PhMegaphone, PhAddressBook,
  PhSignOut,
} from '@phosphor-icons/vue';
import Logo from '@/components/layout/Logo.vue';
import http from '@/lib/http';

// An item is active on its list and on the pages below it (forms, teasers,
// CVs): match is tested against the route name
const navigation = [
  {
    title: 'Startseite',
    items: [
      { name: 'Teaser', route: 'home-teasers', match: /^home-/, icon: PhSquaresFour },
      { name: 'Artikel', route: 'articles', match: /^articles?(-|$)/, icon: PhArticle },
    ],
  },
  {
    title: 'Projekte',
    items: [
      { name: 'Projekte', route: 'projects', match: /^projects?(-|$)/, icon: PhBuildings },
      { name: 'Übersicht', route: 'landings', match: /^landings?(-|$)/, icon: PhLayout },
      { name: 'Kategorien', route: 'categories', match: /^categor(y|ies)(-|$)/, icon: PhTag },
      { name: 'Status', route: 'states', match: /^states?(-|$)/, icon: PhFlag },
    ],
  },
  {
    title: 'Team',
    items: [
      { name: 'Mitarbeitende', route: 'employees', match: /^(employees|employee-(create|edit)|cvs|cv-(create|edit))$/, icon: PhUsers },
      { name: 'Teams', route: 'teams', match: /^teams?(-|$)/, icon: PhUsersThree },
      { name: 'Kategorien', route: 'employee-categories', match: /^employee-categor/, icon: PhTag },
      { name: 'CV-Kategorien', route: 'cv-categories', match: /^cv-categor/, icon: PhTag },
    ],
  },
  {
    title: 'Büro',
    items: [
      { name: 'Profil', route: 'profile', match: /^profile(-|$)/, icon: PhIdentificationCard },
      { name: 'Publikationen', route: 'publications', match: /^publications?(-|$)/, icon: PhBooks },
      { name: 'Auszeichnungen', route: 'awards', match: /^awards?(-|$)/, icon: PhTrophy },
      { name: 'Jobs', route: 'jobs', match: /^jobs?(-(create|edit)|$)/, icon: PhBriefcase },
      { name: 'Inserate', route: 'job-articles', match: /^job-articles?(-|$)/, icon: PhMegaphone },
      { name: 'Kontakt', route: 'contact', match: /^contact(-|$)/, icon: PhAddressBook },
    ],
  },
];

const route = useRoute();

function isActive(item) {
  return item.match.test(String(route.name ?? ''));
}

const csrfToken = document.head.querySelector('meta[name="csrf-token"]').content;
const user = ref('');

http.get('/api/user')
  .then(({ data }) => user.value = `${data.firstname} ${data.name}`)
  .catch(() => {});
</script>
