<template>
  <div>
    <h1>Kategorie erstellen</h1>
    <form @submit.prevent="submit">
      <div :class="[this.errors.description ? 'has-error' : '', 'form-row']">
        <label>Name</label>
        <input v-model="data.description" type="text">
        <label-required />
      </div>
      <button-submit>Speichern</button-submit>
    </form>
  </div>
</template>
<script>
import ButtonSubmit from "@/components/ui/ButtonSubmit.vue";
import LabelRequired from "@/components/ui/LabelRequired.vue";

export default {

  components: {
    ButtonSubmit,
    LabelRequired
  },

  data() {
    return {
     
      data: {
        description: null,
        publish: 1,
      },

      errors: {
        description: false,
      },

      routes: {
        store: '/api/cv/category',
      },

      messages: {
        stored: 'Daten erfasst!',
      },

      isLoading: false,

    }
  },

  methods: {

    submit() {
      this.store();
    },

    store() {

      if (this.validate()) {
        this.isLoading = true;
        this.axios.post(this.routes.store, this.data).then(response => {
          this.$parent.hide();
          this.$notify({ type: "success", text: this.messages.stored });
          this.$emit('saved', response.data.cvCategory);
          this.isLoading = false;
        });
      }
    },

    validate() {
      this.errors.description = false;
      if (!this.data.description) {
        this.errors.description = true;
        this.$notify({ type: "error", text: "Bitte alle markierten Felder prüfen!" });
      }
      return !this.errors.description;
    }
  }

}
</script>