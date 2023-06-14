<template>
  <div>
    <loading-indicator v-if="isLoading"></loading-indicator>
    <div class="teaser-asset-selector" v-if="isFetched">
      <div>
        <a href="" class="btn-close" @click.prevent="$emit('close')">
          <x-icon size="24"></x-icon>
        </a>
        <template v-if="$props.type == 'home'">
          <template v-if="projects.length">
            <h1>Projekt wählen:</h1>
            <div class="select-wrapper">
              <select v-model="selectedProjectId" @change="selectProjectImages()">
                <option :value="null">Bitte wählen...</option>
                <option v-for="p in projects" :key="p.id" :value="p.id">
                  {{ p.title }}, {{ p.location }}
                </option>
              </select>
            </div>
            <div class="teaser-asset-selector__images mt-2x" v-if="selectedProjectId">
              <template v-if="selectedProject.published_images.length">
                <figure v-for="image in selectedProject.published_images" :key="image.id">
                  <img 
                    :src="getSource(image, 'cache')" 
                    height="300" 
                    width="300" v-if="image"
                    @click="$emit('select', {image: image.id, project: selectedProject.id})" />
                </figure>
              </template>
              <template v-else>
                Es sind keine Bilder für dieses Projekt vorhanden.
              </template>
            </div>        
          </template>
        </template>
        <!-- add variant for type 'project' -->
      </div>
    </div>
  </div>
</template>

<script>
import { XIcon } from 'vue-feather-icons';
import ImageUtils from "@/modules/images/mixins/utils";

export default {

  components: {
    XIcon
  },

  mixins: [ImageUtils],

  data() {
    return {
      project: null,
      projects: [],
      selectedProjectId: null,
      selectedProject: [],

      isFetched: false,
      isLoading: false,

      routes: {
        projects: {
          get: '/api/projects',
          find: '/api/project',
        },
      },
    }
  },

  props: {

    type: {
      type: String,
      default: 'home'
    }
  },

  mounted() {
    if (this.$props.type == 'home') {
      this.fetchProjects();
    }
  },

  methods: {

    fetchProjects() {
      this.isLoading = true;
      this.axios.get(`${this.routes.projects.get}`).then(response => {
        this.projects = response.data.data;
        this.isFetched = true;
        this.isLoading = false;
      });
    },

    fetchProject(id) {
      this.isLoading = true;
      this.axios.get(`${this.routes.projects.find}/${id}`).then(response => {
        this.project = response.data.project;
        this.isFetched = true;
        this.isLoading = false;
      });
    },

    selectProjectImages() {
      if (this.selectedProjectId == null) return;
      const index = this.projects.findIndex(x => x.id == this.selectedProjectId);
      this.selectedProject = this.projects[index];
    },
  }
}
</script>