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
  <div class="col-2">
    <h6>Contacts</h6>
    
  </div>
  <div class="col-8">
  <div class="input-group input-group-outline null is-filled">
<input v-model="q" @keyup="search" type="text" class="form-control form-control-default" placeholder="Search by full name and surname">
</div>
  </div>
  <div class="col-2 my-auto text-end">
  <div class="dropdown float-lg-end pe-4"><a class="cursor-pointer" id="dropdownTable" 
  data-bs-toggle="dropdown" aria-expanded="false">
  <i class="fa fa-ellipsis-v text-secondary" aria-hidden="true"></i></a>
  <ul class="dropdown-menu px-2 py-3 ms-sm-n4 ms-n5" aria-labelledby="dropdownTable" style="">
  <li><Link class="dropdown-item border-radius-md" :href="`/upload-contacts`"> Upload </Link></li>
  <li><Link class="dropdown-item border-radius-md" :href="`/create-contact`"> Create Contact </Link></li>
  </ul>
  </div>
  </div>
</div>

        <div class=" my-4">
            <div class="table-responsive p-0">
              <table class="table align-items-center mb-0">
                <thead>
                  <tr>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                      First Names
                    </th>
                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                    Surname
                    </th>
                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                    Mobile Number
                    </th>
                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                    Business Number
                    </th>                    
                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                    Email Address
                    </th>
                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                    View
                    </th>
                    
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="contact in contacts" :key="contact.id">
                    <td class="align-middle text-sm">
                      <Link :href='`/view-contact/${contact.id}`'><h6 class="mb-0 text-sm">{{ contact.first_name }}</h6></Link>
                    </td>
                    <td class="text-center"><Link :href='`/view-contact/${contact.id}`'><h6 class="mb-0 text-sm">{{ contact.last_name }}</h6></Link></td>
                    <td class="text-center">{{ contact.mobile_phone }}</td>
                    <td class="text-center">{{ contact.business_phone }}</td>
                    <td class="text-center">{{ contact.email }}</td>
                    <td class="text-center">
                    <Link :href='`/view-contact/${contact.id}`'><i class="fa fa-eye"></i></Link></td>
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
<script>
import Layout from "../../Shared/Layout.vue";
import { Link } from '@inertiajs/inertia-vue3';

export default {
  props: {
    contacts: Object,
    success: String,
    error: String,
    errors: Object,
  },
  data() {
    return {q: '' }
  },
 components: {
    Layout,
    Link
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