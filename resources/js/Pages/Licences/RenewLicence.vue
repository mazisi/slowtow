<script>
import Layout from "../../Shared/Layout.vue";
import { Head,Link } from '@inertiajs/inertia-vue3';
import { HalfCircleSpinner } from 'epic-spinners';

export default {
name: "dashboard-default",
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
        //  trading_name: this.licence.trading_name,
        //  licence_type: this.licence.licence_type,
        //  is_licence_active: this.licence.licence_status,
        //  file_number: this.licence.file_number,
        //  licence_number: this.licence.licence_number,
        //  old_licence_number: this.licence.old_licence_number,
        //  licence_date: this.licence.licence_date,
        //  licence_expiry_date: this.licence.licence_expiry_date,
        //  account_number: this.licence.account_number,
        //  address: this.licence.address,
        //  province: this.licence.province,
        //  consultant_name: this.licence.consultant_name,
        //  must_renew: this.licence.must_renew,
        //  notes: this.licence.notes,
      },
     
      showMenu: false,
    };
  },
  components: {
    HalfCircleSpinner,
    Layout,
    Link,
    Head,
  },

  methods: {

    updateLicence() {
      this.loading=true;
      this.$inertia.post(`/update-licence/`, this.form)
       .then(() => {
          this.loading=false;
        })
    },

  },
  beforeUnmount() {
    this.$store.state.isAbsolute = false;
  },
};
</script>
<style>
#active-checkbox{
  margin-top: 3px;
  margin-left: 3px;
}
.columns{
  margin-bottom: 1rem;
}</style>

<template>
<Layout>
<div class="container-fluid">
<div class="page-header min-height-100 border-radius-xl" style="background-image: url('https://images.unsplash.com/photo-1531512073830-ba890ca4eba2?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=crop&w=1920&q=80');
">
<span class="mask bg-gradient-success opacity-6"></span>
</div>
<div class="card card-body mx-3 mx-md-4 mt-n6">
<div class="row gx-4">
<div class="col-auto">

</div>
<div class="row">
<div class="col-lg-6 col-7">
<h6 class="mb-1">View Licence for: mmmmm</h6>
</div>
<div class="col-lg-6 col-5 my-auto text-end">
<div class="dropdown float-lg-end pe-4">
<a class="cursor-pointer" id="dropdownTable" data-bs-toggle="dropdown" aria-expanded="false">
 <i class="fa fa-ellipsis-v text-secondary" aria-hidden="true"></i>
</a>
<ul class="dropdown-menu px-2 py-3 ms-sm-n4 ms-n5" aria-labelledby="dropdownTable">
<li><Link :href="`/renew-licence/`" class="dropdown-item border-radius-md">Renew Licence</Link></li>
<li><Link :href="`/nominate/`" class="dropdown-item border-radius-md">Nominate</Link></li>
<li><Link :href="`/nominations/`" class="dropdown-item border-radius-md"> Nominations</Link></li>
<li><Link :href="`/transfer-licence/`" class="dropdown-item border-radius-md"> Transfer Licence</Link></li>
<li><a class="dropdown-item border-radius-md text-danger" ><i class="fa fa-trash-o cursor-pointer" aria-hidden="true"></i> Delete</a></li>
</ul>
</div>
</div>
</div>


</div>
<div class="row">

<div class="mt-3 row">
<div class="col-12 col-md-6 col-xl-8 position-relative">
<div class="card card-plain h-100">
<div class="p-3 card-body">

<div class="table-responsive">
  <table class="table align-items-center mb-0">
    <thead>
      <tr>
        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Renewal Date</th>
        <th class="ps-2 text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Renewal Year</th>
        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Renewal Status</th>
        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Renewal Drocess Date</th>
        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Action</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>
          <div class="d-flex px-2 py-1">
            <div class="d-flex flex-column justify-content-center">
              <h6 class="mb-0 text-sm">12/09/2022</h6>
            </div>
          </div>
        </td>
        <td>
          2022
        </td>
        <td class="align-middle text-center text-sm">Success</td>
        <td class="align-middle d-flex justify-content-end">
          23/06/22
        </td>
        <td>View</td>
      </tr>
     
    </tbody>
  </table>
</div>

</div>
</div>
<hr class="vertical dark" />
</div>

<div class="mt-4 col-12 col-xl-4 mt-xl-0">
<div class="card card-plain h-100">
<div class="p-3 pb-0 card-header">
<h6 class="mb-0" >New Renewal</h6>
</div>
<div class="p-3 card-body">
            <!-- Tabs content -->
<div class="tab-content" id="ex1-content">
<div class="tab-pane fade show active" id="ex1-tabs-1" role="tabpanel" aria-labelledby="ex1-tab-1">

<div class="col-12 columns">    
 <div class="input-group input-group-outline null is-filled">
  <label class="form-label">Home Address</label>
  <input type="text" class="form-control form-control-default" >
   </div>
 
 </div>

 <div class="col-12 columns">    
 <div class="input-group input-group-outline null is-filled">
  <label class="form-label">Home Address</label>
  <input type="text" class="form-control form-control-default" >
   </div>
 
 </div>
 <div class="col-12 columns">    
 <div class="input-group input-group-outline null is-filled">
  <label class="form-label">Home Address</label>
  <input type="text" class="form-control form-control-default" >
   </div>
 
 </div>
<button type="button" class="btn btn-sm btn-secondary ms-2 d-flex justify-content-center">Save</button>


<hr>
</div>
</div>
</div>
</div>
</div>
</div>

</div>
</div>
</div>


</Layout>
</template>