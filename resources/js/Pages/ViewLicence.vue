<script>
import Layout from "../Shared/Layout.vue";
import { Head,Link } from '@inertiajs/inertia-vue3';
import DefaultProjectCard from "./components/DefaultProjectCard.vue";
import { HalfCircleSpinner } from 'epic-spinners';

export default {
name: "dashboard-default",
  props: {
    errors: Object,
    licence_dropdowns: Object,
    licence: Object,
    success: String,
    error: String
  },

  data() {
    return {
      loading: false,
      openModal: false,
        form: {
         trading_name: this.licence.trading_name,
         licence_type: this.licence.licence_type,
         is_licence_active: this.licence.licence_status,
         file_number: this.licence.file_number,
         licence_number: this.licence.licence_number,
         old_licence_number: this.licence.old_licence_number,
         licence_date: this.licence.licence_date,
         licence_expiry_date: this.licence.licence_expiry_date,
         account_number: this.licence.account_number,
         address: this.licence.address,
         province: this.licence.province,
         consultant_name: this.licence.consultant_name,
         must_renew: this.licence.must_renew,
         notes: this.licence.notes,
      },
      //licence docs upload
      licence_doc_upload_form: {
      licence_doc_name: null,
      original_licence: null,
      licence_id: this.licence.id
     },    
      showMenu: false,
      showNominationMessage: false,
    };
  },
  components: {
    HalfCircleSpinner,
    DefaultProjectCard,
    Layout,
    Link,
    Head,
  },

  methods: {
    originalLicenceUpload() {
      this.$inertia.post(`/upload-licence-document`, this.licence_doc_upload_form,{
       forceFormData: true,
      })
    },

    updateLicence() {
      // this.loading=true;
      this.$inertia.post(`/update-licence/${this.licence.slug}`, this.form)
    },

     deleteLicence(){
      if(confirm('Are you sure you want to delete this licence??')){
        this.$inertia.delete(`/delete-licence/${this.licence.slug}`)
      }      
    }

  },
  beforeUnmount() {
    this.$store.state.isAbsolute = false;
  },
};
</script>
<style>
.columns{
  margin-bottom: 1rem;
}
#active-checkbox{
  margin-top: 3px;
  margin-left: 3px;
}</style>

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
<h6 class="mb-1">View Licence for: {{ licence.company.name }}</h6>
</div>


<div class="col-lg-6 col-5 my-auto text-end">
<div class="dropdown float-lg-end pe-4">
<a class="cursor-pointer" id="dropdownTable" data-bs-toggle="dropdown" aria-expanded="false">
 <i class="fa fa-ellipsis-v text-secondary" aria-hidden="true"></i>
</a>
<ul class="dropdown-menu px-2 py-3 ms-sm-n4 ms-n5" aria-labelledby="dropdownTable">
<li><Link :href="`/renew-licence/${licence.slug }`" class="dropdown-item border-radius-md">Licence Renewals</Link></li>
<li><hr></li>
<li><Link :href="`/transfer-licence/${licence.slug }`" class="dropdown-item border-radius-md"> Transfer Licence</Link></li>
<li><Link :href="`/transfer-history/${licence.slug }`" class="dropdown-item border-radius-md"> Transfers</Link></li>
<li><hr></li>
<li><Link :href="`/nominate/${licence.slug }`" class="dropdown-item border-radius-md">Nominate</Link></li>

<li><Link :href="`/nominations/${licence.slug }`" class="dropdown-item border-radius-md"> Nominations</Link></li>


<li><hr></li>
<li><Link :href="`/alter-licence/${licence.slug }`" class="dropdown-item border-radius-md"> Alter Licence</Link></li>

