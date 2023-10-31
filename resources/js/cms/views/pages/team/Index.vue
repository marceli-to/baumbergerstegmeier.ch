<template>
  <div class="is-loaded">
    <page-header>
      <h1>Teams und Mitarbeitende</h1>
    </page-header>
    <div class="content content--wide cards">
      <div class="card">
        <router-link :to="{name: 'employee-index'}">
          <h2>Mitarbeitende</h2>
          <p>Verwaltung der Mitarbeitenden</p>
        </router-link>
      </div>
      <div class="card">
        <router-link :to="{name: 'team-index'}">
          <h2>Teams</h2>
          <p>Verwaltung der Teams</p>
        </router-link>
      </div>
      <div class="card">
        <router-link :to="{name: 'team-settings'}">
          <h2>Einstellungen</h2>
          <p>Kategorien für Mitarbeiter und CV</p>
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