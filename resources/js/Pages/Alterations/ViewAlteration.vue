<script>
import Layout from "../../Shared/Layout.vue";
import Multiselect from '@vueform/multiselect';
import { Link } from '@inertiajs/inertia-vue3';
import Banner from '../components/Banner.vue'

export default {
  name: "ViewAlteration",
 props: {
    errors: Object,
    alteration: Object,
    success: String,
    error: String,

  },
  data() {
    return {
      showMenu: false,
      form: {
         alteration_date: this.alteration.date,
         licence_slug: this.alteration.licence.slug,
         slug: this.alteration.slug,
         status: [],      
      },
    };
  },
    methods: {
      update() {
          this.$inertia.patch(`/update-alteration`, this.form)
        },
    pushData(event){
      if(this.form.status.includes(event)){
        return;
      }else{
        this.form.status.push(event)
      }
      
    },
        
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
   <h5> Alteration for: {{ alteration.licence.trading_name }}</h5>
  </div>
  <div class="col-lg-6 col-5 my-auto text-end"></div>
</div>
      <div class="row">
        <div class="mt-3 row">
          <div class="col-12 col-md-12 col-xl-12 position-relative">
            <div class="card card-plain h-100">
              <div class="p-3 card-body">
  <form @submit.prevent="update">
<div class="row">
<input type="hidden" v-model="form.slug">
<div class="col-md-12 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input class="active-checkbox" @input="pushData($event.target.value)" type="checkbox" value="1" :checked="alteration.status >= '1'">
<label for="client-invoiced" class="form-check-label text-body text-truncate status-heading">Client Invoiced</label>
</div>
<div v-if="errors.alteration_date" class="text-danger">{{ errors.alteration_date }}</div>
</div>  
<label> Invoice Document goes here..</label>   
<hr>
<div class="col-md-12 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input class="active-checkbox" id="client-paid" @input="pushData($event.target.value)" type="checkbox" value="2" :checked="alteration.status >= '2'">
<label for="client-paid" class="form-check-label text-body text-truncate status-heading">Client Paid</label>
</div>
<div v-if="errors.alteration_date" class="text-danger">{{ errors.alteration_date }}</div>
</div> <hr>

<div class="col-md-12 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input class="active-checkbox" id="alteration-details" @input="pushData($event.target.value)" type="checkbox" value="3" :checked="alteration.status >= '3'">
<label class="form-check-label text-body text-truncate status-heading" for="alteration-details">Alteration Details Captured</label>
</div>
<div v-if="errors.status" class="text-danger">{{ errors.status }}</div>
</div> <hr>

<div class="col-md-6 columns">
    <div class="input-group input-group-outline null is-filled">
    <label class="form-label">Alteration Date *</label>
    <input type="date" class="form-control form-control-default" v-model="form.alteration_date">
    </div>
     <div v-if="errors.alteration_date" class="text-danger">{{ errors.alteration_date }}</div>
  </div>
<hr>


<div class="col-md-12 columns">
<div class=" form-switch d-flex ps-0 ms-0  is-filled">
<input id="active-checkbox" @input="pushData($event.target.value)" type="checkbox" value="4" :checked="alteration.status >= '4'">
<label class="form-check-label text-body text-truncate status-heading"> Alteration Complete</label>
</div>
</div> 

<div>
  <button type="submit" class="btn btn-sm btn-secondary ms-2" :style="{float: 'right'}">Save</button></div>
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
