<template>
  <div class="listing__item-action">

    <div v-if="hasPreview">
      <a :href="`${routes.preview}`" class="feather-icon" target="_blank">
        <arrow-up-right-icon size="18"></arrow-up-right-icon>
      </a>
    </div>

    <div v-if="hasTeasers">
      <router-link
        :to="{name: routes.teaser, params: { id: id }}"
        class="feather-icon"
      >
        <grid-icon size="18"></grid-icon>
      </router-link>
    </div>

    <div v-if="hasLanding">
      <router-link
        :to="{name: routes.landing, params: { id: id, view: routes.view, title: routes.title }}"
        class="feather-icon"
      >
        <grid-icon size="18"></grid-icon>
      </router-link>
    </div>

    <div v-if="hasList">
      <router-link
        :to="{name: routes.list, params: { id: id }}"
        class="feather-icon"
      >
        <menu-icon size="18"></menu-icon>
      </router-link>
    </div>

    <div v-if="hasDownload">
      <a
        :href="'/storage/uploads/' + record.file"
        target="_blank"
        class="feather-icon"
      >
        <download-cloud-icon size="18"></download-cloud-icon>
      </a>
    </div>

    <div v-if="hasEdit">
      <router-link
        :to="{name: routes.edit, params: { id: id }}"
        class="feather-icon"
      >
        <edit-icon size="18"></edit-icon>
      </router-link>
    </div>

    <div v-if="hasToggle">
      <a
        href="javascript:;"
        @click.prevent="$emit('toggle', id)"
      >
        <span v-if="record.publish" class="feather-icon">
          <eye-icon size="18"></eye-icon>
        </span>
        <span v-else>
          <eye-off-icon size="18" class="feather-icon"></eye-off-icon>
        </span>
      </a>
    </div>

    <div v-if="hasCopy">
      <a
        href="javascript:;"
        class="feather-icon"
        @click.prevent="$emit('copy', id)"
      >
        <copy-icon size="18"></copy-icon>
      </a>
    </div>

    <div v-if="hasDestroy">
      <a
        href="javascript:;"
        class="feather-icon"
        @click.prevent="$emit('destroy', id)"
      >
        <trash2-icon size="18"></trash2-icon>
      </a>
    </div>

  </div>
</template>
<script>

import { 
  EyeIcon,
  EyeOffIcon,
  EditIcon,
  Trash2Icon,
  CopyIcon,
  DownloadCloudIcon,
  ArrowUpRightIcon,
  SettingsIcon,
  LayersIcon,
  UserIcon,
  UsersIcon,
  DollarSignIcon,
  ClipboardIcon,
  MailIcon,
  XCircleIcon,
  GridIcon,
  MenuIcon,
}
from 'vue-feather-icons';

export default {

  components: {
    EyeIcon,
    EyeOffIcon,
    EditIcon,
    Trash2Icon,
    CopyIcon,
    DownloadCloudIcon,
    SettingsIcon,
    ArrowUpRightIcon,
    LayersIcon,
    UserIcon,
    UsersIcon,
    DollarSignIcon,
    ClipboardIcon,
    MailIcon,
    XCircleIcon,
    GridIcon,
    MenuIcon
  },

  props: {

    id: {
      type: Number,
      default: null
    },

    hasEdit: {
      type: Boolean,
      default: true
    },
    
    hasDestroy: {
      type: Boolean,
      default: true
    },
    
    hasToggle: {
      type: Boolean,
      default: true
    },

    hasCopy: {
      type: Boolean,
      default: false
    },

    hasDownload: {
      type: Boolean,
      default: false,
    },

    hasDraggable: {
      type: Boolean,
      default: false
    },

    hasTeasers: {
      type: Boolean,
      default: false
    },

    hasPreview: {
      type: Boolean,
      default: false
    },

    hasList: {
      type: Boolean,
      default: false
    },

    hasLanding: {
      type: Boolean,
      default: false
    },

    isCollapsible: {
      type: Boolean,
      default: false,
    },

    model: {
      type: String,
      default: null,
    },
    routes: Object,
    record: Object,
  },

  methods: {

  },
}
</script>

