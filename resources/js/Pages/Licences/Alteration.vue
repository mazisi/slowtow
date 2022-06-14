<script>
import Layout from "../../Shared/Layout.vue";
import { Head,Link } from '@inertiajs/inertia-vue3';

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
<div class="page-header min-height-100 border-radius-xl" style="background-image: url('https://images.unsplash.com/photo-1531512073830-ba890ca4eba2?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=crop&w=1920&q=80');
">
<span class="mask bg-gradient-success opacity-6"></span>
</div>
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
      <th class="ps-2 text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Id</th>
        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Alteration Date</th>
        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Description</th>
        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Complete</th>
        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Action</th>
      </tr>
    </thead>
    <tbody v-if="$props. licence.alterations.length > 0">
      <tr v-for="alter in licence.alterations" :key="alter.id">
        <td>
          <div class="d-flex px-2 py-1">
            <div class="d-flex flex-column justify-content-center">
              <h6 class="text-center">{{ alter.id }}</h6>
            </div>
          </div>
        </td>
        <td>
          {{ new Date(alter.date).toLocaleString().split(',')[0] }}
        </td>
         <td class="align-middle text-center">
         Description heree.....
        </td>
        
        <td class="align-middle text-center text-sm">
        <span v-if="alter.status == 'Pending'" class="badge bg-warning text-default">{{ alter.status }}</span>
        <span v-if="alter.status == 'Complete'" class="badge bg-success text-default">{{ alter.status }}</span>
        </td>
       
        <td class="align-end float-end">
        <div class="d-flex align-middle text-center">
        <Link  :href="`#!`" @click="deleteAlteration(alter.slug)"><i class="fa fa-trash-o  text-danger" aria-hidden="true"></i></Link>
        <Link  :href="`#!`"><i class="fa fa-eye px-1 text-secondary" aria-hidden="true"></i></Link>
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