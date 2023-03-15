<style>
 
  
#with-thrashed{
  margin-top: 3px;
  margin-left: 3px;
}
</style>
<template>
<Layout>
<div class="container-fluid">
<Banner/>

<div class="card card-body mx-3 mx-md-4 mt-n6">
<div class="col-12">

<div class="row mb-4">
<div class="d-flex align-items-center justify-content-center vh-60">
  <div class="text-center">
      <h1 v-if="error_message == 500" class="display-1 fw-bold">500</h1>
      <h1 v-else-if="error_message == 404" class="display-1 fw-bold">404</h1>
      <p v-if="error_message == 500" class="fs-3"> <span class="text-danger">Opps!</span> Page not found.</p>
      <p v-else-if="error_message == 400" class="fs-3"> <span class="text-danger">Opps!</span> Server Error.</p>
      <p v-if="error_message == 500" class="lead">Internal Server Error.</p>
      <p v-else-if="error_message == 404" class="lead">The page you’re looking for doesn’t exist.</p>
      
      <Link v-if="$page.props.auth.has_slowtow_admin_role" :href="route('slowtow_dashboard')" class="btn btn-primary">Go Home</Link>
      <Link v-else :href="route('company_dashboard')" class="btn btn-primary">Go Home</Link>
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
import { ref, watch } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import Banner from '../components/Banner.vue';

export default {
  props: {
    success: Object,
    error_message: String,
    licences: Object
  },
  setup(props){
    const term = ref('');

    const form = useForm({
          term: term,
        })

    watch(term, _.debounce(function (value) {
          Inertia.get('/people', { term: value }, { preserveState: true, replace: true });
        }, 2000));

    return{
      term,
      form
    }
  },
 components: {
    Layout,
    Link,
    useForm,
    Banner
},
}
</script>