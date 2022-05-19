<script>
import Layout from "../Shared/Layout.vue";
import { Head,Link } from '@inertiajs/inertia-vue3';
import DefaultProjectCard from "./components/DefaultProjectCard.vue";
import setNavPills from "@/assets/js/nav-pills.js";
import setTooltip from "@/assets/js/tooltip.js";
import { HalfCircleSpinner } from 'epic-spinners'

export default {
  props: {
    errors: Object,
    licence_dropdowns: Object,
    licence: Object,
    success: String,
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
      showMenu: false,
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
    submit() {
      this.loading=true;
      this.$inertia.post(`/submit-licence/${this.licence.slug}`, this.form)
       .then(() => {
          this.loading=false;
        })
    },

  },

  mounted() {
    this.$store.state.isAbsolute = true;
    setNavPills();
    setTooltip();
  },
  beforeUnmount() {
    this.$store.state.isAbsolute = false;
  },
};
</script>
<style>
  .container-fluid{
    margin-top: -13.5rem;
  }
</style>
<template>
<Layout>
<div class="container-fluid" >
    <div
      class="page-header min-height-300 border-radius-xl"
      style="background-image: url('https://images.unsplash.com/photo-1531512073830-ba890ca4eba2?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=crop&w=1920&q=80');
      "
    >
      <span class="mask bg-gradient-success opacity-6"></span>
    </div>
    <div class="card card-body mx-3 mx-md-4 mt-n6">
      <div class="row gx-4">
        <div class="col-auto">
         
        </div>
        <div class="col-auto my-auto">
          <div class="h-100 d-flex">
             <h5 class="mb-1">New Licence for: {{ licence.company.name }}</h5>
            
          </div>
        </div>
      
      </div>
      <div class="row">
      <!-- <form @submit.prevent="submit"> -->
        <div class="mt-3 row">
          <div class="col-12 col-md-6 col-xl-4 position-relative">
            <div class="card card-plain h-100">
              <div class="p-3 card-body">
                <ul class="list-group">
               <li class="px-0 border-0 list-group-item"><div class="form-check form-switch d-flex ps-0 ms-0">
               <input v-model="form.is_licence_active" id="flexSwitchCheckDefault4" class="form-check-input ps-0 ms-0" type="checkbox" 
               name="flexSwitchCheckDefault4"></div>
               </li>
  <li class="px-0 border-0 list-group-item">
  <div class="input-group input-group-outline null is-filled">
  <label class="form-label">Trading Name *</label>
  <input type="text" class="form-control form-control-default" v-model="form.trading_name">
   </div>
  <p v-if="errors.trading_name" class="text-danger">{{ errors.trading_name }}</p>
</li>

<li class="px-0 border-0 list-group-item">
<div class="input-group input-group-outline is-filled">
 <label class="form-label">Licence Type *</label>
<select v-model="form.licence_type" class="form-control form-control-default">
  <option value=" ">&lt;-- Please select an option --&gt;</option>
  <option v-for='licence_dropdown in licence_dropdowns' :value=licence_dropdown.id> {{ licence_dropdown.licence_type }}</option>
</select>
</div>
<p v-if="errors.licence_type" class="text-danger">{{ errors.licence_type }}</p>
 </li>

   <li class="px-0 border-0 list-group-item">
   <div class="input-group input-group-outline null is-filled">
  <label class="form-label">Licence Number</label>
  <input type="text" class="form-control form-control-default" v-model="form.licence_number">
   </div>
   <p v-if="errors.licence_number" class="text-danger">{{ errors.licence_number }}</p>
    </li>
      <li class="px-0 border-0 list-group-item">
   <div class="input-group input-group-outline null is-filled">
  <label class="form-label">Old Licence Number</label>
  <input type="text" class="form-control form-control-default" v-model="form.old_licence_number" >
   </div>
   <p v-if="errors.old_licence_number" class="text-danger">{{ errors.old_licence_number }}</p>
    </li>
 <li class="px-0 border-0 list-group-item">
 <div class="input-group input-group-outline null is-filled">
  <label class="form-label">Licence Date</label>
  <input type="date" class="form-control form-control-default" v-model="form.licence_date">
  </div>
    <p v-if="errors.licence_date" class="text-danger">{{ errors.licence_date }}</p>
  </li>
    <li class="px-0 border-0 list-group-item">
                 
   <div class="input-group input-group-outline null is-filled">
  <label class="form-label">Licence Expiry Date</label>
  <input type="date" class="form-control form-control-default" v-model="form.licence_expiry_date" >
   </div>
   <p v-if="errors.licence_expiry_date" class="text-danger">{{ errors.licence_expiry_date }}</p>
   </li>
    <li class="px-0 border-0 list-group-item">
 
   <div class="input-group input-group-outline null is-filled">
  <label class="form-label">File Number</label>
  <input type="text" class="form-control form-control-default" v-model="form.file_number" >
   </div>
 </li>
