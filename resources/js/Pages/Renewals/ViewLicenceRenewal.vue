<script>
import Layout from "../../Shared/Layout.vue";
import { Head,Link,useForm } from '@inertiajs/inertia-vue3';
import Datepicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';
import { Inertia } from '@inertiajs/inertia';

import { ref } from 'vue';

export default {
  props: {
    errors: Object,
    licence_dropdowns: Object,
    licence: Object,
    success: String,
    error: String,
    renewal: Object,
  },

  setup (props) {
    const year = ref(new Date().getFullYear());

    const form = useForm({
      year: props.renewal.date,
      licence_id: props.renewal.id,
      status: [],
      is_checked: props.renewal.status,
      renewal_id: props.renewal.id    
    })



    function updateRenewal() {
      // Inertia.patch('/update-renewal', form)
      form.patch('/update-renewal', {
        preserveScroll: true,
      })
      
    }

    function getRenewalYear(date){
      let computed_date = new Date(date).getFullYear();
      return computed_date + 1;    
    }

    function pushData(status_value){
         if (event.target.checked) {
            if(this.form.status.includes(status_value)){
                return;
              }else{
                this.form.status.push(status_value)
              } 
          }else if(!event.target.checked){
          // alert('unticked')
          }
      }

    return { year,form, updateRenewal, getRenewalYear, pushData }
  },
   components: {
    Layout,
    Link,
    Head,
    Datepicker
  },
  
};
//The following are status keys
// 1 => Client Quoted
// 2 => Client Invoiced
// 3 => Client Paid
// 4 => Payment To Liquor Board
// 5 => Renewal Complete

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
   <h6>Process Renewal for: <span class="text-success">{{ renewal.date  }}/{{ getRenewalYear(renewal.date)  }}</span> : {{ renewal.licence.trading_name }}</h6>
  </div>
  <div class="col-lg-6 col-5 my-auto text-end"></div>
</div>

      <div class="row">
        <div class="mt-3 row">
          <div class="col-12 col-md-12 col-xl-12 position-relative">
            <div class="card card-plain h-100">
              <div class="p-3 card-body">
  <form @submit.prevent="updateRenewal">
<div class="row">
<div class="col-md-12 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input id="active-checkbox" type="checkbox" 
:checked="form.is_checked >= '1'"
@input="pushData($event.target.value)" value="1">
<label class="form-check-label text-body text-truncate status-heading">Client Quoted</label>
</div>
<ul class="list-group">
<li class="px-0 mb-2 border-0 list-group-item d-flex align-items-center">
<div class="avatar me-3">
<img src="/vue-material-dashboard-2/img/kal-visuals-square.779cce2b.jpg" alt="Doc" class="shadow null border-radius-lg">
</div>
<div class="d-flex align-items-start flex-column justify-content-center">
<h6 class="mb-0 text-sm">Doc Name.</h6><p class="mb-0 text-xs">3Mb</p>
</div>

<a class="mb-0 btn btn-link ps-0 ms-auto" href="javascript:;">
<i class="fa fa-upload h5 text-success" aria-hidden="true"></i>
</a>
<a class="mb-0 btn btn-link ps-0 ms-auto" href="javascript:;">
<i class="fa fa-trash-o text-danger h5" aria-hidden="true"></i>
</a>

</li>
</ul>
</div>     
<hr>
<div class="col-md-12 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input id="active-checkbox"  type="checkbox" value="2"
@input="pushData($event.target.value)" 
:checked="form.is_checked >= '2'">
<label class="form-check-label text-body text-truncate status-heading">Client Invoiced</label>
</div>
</div> <hr>

  <div class="col-md-6 columns">
<Datepicker v-model="form.year" yearPicker />
<p v-if="errors.year" class="text-danger">{{ errors.year }}</p>
</div>

  <div class="col-md-3 columns">
    <label class="form-label">Scanned Renewal Form</label>
  </div>
<hr>
<div class="col-md-12 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input id="active-checkbox" type="checkbox" 
@input="pushData($event.target.value)" value="3" :checked="form.is_checked >= '3'">
<label class="form-check-label text-body text-truncate status-heading">Client Paid</label>
</div>
</div> 


<label>Bank Reference Number</label>


<div class="col-md-12 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input id="active-checkbox" type="checkbox" @input="pushData($event.target.value)" value="4"
:checked="form.is_checked >= '4'">
<label class="form-check-label text-body text-truncate status-heading">Payment To Liquor Board</label>
</div>
</div> 


<label class="form-check-label mb-0 text-body text-truncate"> Stamped Renewal With Proof Of Payment</label>
<hr>




<div class="col-md-12 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input id="active-checkbox" type="checkbox" value="5"
@input="pushData($event.target.value)" :checked="form.is_checked == '5'">
<label class="form-check-label text-body text-truncate status-heading"> Renewal Complete &amp; Delivered</label>
</div>
</div> 

<div class="text-danger">
  <div v-if="form.isDirty" class="text-xs d-flex">There are unsaved changes.</div>
  <button :disabled="form.processing" :style="{float: 'right'}" class="btn btn-sm btn-secondary ms-2" type="submit">
  <span v-if="form.processing" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
  <span class="visually-hidden">Loading...</span> Save</button>
</div>
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
