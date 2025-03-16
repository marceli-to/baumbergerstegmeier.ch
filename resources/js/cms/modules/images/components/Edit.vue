<template>
  <div>

    <!-- listing -->
    <div class="upload-listing">
      <a href="" class="icon-view" @click.prevent="toggleView()">
        <span v-if="view == 'grid'">Listen Ansicht</span>
        <span v-if="view == 'list'">Grid Ansicht</span>
      </a>
      <div class="is-list" v-if="view == 'list'">
        <template>
          <draggable 
            :disabled="false"
            v-model="imageData" 
            @end="order"
            ghost-class="draggable-ghost"
            draggable=".is-draggable">
            <div class="upload-item-row is-draggable" v-for="(image) in imageData" :key="image.id">
              <figure>
                <img :src="getSource(image, 'thumbnail')" height="300" width="300">
              </figure>
              <div>
                <span class="icon-move"></span>
              </div>
            </div>
          </draggable>
        </template>
      </div>
      <div v-if="view == 'grid'">
        <figure
          :class="[image.publish == 0 ? 'is-disabled' : '', 'upload-item']"
          v-for="image in images"
          :key="image.id"
        >
          <a :href="getSource(image, 'crop')" target="_blank" class="upload__preview">
            <img :src="getSource(image, 'thumbnail')" height="300" width="300">
          </a>
          <span class="upload-item__labels" v-if="image.cover || image.worklist">
            <pill v-if="image.cover">Cover</pill>
            <pill v-if="image.worklist">Werkliste</pill>
          </span>
          <div class="upload__actions">
            <image-actions 
              :image="image" 
              :hasPreview="false"
              :hasPreviewState="$props.hasPreviewState"
              :publish="image.publish" 
              :preview="image.preview"
              :imagePreviewRoute="'crop'">
            </image-actions>
          </div>
        </figure>
      </div>
    </div>
    <!-- // listing -->

    <!-- cropper -->
    <div :class="[hasOverlayCropper ? 'is-visible' : '', 'upload-overlay-cropper']">
      <div class="upload-overlay__loader" v-if="isLoading">Bild wird geladen...</div>
      <div class="upload-overlay__close" v-if="!isLoading">
        <a
          href="javascript:;"
          class="feather-icon icon-close-overlay"
          @click.prevent="hideCropper()"
        >
          <x-icon size="24"></x-icon>
        </a>
      </div>
      <div class="upload-overlay-cropper__wrapper" v-if="!isLoading">
        <div :class="'is-' + overlayItem.orientation">

          <div class="cropper-formats" v-if="$props.allowRatioSwitch">
            <div v-for="(format, index) in $props.ratioFormats" :key="index">
              <a 
                href="javascript:;" 
                @click.prevent="changeRatio(format.w, format.h)" 
                :class="`btn-cropper-format is-${format.w}:${format.h}`">
                {{ format.label }} {{ format.w }} x {{ format.h }}
              </a>
            </div>
            <div>
            <a 
                href="javascript:;" 
                @click.prevent="changeRatio(null, null)" 
                :class="`btn-cropper-format`" v-if="$props.allowFreeCrop">
                Frei
              </a>
            </div>
          </div>

          <div class="cropper-info">{{ cropW }} x {{ cropH }}px</div>
          <cropper
            :src="cropImage"
            :defaultPosition="defaultPosition"
            :defaultSize="defaultSize"
            :stencilProps="{
              aspectRatio: this.ratio.w/this.ratio.h,
              linesClassnames: {
                default: 'line',
              },
              handlersClassnames: {
                default: 'handler'
              }
            }"
            @change="change"
          ></cropper>
          <div class="form-buttons flex justify-between">
            <a
              href="javascript:;"
              class="btn-secondary has-icon"
              @click.prevent="hideCropper()">
              <x-icon size="18"></x-icon>
              <span>Schliessen</span>
            </a>
            <a
              href="javascript:;"
              class="btn-primary has-icon"
              @click.prevent="saveCoords(overlayItem)">
              <download-icon size="18"></download-icon>
              <span>Speichern</span>
            </a>

          </div>
        </div>
      </div>
    </div>
    <!-- // cropper -->

    <!-- edit -->
    <div :class="[hasOverlayEdit ? 'is-visible' : '', 'upload-overlay-edit']">
      <div class="upload-overlay__close">
        <a
          href="javascript:;"
          class="feather-icon icon-close-overlay"
          @click.prevent="hideEdit()"
        >
          <x-icon size="24"></x-icon>
        </a>
      </div>
      <div class="upload-overlay__grid">
        <div>
          <figure v-if="hasOverlayEdit">
            <img :src="getSource(overlayItem, 'crop')" height="300" width="300">
            <figcaption v-if="overlayItem.caption">
              <span v-if="overlayItem.caption">{{overlayItem.caption}}</span>
            </figcaption>
          </figure>
        </div>
        <div>
          <div class="form-row">
            <label>Beschreibung</label>
            <input type="text" name="caption" v-model="overlayItem.caption" />
          </div>
          <div class="form-row">
            <label>Credits</label>
            <input type="text" name="credits" v-model="overlayItem.credits" />
          </div>
          <template v-if="$props.imageStates">
            <div class="form-row" v-for="(state, index) in $props.imageStates" :key="index">
              <label class="is-sm">{{ state.label }}</label>
              <div class="form-radio">
                <input
                  v-model="overlayItem[state.key]"
                  type="radio"
                  :name="state.key"
                  :id="state.key + '_1'"
                  value="1"
                  class="visually-hidden"
                  @change="updateState($event.target.name, $event.target.value)"
                >
                <label :for="state.key + '_1'" class="form-control">Ja</label>
                <input
                  v-model="overlayItem[state.key]"
                  type="radio"
                  :name="state.key"
                  :id="state.key + '_0'"
                  value="0"
                  class="visually-hidden"
                  @change="updateState($event.target.name, $event.target.value)"
                >
                <label :for="state.key + '_0'" class="form-control">Nein</label>
              </div>
            </div>
          </template>

          <div class="form-buttons flex justify-between">
            <a
              href="javascript:;"
              class="btn-secondary has-icon"
              @click.prevent="hideEdit()">
              <x-icon size="18"></x-icon>
              <span>Schliessen</span>
            </a>
            <a
              href="javascript:;"
              class="btn-primary has-icon"
              @click.prevent="update()">
              <download-icon size="18"></download-icon>
              <span>Speichern</span>
            </a>
          </div>
        </div>
      </div>
    </div>
    <!-- // edit -->
  </div>