</ul>
              </div>
            </div>
            <hr class="vertical dark" />
          </div>
          <div class="col-12 col-md-6 col-xl-4 position-relative">
            <div class="card card-plain h-100">
              
              <div class="p-3 card-body">
                
<ul class="list-group">
 <li class="px-0 border-0 list-group-item">
<div class="form-check form-switch d-flex ps-0 ms-0">
<input :checked="licence.is_licence_active" id="flexSwitchCheckDefault" class="form-check-input ps-0 ms-0" type="checkbox" disabled>
<label class="form-check-label ms-3 mb-0 text-body text-truncate w-80" for="flexSwitchCheckDefault">Must Renew</label>
</div>
  </li>

 <li class="px-0 border-0 list-group-item">
        
   <div class="input-group input-group-outline null is-filled">
  <label class="form-label">Account Number</label>
  <input type="text" class="form-control form-control-default" v-model="form.account_number" >
   </div>
        </li>
  <li class="px-0 border-0 list-group-item">
 <div class="input-group input-group-outline null is-filled">
  <label class="form-label">Address</label>
  <textarea class="form-control form-control-default" v-model="form.address"></textarea>
   </div>
  </li>
  <li class="px-0 border-0 list-group-item is-filled">
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
    </li>
      <li class="px-0 border-0 list-group-item is-filled">
<div class="input-group input-group-outline null is-filled">
  <label class="form-label">Consultant Name</label>
  <input v-model="form.consultant_name" type="text" class="form-control form-control-default" >
   </div>
    </li>

   <li class="px-0 border-0 list-group-item">
 <div class="input-group input-group-outline null is-filled">
  <label class="form-label">Notes</label>
  <textarea v-model="form.notes" class="form-control form-control-default"></textarea>
   </div>
  </li>
                   
                </ul>
                
              </div>
            </div>
            <hr class="vertical dark" />
          </div>
          <div class="mt-4 col-12 col-xl-4 mt-xl-0">
            <div class="card card-plain h-100">
            <div class="p-3 pb-0 card-header">
                <h6 class="mb-0" >License Documents</h6>
              </div>
              <div class="p-3 card-body">
            <!-- Tabs content -->
<div class="tab-content" id="ex1-content">
<div class="tab-pane fade show active" id="ex1-tabs-1" role="tabpanel" aria-labelledby="ex1-tab-1">
<form @submit.prevent="originalLicenceUpload">
<ul class="list-group mb-0">
<li class="px-0 border-0 list-group-item is-filled">
<div class="input-group input-group-outline null is-filled">
<label class="form-label">Original Licence Name</label>
<input v-model="form.name"  type="text" class="form-control form-control-default" >
</div>
<progress v-if="form.progress" :value="form.progress.percentage" max="100">
      {{ form.progress.percentage }}%
</progress>
</li>
<li class="list-group-item d-flex align-items-center border-0 mb-2 rounded" style="background-color: rgb(244, 246, 247);">
<span>Original Licence</span> <br>
<input @input="form.original_licence = $event.target.files[0]" type="file" aria-label="...">
</li>
<button type="submit" class="btn btn-sm btn-secondary ms-2 d-flex justify-content-center">Upload</button>
</ul>
</form>
<hr>

<form>
<ul class="list-group mb-0">
<li class="px-0 border-0 list-group-item is-filled">
<div class="input-group input-group-outline null is-filled">
<label class="form-label">Original Licence Name</label>
<input  type="text" class="form-control form-control-default" >
</div>
</li>
<li class="list-group-item d-flex align-items-center border-0 mb-2 rounded" style="background-color: rgb(244, 246, 247);">
<span>Original Licence</span> <br>
<input type="file" aria-label="...">
</li>
<button type="submit" class="btn btn-sm btn-secondary ms-2 d-flex justify-content-center">Upload</button>
</ul>
</form>
<hr>

<form>
<ul class="list-group mb-0">
<li class="px-0 border-0 list-group-item is-filled">
<div class="input-group input-group-outline null is-filled">
<label class="form-label">Original Licence Name</label>
<input  type="text" class="form-control form-control-default" >
</div>
</li>
<li class="list-group-item d-flex align-items-center border-0 mb-2 rounded" style="background-color: rgb(244, 246, 247);">
<span>Original Licence</span> <br>
<input type="file" aria-label="...">
</li>
<button type="submit" class="btn btn-sm btn-secondary ms-2 d-flex justify-content-center">Upload</button>
</ul>
</form>
<hr>

           
              </div>
            </div>
            <!-- Tabs content -->

              </div>
            </div>
          </div>
        </div>
<button type="submit" class="btn btn-secondary ms-2 d-flex justify-content-center">
 <span class="mr-5" v-if="loading">
  <div class="half-circle-spinner">
  <div class="circle circle-1"></div>
  <div class="circle circle-2"></div>
</div>
</span>
Save
</button>

        
<!-- </form> -->
        
      </div>
    </div>
  </div>

  </Layout>
</template>