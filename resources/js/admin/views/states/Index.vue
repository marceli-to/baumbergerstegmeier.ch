<template>
  <div>
    <LoadingIndicator v-if="isLoading" />
    <template v-if="listing.isFetched">
      <ContentHeader>
        <h1>Projekt-Status</h1>
        <AddButton :to="{ name: 'state-create' }" />
      </ContentHeader>
      <SortableList v-model="listing.items" class="listing" @end="order" v-if="listing.items.length">
        <div v-for="item in listing.items" :key="item.id" :class="['listing__item is-draggable', { 'is-disabled': item.publish == 0 }]">
          <div class="listing__item-body">{{ item.description }}</div>
          <ListActions :record="item" edit-route="state-edit" @toggle="listing.toggle" @destroy="listing.destroy" />
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
import AddButton from '@/components/ui/AddButton.vue';
import ListActions from '@/components/ui/ListActions.vue';
import SortableList from '@/components/ui/SortableList.vue';
import { useListing } from '@/composables/useListing';
import { useOrder } from '@/composables/useOrder';

// Toggle: GET /api/project/state/state/{id} (resource + '/state/')
const isLoading = ref(false);
const listing = reactive(useListing({ list: '/api/project/states', resource: 'project/state', isLoading }));
const order = useOrder({ url: '/api/project/state/order', key: 'states' });
</script>
