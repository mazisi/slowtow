<template>
<Layout>
<div class="container-fluid">

<Banner/>

<div class="card card-body mt-n6">
<div class="col-12">
<div class="row">
<div  class="col-md-12 col-xl-12 col-lg-12">
<div class="input-group input-group-outline null is-filled">
<i class="fa fa-search h4"></i>&nbsp;&nbsp;&nbsp;
<input v-model="term" placeholder="Search" type="text" class="form-control form-control-default" autofocus>
</div>
</div>

<div class="col-md-3 col-xl-3 col-lg-3 filters">
<div class="input-group input-group-outline null is-filled">
<select @change="search" v-model="form.active_status" class="form-control form-control-default">
<option :value="''" disabled selected>Active/Inactive Status</option>
<option value="All">All</option>
<option value="Active">Active</option>
<option value="Inactive">Inactive</option>
</select>

</div>
</div>

<div class="col-md-3 col-xl-3 col-lg-3 filters">
<div class="input-group input-group-outline null is-filled">
<select @change="search" v-model="form.licence_type" class="form-control form-control-default">
<option :value="''" disabled selected>Licence Type</option>
<option v-for='licence_type in all_licence_types' :value=licence_type.id> {{ licence_type.licence_type }}</option>
</select>
</div>
</div>

<div class="col-md-3 col-xl-3 col-lg-3 filters">
<div class="input-group input-group-outline null is-filled">
<select @change="search" v-model="form.licence_date" class="form-control form-control-default">
<option :value="''" disabled selected>Licence Date</option>
<option value="01">January</option>
<option value="02">February</option>
<option value="03">March</option>
<option value="04">April</option>
<option value="05">May</option>
<option value="06">June</option>
<option value="07">July</option>
<option value="08">August</option>
<option value="09">September</option>
<option value="10">October</option>
<option value="11">November</option>
<option value="12">December</option>
</select>
</div>
</div>

<div class="col-md-3 col-xl-3 col-lg-3 filters">
<div class="input-group input-group-outline null is-filled">
<select @change="search" v-model="form.province" class="form-control form-control-default">
<option :value="''" disabled selected>Province</option>
<option value="Eastern Cape">Eastern Cape</option>
<option value="Free State">Free State</option>
<option value="Gauteng">Gauteng</option>
<option value="KwaZulu-Natal">KwaZulu-Natal</option>
<option value="Limpopo">Limpopo</option>
<option value="Mpumalanga">Mpumalanga</option>
<option value="Northern Cape">Northern Cape</option>
<option value="North West">North West</option>
<option value="Western Cape">Western Cape</option>
</select>
</div>
</div>



</div>
<div class="my-4">

<div class="px-0 pb-2">
<div class="table-responsive p-0">
  <table class="table table-striped table-hover" style="font-size:85%">
<thead>
<tr>
<th>Active</th>
<th class="ps-2">Trading Name</th>
<th class="ps-2">Licence Number</th>
<th class="ps-2">Licence Date</th>
<th class="ps-2">Licence Type</th>
<th class="ps-2">Company</th>
</tr>
</thead>
<tbody>
<tr v-for="licence in licences.data" :key="licence.id">
<td v-if="licence.is_licence_active == '1'"><i class="fa fa-check text-success" aria-hidden="true"></i></td>
<td v-else><i class="fa fa-times text-danger" aria-hidden="true"></i></td>
<td><Link :href="`/view-licence?slug=${licence.slug}`" data-bs-placement="top" :title="licence.trading_name">{{ licence.trading_name }}</Link></td>
<td><Link :href="`/view-licence?slug=${licence.slug}`" data-bs-placement="top" :title="licence.licence_number">{{ licence.licence_number }}</Link></td>
<td><Link :href="`/view-licence?slug=${licence.slug}`" data-bs-placement="top" :title="licence.licence_date">{{ licence.licence_date }}</Link></td>
<td><Link :href="`/view-licence?slug=${licence.slug}`" data-bs-placement="top" :title="licence.licence_type.licence_type">{{ limit(licence.licence_type.licence_type) }}</Link></td>
<td><Link :href="`/view-licence?slug=${licence.slug}`">{{ licence.belongs_to == 'Person' ? limit(licence.people.full_name) : limit(licence.company.name) }}</Link></td>

</tr>
</tbody>
</table>


<Paginate
  :modelName="licences"
  :modelType="Licences"
  />

</div>
</div>

</div>
</div>
</div>
</div>
</Layout>
</template>

<style>
.filters{
 margin-top: 10px;
}
  .table thead th {
    padding: 0;
    }
 
  #with-thrashed{
    margin-top: 3px;
    margin-left: 3px;
  }
</style>

<script>
import Layout from "../../Shared/Layout.vue";
import { Link, useForm } from '@inertiajs/inertia-vue3';
import { onMounted, ref, watch } from 'vue'
import { Inertia } from '@inertiajs/inertia'
import Banner from '../components/Banner.vue';
import Paginate from "../../Shared/Paginate.vue";

export default {
    props: {
      licences: Object,
      success: String,
      error: String,
      errors: Object,
      all_licence_types: Object
    },

    components: {
        Layout,
        Link,
        Banner,
        Paginate
    },

    setup(props) {

    const term = ref('')
    const form = useForm({
          term: term,
          active_status: '',
          licence_type: '',
          licence_date: '',
          province: ''
        })

       function limit(string='', limit = 25) {
        if(string !== ''){
          if(string.length >= limit){
          return string.substring(0, limit) + '...'
        }  
          return string.substring(0, limit)
        }
        }

       function search(){
          form.get(`/licences`, {
            replace: true,
            preserveState: true,
            onSuccess: () => {},
            
        })
        }

        const notify = (message) => {
          if(props.success){
            toast.success(message, {
            autoClose: 2000,
          });
          
          }else if(props.error){
            toast.error(message, {
            autoClose: 2000,
          });
          }
        }

        watch(term, _.debounce(function (value) {
          Inertia.get('/licences', { term: value }, { preserveState: true, replace: true });
        }, 1000));
        
        onMounted(() => {
          if(props.success){
            notify(props.success)
          }else if(props.error){
            notify(props.error)
          }
        });

        return {
          limit,
          form,
          term,
          search,
          notify
        }
    },

    
}
</script>