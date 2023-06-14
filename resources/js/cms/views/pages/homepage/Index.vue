<template>
  <div class="is-loaded">
    <page-header>
      <h1>Homepage-Teaser und Artikel</h1>
    </page-header>
    <div class="content content--wide cards">
      <!-- <div class="card">
        <router-link :to="{name: 'project-index'}">
          <h2>Projekte</h2>
          <p>Verwaltung der Projekte</p>
        </router-link>
      </div> -->
      <div class="card">
        <router-link :to="{name: 'article-index'}">
          <h2>Artikel</h2>
          <p>Verwaltung der Teaser-Artikel</p>
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