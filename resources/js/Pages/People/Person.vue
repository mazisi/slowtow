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
   
    <div class="card card-body mx-3 mx-md-4 mt-n6">
      <div class="col-12">
 
     <div class="row mb-4">
       <div class="col-3">
        <div class="input-group input-group-outline null is-filled">
  <label class="form-label">Search Person </label>
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
  <label class="">Include deleted people: </label>
  <input @change="search" type="checkbox" id="with-thrashed" v-model="withThrashed">

   </div>
 </div>
       
     </div>
        <div class="">
          <div class=" px-0 pb-2">
          <div class="table-responsive p-0">
<table class="table align-items-center mb-0">
  <thead>
    <tr>
      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ID</th>
      <th class="ps-2 text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Active</th>
      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Person</th>
      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Action</th>
    </tr>
  </thead>
  <tbody>
    <tr v-for="person in people" :key="person.id">
      <td>
        <div class="d-flex px-2 py-1">
          <div class="d-flex flex-column ">
            <h6 class="mb-0 text-sm">{{ person.id }}</h6>
          </div>
        </div>
      </td>
      <td>
        <div class="avatar-group mt-2">
         <i v-if="person.active" class="fa fa-check text-info" aria-hidden="true"></i>
         <i v-else class="fa fa-times text-danger" aria-hidden="true"></i>
        </div>
      </td>
      <td class="align-middle text-center text-sm">
      <Link :href="`/view-person/${person.slug}`">
      <h6 class="mb-0 text-sm">{{ person.full_name }}</h6></Link>
      </td>
      <td class="align-middle d-flex justify-content-center">
      <Link :href="`/view-person/${person.slug}`" class="px-0 nav-link font-weight-bold lh-1 text-body" href="">
      <i class="material-icons me-sm-1">visibility </i></Link>
      </td>
    </tr>
  
  </tbody>
</table>

            </div>
          </div>
          
        </div>
        {{ links }}
      </div>
      <!-- <nav aria-label="Page navigation float-right">
  <ul class="pagination">
    <li class="page-item"><a v-if="links.length > 3" class="page-link" href="#">Prev</a></li>
    <li v-for="(link, key) in links" class="page-item"><a class="page-link" href="#">1</a></li>
    <li class="page-item"><a class="page-link" href="#">Next</a></li>
  </ul>
</nav> -->
    </div>
    
  </div>

  </Layout>
</template>
<script>
import Layout from "../../Shared/Layout.vue";
import { useForm ,Link } from '@inertiajs/inertia-vue3';
export default {
  props: {
    success: String,
    people: Object,
    links: Array,
  },
  data(){
    return{
       term: '',
      withThrashed: '', 
      active_status: '' 
    }
  },
  methods: {
    search(){
    this.$inertia.replace(route('people',{
      term: this.term,
      withThrashed: this.withThrashed,
      active_status: this.active_status
      }))
    },
  },
 components: {
    Layout,
    Link,
    useForm
},
}
</script>