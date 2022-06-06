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
    error: String,
  },

  data() {
    return {
        form: {
         alter_date: null,
         alter_status: null
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
      this.$inertia.post(`/submit-altered-licence/${this.licence.id}`, this.form)
    },

    deleteAlteration(slug){
       if (confirm('Are you sure you want to delete this alteration?')) {
        this.$inertia.delete(`/delete-altered-licence/${slug}`)
      }
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

<div class="row">
<div class="col-lg-4 col-4">
<h6 class="mb-1">Alterations for:  {{ licence.trading_name }}</h6>
</div></div>


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
      <th class="ps-2 text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Id</th>
        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Alteraction Date</th>
        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Description</th>
        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Complete</th>
        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Action</th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="alter in licence.alterations" :key="alter.id">
        <td>
          <div class="d-flex px-2 py-1">
            <div class="d-flex flex-column justify-content-center">
              <h6 class="text-center">{{ alter.id }}</h6>
            </div>
          </div>
        </td>
        <td>
          {{ new Date(alter.date).toLocaleString().split(',')[0] }}
        </td>
         <td class="align-middle text-center">
         Description heree.....
        </td>
        
        <td class="align-middle text-center text-sm">
        <span v-if="alter.status == 'Pending'" class="badge bg-warning text-default">{{ alter.status }}</span>
        <span v-if="alter.status == 'Complete'" class="badge bg-success text-default">{{ alter.status }}</span>
        </td>
       
        <td class="align-end float-end">
        <div class="d-flex align-middle text-center">
        <Link  :href="`#!`" @click="deleteAlteration(alter.slug)"><i class="fa fa-trash-o  text-danger" aria-hidden="true"></i></Link>
        <Link  :href="`#!`"><i class="fa fa-eye px-1 text-secondary" aria-hidden="true"></i></Link>
        </div>
        </td>
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
<h6 class="mb-0" >New Alteration</h6>
</div>
<div class="p-3 card-body">
            <!-- Tabs content -->
<div class="tab-content" id="ex1-content">
<div class="tab-pane fade show active" id="ex1-tabs-1" role="tabpanel" aria-labelledby="ex1-tab-1">
<form @submit.prevent="submit">
<div class="col-12 columns">    
 <div class="input-group input-group-outline null is-filled">
  <label class="form-label">Alteration Date</label>
  <input v-model="form.alter_date" type="date" class="form-control form-control-default" >
   </div>
 <p v-if="errors.alter_date" class="text-danger">{{ errors.alter_date }}</p>
 </div>

 <div class="col-12 columns">    
 <div class="input-group input-group-outline null is-filled">
  <label class="form-label">Status</label>
  <select v-model="form.alter_status" class="form-control form-control-default">
  <option value="Pending">Pending</option>
  <option value="Complete">Complete</option>
  </select>
   </div>
<p v-if="errors.alter_status" class="text-danger">{{ errors.alter_status }}</p>
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