<template>
  <div class="is-loaded">
    <page-header>
      <h1>Projekte und Einstellungen</h1>
    </page-header>
    <div class="content content--wide cards">
      <div class="card">
        <router-link :to="{name: 'project-index'}">
          <h2>Projekte</h2>
          <p>Verwaltung der Projekte</p>
        </router-link>
      </div>
      <div class="card">
        <router-link :to="{name: 'project-landing-index'}">
          <h2>Projekt Übersicht</h2>
          <p>Verwaltung der Übersichtsseiten</p>
        </router-link>
      </div>
      <div class="card">
        <router-link :to="{name: 'project-category-index'}">
          <h2>Kategorien</h2>
          <p>Verwaltung der Kategorien</p>
        </router-link>
      </div>
      <div class="card">
        <router-link :to="{name: 'project-state-index'}">
          <h2>Status</h2>
          <p>Verwaltung der Status</p>
        </router-link>
      </div>
    </div>
    <page-footer>
      <button-back :route="'dashboard'">Zurück</button-back>
    </page-footer>
  </div>
</template>
<script>

import Helpers from "@/mixins/Helpers";
import ButtonBack from "@/components/ui/ButtonBack.vue";
import PageHeader from "@/components/ui/PageHeader.vue";
import PageFooter from "@/components/ui/PageFooter.vue";

export default {

  components: {
    PageHeader,
    PageFooter,
    ButtonBack
  },
  mixins: [Helpers],
}
</script>