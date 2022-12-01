<script>
import Layout from "../../Shared/Layout.vue";
import { Head,Link } from '@inertiajs/inertia-vue3';
import Banner from '../components/Banner.vue'

export default {
name: "dashboard-default",
  props: {
    errors: Object,
    licence_dropdowns: Object,
    licence: Object,
    success: String,
    error: String,
  },

  data() {
    return {
        form: {
         alter_date: null,
         alter_status: null
      },
      showMenu: false,
    };
  },
  components: {
    Layout,
    Link,
    Head,
    Banner
  },

  methods: {
    submit() {
      this.$inertia.post(`/submit-altered-licence/${this.licence.id}`, this.form)
    },

    deleteAlteration(slug){
       if (confirm('Are you sure you want to delete this alteration?')) {
        this.$inertia.delete(`/delete-altered-licence/${slug}`)
      }
    }

  },
  beforeUnmount() {
    this.$store.state.isAbsolute = false;
  },
};
</script>
<style>
#active-checkbox{
  margin-top: 3px;
  margin-left: 3px;
}
.columns{
  margin-bottom: 1rem;
}</style>

<template>
<Layout>
<div class="container-fluid">
<Banner/>
<div class="card card-body mx-3 mx-md-4 mt-n6">
<div class="row gx-4">

<div class="row">
  <div class="col-lg-6 col-7">
   <h6 class="mb-1">Alterations for:  {{ licence.trading_name }}</h6>
  </div>
  <div class="col-lg-6 col-5 my-auto text-end">
    <Link :href="`/new-alteration?slug=${licence.slug }`" class="btn btn-sm btn-secondary"> New Alteration</Link>
  </div>
</div>

</div>
<div class="row">

<div class="mt-3 row">
<div class="col-12 col-md-6 col-xl-12 position-relative">
<div class="card card-plain h-100">
<div class="p-3 card-body">

<div class="table-responsive">
  <table class="table align-items-center mb-0">
    <thead>
      <tr>
      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Id</th>
        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Alteration Date</th>
        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Description</th>
        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Complete</th>
        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Action</th>
      </tr>
    </thead>
    <tbody v-if="$props. licence.alterations.length > 0">
      <tr v-for="alter in licence.alterations" :key="alter.id">
        <td class="text-center text-sm"><h6 class="text-center">{{ alter.id }}</h6></td>
        <td class="text-center text-sm">
          {{ new Date(alter.date).toLocaleString().split(',')[0] }}
        </td>
         <td class="text-sm text-center">
         Description heree.....
        </td>
        
        <td class="text-sm text-center">
        <span v-if="alter.status == '1'" class="badge bg-warning text-default">Client Invoiced</span>
        <span v-if="alter.status == '2'" class="badge bg-default text-default">Client Paid</span>
        <span v-if="alter.status == '3'" class="badge bg-dark text-default">Alteration Details Captured</span>
        <span v-if="alter.status == '4'" class="badge bg-success text-default">Alteration Complete</span>

        </td>

        <td class="text-sm text-end">
        <div class="d-flex text-end" style="margin-left: 3rem;">
        <Link  :href="`#!`" @click="deleteAlteration(alter.slug)"><i class="fa fa-trash-o  text-danger" aria-hidden="true"></i></Link>
        <Link  :href="`/view-alteration/${alter.slug}`"><i class="fa fa-eye px-1 text-secondary" aria-hidden="true"></i></Link>
        </div>
        </td>
      </tr>
    </tbody>

     <tbody v-else>
        <td></td>
        <td></td>
        <td>No alterations found.</td>
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