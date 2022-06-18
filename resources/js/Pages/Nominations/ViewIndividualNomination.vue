<script>
import Layout from "../../Shared/Layout.vue";
import Multiselect from '@vueform/multiselect';
import { Link } from '@inertiajs/inertia-vue3';


export default {
 props: {
   errors: Object,
    nominees: Array,
    licence: Object,
    success: String,
    error: String,
    nomination: Object

  },
  data() {
    return {
      showMenu: false,
      form: {
         nomination_date: this.nomination.date,
         licence_id: '',
         nominee: this.nomination.people[0].name + ' ' + this.nomination.people[0].surname,
         status: []      
      },
      options: this.nominees,
    };
  },
    methods: {
      submit() {
          this.$inertia.post(`/submit-nomination`, this.form)
          .then(() => {
              
            })
        },

        fetchTableData(){
          this.$inertia.post(`/fetch-table-data-on-search`, this.form)
        }
        
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
   <h6>Nomination Info:  {{ nomination.licence.trading_name }}</h6>
  
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
<input type="hidden" v-model="form.slug">
<div class="col-md-12 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input id="active-checkbox" v-model="form.status" type="checkbox" value="Client Invoiced">
<label class="form-check-label text-body text-truncate status-heading">Client Invoiced</label>
</div>
</div>     
<hr>
<div class="col-md-12 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input id="active-checkbox" v-model="form.status" type="checkbox" value="Nomination Paid">
<label class="form-check-label text-body text-truncate status-heading">Nomination  Paid</label>
</div>
</div> <hr>
<label>Payment Document</label><hr>


<div class="col-md-6 columns">
    <div class="input-group input-group-outline null is-filled">
    <label class="form-label">Nominee *</label>
    <input type="text" class="form-control form-control-default" v-model="form.nominee" readonly>
    </div>
     <p v-if="errors.nominee" class="text-danger">{{ errors.nominee }}</p>
  </div>

<div class="col-md-6 columns">
    <div class="input-group input-group-outline null is-filled">
    <label class="form-label">Nomination Date *</label>
    <input type="date" class="form-control form-control-default" v-model="form.nomination_date">
    </div>
     <p v-if="errors.nomination_date" class="text-danger">{{ errors.nomination_date }}</p>
  </div>
<hr>
<label>Required Documents</label><hr>
<div class="col-md-12 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input id="active-checkbox" v-model="form.status" type="checkbox" value="Nomination Lodged">
<label class="form-check-label text-body text-truncate status-heading">Nomination Lodged</label>
</div>
</div> 
<label>Logded Nomination File</label><hr>


<div class="col-md-12 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input id="active-checkbox" v-model="form.status" type="checkbox" value="Certificate Received">
<label class="form-check-label text-body text-truncate status-heading">Certificate Received</label>
</div>
</div> 


<div class="col-md-12 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<label class="form-check-label mb-0 text-body text-truncate"> Nomination Certificate File</label>
</div>
</div> <hr>



<div class="col-md-12 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<label class="form-check-label mb-0 text-body text-truncate">Transfer Certificate File</label>
</div>
</div> <hr>


<div class="col-md-12 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input id="active-checkbox" v-model="form.status" type="checkbox" value="Nomination Complete And Delivered">
<label class="form-check-label text-body text-truncate status-heading"> Nomination Complete &amp; Delivered</label>
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
