<template>
  <div :class="[isOpen ? 'is-visible' : '', 'overlay overlay--widget']">
    <div class="overlay__inner px-0x">
      <a href="" @click.prevent="hide()" class="feather-icon icon-close">
        <x-icon />
      </a>
      <slot />
    </div>
  </div>
</template>
<script>
import { XIcon } from 'vue-feather-icons';

export default {

  components: {
    XIcon
  },

  data() {
    return {
      isOpen: false,
    }
  },

  created() {
    const onEscape = (e) => {
      if (this.isOpen && e.keyCode === 27) {
        this.hide();
      }
    }
    document.addEventListener('keydown', onEscape);
  },

  methods: {

    show() {
      this.isOpen = true;
    },

    hide() {
      this.isOpen = false;
    }
  }
}
</script>