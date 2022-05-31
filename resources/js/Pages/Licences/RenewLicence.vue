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
         renewal_date: null,
         renewal_status: null
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
      this.$inertia.post(`/sumbmit-licence-renewal/${this.licence.id}/${this.licence.slug}`, this.form)
    },

    getRenewalYear(date){
      let computed_date = new Date(date).getFullYear();
      return computed_date + 1;    
    }
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
<div class="col-lg-4 col-4">
<h6 class="mb-1">Renewal Information:  {{ licence.trading_name }}</h6>
</div>

<div class="col-lg-6 col-4" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">
<div v-if="success" class="alert text-white alert-success alert-dismissible fade show font-weight-light" role="alert">
<span class="alert-icon"><i class=""></i></span><span class="alert-text"> 
<span class="text-sm">{{ success }}</span></span>
</div>

<div v-if="error" class="alert text-white alert-primary alert-dismissible fade show font-weight-light" role="alert">
<span class="alert-icon"><i class=""></i></span>
<span class="alert-text"> 
<span class="text-sm">{{ error }}</span></span>
</div>
</div>


</div>


</div>
<div class="row">

<div class="mt-3 row">
<div class="col-12 col-md-6 col-xl-9 position-relative">
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
      <tr v-for="renewal in renewals" :key="renewal.id">
        <td>
          <div class="d-flex px-2 py-1">
            <div class="d-flex flex-column justify-content-center">
              <h6 class="text-center">{{ new Date(renewal.date).toLocaleString().split(',')[0] }}</h6>
            </div>
          </div>
        </td>
        <td>
          {{ this.getRenewalYear(renewal.date)  }}
        </td>
        <td class="align-middle text-center text-sm">
        <span v-if="renewal.status == 'Pending'" class="badge bg-warning text-default">{{ renewal.status }}</span>
        <span v-if="renewal.status == 'Processed'" class="badge bg-primary text-default">{{ renewal.status }}</span>
        <span v-if="renewal.status == 'Complete'" class="badge bg-success text-default">{{ renewal.status }}</span>
        </td>
        <td class="align-middle text-center">
          ????
        </td>
        <td class="align-middle text-center">
        <Link class="btn btn-sm btn-secondary" :href="`/view-temp-licence/${renewal.slug}`">View</Link>
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

<div class="mt-4 col-12 col-xl-3 mt-xl-0">
<div class="card card-plain h-100">
<div class="p-3 pb-0 card-header">
<h6 class="mb-0" >New Renewal</h6>
</div>
<div class="p-3 card-body">
            <!-- Tabs content -->
<div class="tab-content" id="ex1-content">
<div class="tab-pane fade show active" id="ex1-tabs-1" role="tabpanel" aria-labelledby="ex1-tab-1">
<form @submit.prevent="submit">
<div class="col-12 columns">    
 <div class="input-group input-group-outline null is-filled">
  <label class="form-label">Renewal Date</label>
  <input v-model="form.renewal_date" type="date" class="form-control form-control-default" >
   </div>
 <p v-if="errors.renewal_date" class="text-danger">{{ errors.renewal_date }}</p>
 </div>

 <div class="col-12 columns">    
 <div class="input-group input-group-outline null is-filled">
  <label class="form-label">Status</label>
  <select v-model="form.renewal_status" class="form-control form-control-default">
  <option value="Pending">Pending</option>
  <option value="Processed">Processed</option>
  <option value="Complete">Complete</option>
  </select>
   </div>
<p v-if="errors.renewal_status" class="text-danger">{{ errors.renewal_status }}</p>
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