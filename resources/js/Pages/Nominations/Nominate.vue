<script>
import Layout from "../../Shared/Layout.vue";
import { Head,Link } from '@inertiajs/inertia-vue3';
import DefaultProjectCard from "./../components/DefaultProjectCard.vue";
import Multiselect from '@vueform/multiselect';

export default {
  props: {
    errors: Object,
    nominees: Array,
    licence: Object,
    success: String,
    error: String,
  },

  data() {
    return {
      loading: false,
       
        form: {
         nomination_date: '',
         nomination_document: '',
         slug: this.licence.slug,
         people: null, 
      },
      showMenu: false,
      options: this.nominees,
    };
  },
  components: {
    DefaultProjectCard,
    Layout,
    Link,
    Head,
    Multiselect
  },

  methods: {
    submit() {
      this.$inertia.post(`/submit-nomination`, this.form)
    },
  },

  beforeUnmount() {
    this.$store.state.isAbsolute = false;
  },
};
</script>
<style src="@vueform/multiselect/themes/default.css"></style>
<style>
.columns{
  margin-bottom: 1rem;
}
</style>
<template>
<Layout>
<div class="container-fluid" >

    <div class="page-header min-height-100 border-radius-xl" style="background-image: url('https://images.unsplash.com/photo-1531512073830-ba890ca4eba2?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=crop&w=1920&q=80');
      ">
      <span class="mask bg-gradient-success opacity-6"></span>
    </div>
    <div class="card card-body mx-3 mx-md-4 mt-n6">
      <div class="row gx-4">
        <div class="col-auto">
         <div v-if="success" class="col-6" 
      x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">
      <div class="alert text-white alert-success alert-dismissible fade show font-weight-light" role="alert">
      <span class="alert-icon"><i class=""></i></span><span class="alert-text"> 
      <span class="text-sm">{{ success }}</span></span><button type="button" class="btn-close d-flex justify-content-center align-items-center" data-bs-dismiss="alert" aria-label="Close">
      <span aria-hidden="true" class="text-lg font-weight-bold">×</span>
      </button>
      </div>
      </div>

      <div v-else-if="error" class="col-6" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">
      <div class="alert text-white alert-danger alert-dismissible fade show font-weight-light" role="alert">
      <span class="alert-icon"><i class=""></i></span><span class="alert-text"> 
      <span class="text-sm">{{ error }}</span></span><button type="button" class="btn-close d-flex justify-content-center align-items-center" data-bs-dismiss="alert" aria-label="Close">
      <span aria-hidden="true" class="text-lg font-weight-bold">×</span>
      </button>
      </div>
      </div>
        </div>
        <div class="col-auto my-auto">
          <div class="h-100 d-flex">
             <h5 class="mb-1">Add Nomination for: {{ licence.trading_name }}</h5>
          </div>
        </div>
      
      </div>
      <div class="row">
    <form @submit.prevent="submit">
    <input type="hidden" v-model="form.slug">
    <div class="mt-3 row">
  <div class="row">
  <div class="col-md-6 columns">
    <div class="input-group input-group-outline null is-filled">
    <label class="form-label">Nomination Date *</label>
    <input type="date" class="form-control form-control-default" v-model="form.nomination_date">
    </div>
     <p v-if="errors.nomination_date" class="text-danger">{{ errors.nomination_date }}</p>
  </div>

<div class="col-md-6 columns">
  <div class="input-group input-group-outline null is-filled">
     <Multiselect
       v-model="form.people"
        mode="tags"
        placeholder="Search..."
        :options="options"
        :searchable="true"
      />
    </div>
    <p v-if="errors.people" class="text-danger">{{ errors.people }}</p>
</div>
 </div>
<div class="float-end">
  <button type="submit" class="btn btn-secondary ms-2 d-flex justify-content-center">Save</button>
</div>

     </div>   
</form>
        
      </div>
    </div>
  </div>

  </Layout>
</template>