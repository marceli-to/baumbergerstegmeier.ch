<template v-if="$props.item.image">
  <div :class="[!$props.item.publish ? 'is-hidden' : '', 'teaser__item']">
    <div class="teaser__item__actions">
      <a 
        href="" 
        class="btn btn--toggle"
        @click.prevent="$emit('toggle', $props.item.id)">
        <eye-icon size="16" v-if="$props.item.publish"></eye-icon>
        <eye-off-icon size="16" v-else></eye-off-icon>
      </a>
      <a 
        href="" 
        class="btn btn--delete"
        @click.prevent="$emit('destroy', $props.item.id)">
        <trash-2-icon size="16"></trash-2-icon>
      </a>
    </div>
    <figure>
      <img :src="getSource($props.item.image, 'cache')" height="300" width="300">
      <figcaption>
        <span>{{ $props.item.image.caption }}</span>
      </figcaption>
    </figure>
  </div>
</template>
<script>
import { Trash2Icon, EyeIcon, EyeOffIcon } from 'vue-feather-icons';
import ImageUtils from "@/modules/images/mixins/utils";
export default {

  components: {
    Trash2Icon,
    EyeIcon,
    EyeOffIcon
  },

  mixins: [
    ImageUtils
  ],

  props: {

    item: {
      type: Object,
      required: true
    },
  
  },
}
</script>