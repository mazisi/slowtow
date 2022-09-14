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
  <div class="col-10">
    <h6>Licence Renewals</h6>
    
  </div>
  <div class="col-2 my-auto text-end">
  <div class="dropdown float-lg-end pe-4">
  <a href="/renewal-export" class="btn btn-sm btn-success">Export</a>
  </div>
  </div>
</div>

        <div class=" my-4">
            <div class="table-responsive p-0">
              <table class="table align-items-center mb-0">
                <thead>
                  <tr>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                      Active/Deactive
                    </th>
                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                    Trading Name
                    </th>
                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                    Licence Number
                    </th>
                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                    Renewal Date
                    </th>                    
                    
                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                    Quoted
                    </th>
                    
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="renewal in renewals.data">
                    <td class="align-middle text-sm">
                      <i v-if="renewal.licence.is_licence_active === '1'" class="fa fa-check text-success" aria-hidden="true"></i>
                      <i v-else class="fa fa-times text-danger" aria-hidden="true"></i>
                      
                    </td>
                    <td class="text-center text-sm">{{ renewal.licence.trading_name }}</td>
                    <td class="text-center text-sm">{{ renewal.licence.licence_number }}</td>
                    <td class="text-center text-sm">{{ renewal.date }}</td>
                    <td v-if="renewal.renewal_documents[0] != null" class="text-center text-sm">True</td>
                    <td v-else class="text-center text-sm">False</td>
                  </tr>
                  
                 
                </tbody>
              </table>
            </div>
          </div>          
      </div>
      <paginate :links="renewals.links"/>
    </div>
  </div>

  </Layout>
</template>
<script>
import Layout from "../Shared/Layout.vue";
import Paginate from "../Shared/Paginate.vue";
import { Link } from '@inertiajs/inertia-vue3';

export default {
  props: {
    renewals: Object,
    success: String,
    error: String,
    errors: Object,
  },
  data() {
    return {q: '' }
  },
 components: {
    Layout,
    Link,
    Paginate
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