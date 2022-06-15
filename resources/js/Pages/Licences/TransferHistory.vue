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
   <h5>Transfers for: {{ licence.trading_name }}</h5>
  </div>
  <div class="col-lg-6 col-5 my-auto text-end">
    <div class="dropdown float-lg-end pe-4">
     <Link :href="`/transfer-licence?slug=${$page.props.slug}`" class="btn btn-sm btn-secondary">New Transfer</Link>
    </div>
  </div>
</div>
        <div class=" my-4">
            <div class="table-responsive p-0">
              <table class="table align-items-center mb-0">
                <thead>
                  <tr>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                      ID
                    </th>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                    Current Company
                    </th>
                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                    Old Company
                    </th>
                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                    Licence Trading Name
                    </th>
                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                    Transfer Date
                    </th>
                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                    Status
                    </th>
                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                    Action
                    </th>
                    
                  </tr>
                </thead>
                <tbody>
                  <tr v-for=" (  currentCompany, index  )   in licence.transfers" :key="index" >
                    <td class="align-middle text-sm"><div class="d-flex px-2 py-1">{{currentCompany.id}}</div></td>
                    <td>
                      <div class="d-flex px-2 py-1" >
                       
                        <div class="d-flex flex-column justify-content-left">
                        <inertia-link :href="`/view-transfered-licence/${currentCompany.pivot.slug}`">
                          <h6 class="mb-0 text-sm">
                          {{currentCompany.name}}
                           </h6>    
                           </inertia-link>                      
                        </div>
                      </div>
                    </td>
                    <td class="text-center">{{ licence.old_company[index].name}}</td>
                     <td class="text-center">
                     {{ licence.trading_name }}
                    </td>
                     <td class="text-center"> {{ currentCompany.pivot.date}}</td>
                     <td>{{ currentCompany.pivot.status}}</td>
                      <td class="text-center">
                    <inertia-link :href="`/view-transfered-licence/${currentCompany.pivot.slug}`">
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
    licence: Object,
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

