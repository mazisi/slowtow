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
<div class="col-9">
<div class="input-group input-group-outline null is-filled">
<input v-model="term" type="text" class="form-control form-control-default" placeholder="Search Person">
</div>
</div>
<div class="col-2">
<div class="input-group input-group-outline null is-filled">
<select @change="search"  v-model="form.active_status" class="form-control form-control-default">
  <option :value="``" disabled selected>Filter Options</option>
<option value="All">All</option>
<option value="Active">Active</option>
<option value="Inactive">Inactive</option>
</select>

</div>
</div>


</div>
<div class="">
<div class=" px-0 pb-2">
<div class="table-responsive p-0">
<table class="table table-striped table-hover " style="font-size:90%">
<thead>
<tr>
<th class="ps-2 text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Active</th>
<th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Person</th>
<th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Action</th>
</tr>
</thead>
<tbody>
<tr v-for="person in people.data" :key="person.id">
<td>
<div class="avatar-group ">
<i v-if="person.active" class="fa fa-check text-info" aria-hidden="true"></i>
<i v-else class="fa fa-times text-danger" aria-hidden="true"></i>
</div>
</td>
<td class="align-middle text-center text-sm">
<Link :href="`/view-person/${person.slug}`">
<h6 class="mb-0 text-sm">{{ person.full_name }}</h6></Link>
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

<Paginate
  :modelName="people"
  :modelType="People"
  />
  
</div>
{{ links }}
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
import Banner from '../components/Banner.vue'
import Paginate from "@/Shared/Paginate.vue";

export default {
  props: {
    success: Object,
    people: Object,
    links: Array,
  },
  setup(props){
    const term = ref('');

    const form = useForm({
          term: term,
          active_status: ''
        })

    function search(){
      form.get(`/people`, {
            replace: true,
            preserveState: true
            
        })
    }
    watch(term, _.debounce(function (value) {
          Inertia.get('/people', { term: value }, { preserveState: true, replace: true });
        }, 2000));

    return{
      term,
      search,
      form
    }
  },
 components: {
    Layout,
    Link,
    useForm,
    Banner,
    Paginate
},
}
</script>