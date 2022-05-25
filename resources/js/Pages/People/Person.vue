<style>
 
  .table thead th {
    padding: 0;
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
      <div class="col-2"></div>
      <div v-if="success" class="col-6" 
      x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">
      <div class="alert text-white alert-success alert-dismissible fade show font-weight-light" role="alert">
      <span class="alert-icon"><i class=""></i></span><span class="alert-text"> 
      <span class="text-sm">{{ success }}</span></span><button type="button" class="btn-close d-flex justify-content-center align-items-center" data-bs-dismiss="alert" aria-label="Close">
      <span aria-hidden="true" class="text-lg font-weight-bold">×</span>
      </button>
      </div>
      </div>

      <div v-else-if="error" class="col-6" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">
      <div class="alert text-white alert-danger alert-dismissible fade show font-weight-light" role="alert">
      <span class="alert-icon"><i class=""></i></span><span class="alert-text"> 
      <span class="text-sm">{{ error }}</span></span><button type="button" class="btn-close d-flex justify-content-center align-items-center" data-bs-dismiss="alert" aria-label="Close">
      <span aria-hidden="true" class="text-lg font-weight-bold">×</span>
      </button>
      </div>
      </div>
<div v-else-if="!error" class="col-6"></div>
      <div class="col-2"></div>
      <div class="col-2">
 
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
    <tr v-for="person in people"  :key="person.id">
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
      <h6 class="mb-0 text-sm">{{ person.name }} {{ person.surname }}</h6></Link>
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
      </div>
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
    people: Object
  },
 components: {
    Layout,
    Link,
    useForm
},
}
</script>