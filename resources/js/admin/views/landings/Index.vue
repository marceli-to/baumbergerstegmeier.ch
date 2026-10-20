<template>
  <div>
    <LoadingIndicator v-if="isLoading" />
    <template v-if="isFetched">
      <section v-for="group in groups" :key="group.view" class="mb-6x">
        <ContentHeader>
          <h2>{{ group.label }}</h2>
        </ContentHeader>
        <div class="listing" v-if="group.items.length">
          <div v-for="item in group.items" :key="item.id" :class="['listing__item', { 'is-disabled': item.publish == 0 }]">
            <div class="listing__item-body">{{ item.description }}</div>
            <div class="listing__item-action">
              <div>
                <router-link :to="{ name: 'landing-edit', params: { id: item.id, view: group.view } }" class="feather-icon" title="Landing">
                  <PhSquaresFour :size="18" weight="light" />
                </router-link>
              </div>
            </div>
          </div>
        </div>
        <p class="no-records" v-else>Es sind noch keine Daten vorhanden...</p>
      </section>
    </template>
  </div>
</template>
<script setup>
import { ref } from 'vue';
import { PhSquaresFour } from '@phosphor-icons/vue';
import LoadingIndicator from '@/components/ui/LoadingIndicator.vue';
import ContentHeader from '@/components/ui/ContentHeader.vue';
import http from '@/lib/http';

// The categories (of projects without a landing state) and the states with
// a landing; each has its own landing page
const isLoading = ref(true);
const isFetched = ref(false);
const groups = ref([]);

http.get('/api/project/landing')
  .then(({ data }) => {
    groups.value = [
      { view: 'category', label: 'Projekt Kategorien', items: data.categories },
      { view: 'state', label: 'Projekt Status', items: data.states },
    ];
    isFetched.value = true;
  })
  .catch(() => {})
  .finally(() => isLoading.value = false);
</script>
