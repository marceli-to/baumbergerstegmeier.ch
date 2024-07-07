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
        <div :class="[this.errors.periode ? 'has-error' : '', 'form-row']">
          <label>Zeitraum</label>
          <input type="text" v-model="data.periode">
        </div>
        <div :class="[this.errors.description ? 'has-error' : '', 'form-row']">
          <label>Beschreibung</label>
          <textarea name="description" v-model="data.description"></textarea>
        </div>
        <div class="form-row">
          <div class="flex justify-between">
            <label>Kategorie (optional)</label>
            <a href="" @click.prevent="$refs.categoryWidget.show()">
              <plus-icon />
            </a>
          </div>
          <div class="select-wrapper">
            <select v-model="data.cv_category_id">
              <option :value="null">Bitte wählen...</option>
              <option v-for="c in categories" :key="c.id" :value="c.id">{{ c.description }}</option>
            </select>
          </div>
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
      </div>
    </div>

    <modal ref="categoryWidget">
      <category-widget @saved="addCategory" />
    </modal>

    <page-footer>
      <router-link :to="{name: 'cv-index', params: { id: this.data.employee_id }}" class="btn-secondary has-icon">
        <arrow-left-icon size="18"></arrow-left-icon>
        <span>Zurück</span>
      </router-link>
      <button-submit>Speichern</button-submit>
    </page-footer>
  </form>
</div>
</template>
<script>
import { PlusIcon, ArrowLeftIcon } from 'vue-feather-icons';
import TinymceEditor from "@tinymce/tinymce-vue";
import tinyConfig from "@/config/tiny.js";
import ErrorHandling from "@/mixins/ErrorHandling";
import RadioButton from "@/components/ui/RadioButton.vue";
import ButtonBack from "@/components/ui/ButtonBack.vue";
import ButtonSubmit from "@/components/ui/ButtonSubmit.vue";
import LabelRequired from "@/components/ui/LabelRequired.vue";
import Tabs from "@/components/ui/Tabs.vue";
import tabsConfig from "@/views/pages/team/cv/config/tabs.js";
import PageFooter from "@/components/ui/PageFooter.vue";
import PageHeader from "@/components/ui/PageHeader.vue";
import Images from "@/modules/images/Index.vue";
import CategoryWidget from "@/views/pages/team/cvCategory/components/CategoryWidget.vue";
import Modal from "@/components/ui/Modal.vue";

export default {
  components: {
    PlusIcon,
    ArrowLeftIcon,
    RadioButton,
    ButtonBack,
    ButtonSubmit,
    LabelRequired,
    Tabs,
    PageFooter,
    PageHeader,
    Images,
    TinymceEditor,
    CategoryWidget,
    Modal
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
        periode: null,
        description: null,
        cv_category_id: null,
        employee_id: null,
        publish: 1,
      },

      categories: [],

      // Validation
      errors: {
        description: false,
      },

      // Routes
      routes: {
        find: '/api/cvs',
        store: '/api/cv',
        update: '/api/cv',
        categories: {
          get: '/api/cv/categories',
        }
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
    else {
      this.fetchCategories();
    }
    this.data.employee_id = this.$route.params.employee_id;
    
    this.axios.get(`/api/linklist`).then(response => {
      this.tinyConfig.link_list = response.data;
    });
  },

  methods: {

    fetch() {
      this.isFetched = false;
      this.isLoading = true;
      this.axios.get(`${this.routes.find}/${this.$route.params.id}`).then(response => {
        this.data = response.data.cv;
        this.categories = response.data.categories;
        this.isFetched = true;
        this.isLoading = false;
      });
    },

    fetchCategories() {
      this.isFetched = false;
      this.isLoading = true;
      this.axios.get(`${this.routes.categories.get}`).then(response => {
        this.categories = response.data.data;
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
        this.$router.push({ name: "cv-index", params: { id: this.data.employee_id }});
        this.$notify({ type: "success", text: this.messages.stored });
        this.isLoading = false;
      });
    },

    update() {
      this.isLoading = true;
      this.axios.put(`${this.routes.update}/${this.$route.params.id}`, this.data).then(response => {
        this.$router.push({ name: "cv-index", params: { id: this.data.employee_id }});
        this.$notify({ type: "success", text: this.messages.updated });
        this.isLoading = false;
      });
    },

    addCategory(category) {
      this.categories.push(category);
      this.data.cv_category_id = category.id;
    },
  },

  computed: {
    title() {
      return this.$props.type == "edit" 
        ? "CV bearbeiten" 
        : "CV hinzufügen";
    }
  }
};
</script>
