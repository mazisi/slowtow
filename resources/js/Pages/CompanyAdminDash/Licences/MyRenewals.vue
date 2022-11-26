<template>
    <Layout>
    <div class="container-fluid">
    <div class="page-header min-height-100 border-radius-xl" style="background-image: url('https://images.unsplash.com/photo-1531512073830-ba890ca4eba2?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=crop&w=1920&q=80');
    ">
    <span class="mask bg-gradient-success opacity-6"></span>
    </div>
    <div class="card card-body mx-3 mx-md-4 mt-n6">
    <div class="row gx-4">
    <div class="col-auto">
    
    </div>
    <div class="row">
    <div class="col-lg-12">
    <h6 class="mb-1">
        {{ licence.trading_name }} Renewals</h6>
    </div>
    
    
    
    </div>
    </div>
    <div class="row">
    
    <div class="mt-3 row">
    <div class="col-12 col-md-12 col-xl-12 position-relative">
    <div class="card card-plain h-100">
    <div class="p-3 card-body">
    
    <div class="table-responsive">
    <table class="table align-items-center mb-0">
    <thead>
    <tr>
    <th class="ps-2 text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Renewal Year</th>
    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Renewal Status</th>
    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">View</th>
    </tr>
    </thead>
    <tbody>
    <tr v-for="renewal in licence.licence_renewals" :key="renewal.id">
    <td>
    <div class="d-flex px-2 py-1">
    <div class="d-flex flex-column justify-content-center">
    <Link :href="`/company/view-my-licence-renewal/${renewal.slug}`"><h6 class="text-center">{{ renewal.date }}</h6></Link>
    </div>
    </div>
    </td>
   
    <td class="align-middle text-center text-sm">
    <span v-if="renewal.status == '1'" class="badge bg-dark text-default">Client Quoted</span>
    <span v-if="renewal.status == '2'" class="badge bg-info text-default">Client Invoiced</span>
    <span v-if="renewal.status == '3'" class="badge bg-light text-dark">Client Paid</span>
    <span v-if="renewal.status == '4'" class="badge bg-warning text-default">Payment To The Liquor Board</span>
    <span v-if="renewal.status == '5'" class="badge bg-secondary text-default">Renewal Issued</span>
    <span v-if="renewal.status == '6'" class="badge bg-success text-default">Renewal Delivered</span>
    </td>
    <td class="align-middle text-end" >
    <div class="d-flex justify-content-center">
    <Link :href="`/company/view-my-licence-renewal/${renewal.slug}`"><i class="fa fa-eye px-1 text-secondary" aria-hidden="true"></i></Link>
    </div>
    </td>
    </tr>
    
    <tr v-if="!licence.licence_renewals">
    <td></td>
    <td></td>
    <td><h6>No licence renewals found for this licence.</h6></td>
    <td></td>
    <td></td>
    </tr>
    
    </tbody>
    </table>
    </div>
    
    </div>
    </div>
    <hr class="vertical dark" />
    </div>
    
    </div>
    
    </div>
    </div>
    </div>
    </Layout>
    </template>
    
    <script>
    import Layout from "../../../Shared/Layout.vue";
    import { Head,Link,useForm } from '@inertiajs/inertia-vue3';
    import { Inertia } from '@inertiajs/inertia';
    
    import { ref } from 'vue';
    
    export default {
      props: {
        errors: Object,
        licence: Object,
        success: String,
        error: String,
      },
    
      setup (props) {
        const year = ref(new Date().getFullYear());
    
        const form = useForm({
          year: null,
          licence_id: props.licence.id
        })
    
        function submit() {
          Inertia.post('/submit-licence-renewal', form)
        }
    
        return { year,form, submit }
      },
       components: {
        Layout,
        Link,
        Head,
      },
      
    };
    </script>
    