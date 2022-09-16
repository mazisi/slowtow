<style>
 
  .table thead th {
    padding: 0;
    }
    .upload-btn{
      float: right!important;
    }
</style>
<template>
<Layout>
  <div class="container-fluid">
<div class="page-header min-height-100 border-radius-xl mt-4"
      style="background-image: url('https://images.unsplash.com/photo-1531512073830-ba890ca4eba2?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=crop&w=1920&q=80');
      ">
      <span class="mask bg-gradient-success opacity-6"></span>
    </div>
   
    <div class="card card-body mx-3 mx-md-4 mt-n6">
      <div class="col-12">
      <div class="row">
  <div class="col-10">
    <h6>Licence Renewals</h6>
    
  </div>
  <div class="col-2 my-auto text-end">
  <div class="dropdown float-lg-end pe-4">
  <a href="/renewal-export" class="btn btn-sm btn-success">Export</a>
  </div>
  </div>
</div>
<nav>
  <div class="nav nav-tabs" id="nav-tab" role="tablist">
    <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true">Emails</button>
    <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile" aria-selected="false">Renewals</button>
    <button class="nav-link" id="nav-contact-tab" data-bs-toggle="tab" data-bs-target="#nav-contact" type="button" role="tab" aria-controls="nav-contact" aria-selected="false">Contact</button>
  </div>
</nav>
<div class="tab-content" id="nav-tabContent">
  <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
    <EmailComponent :emails="emails"/> 
  </div>



  <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
    <RenewalComponent :renewals="renewals"/>
  </div>
  <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">...</div>
</div>

           
      </div>
      <!-- <paginate :links="renewals.links"/> -->
    </div>
  </div>

  </Layout>
</template>
<script>
import Layout from "../Shared/Layout.vue";
import Paginate from "../Shared/Paginate.vue";
import { Link } from '@inertiajs/inertia-vue3';
import EmailComponent from "./Reporting/EmailComponent.vue";
import RenewalComponent from "./Reporting/RenewalComponent.vue";

export default {
  props: {
    renewals: Object,
    emails: Object,
    success: String,
    error: String,
    errors: Object,
  },
  data() {
    return {q: '' }
  },
 components: {
    Layout,
    Link,
    Paginate,
    EmailComponent,
    RenewalComponent
},
methods: {
  deleteSingleContact(id){
    if(confirm('Are you sure??')){
        this.$inertia.delete(`/delete-contact/${id}`)
    }
  },
  search(){
     this.$inertia.replace(route('contacts',{q: this.q}))
},
},

}
</script>