<script>
import Layout from "../../../Shared/Layout.vue";
import { Link } from "@inertiajs/inertia-vue3";

export default {
  props: {
  licences: Object,
  success: String,
  error: Object,
  errors: Object
  },
  data() {
    return {
     term: '',
      withThrashed: '', 
      active_status: '' 
    }
  },
  components: {
    Layout,
    Link
},
methods: {
     search(){
          this.$inertia.replace(route('temp_licences',{
          term: this.term,
          withThrashed: this.withThrashed,
          active_status: this.active_status
          }))
      
        
        },

      
    },
};
</script>
<style>
#with-thrashed{
  margin-left: 3px;
  margin-top: -10px;
}</style>
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
       <div class="col-3">
        <div class="input-group input-group-outline null is-filled">
  <label class="form-label">Search Company </label>
  <input v-model="term" @keyup="search" type="text" class="form-control form-control-default">
   </div>
       </div>
       <div class="col-2">
        <div class="input-group input-group-outline null is-filled">
  <label class="form-label">Active status: </label>
  <select @change="search" v-model="active_status" class="form-control form-control-default">
   <option value="Active">Active</option>
   <option value="Inactive">Inactive</option>
  </select>

   </div>
       </div>
        <div class="col-3">
        <div class="input-group ">
  <label class="">Include deleted licences: </label>
  <input @change="search" type="checkbox" id="with-thrashed" v-model="withThrashed">

   </div>
       </div>
        <div class="col-3">
       </div>
       
     </div>
     </form>
        <div class=" my-4">
            <div class="table-responsive p-0">
              <table class="table align-items-center mb-0">
                <thead>
                  <tr>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                     Liqour Licence Number
                    </th>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                    Company Name
                    </th>
                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                    View
                    </th>
                    
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="licence in licences" :key="licence.id">
                    <td class="align-middle text-sm">
                    <div class="d-flex flex-column justify-content-center">
                    <Link :href="`#`">
                    <h6 class="mb-0 text-sm justify-content-center">{{ licence.liquor_licence_number }}</h6>
                    </Link>
                    
                    </div>
                      
                    </td>
                    <td v-if="licence.people == null">
                          <h6 class="mb-0 text-sm">               
                          <Link :href="`#`"
                            class="px-0 nav-link font-weight-bold lh-1"
                            :class="color ? color : 'text-body'">{{ licence.company.full_name }}
                          </Link>
                           </h6>
                    </td>

                    <td v-else>
                          <h6 class="mb-0 text-sm">
                           <Link :href="`#`">
                            {{ licence.people.full_name }}
                          </Link>
                           </h6>
                    </td>
                    <td class="text-center">
                    <Link :href="`/view-temp-licence/${licence.slug}`"><i class="fa fa-eye  " aria-hidden="true"></i></Link>
                      
                    </td>
                    
                  </tr>
                  
                 
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </Layout>
</template>
