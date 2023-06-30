<template>

<div v-if="isFetched">
  <loading-indicator v-if="isLoading"></loading-indicator>

  <page-header>
    <h1 v-if="$props.type == 'home'">Startseite</h1>
    <h1 v-else>Projekt «{{ project.title }}, {{ project.location }}»</h1>
  </page-header>

  <teaser-image-selector
    :type="$props.type"
    :projectId="$props.projectId"
    @close="toggleImageSelector()"
    @select="storeImage($event)"
    v-if="hasImageSelector">
  </teaser-image-selector>
  
  <teaser-article-selector
    :type="$props.type"
    @close="toggleArticleSelector()"
    @select="storeArticle($event)"
    v-if="hasArticleSelector">
  </teaser-article-selector>  

  <div class="teasers grid-cols-12">
    <div class="span-4" v-for="index in [0,1,2]" :key="index">
      <draggable 
        :disabled="false"
        v-model="items['col-' + index]" 
        @end="order(index)"
        ghost-class="draggable-ghost"
        draggable=".is-draggable"
        v-if="items['col-' + index]">
        <div v-for="item in items['col-' + index]" :key="item.id" class="is-draggable">
          <teaser-image 
            :item="item" 
            @destroy="destroy"
            @toggle="toggle"
            v-if="item.image">
          </teaser-image>
          <teaser-article 
            :item="item" 
            @destroy="destroy"
            @toggle="toggle"
            v-if="item.article_id">
          </teaser-article>
        </div>
      </draggable>
      <div class="flex justify-start">
        <a 
          href="" 
          class="btn btn--select mr-3x" 
          @click.prevent="toggleImageSelector(index)">
          <image-icon size="16"></image-icon>
          <span v-if="$props.type == 'home'">Projekt</span>
          <span v-if="$props.type == 'project'">Bild auswählen</span>
        </a>
        <a 
          href="" 
          class="btn btn--select" 
          @click.prevent="toggleArticleSelector(index)"
          v-if="hasArticles">
          <align-left-icon size="16"></align-left-icon>
          <span>Artikel</span>
        </a>
      </div>
    </div>
  </div>
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

export default {

  components: {
    TeaserImage,
    TeaserArticle,
    TeaserImageSelector,
    TeaserArticleSelector,
    draggable,
    PageHeader,
    PlusIcon,
    ImageIcon,
    Trash2Icon,
    AlignLeftIcon,
    MoveIcon
  },

  mixins: [ErrorHandling],

  props: {
    type: {
      type: String,
      default: 'home'
    },

    projectId: {
      type: [String, Number],
    }
  },

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
        get: '/api/teasers',
        store: '/api/teaser',
        delete: '/api/teaser',
        toggle: '/api/teaser/state',
        order: '/api/teaser/order',
        project: {
          find: '/api/project',
        }
      },

      // States
      isLoading: false,
      isFetched: true,
      hasImageSelector: false,
      hasArticleSelector: false,
      hasArticles: false,

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
    this.hasArticles = this.$props.type == 'home' ? true : false;
    this.fetch();
  },

  methods: {

    fetch() {
      this.isLoading = true;
      this.isFetched = false;
      const uri = this.$props.type == 'home' ? `${this.routes.get}/${this.$props.type}` : `${this.routes.get}/${this.$props.type}/${this.$props.projectId}`;
      this.axios.get(uri).then(response => {
        this.items = response.data.items;

        // create empty columns if no data is available
        if (this.items.length == 0) {
          this.items = [[], [], []];
        }

        if (this.$props.type == 'project') {
          this.axios.get(`${this.routes.project.find}/${this.$props.projectId}`).then(response => {
            this.project = response.data.project;
            this.isLoading = false;
            this.isFetched = true;
          });
        }
        else {
          this.isLoading = false;
          this.isFetched = true;
        }
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
        project_id: data.project ? data.project : null,
        type: this.$props.type,
      }
      this.isLoading = true;
      this.axios.post(this.routes.store, item).then(response => {
        this.items[this.currentColumn].push(response.data.teaser);
        this.hideImageSelector();
        this.$notify({ type: "success", text: this.messages.stored });
        this.isLoading = false;
      });
    },

    storeArticle(data) {
      const item = {
        column: this.currentColumn,
        article_id: data.article,
        type: this.$props.type,
      }
      this.isLoading = true;
      this.axios.post(this.routes.store, item).then(response => {
        this.items[this.currentColumn].push(response.data.teaser);
        this.$notify({ type: "success", text: this.messages.stored });
        this.isLoading = false;
        this.hideArticleSelector();
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

    toggleArticleSelector(column) {
      this.currentColumn = column;
      this.hasArticleSelector = this.hasArticleSelector ? false : true;
    },

    showArticleSelector(column) {
      this.currentColumn = column;
      this.hasArticleSelector = true;
    },

    hideArticleSelector() {
      this.currentColumn = null;
      this.hasArticleSelector = false;
    },

  },

  watch: {
    grids() {
      this.gridItems = this.$props.grids;
    }
  }
}
</script>