</template>
<script>

import draggable from 'vuedraggable';
import { Cropper } from "vue-advanced-cropper";
import { XIcon,DownloadIcon } from 'vue-feather-icons';
import ImageActions from "@/modules/images/components/Actions.vue";
import ImageEdit from "@/modules/images/mixins/edit";
import ImageCrop from "@/modules/images/mixins/crop";
import ImageUtils from "@/modules/images/mixins/utils";
import RadioButton from "@/components/ui/RadioButton.vue";
import Pill from "@/components/ui/Pill.vue";

export default {
  
  components: {
    ImageActions,
    XIcon,
    DownloadIcon,
    Cropper,
    draggable,
    RadioButton,
    Pill
  },

  mixins: [ImageUtils, ImageEdit, ImageCrop],

  props: {

    images: {
      type: Array,
      default: [],
    },

    imagePreviewRoute: {
      type: String,
      default: "cache"
    },

    ratioW: {
      type: Number,
      default: 3
    },

    ratioH: {
      type: Number,
      default: 2
    },

    allowRatioSwitch: {
      type: Boolean,
      default: true,
    },

    allowFreeCrop: {
      type: Boolean,
      default: false,
    },

    hasPreview: {
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
      default: function() {
        return [];
      }
    }
  },

  data() {
    return {

      // Data
      imageData: null,
      view: 'grid',

      ratio: {
        w: null,
        h: null
      },

      // States
      isFetched: false,
      isLoading: false,

      // Routes
      routes: {
        update: '/api/image',
        states: {
          cover: '/api/image/cover/state',
          worklist: '/api/image/worklist/state',
        }
      },

      // Messages
      messages: {
        updated: 'Daten aktualisiert!',
      },
    };
  },

  mounted() {
    this.imageData = this.$props.images;
    this.ratio.w = this.$props.ratioW;
    this.ratio.h = this.$props.ratioH;
  },

  methods: {

    changeRatio(w,h) {
      this.ratio.w = w;
      this.ratio.h = h;
      this.resetCropper();
    },

    resetCropper() {
      let image = this.overlayItem;
      this.hideCropper();
      this.showCropper(image, true)
    },

    update() {
      this.axios.put(`${this.routes.update}/${this.overlayItem.id}`, this.overlayItem).then((response) => {
        this.$notify({type: 'success', text: this.messages.updated});
        this.hideEdit();
      });
    },

    order() {
      let images = this.imageData.map(function(image, index) {
        image.order = index;
        return image;
      });

      if (this.debounce) return;

      this.debounce = setTimeout(function(images) {
        this.debounce = false;
        let uri = `/api/images/order`;
        this.axios.post(uri, {images: images}).then((response) => {
          this.$notify({type: 'success', text: 'Reihenfolge angepasst'});
        });
      }.bind(this, images), 1000);
    },

    updateState(key, value) {
      this.overlayItem[key] = value;
      this.axios.get(`${this.routes.states[key]}/${this.overlayItem.id}`).then((response) => {
        if (value == 1) {
          this.imageData.forEach((image) => {
            if (image.id != this.overlayItem.id) {
              image[key] = 0;
            }
          });
        }
      });
    },

    toggleView() {
      this.imageData = this.$props.images;
      this.view = this.view == 'grid' ? 'list' : 'grid';
    }
  }

};
</script>