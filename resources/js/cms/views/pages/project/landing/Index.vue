<template>
  <div>
    <loading-indicator v-if="isLoading"></loading-indicator>
    <div v-if="isFetched" class="is-loaded">
      <page-header>
        <h2>Projekt Kategorien</h2>
      </page-header>
      <div class="listing mb-6x" v-if="data.categories">
        <div
          :class="[d.publish == 0 ? 'is-disabled' : '', 'listing__item']"
          v-for="d in data.categories"
          :key="d.id"
          >
          <div class="listing__item-body">
            {{ d.description }}
          </div>
          <list-actions 
            :id="d.id" 
            :record="d"
            :routes="{landing: 'project-landing-category-edit', view: 'category', title: d.description}"
            :hasEdit="false"
            :hasDestroy="false"
            :hasToggle="false"
            :hasLanding="true">
          </list-actions>
        </div>
      </div>
      <div v-else>
        <p class="no-records">{{messages.emptyData}}</p>
      </div>

      <page-header>
        <h2>Projekt Status</h2>
      </page-header>
      <div class="listing mb-6x" v-if="data.states">
        <div
          :class="[d.publish == 0 ? 'is-disabled' : '', 'listing__item']"
          v-for="d in data.states"
          :key="d.id"
          >
          <div class="listing__item-body">
            {{ d.description }}
          </div>
          <list-actions 
            :id="d.id" 
            :record="d"
            :routes="{landing: 'project-landing-state-edit', view: 'state', title: d.description}"
            :hasEdit="false"
            :hasDestroy="false"
            :hasToggle="false"
            :hasLanding="true">
          </list-actions>
        </div>
      </div>
      <div v-else>
        <p class="no-records">{{messages.emptyData}}</p>
      </div>

      <page-footer>
        <button-back :route="'dashboard'">Zurück</button-back>
      </page-footer>
    </div>
  </div>
  </template>
  <script>
  import { PlusIcon, EditIcon, Trash2Icon, StarIcon } from 'vue-feather-icons';
  import ButtonBack from "@/components/ui/ButtonBack.vue";
  import Helpers from "@/mixins/Helpers";
  import ListActions from "@/components/ui/ListActions.vue";
  import Separator from "@/components/ui/Separator.vue";
  import PageFooter from "@/components/ui/PageFooter.vue";
  import PageHeader from "@/components/ui/PageHeader.vue";
  import Pill from "@/components/ui/Pill.vue";
  import draggable from 'vuedraggable';
  
  export default {
  
    components: {
      ListActions,
      Separator,
      PlusIcon,
      EditIcon,
      Trash2Icon,
      StarIcon,
      ButtonBack,
      PageFooter,
      PageHeader,
      draggable,
      Pill
    },
  
    mixins: [Helpers],
  
    data() {
      return {
  
        data: [],
  
        // Routes
        routes: {
          get: '/api/project/landing',
        },
  
        // States
        isLoading: false,
        isFetched: false,
  
        // Messages
        messages: {
          emptyData: 'Es sind noch keine Daten vorhanden...',
          confirm: 'Bitte löschen bestätigen!',
          updated: 'Daten aktualisiert',
        }
      };
    },
  
    created() {
      this.fetch();
    },
  
    methods: {
  
      fetch() {
        this.isLoading = true;
        this.axios.get(`${this.routes.get}`).then(response => {
          this.data.categories = response.data.categories;
          this.data.states = response.data.states;
          this.isLoading = false;
          this.isFetched = true;
        });
      },
    }
  }
  </script>