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


                  <div class="col-10">
                    <h6>Licence Renewals</h6>              
                  </div>
                  <div class="col-2 my-auto text-end">
                    <div class="dropdown float-lg-end pe-4">
                    <a href="/renewal-export" class="btn btn-sm btn-success">Export</a>
                    </div>
                  </div>

           </div>
        </div>

        <div class="row">
          <div class="col-4">
            <button @click="getType('Renewals')" type="button" class="btn btn-success w-45">Renewals</button>
          </div>
          <div class="col-4">
            <button @click="getType('Transfers')" type="button" class="btn btn-success">Transfers</button>
          </div>
          <div class="col-4">
            <button @click="getType('Nominations')" type="button" class="btn btn-success">Nominations</button>
          </div>

          <div class="col-4">
            <button @click="getType('New-App')" type="button" class="btn btn-success w-45">New Applications</button>
          </div>
          <div class="col-4">
            <button @click="getType('Alterations')" type="button" class="btn btn-success">Alterations</button>
          </div>
          <div class="col-4">
            <button @click="getType('Alterations')" type="button" class="btn btn-success w-45">Temporary Applications</button>
          </div>
<hr/>
<div v-if="form.variation" class="row">
  <div class="col-4">
    <button type="button" class="btn btn-success w-45">Filter By:</button>
  </div>
  <div class="col-8">
    <div class="input-group input-group-outline null is-filled">
        <Multiselect
           
           :options="options"
           :multiple="true"
           :taggable="true"
         />
      </div>
  </div>
  
  <div class="col-3">
    <div class="input-group input-group-outline null is-filled">
      <select v-model="form.year" class="form-control form-control-default">
      <option :value="''" disabled selected>Year</option>
      <option value="01">January</option>
      <option value="02">February</option>
      </select>
      </div>
  </div>
  <div class="col-2"></div>
  <div class="col-2">
    <div class="input-group input-group-outline null is-filled">
      <select v-model="form.activeStatus" class="form-control form-control-default">
      <option :value="''" disabled selected>Active/Inactive</option>
      <option value="Active">Active</option>
      <option value="Inactive">Inactive</option>
      </select>
      </div>
  </div>
  <div class="col-2"></div>
  <div class="col-3">
    <div class="input-group input-group-outline null is-filled">
      <select v-model="form.month" class="form-control form-control-default">
      <option :value="''" disabled selected>Year</option>
      <option value="01">January</option>
      <option value="02">February</option>
      </select>
      </div>
  </div>
  <div class="col-3">
    <div class="input-group input-group-outline null is-filled">
      <select v-model="licence_date" class="form-control form-control-default">
      <option :value="''" disabled selected>Year</option>
      <option value="01">January</option>
      <option value="02">February</option>
      </select>
      </div>
  </div>
  <div class="col-2"></div>
  <div class="col-2"> </div>
  <div class="col-2"></div>
  <div class="col-3  mt-3">
    <div class="input-group input-group-outline null is-filled">
      <select v-model="licence_date" class="form-control form-control-default">
      <option :value="''" disabled selected>Year</option>
      <option value="01">January</option>
      <option value="02">February</option>
      </select>
      </div>
  </div>
  <div class="col-3 mt-3">
    <div class="input-group input-group-outline null is-filled">
      <select v-model="licence_date" class="form-control form-control-default">
      <option :value="''" disabled selected>Applicant</option>
      <option value="01">January</option>
      <option value="02">Februatttttry</option>
      </select>
      </div>
  </div>
  <div class="col-8"></div>
  <div class="col-4 mt-4">
    <button type="button" class="btn btn-success">Export</button>
  </div>
</div>

        </div>
          </div>
          
          
</div>

  </Layout>
</template>
<script>
import Layout from "../Shared/Layout.vue";
import { Link } from '@inertiajs/inertia-vue3';
import { useForm } from '@inertiajs/inertia-vue3';
import Multiselect from '@vueform/multiselect';


export default {
  props: {
    renewals: Object,
    emails: Object,
    success: String,
    error: String,
    errors: Object,
  },
  setup() {
    let options = ['January','February','March','April','May','June','July','August','September','October','November','December'];
    const form = useForm({
      variation: null,
      activeStatus: '',
      month: '',
      year: ''
    })
    
  function getType(type){
     form.variation=type;
   }
return{
  getType,
  form,
  options
}
},
 components: {
    Layout,
    Link,
    Multiselect
},


}
</script>
<style src="@vueform/multiselect/themes/default.css"></style>