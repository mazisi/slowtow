<template>
<Layout>
  <div class="container-fluid">
    <div class="page-header min-height-100 border-radius-xl mt-4" style="background-image: url('https://images.unsplash.com/photo-1531512073830-ba890ca4eba2?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=crop&w=1920&q=80');
      ">
      <span class="mask bg-gradient-success opacity-6"></span>
    </div>
    <div class="card card-body mx-3 mx-md-4 mt-n6">
      <div class="col-12">
      <div class="row">
  <div class="col-lg-6 col-7">
   <h5>Consultants</h5>
  </div>
  <div class="col-lg-6 col-5 my-auto text-end">
    <div class="dropdown float-lg-end pe-4">
     <div class="input-group input-group-outline null is-filled">
    <input type="text" class="form-control form-control-default" placeholder="Search...">
   </div>
    </div>
  </div>
</div>
        <div class=" my-4">
            <div class="table-responsive p-0">
              <table class="table align-items-center mb-0">
                <thead>
                  <tr>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                    First Name
                    </th>
                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                    Last Name
                    </th>
                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                    Company
                    </th>
                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                    Position
                    </th>
                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                    Percentage
                    </th>
                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                    Action
                    </th>
                    
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="consultant in consultants" :key="consultant.id">
                    <td class="align-middle text-sm">
                   <inertia-link class="" :href="`#!`" >
                          <h6 class="mb-0 text-sm">
                         {{ consultant.first_name }}
                           </h6>    
                    </inertia-link>
                    </td>
                   
                   <td class="text-center">
                        <inertia-link class="text-center" :href="`#!`" >
                          <h6 class="mb-0 text-sm">
                         {{ consultant.last_name }}
                           </h6>    
                          </inertia-link>                         
                    </td>

                    <td v-for="get_company in consultant.companies" class="text-center">
                     <inertia-link class="text-center" :href="`#!`" >
                          <h6 class="mb-0 text-sm">
                         {{ get_company.name }}
                           </h6>    
                          </inertia-link> 
                     </td>

                    <td v-for="position in consultant.companies" class="text-center">
                    {{ position.pivot.position }}
                    </td>

                    <td v-for="percentage in consultant.companies" class="text-center">
                    {{ percentage.pivot.percentage }}
                    </td>
                      <td class="text-center">
                    <inertia-link :href="`#`">
                    <i class="fa fa-eye  " aria-hidden="true"></i>
                    </inertia-link>
                      
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

<script>
import Layout from "../../Shared/Layout.vue";
import { Link } from '@inertiajs/inertia-vue3'

export default {
  name: "dashboard-default",
  props: {
    consultants: Object,
    success: String,
    error: String,
    errors: Object
  },
  
  components: {
    Layout,
    Link
    },

};
</script>

