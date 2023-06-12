<template>
<div>
  <loading-indicator v-if="isLoading"></loading-indicator>
  <div v-if="isFetched" class="is-loaded">
    <page-header>
      <h2>Mitarbeiter</h2>
      <router-link :to="{ name: 'employee-create'}" class="btn-add has-icon">
        <plus-icon size="16"></plus-icon>
        <span>Hinzufügen</span>
      </router-link>
    </page-header>

    <template v-if="data.bsa_leadership.length">
      <h2 class="mb-4x">BSA Partner:in/Geschäftsleitung</h2>
      <div class="listing is-grouped">
        <draggable 
          :disabled="false"
          v-model="data.bsa_leadership" 
          @end="order('bsa_leadership')"
          ghost-class="draggable-ghost"
          draggable=".listing__item"
          class="listing"
          v-if="data.bsa_leadership.length">
          <div
            :class="[d.publish == 0 ? 'is-disabled' : '', 'listing__item is-draggable']"
            v-for="d in data.bsa_leadership"
            :key="d.id"
            >
            <div class="listing__item-body">
              {{d.firstname }} {{ d.name }} <separator /> {{ d.title }}
            </div>
            <list-actions 
              :id="d.id" 
              :record="d"
              :routes="{edit: 'employee-edit'}"
              @toggle="toggle($event)"
              @destroy="destroy($event)">
            </list-actions>
          </div>
        </draggable>
      </div>
    </template>

    <template v-if="data.bsa_employees.length">
      <h2 class="mt-8x mb-4x">BSA Mitarbeitende</h2>
      <div class="listing is-grouped">
        <div
          :class="[d.publish == 0 ? 'is-disabled' : '', 'listing__item']"
          v-for="d in data.bsa_employees"
          :key="d.id"
          >
          <div class="listing__item-body">
            {{d.firstname }} {{ d.name }} <separator /> {{ d.title }}
          </div>
          <list-actions 
            :id="d.id" 
            :record="d"
            :routes="{edit: 'employee-edit'}"
            @toggle="toggle($event)"
            @destroy="destroy($event)">
          </list-actions>
        </div>
      </div>
    </template>

    <template v-if="data.bsemi_leadership.length">
      <h2 class="mt-8x mb-4x">BS+EMI Partner:in/Geschäftsleitung</h2>
      <div class="listing is-grouped">
        <draggable 
          :disabled="false"
          v-model="data.bsemi_leadership" 
          @end="order('bsemi_leadership')"
          ghost-class="draggable-ghost"
          draggable=".listing__item"
          class="listing"
          v-if="data.bsemi_leadership.length">
          <div
            :class="[d.publish == 0 ? 'is-disabled' : '', 'listing__item is-draggable']"
            v-for="d in data.bsemi_leadership"
            :key="d.id"
            >
            <div class="listing__item-body">
              {{d.firstname }} {{ d.name }} <separator /> {{ d.title }}
            </div>
            <list-actions 
              :id="d.id" 
              :record="d"
              :routes="{edit: 'employee-edit'}"
              @toggle="toggle($event)"
              @destroy="destroy($event)">
            </list-actions>
          </div>
        </draggable>
      </div>
    </template>

    <template v-if="data.bsemi_employees.length">
      <h2 class="mt-8x mb-4x">BS+EMI Mitarbeitende</h2>
      <div class="listing is-grouped">
        <div
          :class="[d.publish == 0 ? 'is-disabled' : '', 'listing__item']"
          v-for="d in data.bsemi_employees"
          :key="d.id"
          >
          <div class="listing__item-body">
            {{d.firstname }} {{ d.name }} <separator /> {{ d.title }}
          </div>
          <list-actions 
            :id="d.id" 
            :record="d"
            :routes="{edit: 'employee-edit'}"
            @toggle="toggle($event)"
            @destroy="destroy($event)">
          </list-actions>
        </div>
      </div>
    </template>

    <template v-if="data.former_employees.length">
      <h2 class="mt-8x mb-4x">Ehemalige Mitarbeitende</h2>
      <div class="listing is-grouped">
        <div
          :class="[d.publish == 0 ? 'is-disabled' : '', 'listing__item']"
          v-for="d in data.former_employees"
          :key="d.id"
          >
          <div class="listing__item-body">
            {{d.firstname }} {{ d.name }} <separator /> {{ d.title }} <span v-if="d.team_id == 2">*</span>
          </div>
          <list-actions 
            :id="d.id" 
            :record="d"
            :routes="{edit: 'employee-edit'}"
            @toggle="toggle($event)"
            @destroy="destroy($event)">
          </list-actions>
        </div>
      </div>
    </template>

    <page-footer>
      <button-back :route="'team-dashboard'">Zurück</button-back>
    </page-footer>
  </div>
</div>
</template>
<script>
import { PlusIcon, EditIcon, Trash2Icon } from 'vue-feather-icons';
import ButtonBack from "@/components/ui/ButtonBack.vue";
import Helpers from "@/mixins/Helpers";
import ListActions from "@/components/ui/ListActions.vue";
import Separator from "@/components/ui/Separator.vue";
import PageFooter from "@/components/ui/PageFooter.vue";
import PageHeader from "@/components/ui/PageHeader.vue";
import draggable from 'vuedraggable';

export default {

  components: {
    ListActions,
    Separator,
    PlusIcon,
    EditIcon,
    Trash2Icon,
    ButtonBack,
    PageFooter,
    PageHeader,
    draggable
  },

  mixins: [Helpers],

  data() {
    return {

      data: [],

      // Routes
      routes: {
        get: '/api/employees',
        store: '/api/employee',
        delete: '/api/employee',
        order: '/api/employee/order',
        toggle: '/api/employee/state',
      },

      // States
      isLoading: false,
      isFetched: false,

      // Messages
      messages: {
        emptyData: 'Es sind noch keine Daten vorhanden...',
        confirm: 'Bitte löschen bestätigen!',
        updated: 'Daten aktualisiert',
      }
    };
  },

  created() {
    this.fetch();
  },

  methods: {

    fetch() {
      this.isLoading = true;
      this.axios.get(`${this.routes.get}`).then(response => {
        this.data = response.data;
        this.isLoading = false;
        this.isFetched = true;
      });
    },

    toggle(id) {
      this.isLoading = true;
      this.axios.get(`${this.routes.toggle}/${id}`).then(response => {
        const index = this.data.findIndex(x => x.id === id);
        this.data[index].publish = response.data;
        this.$notify({ type: "success", text: this.messages.updated });
        this.isLoading = false;
      });
    },

    destroy(id) {
      if (confirm(this.messages.confirm)) {
        this.isLoading = true;
        this.axios.delete(`${this.routes.delete}/${id}`).then(response => {
          this.fetch();
          this.isLoading = false;
        });
      }
    },

    order(key) {
      let employees = this.data[key].map(function(employee, idx) {
        employee.order = idx;
        return employee;
      });

      if (this.debounce) return;
      this.debounce = setTimeout(function() {
        this.debounce = false 
        this.axios.post(`${this.routes.order}`, {employees: employees}).then((response) => {
          this.$notify({type: 'success', text: 'Reihenfolge angepasst'});
        });
      }.bind(this, employees), 500);
    },
  }
}
</script>