<li><hr class="text-danger"></li>
<li><button @click="deleteLicence" class="dropdown-item border-radius-md text-danger" >
<i class="fa fa-trash-o cursor-pointer" aria-hidden="true"></i> Delete</button></li>
</ul>
</div>
</div>
</div>
      <div class="row">
        <div class="mt-3 row">
          <div class="col-12 col-md-12 col-xl-12 position-relative">
            <div class="card card-plain h-100">
              <div class="p-3 card-body">
  <form @submit.prevent="updateLicence">
<div class="row">
<div class="col-md-6 columns">
<div class=" d-flex ps-0 ms-0  is-filled">
<label class=" mb-0 text-body text-truncate">Active</label>
<input v-model="form.is_licence_active" id="active-checkbox" type="checkbox" :checked="form.is_licence_active">
</div>
</div>

   <div class="col-md-6 columns">                  
   <div class=" null is-filled">
  <label class="form-label">Must Renew</label>
 <input v-model="form.must_renew" id="active-checkbox" type="checkbox" :checked="form.must_renew">
   </div>
  <div v-if="errors.must_renew" class="text-danger">{{ errors.must_renew }}</div>
  </div>
                  
  <div class="col-md-4 columns">
    <div class="input-group input-group-outline null is-filled ">
    <label class="form-label">Trading Name *</label>
    <input type="text" required class="form-control form-control-default" v-model="form.trading_name">
     </div>
  <div v-if="errors.trading_name" class="text-danger">{{ errors.trading_name }}</div>
   </div>
  
  <div class="col-md-4 columns">
  <div class="input-group input-group-outline null is-filled">
  <label class="form-label">Licence Type *</label>
  <select v-model="form.licence_type" class="form-control form-control-default">
<option value=" ">&lt;-- Please select an option --&gt;</option>
<option v-for='licence_dropdown in licence_dropdowns' :value=licence_dropdown.id> {{ licence_dropdown.licence_type }}</option>
</select>
  </div>
 <div v-if="errors.licence_type" class="text-danger">{{ errors.licence_type }}</div>
</div>

 <div class="col-md-4 columns">
    <div class="input-group input-group-outline null is-filled ">
    <label class="form-label">Licence Number</label>
    <input type="text" class="form-control form-control-default" v-model="form.old_licence_number" >
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
  <input type="date" class="form-control form-control-default" v-model="form.licence_expiry_date" >
</div>
<div v-if="errors.licence_expiry_date" class="text-danger">{{ errors.licence_expiry_date }}</div>
</div>

  <div class="col-md-4 columns">            
 <div class="input-group input-group-outline null is-filled">
  <label class="form-label">File Number</label>
  <input type="text" class="form-control form-control-default" v-model="form.file_number">
   </div>
  <div v-if="errors.file_number" class="text-danger">{{ errors.file_number }}</div>
  </div>              
              

                  
              
  <div class="col-md-4 columns">    
   <div class="input-group input-group-outline null is-filled">
  <label class="form-label">Account Number</label>
  <input type="text" class="form-control form-control-default" v-model="form.account_number" >
   </div>
   <div v-if="errors.account_number" class="text-danger">{{ errors.account_number }}</div>
   </div>

<div class="col-md-4 columns">    
  <div class="input-group input-group-outline null is-filled">
  <label class="form-label">Province</label>
<select class="form-control form-control-default" v-model="form.province" >
<option value="" selected=""></option>
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
  <label class="form-label">Consultant Name</label>
  <input type="text" class="form-control form-control-default" v-model="form.consultant_name" >
   </div>
   <div v-if="errors.consultant_name" class="text-danger">{{ errors.consultant_name }}</div>
   </div>
             
 <div class="col-md-12 columns">    
   <div class="input-group input-group-outline null is-filled">
  <label class="form-label">Address</label>
  <textarea class="form-control form-control-default" v-model="form.address"></textarea>
   </div>
 <div v-if="errors.address" class="text-danger">{{ errors.address }}</div>
 </div>
  <div>
  <button type="submit" class="btn btn-secondary ms-2" :style="{float: 'right'}">Save</button></div>
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
