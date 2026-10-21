<template>
  <AppDialog :open="isOpen" title="Link" size="md" :dismissible="false" @close="onClose()">
    <form :id="formId" class="flex flex-col gap-16" @submit.prevent="apply()">
      <FormGroup>
        <FormLabel :for="`${formId}-type`">Typ</FormLabel>
        <FormSelect :id="`${formId}-type`" v-model="link.type" :options="typeOptions" />
      </FormGroup>

      <FormGroup v-if="link.type === 'page'">
        <FormLabel :for="`${formId}-value`">Seite</FormLabel>
        <FormSelect :id="`${formId}-value`" v-model="link.value" :options="pages.map(page => ({ value: page.value, label: page.title }))" />
      </FormGroup>

      <FormGroup v-else-if="link.type === 'url'">
        <FormLabel :for="`${formId}-value`">URL</FormLabel>
        <div class="flex gap-8">
          <div class="w-110 shrink-0">
            <FormSelect :id="`${formId}-protocol`" v-model="link.protocol" :options="protocolOptions" />
          </div>
          <FormInput :id="`${formId}-value`" v-model="link.value" class="min-w-0 flex-1" placeholder="www.example.com" />
        </div>
      </FormGroup>

      <FormGroup v-else>
        <FormLabel :for="`${formId}-value`">{{ link.type === 'email' ? 'E-Mail-Adresse' : 'Telefonnummer' }}</FormLabel>
        <FormInput :id="`${formId}-value`" v-model="link.value" :placeholder="link.type === 'email' ? 'name@baumbergerstegmeier.ch' : '+41 44 000 00 00'" />
      </FormGroup>

      <FormGroup>
        <FormLabel :for="`${formId}-title`">Titel (optional)</FormLabel>
        <FormInput :id="`${formId}-title`" v-model="link.title" />
      </FormGroup>

      <div class="flex flex-col gap-12">
        <FormCheckbox v-if="link.type === 'url'" :id="`${formId}-blank`" v-model="blank">In neuem Fenster öffnen</FormCheckbox>
        <FormCheckbox :id="`${formId}-icon`" v-model="icon">Mit Icon ↗</FormCheckbox>
      </div>
    </form>
    <template #footer>
      <div class="flex items-center justify-between gap-16">
        <div>
          <FormButton v-if="isEditing" variant="secondary" @click="remove()">Entfernen</FormButton>
        </div>
        <div class="flex gap-16">
          <FormButton variant="secondary" @click="close()">Abbrechen</FormButton>
          <FormButton type="submit" :form="formId">Übernehmen</FormButton>
        </div>
      </div>
    </template>
  </AppDialog>
</template>
<script setup>
import { ref, reactive, computed } from 'vue';
import AppDialog from '@/components/ui/dialog/AppDialog.vue';
import FormGroup from '@/components/ui/form/FormGroup.vue';
import FormLabel from '@/components/ui/form/FormLabel.vue';
import FormInput from '@/components/ui/form/FormInput.vue';
import FormSelect from '@/components/ui/form/FormSelect.vue';
import FormCheckbox from '@/components/ui/form/FormCheckbox.vue';
import FormButton from '@/components/ui/form/FormButton.vue';
import http from '@/lib/http';

const props = defineProps({
  editor: { type: Object, required: true },
});

const types = { page: 'Seite', url: 'URL', email: 'E-Mail', tel: 'Telefon' };
const typeOptions = Object.entries(types).map(([value, label]) => ({ value, label }));
const protocolOptions = ['https://', 'http://'].map(value => ({ value, label: value }));

// The site's pages ({ title, value: '/werkliste' }), loaded once
const iconClass = 'icon-arrow-right-up';
const pages = ref([]);
let pagesRequest = null;
function loadPages() {
  pagesRequest ??= http.get('/api/linklist').then(({ data }) => pages.value = data).catch(() => pagesRequest = null);
  return pagesRequest;
}

const isOpen = ref(false);

// Several editors on a page: each link form needs its own id
const formId = `link-${Math.random().toString(36).slice(2)}`;
const isEditing = ref(false);
const link = reactive({ type: 'url', protocol: 'https://', value: '', title: '', blank: false, icon: false });

// Fill the form from an existing link's href. A page from the list is a
// 'page'; other relative links (/projekte/…) stay as they are: the protocol
// select is skipped for them.
function parse(href) {
  if (pages.value.some(page => page.value === href)) {
    return { type: 'page', value: href };
  }
  if (href.startsWith('mailto:')) {
    return { type: 'email', value: href.slice(7) };
  }
  if (href.startsWith('tel:')) {
    return { type: 'tel', value: href.slice(4) };
  }
  const match = href.match(/^(https?:\/\/)(.*)$/);
  return { type: 'url', protocol: match?.[1] ?? 'https://', value: match?.[2] ?? href };
}

async function open() {
  await loadPages();
  const attributes = props.editor.getAttributes('link');
  isEditing.value = !!attributes.href;
  Object.assign(link, { type: 'url', protocol: 'https://', value: '', title: '', blank: false, icon: false });

  if (attributes.href) {
    Object.assign(link, parse(attributes.href), {
      title: attributes.title ?? '',
      blank: attributes.target === '_blank',
      icon: (attributes.class ?? '').split(' ').includes(iconClass),
    });
  }

  isOpen.value = true;
}

function href() {
  const value = link.value.trim();
  if (!value) {
    return null;
  }
  switch (link.type) {
    case 'email': return `mailto:${value}`;
    case 'tel': return `tel:${value.replace(/\s+/g, '')}`;
    case 'page': return value;
    default: return /^(https?:\/\/|\/|#|\.\.?\/)/.test(value) ? value : link.protocol + value;
  }
}

function apply() {
  const url = href();
  if (!url) {
    return;
  }
  const blank = link.blank && link.type === 'url';
  props.editor.chain().focus().extendMarkRange('link').setLink({
    href: url,
    title: link.title.trim() || null,
    target: blank ? '_blank' : null,
    rel: blank ? 'noopener' : null,
    class: link.icon ? iconClass : null,
  }).run();
  close();
}

function remove() {
  props.editor.chain().focus().extendMarkRange('link').unsetLink().run();
  close();
}

function close() {
  isOpen.value = false;
}

// Also fired when the dialog is torn down with an already destroyed editor
function onClose() {
  isOpen.value = false;
  if (!props.editor.isDestroyed) {
    props.editor.commands.focus();
  }
}

// The checkboxes store 1/0
const blank = computed({ get: () => link.blank ? 1 : 0, set: value => link.blank = !!value });
const icon = computed({ get: () => link.icon ? 1 : 0, set: value => link.icon = !!value });

defineExpose({ open });
</script>
