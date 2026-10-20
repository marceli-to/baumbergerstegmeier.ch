<template>
  <div>
    <LoadingIndicator v-if="isLoading" />
    <template v-if="listing.isFetched">
      <!-- No add button and no ordering (as before): the employee list
           groups by exactly these three categories -->
      <ContentHeader>
        <h1>Mitarbeiter-Kategorien</h1>
      </ContentHeader>
      <div class="listing" v-if="listing.items.length">
        <div v-for="item in listing.items" :key="item.id" :class="['listing__item', { 'is-disabled': item.publish == 0 }]">
          <div class="listing__item-body">{{ item.name }}</div>
          <ListActions :record="item" edit-route="employee-category-edit" @toggle="listing.toggle" @destroy="listing.destroy" />
        </div>
      </div>
      <p class="no-records" v-else>Es sind noch keine Daten vorhanden...</p>
    </template>
  </div>
</template>
<script setup>
import { ref, reactive } from 'vue';
import LoadingIndicator from '@/components/ui/LoadingIndicator.vue';
import ContentHeader from '@/components/ui/ContentHeader.vue';
import ListActions from '@/components/ui/ListActions.vue';
import { useListing } from '@/composables/useListing';

const isLoading = ref(false);
const listing = reactive(useListing({ list: '/api/employee/categories', resource: 'employee/category', isLoading }));
</script>
