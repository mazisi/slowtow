<script>
import Layout from "../../Shared/Layout.vue";
import Multiselect from '@vueform/multiselect';
import { Link } from '@inertiajs/inertia-vue3';


export default {
  name: "profile-overview",
 props: {
    errors: Object,
    licence: Object,
    companies_dropdown: Array,
    success: String,
    error: String,

  },
  data() {
    return {
      showMenu: false,
      form: {
      old_company: this.licence.company.name,
      old_company_id: this.licence.company_id,
      new_company: null,
      date: null,
      licence_id: this.licence.id,
      status: [],
      
      },
      options: this.companies_dropdown,
    };
  },
    methods: {
      submit() {
          this.$inertia.post(`/transfer-licence-submit/${this.licence.slug}`, this.form)
          .then(() => {
              
            })
        },
  },
  components: {
    Layout,
    Multiselect,
    Link
  },
  beforeUnmount() {
    this.$store.state.isAbsolute = false;
  },
};

//The following are status keys
// 1 => Deposit Paid
// 2 => Collate Transfer Details
// 3 => Client Invoiced
// 4 => Client Paid
// 5 => Transfer Logded
// 6 => Certificate Received
//7 => Transfer Complete And Delivered
</script>
<style>
.columns{
  margin-bottom: 1rem;
}
#active-checkbox{
  margin-top: -10px;
  margin-left: 3px;
}
.status-heading{
  font-weight: 700;
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
      <div class="row">
  <div class="col-lg-6 col-7">
   <h5>New Transfer for: {{ licence.trading_name }}</h5>
  </div>
  <div class="col-lg-6 col-5 my-auto text-end"></div>
</div>
      <div class="row">
        <div class="mt-3 row">
          <div class="col-12 col-md-12 col-xl-12 position-relative">
            <div class="card card-plain h-100">
              <div class="p-3 card-body">
  <form @submit.prevent="submit">
<div class="row">
<input type="hidden" v-model="form.old_company_id"> 
<input type="hidden" v-model="form.licence_id"> 
<div class="col-md-12 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input id="active-checkbox" v-model="form.status" type="checkbox" value="1">
<label class="form-check-label text-body text-truncate status-heading">Deposit Quoted </label>
</div>
</div>     

<hr>

<div class="col-md-12 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input id="active-checkbox" v-model="form.status" type="checkbox" value="2">
<label class="form-check-label text-body text-truncate status-heading">Client Invoiced </label>
</div>
</div>   
<hr>
<div class="col-md-12 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input id="active-checkbox" v-model="form.status" type="checkbox" value="3">
<label class="form-check-label text-body text-truncate status-heading">Client Paid</label>
</div>
</div>  
<hr>

<div class="col-md-12 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input id="active-checkbox" v-model="form.status" type="checkbox" value="4">
<label class="form-check-label text-body text-truncate status-heading">Collate Transfer Details</label>
</div>
</div> 

  <div class="col-md-4 columns">
    <div class="input-group input-group-outline null is-filled ">
    <label class="form-label">Previous Licence Holder</label>
    <input type="text" required readonly title="You can`t change this field." class="form-control form-control-default" v-model="form.old_company" >
     </div>
   <div v-if="errors.old_company" class="text-danger">{{ errors.old_company }}</div>
   </div>

   <div class="col-md-4 columns">
    <div class="input-group input-group-outline null is-filled ">
    <label class="mx-6 mt-2">Transfering To</label>
     </div>
   </div>

  <div class="col-md-4 columns">
  <div class="input-group input-group-outline null is-filled">
     <Multiselect
     v-model="form.new_company"
        placeholder="Current Licence Holder"
        :options="options"
        :searchable="true"
      />
    </div>
 <div v-if="errors.new_company" class="text-danger">{{ errors.new_company }}</div>
</div>
<div class="col-md-4 columns"></div>
   <div class="col-md-4 columns">
    <label class="form-label mx-6 " style="border: 2px solid; padding: 5px; border-radius: 5px">Documents Required</label>
   </div>
   <div class="col-md-4 columns"></div>


<div class="col-md-4" style="text-align: center;"><i class="fa fa-cloud-upload h5" aria-hidden="true"></i></div>
   <div class="col-md-4 ">
    <label class="form-label mx-6 " style="border: 2px solid; padding: 5px; border-radius: 5px">Transfer Forms</label>
   </div>
   <div class="col-md-4 " style="text-align: center;"><i class="fa fa-cloud-upload h5" aria-hidden="true"></i></div>



<div class="col-md-4 columns" style="text-align: center;"><i class="fa fa-cloud-upload h5" aria-hidden="true"></i></div>
   <div class="col-md-4 columns">
    <label class="form-label mx-6 " style="border: 2px solid; padding: 5px; border-radius: 5px">Smoking Affidavit</label>
   </div>
   <div class="col-md-4 columns" style="text-align: center;"><i class="fa fa-cloud-upload h5" aria-hidden="true"></i></div>

 <!-- <div class="col-md-4 columns">
    <div class="input-group input-group-outline null is-filled ">
    <label class="form-label">Transfer Date</label>
    <input type="date" class="form-control form-control-default" v-model="form.date">
     </div>
   <div v-if="errors.date" class="text-danger">{{ errors.date }}</div>
   </div> -->

<hr>

<div class="col-md-12 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input id="active-checkbox" v-model="form.status" type="checkbox" value="4">
<label class="form-check-label text-body text-truncate status-heading">Payment To The Liquor Board</label>
</div>
</div>  
<hr>


<div class="col-md-12 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input id="active-checkbox" v-model="form.status" type="checkbox" value="5">
<label class="form-check-label text-body text-truncate status-heading">Transfer Logded</label>
</div>
</div>  
<hr>

<div class="col-md-12 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input id="active-checkbox" v-model="form.status" type="checkbox" value="6">
<label class="form-check-label text-body text-truncate status-heading">Activation Fee Paid</label>
</div>
</div>  
<hr>


<div class="col-md-12 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input id="active-checkbox" v-model="form.status" type="checkbox" value="7">
<label class="form-check-label text-body text-truncate status-heading">Transfer Issued</label>
</div>
</div> 
<hr>

<div class="col-md-12 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input id="active-checkbox" v-model="form.status" type="checkbox" value="8">
<label class="form-check-label text-body text-truncate status-heading">Transfer Delivered</label>
</div>
</div> 
<hr>
<hr>


<div class="col-md-12 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input id="active-checkbox" v-model="form.status" type="checkbox" value="5">
<label class="form-check-label text-body text-truncate status-heading"> Transfer Logded</label>
</div>
</div> 


<div class="col-md-12 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input id="active-checkbox" v-model="form.status" type="checkbox" value="6">
<label class="form-check-label text-body text-truncate status-heading"> Certificate Received</label>
</div>
</div> 

<div>
  <button type="submit" class="btn btn-secondary ms-2" :style="{float: 'right'}">Create</button></div>
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
