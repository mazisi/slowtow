<script>
import Layout from "../Shared/Layout.vue";

export default {
  name: "dashboard-default",
  props: ['companies'],
  data() {
    return {
      term: '',
      withThrashed: '', 
      active_status: ''  
    }
  },
  components: {
    Layout
},
methods: {
     search(){
        this.$inertia.replace(route('companies',{
          term: this.term,
          active_status: this.active_status
          }))
        },

      
    },
};
</script>
<style>
#with-thrashed{
  margin-top: -10px;
  margin-left: 3px;
}</style>
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
<div class="col-3">
<div class="input-group input-group-outline null is-filled">
<label class="form-label">Search Company </label>
<input v-model="term" @keyup="search" type="text" class="form-control form-control-default">
</div>
</div>
<div class="col-2">
<div class="input-group input-group-outline null is-filled">
<label class="form-label">Active status: </label>
<select @change="search" v-model="active_status" class="form-control form-control-default">
<option value="All">All</option>
<option value="Active">Active</option>
<option value="Inactive">Inactive</option>
</select>

</div>
</div>
<div class="col-3">
</div>

</div>
</form>
<div class=" my-4">
<div class="table-responsive p-0">
<table class="table align-items-center mb-0">
<thead>
<tr>
<th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
Active
</th>
<th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
Company Name
</th>
<th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
View
</th>

</tr>
</thead>
<tbody>
<tr v-for="company in companies" :key="company.id">
<td class="align-middle text-sm">
<i v-if="company.active == 1" class="fa fa-check text-info" aria-hidden="true"></i>

<i v-else class="fa fa-times text-danger" aria-hidden="true"></i>
</td>
<td>
<div class="d-flex px-2 py-1">

<div class="d-flex flex-column justify-content-left">
<h6 class="mb-0 text-sm">

<inertia-link
:href="`/view-company/${company.slug}`" class="px-0 nav-link font-weight-bold lh-1" :class="color ? color : 'text-body'">{{ company.name }}
</inertia-link>
</h6>                          
</div>
</div>
</td>
<td class="text-center">
<inertia-link :href="`/view-company/${company.slug}`"><i class="fa fa-eye  " aria-hidden="true"></i></inertia-link>

</td>

</tr>


</tbody>
</table>
</div>
</div>
</div>
</div>
</div>
  </Layout>
</template>
