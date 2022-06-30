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
            <h5 class="mb-1">New Person</h5>
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
<div class="col-md-12 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<label class="form-check-label mb-0 text-body text-truncate">Active Person</label>
<input v-model="form.active"  type="checkbox" id="active-checkbox" value="1">
</div>
</div>
                  
  <div class="col-md-4 columns">
    <div class="input-group input-group-outline null is-filled ">
    <label class="form-label">Name *</label>
    <input type="text" required class="form-control form-control-default" v-model="form.name" >
     </div>
   <div v-if="errors.name" class="text-danger">{{ errors.name }}</div>
   </div>
  
  <div class="col-md-4 columns">
  <div class="input-group input-group-outline null is-filled">
  <label class="form-label">Surname *</label>
  <input type="text" required class="form-control form-control-default" v-model="form.surname" >
  </div>
 <div v-if="errors.surname" class="text-danger">{{ errors.surname }}</div>
</div>


<div class="col-md-4 columns">
   <div class="input-group input-group-outline null is-filled">
  <label class="form-label">Date of Birth</label>
  <input type="date" class="form-control form-control-default" v-model="form.date_of_birth" >
   </div>
     <div v-if="errors.date_of_birth" class="text-danger">{{ errors.date_of_birth }}</div>
</div>  
<div class="col-md-4 columns">
<div class="input-group input-group-outline null is-filled">
  <label class="form-label">ID or Passport</label>
  <select v-model="form.id_or_passport" required class="form-control form-control-default">
    <option value="i_d">Identity Number</option>
    <option value="passport">Passport</option>
  </select>
</div>
<div v-if="errors.id_or_passport" class="text-danger">{{ errors.id_or_passport }}</div>
</div>

  <div v-if="form.id_or_passport == 'i_d'" class="col-md-4 columns">            
 <div class="input-group input-group-outline null is-filled">
  <label class="form-label">Identity Number</label>
  <input required type="text" class="form-control form-control-default" v-model="form.id_number" placeholder="Enter ID Number">
   </div>
    <div v-if="errors.id_number" class="text-danger">{{ errors.id_number }}</div>
    </div>              
              
   <div v-if="form.id_or_passport == 'passport'" class="col-md-4 columns">                  
   <div class="input-group input-group-outline null is-filled">
  <label class="form-label">Passport Number</label>
  <input type="text" required class="form-control form-control-default" v-model="form.passport_number" placeholder="Enter Passport Number">
   </div>
  <div v-if="errors.passport_number" class="text-danger">{{ errors.passport_number }}</div>
  </div>
                  
              
  <div class="col-md-4 columns">    
   <div class="input-group input-group-outline null is-filled">
  <label class="form-label">Email Address #1</label>
  <input type="email" class="form-control form-control-default" v-model="form.email_address_1" >
   </div>
   <div v-if="errors.email_address_1" class="text-danger">{{ errors.email_address_1 }}</div>
   </div>
                  
                 
              
 <div class="col-md-4 columns">    
   <div class="input-group input-group-outline null is-filled">
  <label class="form-label">Email Address #2</label>
  <input type="email" class="form-control form-control-default" v-model="form.email_address_2" >
   </div>
 <div v-if="errors.email_address_2" class="text-danger">{{ errors.email_address_2 }}</div>
 </div>
                  
 
<div class="col-md-4 columns">    
  <div class="input-group input-group-outline null is-filled">
  <label class="form-label">Cellphone Number</label>
  <input type="text" class="form-control form-control-default" v-model="form.cell_number" >
   </div>
   <div v-if="errors.cell_number" class="text-danger">{{ errors.cell_number }}</div>
   </div>
                  
    
   <div class="col-md-4 columns">        
  <div class="input-group input-group-outline null is-filled">
  <label class="form-label">Telephone Number</label>
  <input type="tel" class="form-control form-control-default" v-model="form.telephone" >
   </div>
   <div v-if="errors.telephone" class="text-danger">{{ errors.telephone }}</div>
  </div>
     
  <div class="col-md-4 columns">        
  <div class="input-group input-group-outline null is-filled">
  <label class="form-label">Position</label>
  <input type="tel" class="form-control form-control-default" v-model="form.position" >
   </div>
   <div v-if="errors.position" class="text-danger">{{ errors.position }}</div>
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

<style scoped>
  .columns{
    margin-bottom: 1rem;
  }

  #active-checkbox{
    margin-left: 3px;
  }
</style>

<script>
import Layout from "../../Shared/Layout.vue";

export default {
 props: {
    errors: Object,
  },
  data() {
    return {
        showMenu: false,
        form: {
        name: null,
        surname: null,
        date_of_birth: null,
        id_number: null,
        passport_number: null,
        id_or_passport: null,
        email_address_1: null,
        email_address_2: null,
        cell_number: null,
        position:null,
        telephone: null,
        valid_certified_id: null,
        valid_saps_clearance: '',
        saps_clearance_valid_until: null,
        passport_valid_until: null,
        valid_fingerprint: null,
        fingerprint_valid_until: null,
        active: null,
        }
    };
  },
  methods: {
      submit() {
          this.$inertia.post(`/submit-person`, this.form)
        },
  },

  components: {
    Layout,
  },
  
  beforeUnmount() {
    this.$store.state.isAbsolute = false;
  },
};

</script>


