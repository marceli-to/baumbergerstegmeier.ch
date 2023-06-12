<template>
  <div class="is-loaded">
    <page-header>
      <h1>Kategorien für Mitarbeiter und CV</h1>
    </page-header>
    <div class="content content--wide cards">
      <div class="card">
        <router-link :to="{name: 'employee-category-index'}">
          <h2>Mitarbeitende</h2>
          <p>Verwaltung der Mitarbeitenden-Kategorien</p>
        </router-link>
      </div>
      <div class="card">
        <router-link :to="{name: 'cv-category-index'}">
          <h2>CV</h2>
          <p>Verwaltung der CV-Kategorien</p>
        </router-link>
      </div>
    </div>
    <page-footer>
      <button-back :route="'team-dashboard'">Zurück</button-back>
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