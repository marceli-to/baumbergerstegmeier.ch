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
        <div :class="[this.errors.firstname ? 'has-error' : '', 'form-row']">
          <label>Vorname</label>
          <input v-model="data.firstname" type="text" name="firstname">
          <label-required />
        </div>
        <div :class="[this.errors.name ? 'has-error' : '', 'form-row']">
          <label>Name</label>
          <input v-model="data.name" type="text" name="name">
          <label-required />
        </div>
        <div class="form-row">
          <label>Titel</label>
          <input v-model="data.title" type="text" name="title">
        </div>
        <div class="form-row">
          <label>E-Mail</label>
          <input v-model="data.email" type="text" name="name">
        </div>
        <div class="form-row">
          <label>Telefon</label>
          <input v-model="data.phone" type="text" name="name">
        </div>
        <div :class="[this.errors.team_id ? 'has-error' : '', 'form-row']">
          <label>Team</label>
          <div class="select-wrapper">
            <select v-model="data.team_id">
              <option :value="null">Bitte wählen...</option>
              <option v-for="t in teams" :key="t.id" :value="t.id">{{ t.name }}</option>
            </select>
          </div>
          <label-required />
        </div>
        <div :class="[this.errors.employee_category_id ? 'has-error' : '', 'form-row mt-8x']">
          <label>Position</label>
          <div class="select-wrapper">
            <select v-model="data.employee_category_id">
              <option :value="null">Bitte wählen...</option>
              <option v-for="ec in employeeCategories" :key="ec.id" :value="ec.id">{{ ec.name }}</option>
            </select>
          </div>
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
      </div>
    </div>

    <page-footer>
      <button-back :route="'employee-index'">Zurück</button-back>
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
import tabsConfig from "@/views/pages/team/employeeCategory/config/tabs.js";
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
        firstname: null,
        name: null,
        title: null,
        email: null,
        phone: null,
        team_id: null,
        employee_category_id: null,
        publish: 1,
      },

      employeeCategories: [],
      teams: [],

      // Validation
      errors: {
        firstname: false,
        name: false,
        team_id: false,
        employee_category_id: false,
      },

      // Routes
      routes: {
        find: '/api/employee',
        store: '/api/employee',
        update: '/api/employee',

        team: {
          get: '/api/teams',
        },

        employeeCategory: {
          get: '/api/employee/categories',
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
    };
  },

  created() {
    if (this.$props.type == "edit") {
      this.fetch();
    }

    if (this.$props.type == "create") {
      this.fetchTeamsAndCategories();
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
        this.data = response.data.employee;
        this.teams = response.data.teams;
        this.employeeCategories = response.data.employeeCategories;
        this.isFetched = true;
        this.isLoading = false;
      });
    },

    fetchTeamsAndCategories() {
      this.isLoading = true;
      this.axios.all([
        this.axios.get(`${this.routes.team.get}`),
        this.axios.get(`${this.routes.employeeCategory.get}`),
      ]).then(this.axios.spread((...responses) => {
        this.teams = responses[0].data.data;
        this.employeeCategories = responses[1].data.data;
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
        this.$router.push({ name: "employee-index"});
        this.$notify({ type: "success", text: this.messages.stored });
        this.isLoading = false;
      });
    },

    update() {
      this.isLoading = true;
      this.axios.put(`${this.routes.update}/${this.$route.params.id}`, this.data).then(response => {
        this.$router.push({ name: "employee-index"});
        this.$notify({ type: "success", text: this.messages.updated });
        this.isLoading = false;
      });
    },
  },

  computed: {
    title() {
      return this.$props.type == "edit" 
        ? "Mitarbeiter bearbeiten" 
        : "Mitarbeiter hinzufügen";
    }
  }
};
</script>
