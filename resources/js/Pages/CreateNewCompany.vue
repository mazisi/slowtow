<template>
<Layout>
<div class="container-fluid">
<Banner/>
<div class="card card-body mx-3 mx-md-4 mt-n6">
<div class="row">
<div class="col-lg-6 col-7">
<h6 class="mb-1">New Company </h6>
</div>

</div>



<div class="row">
<div class="mt-3 ">
<form class="row" @submit.prevent="submit">
<input type="hidden" v-model="form.slug">
<div class="col-8 col-md-8 col-xl-8 position-relative">
<div class="card card-plain h-100">
<div class="p-3 card-body">
<div class="row">
<div class="col-md-12 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<label class="form-check-label ms-3 mb-0 text-body text-truncate">Active Company</label>
<input id="active-checkbox" type="checkbox" value="1" :checked="form.active == '1'" >
</div>
</div>

<div class="col-md-6 columns">
<div class="input-group input-group-outline null is-filled ">
<label class="form-label">Company Name *</label>
<input type="text" class="form-control form-control-default" v-model="form.company_name">
</div>
<div v-if="errors.company_name" class="text-danger">{{ errors.company_name }}</div>
</div>

<div class="col-md-6 columns">
<div class="input-group input-group-outline null is-filled ">
<label class="form-label">Company Type </label>
<select class="form-control form-control-default" v-model="form.company_type" >
  <option :value="''" disabled >Select Company Type*</option>
<option value="Association">Association</option>
<option value="Close Corporation CC">Close Corporation  CC</option>
<option value="Individual">Individual</option>
<option value="Non-profit Organization (NPO)">Non-profit Organization (NPO)</option>
<option value="Partnership">Partnership</option>
<option value="Private Company  (Proprietary) Limited">Private Company  (Proprietary) Limited</option>
<option value="Public Company">Public Company</option>
<option value="Sole Proprietor">Sole Proprietor</option>
<option value="Trust">Trust</option>
</select>
</div>
<div v-if="errors.company_type" class="text-danger">{{ errors.company_type }}</div>
</div>

<div class="col-md-6 columns">
<div class="input-group input-group-outline null is-filled">
<label class="form-label">Registration Number</label>
<input type="text" class="form-control form-control-default" v-model="form.reg_number" >
</div>
<div v-if="errors.reg_number" class="text-danger">{{ errors.reg_number }}</div>
</div>

<div class="col-md-6 columns">
<div class="input-group input-group-outline null is-filled ">
<label class="form-label">Vat Number</label>
<input type="text" class="form-control form-control-default" v-model="form.vat_number" >
</div>
<div v-if="errors.vat_number" class="text-danger">{{ errors.vat_number }}</div>
</div>

<div class="col-md-6 columns">
<div class="input-group input-group-outline null is-filled">
<label class="form-label">Email Address </label>
<input type="email" class="form-control form-control-default" v-model="form.email_address_1" >
</div>
<div v-if="errors.email_address_1" class="text-danger">{{ errors.email_address_1 }}</div>
</div>  
<div class="col-md-6 columns">
<div class="input-group input-group-outline null is-filled">
<label class="form-label">Email Address</label>
<input type="email" class="form-control form-control-default" v-model="form.email_address_2" >
</div>
<div v-if="errors.email_address_2" class="text-danger">{{ errors.email_address_2 }}</div>
</div>

<div class="col-md-6 columns">
<div class="input-group input-group-outline null is-filled">
<label class="form-label">Email Address </label>
<input type="email" class="form-control form-control-default" v-model="form.email_address_3" >
</div>
<div v-if="errors.email_address_3" class="text-danger">{{ errors.email_address_3 }}</div>
</div>

<div class="col-md-6 columns">
<div class="input-group input-group-outline null is-filled">
<label class="form-label">Phone Number </label>
<input type="text" class="form-control form-control-default" v-model="form.telephone_number_1" >
</div>
<div v-if="errors.telephone_number_1" class="text-danger">{{ errors.telephone_number_1 }}</div>
</div>

<div class="col-md-6 columns">
<div class="input-group input-group-outline null is-filled">
<label class="form-label">Phone Number </label>
<input type="text" class="form-control form-control-default" v-model="form.telephone_number_2" >
</div>
<div v-if="errors.telephone_number_2" class="text-danger">{{ errors.telephone_number_2 }}</div>
</div>

<div class="col-md-6 columns">
<div class="input-group input-group-outline null is-filled">
<label class="form-label">Website</label>
<input type="url" class="form-control form-control-default" v-model="form.website" >
</div>
<div v-if="errors.website" class="text-danger">{{ errors.website }}</div>
</div>



</div>

</div>
</div>
<hr class="vertical dark" />
</div>

<div class="col-4 col-md-4 col-xl-4" style="margin-top: 3.4rem;">
<div class="row">
<div class="col-12 columns">            
<div class="input-group input-group-outline null is-filled">
<label class="form-label">Business Address Line 1</label>
<input type="text" class="form-control form-control-default" v-model="form.business_address" >
</div>
<div v-if="errors.business_address" class="text-danger">{{ errors.business_address }}</div>
</div> 

