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
        <div :class="[this.errors.category ? 'has-error' : '', 'form-row']">
          <label>Kategorie</label>
          <input type="text" v-model="data.category">
          <label-required />
        </div>
        <div :class="[this.errors.title ? 'has-error' : '', 'form-row']">
          <label>Titel</label>
          <input type="text" v-model="data.title">
          <label-required />
        </div>
        <div class="form-row">
          <label>Text</label>
          <tinymce-editor
            :init="tinyConfig"
            v-model="data.text"
          ></tinymce-editor>
        </div>
        <div class="form-row">
          <label>Link</label>
          <input type="text" v-model="data.link">
        </div>
      </div>
    </div>

    <div v-show="tabs.images.active">
      <images 
        :allowRatioSwitch="true"
        :imageRatioW="3" 
        :imageRatioH="2"
        :ratioFormats="[{label: 'Quer', w: 3, h: 2}, {label: 'Hoch', w: 2, h: 3}]"
        :images="data.images">
      </images>
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

    <page-footer>
      <button-back :route="'article-index'">Zurück</button-back>
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
import tabsConfig from "@/views/pages/homepage/article/config/tabs.js";
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
        category: null,
        title: null,
        text: null,
        link: null,
        publish: 1,
      },

      // Validation
      errors: {
        category: false,
        title: false,
      },

      // Routes
      routes: {
        find: '/api/article',
        store: '/api/article',
        update: '/api/article',
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
        this.data = response.data.article;
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
        this.$router.push({ name: "article-index" });
        this.$notify({ type: "success", text: this.messages.stored });
        this.isLoading = false;
      });
    },

    update() {
      this.isLoading = true;
      this.axios.put(`${this.routes.update}/${this.$route.params.id}`, this.data).then(response => {
        this.$router.push({ name: "article-index" });
        this.$notify({ type: "success", text: this.messages.updated });
        this.isLoading = false;
      });
    },
  },

  computed: {
    title() {
      return this.$props.type == "edit" 
        ? "Artikel bearbeiten" 
        : "Artikel hinzufügen";
    }
  }
};
</script>
