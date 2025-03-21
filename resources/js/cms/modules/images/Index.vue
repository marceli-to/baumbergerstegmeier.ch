<template>
<div>
  <loading-indicator v-if="isLoading"></loading-indicator>
  <div v-if="isFetched" class="is-loaded">
    <div class="form-row">
      <image-upload
        :restrictions="'jpg, png | max. 16 MB'"
        :maxFiles="99"
        :maxFilesize="32"
        :acceptedFiles="'.png,.jpg'"
      ></image-upload>
    </div>
    <div class="form-row">
      <image-edit 
        :images="data"
        :imagePreviewRoute="'crop'"
        :imageStates="$props.imageStates"
        :hasPreviewState="$props.hasPreviewState"
        :ratioW="$props.imageRatioW"
        :ratioH="$props.imageRatioH"
        :allowFreeCrop="$props.allowFreeCrop"
        :allowRatioSwitch="$props.allowRatioSwitch"
        :ratioFormats="$props.ratioFormats"
      ></image-edit>
    </div>
  </div>
</div>
</template>
<script>
import Helpers from "@/mixins/Helpers";
import ImageUpload from "@/modules/images/components/Upload.vue";
import ImageEdit from "@/modules/images/components/Edit.vue";

export default {

  components: {
    ImageUpload,
    ImageEdit
  },

  mixins: [Helpers],

  props: {
    imageRatioW: {
      type: Number,
      default: 3
    },

    imageRatioH: {
      type: Number,
      default: 2
    },

    allowRatioSwitch: {
      type: Boolean,
      default: false,
    },

    allowFreeCrop: {
      type: Boolean,
      default: false,
    },

    hasPreviewState: {
      type: Boolean,
      default: false,
    },

    imageStates: {
      type: Array,
      default: function() {
        return [];
      }
    },

    ratioFormats: {
      type: Array,
      default: () => (
        [
          {label: 'Hoch', w: 3, h: 4},
          {label: 'Quer', w: 16, h: 10}
        ]
      )
    },

    typeId: null,
    type: null,
    images: null,
  },

  data() {
    return {

      // Data
      data: [],

      // Routes
      routes: {
        get: '/api/images',
        store: '/api/image',
        delete: '/api/image',
        toggle: '/api/image/state',
        togglePreview: '/api/image/preview/state',
        coords: '/api/image/coords',
      },

      // States
      isLoading: false,
      isFetched: false,

      // Messages
      messages: {
        emptyData: 'Es sind noch keine Dateien vorhanden...',
        saved: 'Datei gespeichert!',
        updated: 'Änderungen gespeichert!',
      },
    };
  },

  created() {
    if (this.$props.images) {
      this.data = this.$props.images;
      this.isFetched = true;
    }
    this.isFetched = true;
  },

  methods: {

    fetch() {
      this.axios.get(`${this.routes.get}`).then(response => {
        this.data = response.data.data;
        this.isFetched = true;
      });
    },

    store(media) {
      let image = {
        id: null,
        name: media.name,
        original_name: media.original_name,
        size: media.size,
        extension: media.extension,
        orientation: media.orientation,
        ratio: media.ratio,
        coords_w: 0,
        coords_h: 0,
        coords_x: 0,
        coords_y: 0,
        preview: 0,
        publish: 1,
        cover: 0,
        worklist: 0,
        imageable_id: this.$props.typeId,
        imageable_type: this.$props.type,
      };

      this.axios.post(`${this.routes.store}`, image).then(response => {
        this.$notify({ type: "success", text: this.messages.saved });
        image.id = response.data.imageId;
        this.data.push(image);
      });
    },

    destroyImage(image) {
      if (confirm("Bitte löschen bestätigen!")) {
        this.isLoading = true;
        this.axios.delete(`${this.routes.delete}/${image}`).then(response => {
          const index = this.data.findIndex(x => x.name === image);
          this.data.splice(index, 1);
          this.isLoading = false;
        });
      }
    },

    toggleImage(image) {
      this.isLoading = true;
      this.axios.get(`${this.routes.toggle}/${image.id}`).then(response => {
        const index = this.data.findIndex(x => x.id === image.id);
        this.data[index].publish = response.data;
        this.isLoading = false;
      });
    },

    togglePreviewState(image) {
      this.isLoading = true;
      this.axios.get(`${this.routes.togglePreview}/${image.id}`).then(response => {
        const index = this.data.findIndex(x => x.id === image.id);
        this.data[index].preview = response.data;
        this.isLoading = false;
      });
    },

    saveImageCoords(image) {
      this.isLoading = true;
      this.axios.put(`${this.routes.coords}/${image.id}`, image).then(response => {
        this.$notify({ type: "success", text: this.messages.updated });
        this.isLoading = false;
      });
    },
  }
}
</script>