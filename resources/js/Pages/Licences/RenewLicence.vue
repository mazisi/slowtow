<script>
import Layout from "../../Shared/Layout.vue";
import { Head,Link } from '@inertiajs/inertia-vue3';

export default {
name: "dashboard-default",
  props: {
    errors: Object,
    licence_dropdowns: Object,
    licence: Object,
    success: String,
    renewals: Object
  },

  data() {
    return {
        form: {
         renewal_date: '',
         licence_id: this.licence.id,
      },
      showMenu: false,
    };
  },
  components: {
    Layout,
    Link,
    Head,
  },

  methods: {
    submit() {
      this.$inertia.post(`/submit-licence-renewal`, this.form)
    },

    getRenewalYear(date){
      let computed_date = new Date(date).getFullYear();
      return computed_date + 1;    
    },
    deleteRenewal(slug){
      if (confirm('Are you sure you want to delete this renewal?')) {
      this.$inertia.delete(`/delete-licence-renewal/${slug}`)
    }
    },
    beforeUnmount() {
    this.$store.state.isAbsolute = false;
  },
  
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
<div class="col-lg-12">
<h6 class="mb-1">Renewal Information:  {{ licence.trading_name }}</h6>
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
        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Action</th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="renewal in renewals" :key="renewal.id">
        <td>
          <div class="d-flex px-2 py-1">
            <div class="d-flex flex-column justify-content-center">
              <h6 class="text-center">{{ renewal.date }}</h6>
            </div>
          </div>
        </td>
        <td>
          {{ this.getRenewalYear(renewal.date)  }}
        </td>
        <td class="align-middle text-center text-sm">
        <span v-if="renewal.status == 'Invoiced'" class="badge bg-dark text-default">{{ renewal.status }}</span>
        <span v-if="renewal.status == 'Paid'" class="badge bg-info text-default">{{ renewal.status }}</span>
        <span v-if="renewal.status == 'Get Client Docs'" class="badge bg-light text-default">{{ renewal.status }}</span>
        <span v-if="renewal.status == 'Awaiting Liquor Board'" class="badge bg-warning text-default">{{ renewal.status }}</span>
        <span v-if="renewal.status == 'Issued'" class="badge bg-secondary text-default">{{ renewal.status }}</span>
        <span v-if="renewal.status == 'Complete'" class="badge bg-success text-default">{{ renewal.status }}</span>
        </td>
        <td class="align-middle text-end">
        <div class="d-flex align-middle text-end">
        <Link  :href="`#!`" @click="deleteRenewal(renewal.slug)"><i class="fa fa-trash-o  text-danger" aria-hidden="true"></i></Link>
        <Link  :href="`/view-temp-licence/`"><i class="fa fa-eye px-1 text-secondary" aria-hidden="true"></i></Link>
        </div>
        </td>
      </tr>

      <tr v-if="!renewals">
        <td></td>
        <td></td>
        <td><h6>No renewals found for this licence.</h6></td>
        <td></td>
        <td></td>
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
<h6>Process Renewal For: {{ new Date().getFullYear() }}</h6>
</div>
<div class="p-3 card-body">
            <!-- Tabs content -->
<div class="tab-content" id="ex1-content">
<div class="tab-pane fade show active" id="ex1-tabs-1" role="tabpanel" aria-labelledby="ex1-tab-1">
<form @submit.prevent="submit">
<div class="col-12 columns">    
 <div class="input-group input-group-outline null is-filled">
  <label class="form-label">Renewal Date</label>
  <input v-model="form.renewal_date" type="date" min="2022-12-01" class="form-control form-control-default" >
 </div>
 <p v-if="errors.renewal_date" class="text-danger">{{ errors.renewal_date }}</p>
 </div>

 

<button type="submit" class="btn btn-sm btn-secondary ms-2 float-end justify-content-center">Save</button>
</form>

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