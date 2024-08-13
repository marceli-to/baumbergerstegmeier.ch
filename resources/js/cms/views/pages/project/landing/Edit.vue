<template>
<div>
  <div v-if="isFetched">
    <loading-indicator v-if="isLoading"></loading-indicator>

    <page-header>
      <h1>Projekt Landing "{{ this.$route.params.title }}"</h1>
    </page-header>

    <teaser-image-selector
      :type="'project_landing'"
      :view="this.$route.params.view"
      :viewId="this.$route.params.id"
      @close="toggleImageSelector()"
      @select="storeImage($event)"
      v-if="hasImageSelector">
    </teaser-image-selector>

    <div class="teasers grid-cols-12">
      <div class="span-4" v-for="index in [0,1,2]" :key="index">
        <draggable 
          :disabled="false"
          v-model="items[index]" 
          @end="order(index)"
          ghost-class="draggable-ghost"
          draggable=".is-draggable"
          v-if="items[index]">
          <div v-for="item in items[index]" :key="item.id" class="is-draggable">
            <h2 class="mb-2x">{{ item.project.title }}</h2>
            <teaser-image 
              :item="item" 
              @destroy="destroy"
              @toggle="toggle"
              v-if="item.image">
            </teaser-image>
          </div>
        </draggable>
        <div class="flex justify-start">
          <a 
            href="" 
            class="btn btn--select mr-3x" 
            @click.prevent="toggleImageSelector(index)">
            <image-icon size="16"></image-icon>
            <span>Projekt</span>
          </a>
        </div>
      </div>
    </div>
  </div>
  <page-footer>
    <button-back :route="'project-landing-index'">Zurück</button-back>
  </page-footer>
</div>
</template>
<script>
import { PlusIcon, Trash2Icon, MoveIcon, ImageIcon, AlignLeftIcon } from 'vue-feather-icons';
import ErrorHandling from "@/mixins/ErrorHandling";
import TeaserImage from "@/modules/teaser/components/Image";
import TeaserArticle from "@/modules/teaser/components/Article";
import TeaserImageSelector from "@/modules/teaser/components/ImageSelector.vue";
import TeaserArticleSelector from "@/modules/teaser/components/ArticleSelector.vue";

import draggable from 'vuedraggable';
import PageHeader from "@/components/ui/PageHeader.vue";
import PageFooter from "@/components/ui/PageFooter.vue";
import ButtonBack from "@/components/ui/ButtonBack.vue";

export default {

  components: {
    TeaserImage,
    TeaserArticle,
    TeaserImageSelector,
    TeaserArticleSelector,
    draggable,
    PageHeader,
    PageFooter,
    ButtonBack,
    PlusIcon,
    ImageIcon,
    Trash2Icon,
    AlignLeftIcon,
    MoveIcon
  },

  mixins: [ErrorHandling],

  data() {
    return {

      items: [],
      currentColumn: null,
      project: {
        title: null,
        location: null
      },

      // Routes
      routes: {
        get: '/api/project/landing/items',
        store: '/api/project/landing/item',
        delete: '/api/project/landing/item',
        toggle: '/api/project/landing/item/state',
        order: '/api/project/landing/item/order',
      },

      // States
      isLoading: false,
      isFetched: true,
      hasImageSelector: false,

      // Messages
      messages: {
        emptyData: 'Es sind noch keine Daten vorhanden...',
        confirm: 'Bitte löschen bestätigen!',
        updated: 'Daten aktualisiert',
        stored: 'Daten gespeichert',
        deleted: 'Daten gelöscht',
      }
    }
  },

  mounted() {
    this.fetch();
  },

  methods: {

    fetch() {
      this.isLoading = true;
      this.isFetched = false;
      const view = this.$route.params.view;
      const id = this.$route.params.id;
      const uri = `${this.routes.get}/${view}/${id}`;
      this.axios.get(uri).then(response => {
        this.items = response.data.items;

        // create empty columns if no data is available
        if (this.items.length == 0) {
          this.items = [[], [], []];
        }
        this.isLoading = false;
        this.isFetched = true;

      });
    },

    destroy(id) {
      if (confirm(this.messages.confirm)) {
        this.isLoading = true;
        this.axios.delete(`${this.routes.delete}/${id}`).then(response => {
          this.items.forEach((column, index) => {
            column.forEach((item, itemIndex) => {
              if (item.id == id) {
                this.items[index].splice(itemIndex, 1);
              }
            });
          });
          this.$notify({ type: "success", text: this.messages.deleted });
          this.isLoading = false;
        });
      }
    },

    toggle(id) {
      this.isLoading = true;
      this.axios.get(`${this.routes.toggle}/${id}`).then(response => {
        this.items.forEach((column, index) => {
            column.forEach((item, itemIndex) => {
              if (item.id == id) {
                this.items[index][itemIndex].publish = response.data;
              }
            });
          });
        this.$notify({ type: "success", text: this.messages.updated });
        this.isLoading = false;
      });
    },

    order(key) {
      let items = this.items[key].map(function(item, idx) {
        console.log(idx);
        item.position = idx;
        return item;
      });

      if (this.debounce) return;
      this.debounce = setTimeout(function() {
        this.debounce = false 
        this.axios.post(`${this.routes.order}`, {items: items}).then((response) => {
          this.$notify({type: 'success', text: 'Reihenfolge angepasst'});
        });
      }.bind(this, items), 500);
    },

    storeImage(data) {
      const item = {
        column: this.currentColumn,
        image_id: data.image,
        project_id: data.project,
      }

      // add category_id if view is category
      if (this.$route.params.view == 'category') {
        item.category_id = this.$route.params.id;
      }

      // add state_id if view is state
      if (this.$route.params.view == 'state') {
        item.state_id = this.$route.params.id;
      }

      this.isLoading = true;
      this.axios.post(this.routes.store, item).then(response => {
        this.items[this.currentColumn].push(response.data.item);
        this.hideImageSelector();
        this.$notify({ type: "success", text: this.messages.stored });
        this.isLoading = false;
      });
    },

    toggleImageSelector(column) {
      this.currentColumn = column;
      this.hasImageSelector = this.hasImageSelector ? false : true;
    },

    showImageSelector(column) {
      this.currentColumn = column;
      this.hasImageSelector = true;
    },

    hideImageSelector() {
      this.currentColumn = null;
      this.hasImageSelector = false;
    },
  },

  watch: {
    grids() {
      this.gridItems = this.$props.grids;
    }
  }
}
</script>