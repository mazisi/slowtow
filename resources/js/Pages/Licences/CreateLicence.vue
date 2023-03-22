<template>
<Layout>
  <Head title="Create Licence" />
<div class="container-fluid">
<Banner/>
<div class="card card-body mx-3 mx-md-4 mt-n6">
<div class="row">
<div class="col-lg-6 col-7">
<h6 class="mb-1">New Licence </h6>
</div>

</div>
<div class="row">
<div class="mt-3 ">
<form class="row" @submit.prevent="submit">
<div class="col-8 col-md-8 col-xl-8 position-relative">
<div class="card card-plain h-100">
<div class="p-3 card-body">

<div class="row">
<div class="col-md-12 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<label class="form-check-label mb-0 text-body text-truncate">Active</label>
<input id="active-checkbox" type="checkbox" value="1" :checked="form.is_licence_active == '1'" >
</div>
</div>

<div class="col-md-6 columns">
<div class="input-group input-group-outline null is-filled ">
<label class="form-label">Trading Name *</label>
<input type="text" required class="form-control form-control-default" v-model="form.trading_name" >
</div>
<div v-if="errors.trading_name" class="text-danger">{{ errors.trading_name }}</div>
</div>

<div class="col-6 columns" >
  <div class="input-group input-group-outline null is-filled">
  <label class="form-label">Applicant</label>
  <select v-model="form.belongs_to" @change="selectApplicant($event)" class="form-control form-control-default" required>
  <option :value="''" disabled selected>Select Applicant</option>
  <option value="Company">Company</option>
  <option value="Person">Person</option>
  </select>
  </div>
  <div v-if="errors.belongs_to" class="text-danger">{{ errors.belongs_to }}</div>
  </div>

<div class="col-md-6 columns" v-if="form.belongs_to === 'Company'">
 <Multiselect
     v-model="form.company"
        placeholder="Search company"
        :options="company_options"
        :searchable="true"
        :class="multiselect"
      />
<div v-if="errors.company" class="text-danger">{{ errors.company }}</div>
</div>

<div class="col-md-6 columns" v-if="form.belongs_to === 'Person'">
  <Multiselect
      v-model="form.person"
         placeholder="Search Person"
         :options="people_options"
         :searchable="true"
         style="margin:top: 1rem;"
       />
 <div v-if="errors.person" class="text-danger">{{ errors.person }}</div>
 </div>

<div class="col-md-6 columns">
<div class="input-group input-group-outline null is-filled">
<label class="form-label">Licence Type *</label>
<select v-model="form.licence_type" class="form-control form-control-default">
 <option :value="''">Select Licence Type</option>
<option v-for='licence_dropdown in licence_dropdowns' :value=licence_dropdown.id> {{ licence_dropdown.licence_type }}</option>
</select>
</div>
<div v-if="errors.licence_type" class="text-danger">{{ errors.licence_type }}</div>
</div>

<div class="col-md-6 columns">
<div class="input-group input-group-outline null is-filled ">
<label class="form-label">Licence Number</label>
<input type="text" required class="form-control form-control-default" v-model="form.licence_number" >
</div>
<div v-if="errors.licence_number" class="text-danger">{{ errors.licence_number }}</div>
</div>

<div class="col-md-6 columns">
<div class="input-group input-group-outline null is-filled">
<label class="form-label">Old Licence Number</label>
<input type="text" class="form-control form-control-default" v-model="form.old_licence_number" >
</div>
<div v-if="errors.old_licence_number" class="text-danger">{{ errors.old_licence_number }}</div>
</div>  
<div class="col-md-6 columns">
<div class="input-group input-group-outline null is-filled">
<label class="form-label">Licence Date</label>
<input type="date" class="form-control form-control-default" v-model="form.licence_date">
</div>
<div v-if="errors.licence_date" class="text-danger">{{ errors.licence_date }}</div>
</div>

</div>

</div>
</div>
<hr class="vertical dark" />
</div>

<div class="col-4 col-md-4 col-xl-4" style="margin-top: 3.4rem;">

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
<select class="form-control form-control-default" v-model="form.province" required>
<option :value="''" disabled selected>Select Province</option>
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

</div>
<div>
  <button :disabled="form.processing" :style="{float: 'right'}" class="btn btn-sm btn-secondary ms-2" type="submit">
  <span v-if="form.processing" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
  <span class="visually-hidden">Loading...</span> Submit</button>
</div>
</form>
<hr>

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
    people: Array,
    success: String,
    error: String,
  },
  
  
  setup (props) {
    let company_options = props.companies;
    let people_options = props.people;

    const form = useForm({
          trading_name: '',
          licence_type: '',
          is_licence_active: '1',
          licence_number: '',
          old_licence_number: '',
          licence_date: null,
          address: '',
          address2: '',
          address3: '',
          province: '',
          company: '',//company id
          person: '', //person id
          postal_code: '',
          belongs_to: ''   
    })

    function submit() {
      form.post('/submit-licence', {
        preserveScroll: true,
      })
      
    }

     function selectApplicant(event){
      if(form.belongs_to === 'Company'){
        form.belongs_to = event.target.value;
        form.person='';
      }else{
        form.belongs_to = event.target.value;
        form.company='';
      }

     }
    

    return { submit,selectApplicant,company_options, people_options, form }
  },
   components: {
    Layout,
    Link,
    Head,
    Multiselect,
    Banner
  },
  
};
</script>

