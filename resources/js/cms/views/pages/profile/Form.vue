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
        <div :class="[this.errors.title_bsa ? 'has-error' : '', 'form-row']">
          <label>Titel BSA</label>
          <input type="text" v-model="data.title_bsa">
        </div>
        <div class="form-row">
          <label>Beschreibung BSA</label>
          <tinymce-editor
            :init="tinyConfig"
            v-model="data.text_bsa"
          ></tinymce-editor>
        </div>
        <div :class="[this.errors.title_bsemi ? 'has-error' : '', 'form-row']">
          <label>Titel BS+EMI</label>
          <input type="text" v-model="data.title_bsemi">
        </div>
        <div class="form-row">
          <label>Beschreibung BS+EMI</label>
          <tinymce-editor
            :init="tinyConfig"
            v-model="data.text_bsemi"
          ></tinymce-editor>
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
      <button-back :route="'profile-index'">Zurück</button-back>
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
import Tabs from "@/components/ui/Tabs.vue";
import tabsConfig from "@/views/pages/profile/config/tabs.js";
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
    TinymceEditor
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
        title_bsa: null,
        title_bsemi: null,
        text_bsa: null,
        text_bsemi: null,
        publish: 1,
        images: [],
      },

      // Validation
      errors: {
        address: false,
      },

      // Routes
      routes: {
        find: '/api/profile',
        store: '/api/profile',
        update: '/api/profile',
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
        this.data = response.data.profile;
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
        this.$router.push({ name: "profile-index"});
        this.$notify({ type: "success", text: this.messages.stored });
        this.isLoading = false;
      });
    },

    update() {
      this.isLoading = true;
      this.axios.put(`${this.routes.update}/${this.$route.params.id}`, this.data).then(response => {
        this.$router.push({ name: "profile-index"});
        this.$notify({ type: "success", text: this.messages.updated });
        this.isLoading = false;
      });
    },
  },

  computed: {
    title() {
      return this.$props.type == "edit" 
        ? "Kontakt bearbeiten" 
        : "Kontakt hinzufügen";
    }
  }
};
</script>
