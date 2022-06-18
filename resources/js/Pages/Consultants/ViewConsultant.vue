<script>
import Layout from "../../Shared/Layout.vue";
import Multiselect from '@vueform/multiselect';

export default {
  name: "profile-overview",
 props: {
    errors: Object,
    consultant: Object,
    companies: Object
  },
  data() {
    return {
      showMenu: false,
      form: {
      first_name: this.consultant.first_name,
      last_name: this.consultant.last_name,
      identity_number:  this.consultant.identity_number,
      position: this.consultant.companies[0].pivot.position,
      percentage: this.consultant.companies[0].pivot.percentage,
      company: ''
     
    },
    options: this.companies,
    };
  },
    methods: {
      submit() {
          this.$inertia.patch(`/update-consultant/${this.consultant.slug}`, this.form)
        },
  },
  components: {
    Layout,
    Multiselect,
  },
  beforeUnmount() {
    this.$store.state.isAbsolute = false;
  },
};

</script>
<style scoped>
.columns{
  margin-bottom: 1rem;
}
#active-checkbox{
  margin-top: 3px;
  margin-left: 3px;
}
</style>
<style src="@vueform/multiselect/themes/default.css"></style>
<template>
<Layout>
<div class="container-fluid">
    <div class="page-header min-height-100 border-radius-xl mt-4" style="background-image: url('https://images.unsplash.com/photo-1531512073830-ba890ca4eba2?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=crop&w=1920&q=80');
      ">
      <span class="mask bg-gradient-success opacity-6"></span>
    </div>
    <div class="card card-body mx-3 mx-md-4 mt-n6">
      <div class="row ">
       
        <div class="col-auto my-auto">
          <div class="h-100">
            <h6 class="mb-1">Consultant Info: {{ consultant.first_name }} {{ consultant.last_name }}
            <span class="text-success">[Company: {{ this.consultant.companies[0].name }}]</span></h6>
          </div>
        </div>
        <div class="mx-auto mt-3 col-lg-4 col-md-6 my-sm-auto ms-sm-auto me-sm-0">
          
        </div>
      </div>
      <div class="row">
        <div class="mt-3 row">
          <div class="col-12 col-md-12 col-xl-12 position-relative">
            <div class="card card-plain h-100">
              <div class="p-3 card-body">
  <form @submit.prevent="submit">
<div class="row">         
  <div class="col-md-4 columns">
    <div class="input-group input-group-outline null is-filled ">
    <label class="form-label">First Name *</label>
    <input type="text" required class="form-control form-control-default" v-model="form.first_name" >
     </div>
   <div v-if="errors.first_name" class="text-danger">{{ errors.first_name }}</div>
   </div>
  
  <div class="col-md-4 columns">
  <div class="input-group input-group-outline null is-filled">
  <label class="form-label">Surname *</label>
  <input type="text" required class="form-control form-control-default" v-model="form.last_name" >
  </div>
 <div v-if="errors.last_name" class="text-danger">{{ errors.last_name }}</div>
</div>


<div class="col-md-4 columns">
   <div class="input-group input-group-outline null is-filled">
  <label class="form-label">ID Number</label>
  <input type="text" class="form-control form-control-default" v-model="form.identity_number" >
   </div>
     <div v-if="errors.identity_number" class="text-danger">{{ errors.identity_number }}</div>
</div>  
<div class="col-md-4 columns">
   <div class="input-group input-group-outline null is-filled">
  <label class="form-label">Position</label>
 <input v-model="form.position" class="form-control form-control-default">
  </div>
  </div>


  <div class="col-md-4 columns">
   <div class="input-group input-group-outline null is-filled">
  <label class="form-label">Percentage</label>
 <input v-model="form.percentage" type="number" class="form-control form-control-default">
  </div>
  </div>


  <div class="col-md-4 columns">
  <div class="input-group input-group-outline null is-filled">
     <Multiselect
     v-model="form.company"
        placeholder="Search Company"
        :options="options"
        :searchable="true"
        />
    </div>
 <div v-if="errors.people" class="text-danger">{{ errors.people }}</div>
</div>
<hr>
<h6 class="text-center">Documents Fields</h6>

  <div>
  <button type="submit" class="btn btn-secondary ms-2" style="float: right">Save</button>
  </div>
            </div>
            </form>
              </div>
            </div>
            <hr class="vertical dark" />
          </div>
      <!-- //tasks were here -->
        
        </div>
        
      </div>
    </div>
  </div>
  </Layout>
</template>
