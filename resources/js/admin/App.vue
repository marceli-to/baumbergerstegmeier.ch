<template>
  <AppLayout>
    <router-view :key="route.fullPath" />
  </AppLayout>
</template>
<script setup>
import { useRoute } from 'vue-router';
import AppLayout from '@/components/layout/AppLayout.vue';

// The login is a Blade page (/login); this app is the admin only
const route = useRoute();
</script>