<div class="col-12 columns">            
<div class="input-group input-group-outline null is-filled">
<label class="form-label">Business Address Line 2</label>
<input type="text" class="form-control form-control-default" v-model="form.business_address2" >
</div>
<div v-if="errors.business_address2" class="text-danger">{{ errors.business_address2 }}</div>
</div> 

<div class="col-12 columns">            
<div class="input-group input-group-outline null is-filled">
<label class="form-label">Business Address Line 3</label>
<input type="text" class="form-control form-control-default" v-model="form.business_address3" >
</div>
<div v-if="errors.business_address3" class="text-danger">{{ errors.business_address3 }}</div>
</div> 

<div class="col-6 columns">                  
<div class="input-group input-group-outline null is-filled">
<label class="form-label">Province</label>
<select class="form-control form-control-default" v-model="form.business_province" >
<option :value="''" disabled >Select Province</option>
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
<div v-if="errors.business_province" class="text-danger">{{ errors.business_province }}</div>
</div>

<div class="col-6 columns">            
<div class="input-group input-group-outline null is-filled">
<label class="form-label">Postal Code</label>
<input  type="text" class="form-control form-control-default" v-model="form.business_address_postal_code">
</div>

</div>

<hr>
<div class="col-12 columns">            
  <div class="input-group input-group-outline is-filled">
  <input style="margin-top: -6px; margin-right: 3px;" @change="copyBusinessAddress" 
   type="checkbox" v-model="form.copy_address" >
  <label>Same as Business Address</label>
  </div>
  <div v-if="errors.postal_address" class="text-danger">{{ errors.postal_address }}</div>
  </div> 
  
<div class="col-12 columns">            
<div class="input-group input-group-outline null is-filled">
<label class="form-label">Postal Address Line 1</label>
<input type="text" class="form-control form-control-default" v-model="form.postal_address" >
</div>
<div v-if="errors.postal_address" class="text-danger">{{ errors.postal_address }}</div>
</div> 
<div class="col-12 columns">            
<div class="input-group input-group-outline null is-filled">
<label class="form-label">Postal Address Line 2</label>
<input type="text" class="form-control form-control-default" v-model="form.postal_address2" >
</div>
<div v-if="errors.postal_address2" class="text-danger">{{ errors.postal_address2 }}</div>
</div> 
<div class="col-12 columns">            
<div class="input-group input-group-outline null is-filled">
<label class="form-label">Postal Address Line 3</label>
<input type="text" class="form-control form-control-default" v-model="form.postal_address3" >
</div>
<div v-if="errors.postal_address3" class="text-danger">{{ errors.postal_address }}</div>
</div> 

<div class="col-6 columns">                  
<div class="input-group input-group-outline null is-filled">
<label class="form-label">Province</label>
<select class="form-control form-control-default" v-model="form.postal_province" >
  <option :value="''" disabled >Select Province</option>
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
<div v-if="errors.postal_province" class="text-danger">{{ errors.postal_province }}</div>
</div>

<div class="col-6 columns">            
<div class="input-group input-group-outline null is-filled">
<label class="form-label">Postal Code</label>
<input  type="text" class="form-control form-control-default" v-model="form.postal_code">
</div>

</div>




</div>
</div>
<div>
<button type="submit" class="btn btn-secondary ms-2" :style="{float: 'right'}">Create</button>
</div>
</form>
<hr>
</div>
</div>



</div>
</div>
</Layout>
</template>
<style>
  #active-checkbox{
    margin-top: -3px;
    margin-left: 3px;
  }
  .columns{
      margin-bottom: 1rem;
    }
</style>

<script>
import Layout from "../Shared/Layout.vue";
import Banner from './components/Banner.vue';

export default {
 props: {
    errors: Object,
  },
  data() {
    return {
       form: {
        copy_address: '',
        company_name: '',
        company_type: '',
        reg_number: '',
        vat_number: '',
        fax_number: '',
        email_address_1: '',
        email_address_2: '',
        email_address_3: '',
        telephone_number_1: '',
        telephone_number_2: '',
        website: '',
        business_address: '',
        business_address2: '',
        business_address3: '',
        business_province: '',
        business_address_postal_code: '',
        postal_address: '',
        postal_address2: '',
        postal_address3: '',
        postal_province: '',
        postal_code: '',
        active: '1',
      },
      showMenu: false,
    };
  },
    methods: {
    submit() {
      this.$inertia.post('/submit-company', this.form)
        
    },

    copyBusinessAddress(){
      if(this.form.copy_address){
        this.form.copy_address = true;
        this.form.postal_address = this.form.business_address;
        this.form.postal_address2 = this.form.business_address2;
        this.form.postal_address3 = this.form.business_address3;
        this.form.postal_address3 = this.form.business_address3;
        this.form.postal_province = this.form.business_province;
        this.form.postal_code = this.form.business_address_postal_code;
      }else{
        this.form.copy_address = false;
        this.form.postal_address = ''
        this.form.postal_address2 = '';
        this.form.postal_address3 = '';
        this.form.postal_address3 = '';
        this.form.postal_province = '';
        this.form.postal_code = '';
        this.form.business_province = '';
      }
    }

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


