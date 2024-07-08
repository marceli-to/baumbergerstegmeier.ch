<template>
  <div>
    <loading-indicator v-if="isLoading"></loading-indicator>
    <div class="teaser-asset-selector" v-if="isFetched">
      <div>
        <a href="" class="btn-close" @click.prevent="$emit('close')">
          <x-icon size="24"></x-icon>
        </a>
        <template v-if="$props.type == 'home' || $props.type == 'project_landing'">
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
          <template v-else>
            Es sind keine Projekte vorhanden.
          </template>
        </template>

        <template v-if="$props.type == 'project'">
          <h1>{{ project.title }}, {{ project.location }}</h1>
          <div class="teaser-asset-selector__images">
            <figure v-for="image in project.images" :key="image.id">
              <img 
                :src="getSource(image, 'cache')" 
                height="300" 
                width="300" v-if="image"
                @click="$emit('select', {image: image.id, project: project.id})" />
            </figure>
          </div>  
        </template>

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
          getByCategory: '/api/projects/category',
          getByState: '/api/projects/state',
        },
      },
    }
  },

  props: {

    type: {
      type: String,
      default: 'home'
    },

    view: {
      type: String,
      default: 'category'
    },

    viewId: {
      type: [String, Number],
    },

    projectId: {
      type: [String, Number],
    },
  },

  mounted() {

    if (this.$props.type == 'home') {
      this.fetchProjects();
    }

    if (this.$props.type == 'project_landing') {
      this.fetchLandingProjects();
    }

    if (this.$props.type == 'project') {
      this.fetchProject();
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

    fetchProject() {
      this.isLoading = true;
      this.axios.get(`${this.routes.projects.find}/${this.$props.projectId}`).then(response => {
        this.project = response.data.project;
        this.isFetched = true;
        this.isLoading = false;
      });
    },

    fetchLandingProjects() {
      const uri = this.$props.view == 'category' ? `${this.routes.projects.getByCategory}/${this.$props.viewId}` : `${this.routes.projects.getByState}/${this.$props.viewId}`;

      this.isLoading = true;
      this.axios.get(`${uri}`).then(response => {

        this.projects = response.data.data;
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