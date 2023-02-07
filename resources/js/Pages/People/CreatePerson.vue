<template>
<Layout>
<div class="container-fluid">
    <Banner/>
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
<input  type="checkbox" id="active-checkbox" value="1" :checked="form.active == '1'">
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
   <label class="form-label">ID/Passport Number</label>
   <input @keyup="getDateOfBirth" @paste="getDateOfBirth" required type="text" class="form-control form-control-default" v-model="form.id_or_passport">
    </div>
     <div v-if="errors.id_or_passport" class="text-danger">{{ errors.id_or_passport }}</div>
   </div>

<div class="col-md-4 columns">
   <div class="input-group input-group-outline null is-filled">
  <label class="form-label">Date of Birth</label>
  <input type="date" class="form-control form-control-default" v-model="form.date_of_birth" >
   </div>
     <div v-if="date_of_birth" class="text-danger">{{ date_of_birth }}</div>
</div>  

              
              
  <div class="col-md-4 columns">    
   <div class="input-group input-group-outline null is-filled">
  <label class="form-label">Email Address</label>
  <input type="email" class="form-control form-control-default" v-model="form.email_address_1" >
   </div>
   <div v-if="errors.email_address_1" class="text-danger">{{ errors.email_address_1 }}</div>
   </div>
                  
                 
              
 <div class="col-md-4 columns">    
   <div class="input-group input-group-outline null is-filled">
  <label class="form-label">Email Address</label>
  <input type="email" class="form-control form-control-default" v-model="form.email_address_2" >
   </div>
 <div v-if="errors.email_address_2" class="text-danger">{{ errors.email_address_2 }}</div>
 </div>
                  
 
<div class="col-md-4 columns">    
  <div class="input-group input-group-outline null is-filled">
  <label class="form-label">Phone Number</label>
  <input type="text" class="form-control form-control-default" v-model="form.cell_number" >
   </div>
   <div v-if="errors.cell_number" class="text-danger">{{ errors.cell_number }}</div>
   </div>
                  
    
   <div class="col-md-4 columns">        
  <div class="input-group input-group-outline null is-filled">
  <label class="form-label">Work Number</label>
  <input type="tel" class="form-control form-control-default" v-model="form.telephone" >
   </div>
   <div v-if="errors.telephone" class="text-danger">{{ errors.telephone }}</div>
  </div>
    

  <div><button type="submit" class="btn btn-secondary ms-2" :style="{float: 'right'}" :disabled="form.processing">
    <span v-if="form.processing" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
    Create</button></div>
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
import Banner from '../components/Banner.vue';
import { Head,useForm } from '@inertiajs/inertia-vue3';
import { ref } from 'vue';

export default {
 props: {
    errors: Object,
    error: String
  },
  setup() {
    let showMenu = ref(false);
    const form = useForm({
        name: null,
        surname: null,
        date_of_birth: '',
        id_or_passport: '',
        passport_number: null,
        email_address_1: null,
        email_address_2: null,
        cell_number: null,
        telephone: null,
        valid_certified_id: null,
        valid_saps_clearance: '',
        saps_clearance_valid_until: null,
        passport_valid_until: null,
        valid_fingerprint: null,
        fingerprint_valid_until: null,
        active: '1',
        });

        function submit() {
          form.post(`/submit-person`)
        }

        function getDateOfBirth(){//needs refactoring
          if(this.form.id_or_passport.length === 13){
            let year = this.form.id_or_passport.substring(0,2);
            let month = this.form.id_or_passport.substring(2,4);
            let day = this.form.id_or_passport.substring(4,6);
            let century = '';
            if(year > 20){
              century = '19';
            }else{
              century = '20';
            }
            this.form.date_of_birth = century + year +'-' + month +'-'+ day + '';
          }else{
            this.form.date_of_birth = ''
          }
        }
  

    return {
      showMenu,
      form,submit,
      getDateOfBirth
      
    };
  },
      

  components: {
    Layout,
    Banner
  },
  
  beforeUnmount() {
    this.$store.state.isAbsolute = false;
  },
};

</script>


