<script>
import Layout from "../../Shared/Layout.vue";
import Multiselect from '@vueform/multiselect';
import { Link } from '@inertiajs/inertia-vue3';


export default {
 props: {
    errors: Object,
    renewal: Object,
    success: String,
    error: String

  },
  data() {
    return {
      showMenu: false,
      form: {
         renewal_date: this.renewal.date,
         status: [],
         is_checked: this.renewal.status,
         renewal_status: 'Renewal Received',
         renewal_id: this.renewal.id,      
      },
    };
  },
    methods: {
      submit() {
          this.$inertia.patch(`/update-renewal`, this.form)
          .then(() => {
              
            })
        },

        fetchTableData(){
          this.$inertia.post(`/fetch-table-data-on-search`, this.form)
        },

      getRenewalYear(date){
      let computed_date = new Date(date).getFullYear();
      return computed_date + 1;    
    },

    pushData(event){
      if(this.form.status.includes(event)){
        return;
      }else{
        this.form.status.push(event)
      }
      
    },
    beforeUnmount() {
    this.$store.state.isAbsolute = false;
  },
        
  },

  
  components: {
    Layout,
    Multiselect,
    Link
  },
  
};
//The following are status keys
// 1 => Renewal Received
// 2 => Client Invoiced
// 3 => Client Paid
// 4 => Renewal Processed
// 5 => Renewal Complete & Delivered
</script>
<style>
.columns{
  margin-bottom: 1rem;
}
#active-checkbox{
  margin-top: 3px;
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
  <form @submit.prevent="submit">
<div class="row">
<div class="col-md-12 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input id="active-checkbox" type="checkbox" 
:checked="form.is_checked >= '1'"
@input="pushData($event.target.value)" value="1">
<label class="form-check-label text-body text-truncate status-heading">Renewal Received</label>
</div>
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

<div class="col-md-3 columns">
    <div class="input-group input-group-outline null is-filled">
    <label class="form-label">Renewal Year *</label>
    <input type="number" class="form-control form-control-default" v-model="form.renewal_date" 
    :min="renewal.date" step="1">
    </div>
     <p v-if="errors.renewal_date" class="text-danger">{{ errors.renewal_date }}</p>
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
<label class="form-check-label text-body text-truncate status-heading">Renewal Processed</label>
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

<div>
  <button type="submit" class="btn btn-sm btn-secondary ms-2" :style="{float: 'right'}">Save</button></div>
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
