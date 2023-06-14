<template>
  <div>
    <teaser :type="'home'"></teaser>
    <page-footer>
      <button-back :route="'homepage-dashboard'">Zurück</button-back>
    </page-footer>
  </div>
</template>
<script>
import Teaser from "@/modules/teaser/Index.vue";
import PageFooter from "@/components/ui/PageFooter.vue";
import ButtonBack from "@/components/ui/ButtonBack.vue";

export default {

  components: {
    Teaser,
    PageFooter,
    ButtonBack
  },
};
</script>