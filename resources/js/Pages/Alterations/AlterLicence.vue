<script>
import Layout from "../../Shared/Layout.vue";
import Multiselect from '@vueform/multiselect';
import { Link,useForm } from '@inertiajs/inertia-vue3';
import Banner from '../components/Banner.vue';


export default {
 props: {
   errors: Object,
    licence: Object,
    success: String,
    error: String,

  },
  setup(props) {
   let showMenu = false;
    
     let options = props.nominees;
     const form = useForm({
         alteration_date: '',
         licence_id: props.licence.id,
         licence_slug: props.licence.slug,
         description: '',
         status: [],
        })

    function submit() {
          form.post(`/submit-altered-licence/${props.licence.id}`, props.form)
     }

     
    return {
      submit,
      options,
      form,showMenu
    };
  },

  components: {
    Layout,
    Multiselect,
    Link,
    Banner
  },
  beforeUnmount() {
    this.$store.state.isAbsolute = false;
  },
};
//Status keys:
//1 => Client Invoiced
//2 => Client Paid
//3 => Alteration Details Captured
//4 => Alteration Complete
</script>
<style>
.columns{
  margin-bottom: 1rem;
}
.active-checkbox{
  margin-top: -10px;
}
.status-heading{
  font-weight: 700;
}
</style>
<style src="@vueform/multiselect/themes/default.css"></style>
<template>
<Layout>
<div class="container-fluid">
    <Banner/>
    <div class="card card-body mx-3 mx-md-4 mt-n6">
      <div class="row">
  <div class="col-lg-6 col-7">
   <h6>New Alteration for: <Link :href="`/view-licence?slug=${licence.slug}`"><span class="text-success">{{ licence.trading_name }}</span></Link></h6>
  </div>
  <div class="col-lg-6 col-5 my-auto text-end"></div>
</div>
      <div class="row">
        <div class="mt-3 row">
          <div class="col-12 col-md-12 col-xl-12 position-relative">
            <div class="card card-plain h-100">
              <div class="p-3 card-body">
  <form @submit.prevent="submit">
<div class="row">
<input type="hidden" v-model="form.slug">
<div class="col-md-12 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input id="client-invoiced" class="active-checkbox" v-model="form.status" type="checkbox" value="1">
<label for="client-invoiced" class="form-check-label text-body text-truncate status-heading">Client Invoiced</label>
</div>
</div>  
<hr>
<div class="col-md-12 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input id="client-paid" class="active-checkbox" v-model="form.status" type="checkbox" value="2">
<label for="client-paid" class="form-check-label text-body text-truncate status-heading">Client Paid</label>
</div>
</div>
<hr/>


<div class="col-12 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input id="alteration-details" class="active-checkbox" v-model="form.status" type="checkbox" value="3">
<label for="alteration-details" class="form-check-label text-body text-truncate status-heading">Alteration Details Captured</label>
</div>
</div>



<div class="col-3 columns">
    <div class="input-group input-group-outline null is-filled">
    <label class="form-label">Alteration Date *</label>
    <input type="date" class="form-control form-control-default" v-model="form.alteration_date">
    </div>
     <div v-if="errors.alteration_date" class="text-danger">{{ errors.alteration_date }}</div>
  </div>

  <div class="col-9 columns">    
<div class="input-group input-group-outline null is-filled">
<label class="form-label">Description</label>
<input type="text" class="form-control form-control-default" v-model="form.description" >
</div>
<div v-if="errors.description" class="text-danger">{{ errors.description }}</div>
</div>


<hr>


<div class="col-md-12 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input id="alteration-complete" class="active-checkbox" v-model="form.status" type="checkbox" value="4">
<label for="alteration-complete" class="form-check-label text-body text-truncate status-heading"> Alteration Complete</label>
</div>
</div> 

<div>
  <button :disabled="form.processing" type="submit" class="btn btn-sm btn-secondary ms-2" :style="{float: 'right'}">
    <span v-if="form.processing" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
    Create</button>
</div>
            </div>
            </form>
              </div>
            </div>
            <hr class="vertical dark" />
          </div>
      <!-- //tasks were here -->
        
        </div>
        
      </div>
    </div>
  </div>
  </Layout>
</template>
