<template>
  <div>
    <LoadingIndicator v-if="isLoading" />
    <template v-if="listing.isFetched">
      <!-- No add button (as before): the site knows exactly these teams -->
      <ContentHeader>
        <h1>Teams</h1>
      </ContentHeader>
      <SortableList v-model="listing.items" class="listing" @end="order" v-if="listing.items.length">
        <div v-for="item in listing.items" :key="item.id" :class="['listing__item is-draggable', { 'is-disabled': item.publish == 0 }]">
          <div class="listing__item-body">{{ item.name }}</div>
          <ListActions :record="item" edit-route="team-edit" @toggle="listing.toggle" @destroy="listing.destroy" />
        </div>
      </SortableList>
      <p class="no-records" v-else>Es sind noch keine Daten vorhanden...</p>
    </template>
  </div>
</template>
<script setup>
import { ref, reactive } from 'vue';
import LoadingIndicator from '@/components/ui/LoadingIndicator.vue';
import ContentHeader from '@/components/ui/ContentHeader.vue';
import ListActions from '@/components/ui/ListActions.vue';
import SortableList from '@/components/ui/SortableList.vue';
import { useListing } from '@/composables/useListing';
import { useOrder } from '@/composables/useOrder';

const isLoading = ref(false);
const listing = reactive(useListing({ list: '/api/teams', resource: 'team', isLoading }));
const order = useOrder({ url: '/api/team/order', key: 'teams' });
</script>
