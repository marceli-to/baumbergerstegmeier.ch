<template>
<div>
  <loading-indicator v-if="isLoading"></loading-indicator>
  <form @submit.prevent="submit" v-if="isFetched">

    <header class="content-header">
      <h1>{{title}}</h1>
    </header>
    
    <tabs :tabs="tabs" :errors="errors"></tabs>

    <div v-show="tabs.data.active">
      <div>
        <div :class="[this.errors.description ? 'has-error' : '', 'form-row']">
          <label>Bezeichnung</label>
          <input v-model="data.description" type="text" name="name">
          <label-required />
        </div>
      </div>
    </div>

    <div v-show="tabs.settings.active">
      <div>
        <div class="form-row">
          <radio-button 
            :label="'Publizieren?'"
            v-bind:publish.sync="data.publish"
            :model="data.publish"
            :name="'publish'">
          </radio-button>
        </div>
        <div class="form-row">
          <radio-button 
            :label="'Im Menu anzeigen?'"
            v-bind:show_in_menu.sync="data.show_in_menu"
            :model="data.show_in_menu"
            :name="'show_in_menu'">
          </radio-button>
        </div>
        <div class="form-row">
          <radio-button 
            :label="'Übersichtsseite?'"
            v-bind:has_landing.sync="data.has_landing"
            :model="data.has_landing"
            :name="'has_landing'">
          </radio-button>
        </div>
      </div>
    </div>

    <page-footer>
      <button-back :route="'project-state-index'">Zurück</button-back>
      <button-submit>Speichern</button-submit>
    </page-footer>
    
  </form>
</div>
</template>
<script>
import { PlusIcon } from 'vue-feather-icons';
import TinymceEditor from "@tinymce/tinymce-vue";
import tinyConfig from "@/config/tiny.js";
import ErrorHandling from "@/mixins/ErrorHandling";
import RadioButton from "@/components/ui/RadioButton.vue";
import ButtonBack from "@/components/ui/ButtonBack.vue";
import ButtonSubmit from "@/components/ui/ButtonSubmit.vue";
import LabelRequired from "@/components/ui/LabelRequired.vue";
import LabelInfo from "@/components/ui/LabelInfo.vue";
import Tabs from "@/components/ui/Tabs.vue";
import tabsConfig from "@/views/pages/team/cvCategory/config/tabs.js";
import PageFooter from "@/components/ui/PageFooter.vue";
import PageHeader from "@/components/ui/PageHeader.vue";
import Images from "@/modules/images/Index.vue";

export default {
  components: {
    PlusIcon,
    RadioButton,
    ButtonBack,
    ButtonSubmit,
    LabelRequired,
    Tabs,
    PageFooter,
    PageHeader,
    Images,
    LabelInfo,
    TinymceEditor,
  },

  mixins: [ErrorHandling],

  props: {
    type: String
  },

  data() {
    return {
      
      // Model
      data: {
        id: null,
        description: null,
        publish: 1,
        show_in_menu: 1,
        has_landing: 1,
      },

      // Validation
      errors: {
        description: false,
      },

      // Routes
      routes: {
        find: '/api/project/state',
        store: '/api/project/state',
        update: '/api/project/state',
      },

      // States
      isLoading: false,
      isFetched: true,

      // Messages
      messages: {
        emptyData: 'Es sind noch keine Daten vorhanden...',
        stored: 'Daten erfasst!',
        updated: 'Daten aktualisiert!',
      },

      // Tabs config
      tabs: tabsConfig,

      // TinyMCE
      tinyConfig: tinyConfig,
    };
  },

  created() {
    if (this.$props.type == "edit") {
      this.fetch();
    }

    this.axios.get(`/api/linklist`).then(response => {
      this.tinyConfig.link_list = response.data;
    });
  },

  methods: {

    fetch() {
      this.isFetched = false;
      this.isLoading = true;
      this.axios.get(`${this.routes.find}/${this.$route.params.id}`).then(response => {
        this.data = response.data.state;
        this.isFetched = true;
        this.isLoading = false;
      });
    },

    submit() {
      if (this.$props.type == "edit") {
        this.update();
      }
      if (this.$props.type == "create") {
        this.store();
      }
    },

    store() {
      this.isLoading = true;
      this.axios.post(this.routes.store, this.data).then(response => {
        this.$router.push({ name: "project-state-index"});
        this.$notify({ type: "success", text: this.messages.stored });
        this.isLoading = false;
      });
    },

    update() {
      this.isLoading = true;
      this.axios.put(`${this.routes.update}/${this.$route.params.id}`, this.data).then(response => {
        this.$router.push({ name: "project-state-index"});
        this.$notify({ type: "success", text: this.messages.updated });
        this.isLoading = false;
      });
    },
  },

  computed: {
    title() {
      return this.$props.type == "edit" 
        ? "Projekt-Status bearbeiten" 
        : "Projekt-Status hinzufügen";
    }
  }
};
</script>
