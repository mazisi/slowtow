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
      <h1 class="display-1 fw-bold">404</h1>
      <p class="lead">The file you’re looking for does not exist.</p>
      
      <Link @click="goBack" class="btn btn-primary">Go Back</Link>
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
import { Link } from '@inertiajs/inertia-vue3';
import Banner from '../components/Banner.vue';

export default {
  props: {},
  setup(props) {
    function goBack(){
      return history.go(-1);
    }
    return{
      goBack
    }
  },

 components: {
    Layout,
    Banner
},
}
</script>