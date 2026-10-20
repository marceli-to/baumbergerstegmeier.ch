<template>
  <div>
    <LoadingIndicator v-if="isLoading" />
    <template v-if="listing.isFetched">
      <ContentHeader>
        <h1>Projekte</h1>
        <AddButton :to="{ name: 'project-create' }" />
      </ContentHeader>
      <SortableList v-model="listing.items" class="listing" @end="order" v-if="listing.items.length">
        <div v-for="item in listing.items" :key="item.id" :class="['listing__item is-draggable', { 'is-disabled': item.publish == 0 }]">
          <div class="listing__item-body">
            {{ item.title }}<template v-if="item.location">, {{ item.location }}</template>
            · {{ item.year }}
            <PhStar v-if="item.feature == 1" :size="18" weight="light" class="project-feature" title="Detailseite" />
          </div>
          <div class="project-actions">
            <!-- Only projects with a detail page have a preview -->
            <div v-if="item.feature == 1" class="listing__item-action">
              <div>
                <a :href="`/projekt/vorschau/${item.id}`" target="_blank" class="feather-icon" title="Vorschau">
                  <PhArrowUpRight :size="18" weight="light" />
                </a>
              </div>
            </div>
            <ListActions :record="item" edit-route="project-edit" grid-route="project-teasers" @toggle="toggle" @destroy="listing.destroy" />
          </div>
        </div>
      </SortableList>
      <p class="no-records" v-else>Es sind noch keine Daten vorhanden...</p>
    </template>
  </div>
</template>
<script setup>
import { ref, reactive } from 'vue';
import { PhStar, PhArrowUpRight } from '@phosphor-icons/vue';
import LoadingIndicator from '@/components/ui/LoadingIndicator.vue';
import ContentHeader from '@/components/ui/ContentHeader.vue';
import AddButton from '@/components/ui/AddButton.vue';
import ListActions from '@/components/ui/ListActions.vue';
import SortableList from '@/components/ui/SortableList.vue';
import { notify } from '@/lib/notify';
import http from '@/lib/http';
import { useListing } from '@/composables/useListing';
import { useOrder } from '@/composables/useOrder';

// Sorted by year (newest first), then by the dragged order
const isLoading = ref(false);
const listing = reactive(useListing({ list: '/api/projects', resource: 'project', isLoading }));
const order = useOrder({ url: '/api/project/order', key: 'projects' });

// Projects toggle at /api/project/toggle/{id}, not the usual …/state/{id}
async function toggle(id) {
  isLoading.value = true;
  try {
    const { data } = await http.get(`/api/project/toggle/${id}`);
    listing.items.find(item => item.id === id).publish = data;
    notify({ type: 'success', text: 'Status geändert' });
  }
  catch {
    // Notified by the http error handler
  }
  finally {
    isLoading.value = false;
  }
}
</script>
