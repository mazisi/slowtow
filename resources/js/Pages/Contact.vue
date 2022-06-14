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
  <div class="col-lg-6 col-7">
    <h6>Contacts</h6>
    <p class="text-sm mb-0"><span class="font-weight-bold ms-1">{{ contacts.length }}</span> total</p>
  </div>
  <div class="col-lg-6 col-5 my-auto text-end">
  <Link class="btn btn-sm btn-secondary mr-2" :href="`/upload-contacts`">Upload</Link>
    <Link class="btn btn-sm btn-danger" :href="`/`">Clear All</Link>
  </div>
</div>

        <div class=" my-4">
            <div class="table-responsive p-0">
              <table class="table align-items-center mb-0">
                <thead>
                  <tr>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                      First Name
                    </th>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                    Middle Name
                    </th>
                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                    Last Name
                    </th>
                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                    Business Phone
                    </th>
                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                    Mobile Phone
                    </th>
                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                    Email
                    </th>
                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                    Action
                    </th>
                    
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="contact in contacts" :key="contact.id">
                    <td class="align-middle text-sm">
                      {{ contact.first_name }}
                    </td>
                    <td>
                      <div class="d-flex px-2 py-1">
                       
                        <div class="d-flex flex-column justify-content-left">
                          <h6 class="mb-0 text-sm">
                           {{ contact.middle_name }}
                           </h6>                          
                        </div>
                      </div>
                    </td>
                    <td class="text-center"><h6 class="mb-0 text-sm">{{ contact.last_name }}</h6></td>
                    <td class="text-center">{{ contact.business_phone }}</td>
                    <td class="text-center">{{ contact.mobile_phone }}</td>
                    <td class="text-center">{{ contact.email }}</td>
                    <td class="text-center">
                    <Link :href='`#!`'><i @click="deleteSingleContact(contact.id)" class="fa fa-trash text-danger"></i></Link></td>
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
import Layout from "../Shared/Layout.vue";
import { useForm ,Link } from '@inertiajs/inertia-vue3';
export default {
  props: {
    contacts: Object,
    success: String,
    error: String,
    errors: Object,
  },
 components: {
    Layout,
    Link,
    useForm
},
methods: {
  deleteSingleContact(id){
    if(confirm('Are you sure??')){
        this.$inertia.post(`/delete-contact/${id}`)
    }
  }
},
}
</script>