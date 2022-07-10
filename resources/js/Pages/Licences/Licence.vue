<template>
<Layout>
<div class="container-fluid">
<div class="page-header min-height-100 border-radius-xl mt-4" style="background-image: url('https://images.unsplash.com/photo-1531512073830-ba890ca4eba2?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=crop&w=1920&q=80');
">
<span class="mask bg-gradient-success opacity-6"></span>
</div>
<div class="card card-body mx-3 mx-md-4 mt-n6">
<div class="col-12">
<form>
<div class="row">
<div class="col-md-6 col-xl-4 col-lg-4">
<div class="input-group input-group-outline null is-filled">
<label class="form-label">Search Licence </label>
<input v-model="term" @keyup="search" type="text" class="form-control form-control-default">
</div>
</div>
<div class="col-md-6 col-xl-3 col-lg-3">
<div class="input-group input-group-outline null is-filled">
<label class="form-label">Filter: </label>
<select @change="search" v-model="active_status" class="form-control form-control-default">
<option value="All">All</option>
<option value="Active">Active</option>
<option value="Inactive">Inactive</option>
</select>

</div>
</div>

</div>
</form>
<div class="card my-4">

<div class="card-body px-0 pb-2">
<div class="table-responsive p-0">
  <table class="table table-striped table-hover">
<thead>
<tr>
<th>Trading Name</th>
<th>Licence Number</th>
<th>Licence Date</th>
<th>Licence Type</th>
<th>Company</th>
<th>View</th>
</tr>
</thead>
<tbody>
<tr v-for="licence in licences" :key="licence.id">
<td><Link :href="`/view-licence?slug=${licence.slug}`">{{ licence.trading_name }}</Link></td>
<td><Link :href="`/view-licence?slug=${licence.slug}`">{{ licence.licence_number }}</Link></td>
<td><Link :href="`/view-licence?slug=${licence.slug}`">{{ licence.licence_date }}</Link></td>
<td><Link :href="`/view-licence?slug=${licence.slug}`">{{ licence.licence_type.licence_type }}</Link></td>
<td><Link :href="`/view-licence?slug=${licence.slug}`">{{ licence.company.name }}</Link></td>
<td class="text-center">
<Link :href="`/view-licence?slug=${licence.slug}`"><i class="fa fa-eye  " aria-hidden="true"></i></Link>

</td>
</tr>
</tbody>
</table>
</div>
</div>
</div>
</div>
</div>
</div>
</Layout>
</template>

<style>

  .table thead th {
    padding: 0;
    }
 
    #with-thrashed{
      margin-top: 3px;
      margin-left: 3px;
    }
</style>

<script>
import Layout from "../../Shared/Layout.vue";
import { Link } from '@inertiajs/inertia-vue3';


export default {
    props: {
      licences: Object,
      success: String,
      error: String,
      errors: Object,
    },

    components: {
        Layout,
        Link
    },

    data() {
        return {
          term: '',
          all: '', 
          active_status: ''  
        }
    },

    methods: {
      search(){
         this.$inertia.replace(route('licences',{
          term: this.term,
          active_status: this.active_status,
          }))
        },
    },
}
</script>