<script>
import Layout from "../Shared/Layout.vue";
import { ref, watch, reactive } from 'vue'
import { Inertia } from '@inertiajs/inertia'
import { Link, useForm } from '@inertiajs/inertia-vue3';
import Banner from './components/Banner.vue'


export default {
  name: "companies",
  props: {
    companies: Object,
    success: String,
    errors: Object,
    error: String
  },

  setup(props) {
    let nextPage = ref(0);
    let prevPage =  ref(0);
    let currentPage =  reactive(props.companies.current_page);

    const term = ref('');
    const form = useForm({
          term: term,
          active_status: '',
          company_type: '',
        })

    function search(){
          form.get(`/companies`, {
            replace: true,
            preserveState: true,
            onSuccess: () => {},            
        })
    }
    function paginateNext(){          
          if(props.companies.current_page < props.companies.last_page){            
            nextPage = props.companies.current_page + 1;
            currentPage =  props.companies.current_page+1;
            Inertia.get(`/companies`, { page: nextPage }, { preserveState: true, replace: true });            
          } 
        }

        function paginatePrev(){         
            prevPage = props.companies.current_page - 1;
            currentPage =  props.companies.current_page-1;
            Inertia.get('/companies', { page: prevPage }, { preserveState: true, replace: true });
          
        }

        function limit(string, limit=25){
          if(string.length >= limit){
          return string.substring(0, limit) + '...'
        }  
          return string.substring(0, limit)
        }

        function getArrowButtons(key){
              if(key !== 0){
                return key;
              }  
        }
       watch(term, _.debounce(function (value) {
          Inertia.get('/companies', { term: value }, { preserveState: true, replace: true });
        }, 1000));

    return {
      term,
      form,
      search,
      nextPage,
      prevPage,
      currentPage,
      paginateNext,
      paginatePrev,
      limit,
      getArrowButtons
    }
  },
  components: {
    Layout,
    Link,
    Banner
},
};
</script>
<style>
#with-thrashed{
  margin-top: -10px;
  margin-left: 3px;
}
.filters{
 margin-top: 10px;
}

</style>
<template>
<Layout>
<div class="container-fluid">
  <Banner/>
<div class="card card-body mx-3 mx-md-4 mt-n6">
<div class="col-12">
<div class="row">
<div  class="col-md-12 col-xl-12 col-lg-12">
<div class="input-group input-group-outline null is-filled">
<i class="fa fa-search h4"></i>&nbsp;&nbsp;&nbsp;
<input v-model="term" placeholder="Search Company" type="text" class="form-control form-control-default">
</div>
</div>

<div class="col-6 filters">
<div class="input-group input-group-outline null is-filled">
<select @change="search" v-model="form.active_status" class="form-control form-control-default">
<option :value="''" disabled selected>Active/Inactive Status</option>
<option value="All">All</option>
<option value="Active">Active</option>
<option value="Inactive">Inactive</option>
</select>
</div>
</div>

<div class="col-6 filters">
<div class="input-group input-group-outline null is-filled">
<select @change="search" v-model="form.company_type" class="form-control form-control-default">
<option :value="''" disabled selected>Company Type</option>
<option value="Association">Association</option>
<option value="Close Corporation CC">Close Corporation  CC</option>
<option value="Individual">Individual</option>
<option value="Non-profit Organization (NPO)">Non-profit Organization (NPO)</option>
<option value="Partnership">Partnership</option>
<option value="Private Company  (Proprietary) Limited">Private Company  (Proprietary) Limited</option>
<option value="Public Company">Public Company</option>
<option value="Sole Proprietor">Sole Proprietor</option>
<option value="Sole Proprietor">Sole Proprietor</option>
<option value="Trust">Trust</option>
</select>
</div>
</div>
<div class="col-1"></div>
</div>
<div class=" my-4">
<div class="table-responsive p-0">
<table class="table align-items-center mb-0">
<thead>
<tr>
<th class="text-uppercase text-secondary w-10 text-xxs font-weight-bolder opacity-7">
Active
</th>
<th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
Company Name
</th>

<th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
Company Type
</th>
<th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
 Registration Number
</th>
<th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
View
</th>

</tr>
</thead>
<tbody>
<tr v-for="company in companies.data" :key="company.id">
<td class="align-middle text-sm">
<i v-if="company.active == 1" data-bs-placement="top" title="Active" class="fa fa-check text-success" aria-hidden="true"></i>

<i v-else data-bs-placement="top" title="Inactive" class="fa fa-times text-danger" aria-hidden="true"></i>
</td>
<td>
<h6 class="mb-0 text-sm">
<Link data-bs-placement="top" :title="company.name"
:href="`/view-company/${company.slug}`" class="px-0 nav-link font-weight-bold lh-1" :class="color ? color : 'text-body'">
{{ limit(company.name )}}
</Link>
</h6>   
</td>

<td>
<h6 class="mb-0 text-sm">
<Link data-bs-placement="top" :title="company.company_type"
:href="`/view-company/${company.slug}`" class="px-0 nav-link font-weight-bold lh-1" :class="color ? color : 'text-body'">
{{ limit(company.company_type) }}
</Link>
</h6>   
</td>

<td>
<h6 class="mb-0 text-sm">
<Link data-bs-placement="top" :title="company.reg_number"
:href="`/view-company/${company.slug}`" class="px-0 nav-link font-weight-bold lh-1" :class="color ? color : 'text-body'">{{ company.reg_number }}
</Link>
</h6>   
</td>

<td class="text-center">
<Link data-bs-placement="top" title="View" :href="`/view-company/${company.slug}`"><i class="fa fa-eye  " aria-hidden="true"></i></Link>

</td>

</tr>


</tbody>
</table>

<nav aria-label="Companies Pagination mt-2">
  <ul class="pagination justify-content-end">
    <li class="page-item" :class="{ disabled: companies.prev_page_url == null }">
      <Link preserve-state as="button" type="button" @click=paginatePrev class="page-link">Prev</Link>
    </li>
    <template v-for="(link, key) in companies.links">
    <li class="page-item " :class="{ 'active': link.active }">
      
      <Link preserve-state class="page-link" :href="link.url" v-show="key && link.url !== null" v-html="getArrowButtons(key)"></Link>
    
    </li>
  </template>
    <li class="page-item" :class="{ disabled: companies.next_page_url == null }">
      <Link preserve-state as="button" @click=paginateNext type="button" class="page-link">Next</Link>
    </li>
  </ul>
</nav>

</div>
</div>
</div>
</div>
</div>
  </Layout>
</template>
