<template>
<div>
  <loading-indicator v-if="isLoading"></loading-indicator>
  <div v-if="isFetched" class="is-loaded">
    <page-header>
      <h2>Publikationen</h2>
      <router-link :to="{ name: 'publication-create'}" class="btn-add has-icon">
        <plus-icon size="16"></plus-icon>
        <span>Hinzufügen</span>
      </router-link>
    </page-header>
    <div class="listing is-grouped" v-if="data.length">
      <draggable 
        :disabled="false"
        v-model="data" 
        @end="order(data)"
        ghost-class="draggable-ghost"
        draggable=".listing__item"
        class="listing"
        v-if="data.length">
        <div
          :class="[d.publish == 0 ? 'is-disabled' : '', 'listing__item is-draggable']"
          v-for="d in data"
          :key="d.id"
          >
          <div class="listing__item-body">
            {{ d.year }} <separator /> {{ d.title }}
            <template v-if="d.subtitle">
              <separator /> {{ d.subtitle }}
            </template>
          </div>
          <list-actions 
            :id="d.id" 
            :record="d"
            :routes="{edit: 'publication-edit'}"
            @toggle="toggle($event)"
            @destroy="destroy($event)">
          </list-actions>
        </div>
      </draggable>
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
import { PlusIcon, EditIcon, Trash2Icon } from 'vue-feather-icons';
import ButtonBack from "@/components/ui/ButtonBack.vue";
import Helpers from "@/mixins/Helpers";
import ListActions from "@/components/ui/ListActions.vue";
import Separator from "@/components/ui/Separator.vue";
import PageFooter from "@/components/ui/PageFooter.vue";
import PageHeader from "@/components/ui/PageHeader.vue";
import draggable from 'vuedraggable';

export default {

  components: {
    ListActions,
    Separator,
    PlusIcon,
    EditIcon,
    Trash2Icon,
    ButtonBack,
    PageFooter,
    PageHeader,
    draggable
  },

  mixins: [Helpers],

  data() {
    return {

      data: [],

      // Routes
      routes: {
        get: '/api/publications',
        store: '/api/publication',
        delete: '/api/publication',
        order: '/api/publication/order',
        toggle: '/api/publication/state',
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
        this.data = response.data.data;
        this.isLoading = false;
        this.isFetched = true;
      });
    },

    toggle(id) {
      this.isLoading = true;
      this.axios.get(`${this.routes.toggle}/${id}`).then(response => {
        const index = this.data.findIndex(x => x.id === id);
        this.data[index].publish = response.data;
        this.$notify({ type: "success", text: this.messages.updated });
        this.isLoading = false;
      });
    },

    destroy(id) {
      if (confirm(this.messages.confirm)) {
        this.isLoading = true;
        this.axios.delete(`${this.routes.delete}/${id}`).then(response => {
          this.fetch();
          this.isLoading = false;
        });
      }
    },

    order() {
      let publications = this.data.map(function(publication, idx) {
        publication.order = idx;
        return publication;
      });

      if (this.debounce) return;
      this.debounce = setTimeout(function() {
        this.debounce = false 
        this.axios.post(`${this.routes.order}`, {publications: publications}).then((response) => {
          this.$notify({type: 'success', text: 'Reihenfolge angepasst'});
        });
      }.bind(this, publications), 500);
    },
  }
}
</script>