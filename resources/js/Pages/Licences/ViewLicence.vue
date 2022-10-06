<template>
  <Layout>
  <div class="container-fluid">
  <div class="page-header min-height-100 border-radius-xl mt-4" style="background-image: url('https://images.unsplash.com/photo-1531512073830-ba890ca4eba2?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=crop&w=1920&q=80');
  ">
  <span class="mask bg-gradient-success opacity-6"></span>
  </div>
  <div class="card card-body mx-3 mx-md-4 mt-n6">
  <div class="row">
  <div class="col-lg-6 col-7">
  <h6 class="mb-1">View Application </h6>
  </div>
  
  </div>
  <div class="row">
  <div class="mt-3 ">
  <form class="row" @submit.prevent="submit">
  
    <div class="col-12">
  <div class="card card-plain h-100">
  <div class="p-3 card-body">
  
  <div class="row" style="margin-top: -1rem;">
  <div class="col-4 columns">
  <div class="input-group input-group-outline null is-filled ">
  <label class="form-label">Trading Name *</label>
  <input type="text" required class="form-control form-control-default" v-model="form.trading_name" >
  </div>
  <div v-if="errors.trading_name" class="text-danger">{{ errors.trading_name }}</div>
  </div>

  <div class="col-4 columns">
    <div class="input-group input-group-outline null is-filled ">
    <label class="form-label">Licence Date</label>
    <input type="text" class="form-control form-control-default" v-model="form.trading_name" >
    </div>
    <div v-if="errors.trading_name" class="text-danger">{{ errors.trading_name }}</div>
    </div>

    <div class="col-4 columns">            
      <div class="input-group input-group-outline null is-filled">
      <label class="form-label">Address Line 1</label>
      <input type="text" class="form-control form-control-default" v-model="form.address">
      </div>
      </div>
  
  <div class="col-4 columns">
    <div class="input-group input-group-outline null is-filled">
    <label class="form-label">Applicant</label>
    <select v-model="form.belongs_to" class="form-control form-control-default" required>
    <option value="Company">Company</option>
    <option value="Person">Person</option>
    </select>
    </div>
    <div v-if="errors.licence_type" class="text-danger">{{ errors.licence_type }}</div>
    </div>


    <div class="col-4 columns">
      <div class="input-group input-group-outline null is-filled ">
      <label class="form-label">Licence Number</label>
      <input type="text" class="form-control form-control-default" v-model="form.licence_number" >
      </div>
      <div v-if="errors.licence_number" class="text-danger">{{ errors.licence_number }}</div>
    </div>


    <div class="col-4 columns">            
      <div class="input-group input-group-outline null is-filled">
      <label class="form-label">Address Line 2</label>
      <input type="text" class="form-control form-control-default" v-model="form.address2">
      </div>
      <div v-if="errors.address2" class="text-danger">{{ errors.address2 }}</div>
    </div> 

    <div class="col-4 columns" v-if="form.belongs_to ==='Person'">
      <div class="input-group input-group-outline null is-filled">
      <label class="form-label">ID Number</label>
      <input type="text" class="form-control form-control-default" v-model="form.id_number" >
      </div>
      <div v-if="errors.id_number" class="text-danger">{{ errors.id_number }}</div>
    </div>

      <div class="col-4 columns" v-if="form.belongs_to === 'Company'">
        <div class="input-group input-group-outline null is-filled">
        <label class="form-label">Reg Number</label>
        <input type="text" class="form-control form-control-default" v-model="form.reg_number" >
        </div>
        <div v-if="errors.reg_number" class="text-danger">{{ errors.reg_number }}</div>
      </div>

      

  


   <div class="col-4 columns">            
    <div class="input-group input-group-outline null is-filled">
    <label class="form-label">Latest Renewal</label>
    <input type="text" class="form-control form-control-default" v-model="form.latest_renewal">
    </div>
    <div v-if="errors.latest_renewal" class="text-danger">{{ errors.latest_renewal }}</div>
  </div> 


  <div class="col-4 columns">            
    <div class="input-group input-group-outline null is-filled">
    <label class="form-label">Address Line 3</label>
    <input type="text" class="form-control form-control-default" v-model="form.address3">
    </div>
    </div> 

   <div class="col-4 columns">                  
    <div class="input-group input-group-outline null is-filled">
    <label class="form-label">Licence Type</label>
    <select v-model="form.licence_type" class="form-control form-control-default" required>
      <option v-for='licence_dropdown in licence_dropdowns' :value=licence_dropdown.id> {{ licence_dropdown.licence_type }}</option>
   </select>
    </div>
    </div>

    <div class="col-4 columns"></div>

    <div class="col-4 columns">                  
      <div class="input-group input-group-outline null is-filled">
      <label class="form-label">Province</label>
      <select required class="form-control form-control-default" v-model="form.province" >
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
      </div>

    <div class="col-4 columns">                  
      <div class="input-group input-group-outline null is-filled">
      <label class="form-label">Liquor Board Region</label>
      <select required class="form-control form-control-default" v-model="form.board_region" >
      <option value="Eastern Cape">Eastern Cape</option>
      <option value="Free State">Free State</option>
      <option value="Gauteng Ekurhuleni">Gauteng Ekurhuleni</option>
      <option value="Gauteng Johannesburg">Gauteng Johannesburg</option>
      <option value="Gauteng Sedibeng">Gauteng Sedibeng</option>
      <option value="Gauteng Tshwane">Gauteng Tshwane</option>
      <option value="Gauteng West Rand">Gauteng West Rand</option>
      <option value="KwaZulu-Natal">KwaZulu-Natal</option>
      <option value="Limpopo">Limpopo</option>
      <option value="Mpumalanga">Mpumalanga</option>
      <option value="North West">North West</option>
      <option value="Northern Cape">Northern Cape</option>
      <option value="Western Cape">Western Cape</option>
      </select>
      </div>
      </div>

      <div class="col-4 columns"></div>
      <div class="col-4 columns">            
        <div class="input-group input-group-outline null is-filled">
        <label class="form-label">Postal Code</label>
        <input  type="text" class="form-control form-control-default" v-model="form.postal_code">
      </div> 
       </div>
  
  </div>
  <div>
    <button :disabled="form.processing" :style="{float: 'right'}" class="btn btn-sm btn-secondary ms-2" type="submit">
    <span v-if="form.processing" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
    <span class="visually-hidden">Loading...</span> Update</button>
  </div>
  </div>
  </div>  
 </div>
    
  </form>
  
  </div>
  
  </div>
  </div>
  </div>
  </Layout>
  </template>
  
  <style src="@vueform/multiselect/themes/default.css"></style>
  
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
  import Multiselect from '@vueform/multiselect';
  import { Head,Link,useForm } from '@inertiajs/inertia-vue3';
  
  export default {
   props: {
      errors: Object,
      licence_dropdowns: Object,
      companies: Object,
      licence: Object,
      persons: Object,
      success: String,
      error: String,
    },
    
    
    setup (props) {
      let options;     
  
      const form = useForm({
            trading_name: props.licence.trading_name,
            licence_type: props.licence.licence_type_id,
            belongs_to: props.licence.belongs_to,
            reg_number:  props.licence.reg_number,
            id_number: props.licence.id_number,
            address: props.licence.address,
            address2: props.licence.address2,
            address3: props.licence.address3,
            province: props.licence.province,
            company:  props.licence.company !== null ? props.licence.company.name : '',
            person: props.licence.people !== null ? props.licence.people.full_name : '',
            board_region: props.licence.board_region,
            licence_number: props.licence.licence_number,
            licence_date: props.licence.licence_date,
            postal_code: props.licence.postal_code,
            company_id: props.licence.company !== null ? props.licence.company.id :'',
             
      })

      function submit() {
        form.patch(`/update-new-app/${props.licence.slug}`, {
          preserveScroll: true,
        })
        
      }
      return { submit, form ,options}
    },
     components: {
      Layout,
      Link,
      Head,
      Multiselect
    },
    
  };
  </script>
  
  