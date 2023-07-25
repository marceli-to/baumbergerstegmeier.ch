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
        <div :class="[this.errors.title ? 'has-error' : '', 'form-row']">
          <label>Titel</label>
          <input type="text" v-model="data.title" />
          <label-required />
        </div>
        <div class="form-row">
          <label>Ort</label>
          <input type="text" v-model="data.location">
        </div>
        <div :class="[this.errors.year ? 'has-error' : '', 'form-row']">
          <label>Jahr</label>
          <input type="text" v-model="data.year" maxlength="4">
          <label-required />
        </div>
        <div class="form-row">
          <label>Text</label>
          <tinymce-editor
            :api-key="tinyApiKey"
            :init="tinyConfig"
            v-model="data.text"
          ></tinymce-editor>
        </div>
        <div class="form-row">
          <label>Info</label>
          <tinymce-editor
            :api-key="tinyApiKey"
            :init="tinyConfig"
            v-model="data.info"
          ></tinymce-editor>
        </div>
      </div>
    </div>

    <div v-show="tabs.worklist.active">
      <div :class="[this.errors.type ? 'has-error' : '', 'form-row mt-8x']">
        <label>Typ</label>
        <input type="text" v-model="data.type">
        <label-required />
      </div>
    </div>

    <div v-show="tabs.images.active">
      <images 
        :allowRatioSwitch="true"
        :imageRatioW="3" 
        :imageRatioH="2"
        :imageStates="[{ label: 'Werkliste', key: 'worklist'}, { label: 'Cover', key: 'cover'}]"
        :ratioFormats="[{label: 'Quer', w: 3, h: 2}, {label: 'Hoch', w: 2, h: 3}]"
        :images="data.images">
      </images>
    </div>

    <div v-show="tabs.settings.active">
      <div :class="[this.errors.category_ids ? 'has-error' : '', 'form-row']">
        <label>Kategorie</label>
        <div v-for="category in categories" :key="category.id" class="flex items-center mb-2x">
          <input type="checkbox" :id="`category-${category.id}`" :name="`category-${category.id}`" :value="category.id" v-model="data.category_ids">
          <label :for="`category-${category.id}`" class="ml-3x !mb-0">
            {{category.description}}
          </label>
        </div>
      </div>
      <div :class="[this.errors.state_ids ? 'has-error' : '', 'form-row']">
        <label>Status</label>
        <div v-for="(state, index) in states" :key="index" class="flex items-center mb-2x">
          <input type="checkbox" :id="`state-${state.id}`" :name="`state-${state.id}`" :value="state.id" v-model="data.state_ids">
          <label :for="`state-${state.id}`" class="ml-3x !mb-0">
            {{state.description}}
          </label>
        </div>
      </div>
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
          :label="'Detailseite?'"
          v-bind:feature.sync="data.feature"
          :model="data.feature"
          :name="'feature'">
        </radio-button>
      </div>
      <div class="form-row">
        <radio-button 
          :label="'Startseite?'"
          v-bind:landing.sync="data.landing"
          :model="data.landing"
          :name="'landing'">
        </radio-button>
      </div>
    </div>

    <page-footer>
      <button-back :route="'project-index'">Zurück</button-back>
      <button-submit>Speichern</button-submit>
    </page-footer>
  </form>
</div>
</template>
<script>
import { PlusIcon, Link2Icon } from 'vue-feather-icons';
import TinymceEditor from "@tinymce/tinymce-vue";
import tinyConfig from "@/config/tiny.js";
import ErrorHandling from "@/mixins/ErrorHandling";
import Helpers from "@/mixins/Helpers";
import RadioButton from "@/components/ui/RadioButton.vue";
import ButtonBack from "@/components/ui/ButtonBack.vue";
import ButtonSubmit from "@/components/ui/ButtonSubmit.vue";
import LabelRequired from "@/components/ui/LabelRequired.vue";
import LabelInfo from "@/components/ui/LabelInfo.vue";
import Tabs from "@/components/ui/Tabs.vue";
import tabsConfig from "@/views/pages/project/project/config/tabs.js";
import PageFooter from "@/components/ui/PageFooter.vue";
import PageHeader from "@/components/ui/PageHeader.vue";
import Images from "@/modules/images/Index.vue";

export default {
  components: {
    PlusIcon,
    Link2Icon,
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

  mixins: [ErrorHandling, Helpers],

  props: {
    type: String
  },

  data() {
    return {
      
      // Model
      data: {
        id: null,
        title: null,
        text: null,
        info: null,
        year: null,
        location: null,
        order: null,
        type: null,
        category_ids: [],
        state_ids: [],
        publish: 1,
        feature: 0,
        landing: 0,
        images: [],
      },

      states: [],
      categories: [],

      // Validation
      errors: {
        title: false,
        year: false,
        type: false,
      },

      // Routes
      routes: {
        find: '/api/project',
        store: '/api/project',
        update: '/api/project',
        categories: {
          get: '/api/project/categories',
        },
        states: {
          get: '/api/project/states',
        },
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
      tinyApiKey: 'vuaywur9klvlt3excnrd9xki1a5lj25v18b2j0d0nu5tbwro',
    };
  },

  created() {
    if (this.$props.type == "edit") {
      this.fetch();
    }
    if (this.$props.type == "create") {
      this.fetchSettings();
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
        this.data = response.data.project;
        this.states = response.data.states;
        this.categories = response.data.categories;
        this.isFetched = true;
        this.isLoading = false;
      });
    },

    fetchSettings() {
      this.isFetched = false;
      this.isLoading = true;
      this.axios.all([
        this.axios.get(`${this.routes.categories.get}`),
        this.axios.get(`${this.routes.states.get}`),
      ]).then(this.axios.spread((...responses) => {
        this.categories = responses[0].data.data;
        this.states = responses[1].data.data;
        this.isFetched = true;
        this.isLoading = false;
      }));
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
        this.$router.push({ name: "project-index" });
        this.$notify({ type: "success", text: this.messages.stored });
        this.isLoading = false;
      });
    },

    update() {
      this.isLoading = true;
      this.axios.put(`${this.routes.update}/${this.$route.params.id}`, this.data).then(response => {
        this.$router.push({ name: "project-index" });
        this.$notify({ type: "success", text: this.messages.updated });
        this.isLoading = false;
      });
    },
  },

  computed: {
    title() {
      return this.$props.type == "edit" 
        ? "Projekt bearbeiten" 
        : "Projekt hinzufügen";
    }
  }
};
</script>
