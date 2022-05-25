<style>
  .table thead th {
    padding: 0;
    }
 
#with-thrashed{
  margin-top: 3px;
  margin-left: 3px;
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
     <div v-if="success" class="mb-4 font-medium text-sm text-green-600">
              {{ success }}
        </div>
    <div class="card card-body mx-3 mx-md-4 mt-n6">
      <div class="col-12">
      <form>
     <div class="row">
       <div class="col-3">
        <div class="input-group input-group-outline null is-filled">
  <label class="form-label">Search Licence </label>
  <input v-model="term" @keyup="search" type="text" class="form-control form-control-default">
   </div>
       </div>
       <div class="col-2">
        <div class="input-group input-group-outline null is-filled">
  <label class="form-label">Active status: </label>
  <select @change="search"  v-model="active_status" class="form-control form-control-default">
   <option value="Active">Active</option>
   <option value="Inactive">Inactive</option>
  </select>

   </div>
       </div>
        <div class="col-3">
        <div class="input-group ">
  <label class="">Include deleted licences: </label>
  <input @change="search" type="checkbox" id="with-thrashed" v-model="withThrashed">

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
      <th>Id</th>
      <th>Active</th>
      <th>Trading Name</th>
      <th>Liquor Licence</th>
       <th>Old Liquor Licence</th>
       <th>Company</th>
      <th>View</th>
        
    </tr>
  </thead>
  <tbody>
    <tr v-for="licence in licences" :key="licence.id">
      <th>{{ licence.id }}</th>
      <td>
      <i v-if="licence.licence_status == 1" class="fa fa-check text-info" aria-hidden="true"></i>
      <i v-else class="fa fa-times text-danger" aria-hidden="true"></i>
      </td>
      <td>{{ licence.trading_name }}</td>
      <td>{{ licence.licence_number }}</td>
      <td>{{ licence.old_licence_number }}</td>
      <td>{{ licence.company.name }}</td>
      <td><Link :href="`/view-licence/${licence.slug}`"><i class="material-icons me-sm-1">visibility </i></Link></td>
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
<script>
import Layout from "../Shared/Layout.vue";
import { Link } from '@inertiajs/inertia-vue3';
export default {
  props: {
    licences: Object,
    success: String,
  },
 components: {
    Layout,
    Link
},
 data() {
    return {
      term: '',
      withThrashed: '', 
      active_status: ''  
    }
  },
methods: {
  search(){
    this.$inertia.replace(route('licences',{
      term: this.term,
      withThrashed: this.withThrashed,
      active_status: this.active_status
      }))
    },
},
}
</script>