<template>
<Layout>
<div class="container-fluid">
<div class="page-header min-height-100 border-radius-xl mt-4" style="background-image: url('https://images.unsplash.com/photo-1531512073830-ba890ca4eba2?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=crop&w=1920&q=80');
">
<span class="mask bg-gradient-success opacity-6"></span>
</div>
<div class="card card-body mx-3 mx-md-4 mt-n6">
<div class="col-12">
<form>
<div class="row">
<div  class="col-md-12 col-xl-12 col-lg-12">
<div class="input-group input-group-outline null is-filled">
<i class="fa fa-search h4"></i>&nbsp;&nbsp;&nbsp;
<input v-model="term" type="text" class="form-control form-control-default">
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
</form>
<div class="my-4">

<div class="px-0 pb-2">
<div class="table-responsive p-0">
  <table class="table table-striped table-hover">
<thead>
<tr>
<th>Active</th>
<th>Trading Name</th>
<th>Licence Number</th>
<th>Licence Date</th>
<th>Licence Type</th>
<th>Company</th>
<th>Client Number</th>
<th>View</th>
</tr>
</thead>
<tbody>
<tr v-for="licence in licences.data" :key="licence.id">
<td v-if="licence.is_licence_active == '1'"><i class="fa fa-check text-success" aria-hidden="true"></i></td>
<td v-else><i class="fa fa-times text-danger" aria-hidden="true"></i></td>
<td><Link :href="`/view-licence?slug=${licence.slug}`">{{ licence.trading_name }}</Link></td>
<td><Link :href="`/view-licence?slug=${licence.slug}`">{{ licence.licence_number }}</Link></td>
<td><Link :href="`/view-licence?slug=${licence.slug}`">{{ licence.licence_date }}</Link></td>
<td><Link :href="`/view-licence?slug=${licence.slug}`">{{ limit(licence.licence_type.licence_type) }}</Link></td>
<td><Link :href="`/view-licence?slug=${licence.slug}`">{{ licence.company==null ? licence.people.full_name : licence.company.name }}</Link></td>
<td><Link :href="`/view-licence?slug=${licence.slug}`">{{ licence.client_number }}</Link></td>
<td class="text-center">
<Link :href="`/view-licence?slug=${licence.slug}`"><i class="fa fa-eye  " aria-hidden="true"></i></Link>

</td>
</tr>
</tbody>
</table>


<nav aria-label="Licences Pagination mt-2">
  <ul class="pagination justify-content-end">
    <li class="page-item" :class="{ disabled: licences.prev_page_url == null }">
      <button type="button" @click=paginatePrev class="page-link">Prev</button>
    </li>
    <template v-for="(link, key) in licences.links">
    <li class="page-item " :class="{ 'active': link.active }">
      
      <Link class="page-link" :href="link.url" v-show="key && link.url !== null" v-html="getArrowBbuttons(key)"></Link>
    
    </li>
  </template>
    <li class="page-item" :class="{ disabled: licences.next_page_url == null }">
      <button @click=paginateNext type="button" class="page-link">Next</button>
    </li>
  </ul>
</nav>
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
import { reactive, ref, watch } from 'vue'
import { Inertia } from '@inertiajs/inertia'

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
        Inertia
    },

    setup(props) {

    const term = ref('')
    let nextPage = ref(0);
    let prevPage =  ref(0);
    let currentPage =  reactive(props.licences.current_page);

    const form = useForm({
          term: term,
          active_status: '',
          licence_type: '',
          licence_date: '',
          province: ''
        })
function getArrowBbuttons(key){
  if(key !== 0){
    return key;
  }
  
}
       function limit(string = '', limit = 25) {
        if(string.length >= limit){
          return string.substring(0, limit) + '...'
        }  
          return string.substring(0, limit)
        }

        function paginateNext(){          
          if(props.licences.current_page < props.licences.last_page){            
            this.nextPage = props.licences.current_page + 1;
            this.currentPage =  props.licences.current_page+1;
            Inertia.get('/licences', { page: this.nextPage }, { preserveState: true, replace: true });            
          } 
        }

        function paginatePrev(){         
            this.prevPage = props.licences.current_page - 1;
            this.currentPage =  props.licences.current_page-1;
            Inertia.get('/licences', { page: this.prevPage }, { preserveState: true, replace: true });
          
        }

       function search(){
          form.get(`/licences`, {
            replace: true,
            preserveState: true,
            onSuccess: () => {},
            
        })
        }

        watch(term, _.debounce(function (value) {
          Inertia.get('/licences', { term: value }, { preserveState: true, replace: true });
        }, 2000));
          
        return {
          limit,
          form,
          term,
          search,
          paginateNext,
          nextPage,
          prevPage,
          paginatePrev,
          currentPage,
          getArrowBbuttons
        }
    },

    
}
</script>