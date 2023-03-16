<template>
  <Layout>
  <div class="container-fluid">
  <Banner/>
  <div class="card card-body mx-3 mx-md-4 mt-n6">
  <div class="row">
  <div class="col-lg-6 col-7">
  <h6 class="mb-1 ml-4">Process New Application </h6>
  </div>  
  </div>
  <div class="row">
  <div class="mt-3 ">
  <form class="row" @submit.prevent="submit">
  
    <div class="col-6">
  <div class="card card-plain h-100">
  <div class="p-3 card-body">
  
  <div class="row" style="margin-top: -1rem;">
  <div class="col-12 columns">
  <div class="input-group input-group-outline null is-filled ">
  <label class="form-label">Trading Name *</label>
  <input type="text" required class="form-control form-control-default" v-model="form.trading_name" >
  </div>
  <div v-if="errors.trading_name" class="text-danger">{{ errors.trading_name }}</div>
  </div>
  
  <div class="col-12 columns">
    <div class="input-group input-group-outline null is-filled">
    <label class="form-label">Applicant</label>
    <select v-model="form.belongs_to" class="form-control form-control-default" required>
    <option :value="''" disabled selected>Select Applicant</option>
    <option value="Company">Company</option>
    <option value="Person">Person</option>
    </select>
    </div>
    <div v-if="errors.belongs_to" class="text-danger">{{ errors.belongs_to }}</div>
    </div>

  

  <div class="col-12 columns" v-if="form.belongs_to ==='Company'">
   <Multiselect
       v-model="form.company"
          placeholder="Search company"
          :options="companies"
          :searchable="true"
          @select="selectApplicant"
          style="margin:top: 1rem;"
        />
  <div v-if="errors.company" class="text-danger">{{ errors.company }}</div>
  </div>

  <div class="col-12 columns" v-if="form.belongs_to ==='Person'">
    <Multiselect
        v-model="form.person"
           placeholder="Search Person"
           :options="persons"
           :searchable="true"
           @select="selectApplicant"
           style="margin:top: 1rem;"
         />
   <div v-if="errors.person" class="text-danger">{{ errors.person }}</div>
   </div>


   <div class="col-12 columns" v-if="form.belongs_to ==='Person'">
    <div class="input-group input-group-outline null is-filled">
    <label class="form-label">ID Number</label>
    <input readonly type="text" class="form-control form-control-default" :value="get_reg_num_or_id_number" >
    </div>
    </div>

    <div class="col-12 columns" v-if="form.belongs_to ==='Company'">
      <div class="input-group input-group-outline null is-filled">
      <label class="form-label">Company Registration Number</label>
      <input readonly type="text" class="form-control form-control-default" :value="get_reg_num_or_id_number" >
      </div>
      </div> 
   <div class="col-12 columns">                  
    <div class="input-group input-group-outline null is-filled">
    <label class="form-label">Licence Type</label>
    <select v-model="form.licence_type" class="form-control form-control-default" required>
      <option :value="''" disabled selected>Licence Type</option>
      <option v-for='licence_dropdown in licence_dropdowns' :value=licence_dropdown.id> {{ licence_dropdown.licence_type }}</option>
   </select>
    </div>
    </div>

    <div class="col-12 columns">                  
      <div class="input-group input-group-outline null is-filled">
      <label class="form-label">Liquor Board Region</label>
      <select required class="form-control form-control-default" v-model="form.board_region" >
        <option :value="''" disabled selected>Select Liquor Board Region</option>
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
  
</div>
  
  </div>
  
  </div>
  </div>  
  

  <div class="col-6">  
    <div class="col-12 columns">            
    <div class="input-group input-group-outline null is-filled">
    <label class="form-label">Address Line 1</label>
    <input type="text" class="form-control form-control-default" v-model="form.address">
    </div>
    </div>
    
    <div class="col-12 columns">            
    <div class="input-group input-group-outline null is-filled">
    <label class="form-label">Address Line 2</label>
    <input type="text" class="form-control form-control-default" v-model="form.address2">
    </div>
    <div v-if="errors.address2" class="text-danger">{{ errors.address2 }}</div>
    </div> 
    <div class="col-12 columns">            
    <div class="input-group input-group-outline null is-filled">
    <label class="form-label">Address Line 3</label>
    <input type="text" class="form-control form-control-default" v-model="form.address3">
    </div>
    </div> 
    
    <div class="col-12 columns">                  
    <div class="input-group input-group-outline null is-filled">
    <label class="form-label">Province</label>
    <select required class="form-control form-control-default" v-model="form.province" >
      <option :value="''" disabled selected>Select Province</option>
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
    
    <div class="col-12 columns">            
    <div class="input-group input-group-outline null is-filled">
    <label class="form-label">Postal Code</label>
    <input  type="text" class="form-control form-control-default" v-model="form.postal_code">
    </div>    
    </div>
    <div>
      <button :disabled="form.processing || filterForm.processing" :style="{float: 'right'}" class="btn btn-sm btn-secondary ms-2" type="submit">
      <span v-if="form.processing" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
      <span class="visually-hidden">Loading...</span> Submit</button>
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
  import Banner from '../components/Banner.vue';

  export default {
   props: {
      errors: Object,
      licence_dropdowns: Object,
      companies: Array,
      persons: Array,
      success: String,
      error: String,
      get_reg_num_or_id_number: String
    },
    
    
    setup (props) {
      let options;   

      const form = useForm({
            trading_name: '',
            licence_type: '',
            belongs_to: '',
            reg_number: '',
            id_number: '',
            address: '',
            address2: '',
            address3: '',
            province: '',
            company: '',
            person: '',
            board_region: ''   
      })

      const idRegForm = useForm({
            person : '',
            company: ''  
      })

    
      
      function submit() {
        form.post('/submit-new-app', {
          onSuccess: () => { 
              notify(props.success)
           },
          preserveScroll: true,
        })
        
      }

      const filterForm = useForm({
        variation: '',
        id: null,
        id: form.company ? form.company : form.person
      })

      function selectApplicant(){
          if(form.belongs_to === 'Company'){
            filterForm.variation = 'Company';
            filterForm.id = form.company;
            form.person='';
          }else if(form.belongs_to === 'Person'){
            filterForm.variation = 'Person';
            filterForm.id = form.person;
            form.company='';
          }
          
          filterForm.get(`/create-new-app?id=${filterForm.id}`, {
                      onSuccess: () => { 
                          notify(props.success)
                      },
            preserveScroll: true,
            replace: true,
            preserveState: true
            })

     }
     
     const notify = (message) => {
        toast(message, {
          autoClose: 2000,
        });
        }
      
      return { submit, form ,options, idRegForm, selectApplicant, filterForm}
      
    },
    
     components: {
      Layout,
      Link,
      Head,
      Multiselect,
      Banner
    }

  };
  </script>
  
  