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
<h6 class="mb-1">New Nomination For:  {{ licence.trading_name }}</h6>
</div>
</div>


</div>
<div class="row">

<div class="mt-3 row">
<div class="col-4 position-relative">
 <div class="table-responsive mb-2">
              <table class="table align-items-center mb-0">
                <thead>
                  <tr>
                    <th class="text-uppercase text-center text-secondary text-xxs font-weight-bolder opacity-7">
                      Nomination Year
                    </th>
                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                    View
                    </th>
                  </tr>
                </thead>
                 <tbody>
                  <tr v-for="nom in nomination_years" :key="nom.id" >
                    <td>
                      <div class="text-center" >                       
                   
                     <Link :href="`/view-nomination/${nom.slug}`">
                      <h6 class="mb-0 text-sm">{{ nom.year }}</h6>
                     </Link>                  
                    </div>
                    </td>
                      <td class="text-center">
                      <Link :href='`/view-nomination/${nom.slug}`'>
                      <i class="fa fa-eye"></i></Link>
                      </td>
                  </tr>
                  </tbody>
              </table>
            </div></div>

<div class="col-4 col-md-6 col-xl-8 position-relative">
<div class="card card-plain h-100">
<div class="p-3 card-body">
<form @submit.prevent="submit">
<Datepicker v-model="form.year" yearPicker />
<p v-if="errors.year" class="text-danger">{{ errors.year }}</p>

<button class="btn btn-sm btn-secondary mt-3 float-end justify-content-center" type="submit" >
  <span v-if="form.processing" class="spinner-border mt-1 spinner-border-sm" role="status" aria-hidden="true"></span>
  Submit
</button>

</form>

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

import { ref } from 'vue';

export default {
  props: {
    errors: Object,
    licence: Object,
    success: String,
    error: String,
    nomination_years: Object
  },

  setup (props) {
    const year = ref(new Date().getFullYear());

    const form = useForm({
      year: null,
      licence_id: props.licence.id
    })

    function submit() {
      form.post('/submit-nomination', {
        onFinish: () => form.reset('password'),
      })
    }

    return { year,form, submit }
  },
   components: {
    Layout,
    Link,
    Head,
    Datepicker
  },
  
};
</script>
