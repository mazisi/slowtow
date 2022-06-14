<script>
import Layout from "../../Shared/Layout.vue";
import Multiselect from '@vueform/multiselect';

export default {
  name: "profile-overview",
 props: {
   errors: Object,
    licence_dropdowns: Object,
    companies: Object,
    success: String,
    error: String,
  },
  data() {
    return {
      showMenu: false,
      form: {
         trading_name: '',
         licence_type: '',
         is_licence_active: '',
         licence_number: '',
         old_licence_number: '',
         licence_date: null,
         licence_expiry_date: null,
         address: '',
         province: '',
         company: '',
    },
    options: this.companies,
    };
  },
    methods: {
      submit() {
          this.$inertia.post(`/submit-licence`, this.form)
        },
  },
  components: {
    Layout,
    Multiselect
  },
  beforeUnmount() {
    this.$store.state.isAbsolute = false;
  },
};

</script>
<style src="@vueform/multiselect/themes/default.css"></style>
<style scoped>
.columns{
  margin-bottom: 1rem;
}
#active-checkbox{
  margin-top: 3px;
  margin-left: 3px;
}
</style>

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
            <h5 class="mb-1">New Licence</h5>
          </div>
        </div>
       
      </div>
      <div class="row">
        <div class="mt-3 row">
          <div class="col-12 col-md-12 col-xl-12 position-relative">
            <div class="card card-plain h-100">
              <div class="p-3 card-body">
  <form @submit.prevent="submit">
<div class="row">
<div class="col-md-12 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<label class="form-check-label mb-0 text-body text-truncate">Active</label>
 <input id="active-checkbox" v-model="form.is_licence_active" type="checkbox">
</div>
</div>
                  
  <div class="col-md-4 columns">
    <div class="input-group input-group-outline null is-filled ">
    <label class="form-label">Trading Name *</label>
    <input type="text" required class="form-control form-control-default" v-model="form.trading_name" >
     </div>
   <div v-if="errors.trading_name" class="text-danger">{{ errors.trading_name }}</div>
   </div>
    <div class="col-md-4 columns" >    
   <div class="input-group input-group-outline null is-filled">
  <label class="form-label">Choose Company</label>
    <Multiselect
     v-model="form.company"
        placeholder="Search company"
        :options="options"
        :searchable="true"
        style="margin:top: 1rem;"
      />
   </div>
   <div v-if="errors.company" class="text-danger">{{ errors.company }}</div>
   </div>

  <div class="col-md-4 columns">
  <div class="input-group input-group-outline null is-filled">
  <label class="form-label">Licence Type *</label>
  <select v-model="form.licence_type" class="form-control form-control-default">
  <option v-for='licence_dropdown in licence_dropdowns' :value=licence_dropdown.id> {{ licence_dropdown.licence_type }}</option>
</select>
  </div>
 <div v-if="errors.licence_type" class="text-danger">{{ errors.licence_type }}</div>
</div>

 <div class="col-md-4 columns">
    <div class="input-group input-group-outline null is-filled ">
    <label class="form-label">Licence Number</label>
    <input type="text" class="form-control form-control-default" v-model="form.licence_number" >
     </div>
   <div v-if="errors.licence_number" class="text-danger">{{ errors.licence_number }}</div>
   </div>

<div class="col-md-4 columns">
   <div class="input-group input-group-outline null is-filled">
  <label class="form-label">Old Licence Number</label>
  <input type="date" class="form-control form-control-default" v-model="form.old_licence_number" >
   </div>
     <div v-if="errors.old_licence_number" class="text-danger">{{ errors.old_licence_number }}</div>
</div>  
<div class="col-md-4 columns">
<div class="input-group input-group-outline null is-filled">
  <label class="form-label">Licence Date</label>
  <input type="date" class="form-control form-control-default" v-model="form.licence_date">
</div>
<div v-if="errors.licence_date" class="text-danger">{{ errors.licence_date }}</div>
</div>

  <div class="col-md-4 columns">            
 <div class="input-group input-group-outline null is-filled">
  <label class="form-label">Licence Expiry Date</label>
  <input type="date" class="form-control form-control-default" v-model="form.licence_expiry_date">
   </div>
    <div v-if="errors.licence_expiry_date" class="text-danger">{{ errors.licence_expiry_date }}</div>
    </div>              
              
   <div class="col-md-4 columns">                  
   <div class="input-group input-group-outline null is-filled">
  <label class="form-label">Province</label>
    <select class="form-control form-control-default" v-model="form.province" >
  <option value="Eastern Cape">Eastern Cape</option>
  <option value="Free State">Free State</option>
  <option value="Gauteng">Gauteng</option>
  <option value="KwaZulu-Natal">KwaZulu-Natal</option>
  <option value="Limpopo">Limpopo</option>
  <option value="Mpumalanga">Mpumalanga</option>
  <option value="Northern Cape">Northern Cape</option>
  <option value="North West">North West</option>
  <option value="Western Cape">Western Cape</option>
</select>
   </div>
  <div v-if="errors.province" class="text-danger">{{ errors.province }}</div>
  </div>
                  
     <div class="col-md-4 columns">            
 <div class="input-group input-group-outline null is-filled">
  <label class="form-label">Address</label>
  <input required type="text" class="form-control form-control-default" v-model="form.address">
   </div>
    <div v-if="errors.address" class="text-danger">{{ errors.address }}</div>
    </div>           


  <div><button type="submit" class="btn btn-secondary ms-2" :style="{float: 'right'}">Create</button></div>
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
