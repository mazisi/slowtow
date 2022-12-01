<template>
<Layout>
<div class="container-fluid">
<Banner/>
<div class="card card-body mx-3 mx-md-4 mt-n6">
<div class="row gx-4">
<div class="col-auto">

</div>
<div class="row">
<div class="col-lg-12">
<h6 class="mb-1">
   <Link :href="`/view-licence?slug=${licence.slug}`" class="text-success">{{ licence.trading_name }}</Link>
   Renewals</h6>
</div>



</div>


</div>
<div class="row">

<div class="mt-3 row">
<div class="col-12 col-md-6 col-xl-8 position-relative">
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
<tr v-for="renewal in renewals" :key="renewal.id">
<td>
<div class="d-flex px-2 py-1">
<div class="d-flex flex-column justify-content-center">
<Link :href="`/view-licence-renewal/${renewal.slug}`"><h6 class="text-center">{{ renewal.date }}</h6></Link>
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
<Link :href="`/view-licence-renewal/${renewal.slug}`"><i class="fa fa-eye px-1 text-secondary" aria-hidden="true"></i></Link>
<!-- <Link :href="`#!`" @click="deleteRenewal(renewal.slug)"><i class="fa fa-trash-o  text-danger" aria-hidden="true"></i></Link> -->
</div>
</td>
</tr>

<tr v-if="!renewals">
<td></td>
<td></td>
<td><h6>No renewals found for this licence.</h6></td>
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

<div class="mt-4 col-12 col-xl-4 mt-xl-0">
<div class="card card-plain h-100">
<div class="p-3 pb-0 card-header">
<h6>Process Renewal For: {{ new Date().getFullYear() }}</h6>
</div>
<div class="p-3 card-body">
<!-- Tabs content -->
<div class="tab-content" id="ex1-content">
<div class="tab-pane fade show active" id="ex1-tabs-1" role="tabpanel" aria-labelledby="ex1-tab-1">
<form @submit.prevent="submit">
<Datepicker v-model="form.year" yearPicker />
<p v-if="errors.year" class="text-danger">{{ errors.year }}</p>
<button type="submit" class="btn btn-sm btn-secondary mt-2 float-end justify-content-center">Submit</button>
</form>

</div>
</div>
</div>
</div>
</div>
</div>

</div>
</div>
</div>
</Layout>
</template>

<script>
import Layout from "../../Shared/Layout.vue";
import { Head,Link,useForm } from '@inertiajs/inertia-vue3';
import Datepicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';
import { Inertia } from '@inertiajs/inertia';
import Banner from '../components/Banner.vue';

import { ref } from 'vue';

export default {
  props: {
    errors: Object,
    licence_dropdowns: Object,
    licence: Object,
    success: String,
    error: String,
    renewals: Object
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

    function deleteRenewal(slug){
       if (confirm('Are you sure you want to delete this renewal?')) {
      this.$inertia.delete(`/delete-licence-renewal/${slug}`)
    }
    }

    return { year,form, submit, deleteRenewal }
  },
   components: {
    Layout,
    Link,
    Head,
    Datepicker,
    Banner
  },
  
};
</script>
