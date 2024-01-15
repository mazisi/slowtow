<script>
import { Link,Head } from "@inertiajs/inertia-vue3";
import Layout from "../../Shared/Layout.vue";
import Banner from '../components/Banner.vue';
import Paginate from '../../Shared/Paginate.vue';
import common from '../common-js/common.js';
import useToaster from '../../store/useToaster';
import Navigation from './Navigation.vue';

export default {
  name: "TemporaryLicences",
  props: {
    temp_licences: Object,
    errors: Object,
    error: String,
    success: String

  },
  data() {
    const { notifySuccess, notifyError } = useToaster();

    return {
      month: '',
      province: '',
      stage: '', 
      notifySuccess,
      notifyError
    }
  },
  components: {
    Layout,
    Link,
    Navigation,
    Banner,
    Paginate,
    Head
},

computed: {

computedBoardRegions() {
      return common.getBoardRegions();
    }
},

methods: {
  limit(string='', limit = 25) {
        if(string){
          if(string.length >= limit){
          return string.substring(0, limit) + '...'
        }  
          return string.substring(0, limit)
        }
     }, 
     
     filter(){
        this.$inertia.replace(route('get_temp_licences',{
             month: this.month,
             province: this.province,
             stage: this.stage,
          }))
        },


    },

    

    mounted(){ 
          if(this.success){
            this.notifySuccess(this.success)
          }else if(this.error){
            this.notifyError(this.error)
          }
        },

  computed: {
    computedProvinces() {
      return common.getProvinces();
    },
    
    computedBoardRegions() {
      return common.getBoardRegions();
    }
  },
};
</script>
<style>
#with-thrashed{
  margin-top: 3px;
  margin-left: 3px;
}</style>
<template>
<Layout>
  <Head title="EmailComms - Temporary-Licences" />
  <div class="container-fluid">
    <Banner/>
    <div class="card card-body mx-3 mx-md-4 mt-n6">
      <Navigation/>
<div class="tab-content" id="pills-tabContent">


  <div class="tab-pane fade show active" id="renewals" role="tabpanel" aria-labelledby="Renewals">
  <div class="row">
<div class="col-4 columns">                  
<div class="input-group input-group-outline null is-filled">
<select v-model="stage" @change="filter" class="form-control form-control-default">
<option :value="''" disabled selected>Filter By Stage</option>
<option value="1">Client Quoted </option>
<option value="2">Client Invoiced </option>
<option value="3">Client Paid </option>
<option value="4">Collate Temporary Licence Documents</option>
<option value="5">Payment to the Liquor Board</option>
<option value="6">Scanned Application</option>
<option value="7">Temporary Licence Lodged </option>
<option value="8">Temporary Licence Issued</option>
<option value="9">Temporary Licence Delivered</option>

</select>
</div>

</div>
  <div class="col-4 columns">                  
<div class="input-group input-group-outline null is-filled">
<select v-model="month" @change="filter" class="form-control form-control-default" >
<option :value="''" disabled selected>Filter By Month</option>
<option value="1">January</option>
<option value="2">February</option>
<option value="3">March</option>
<option value="4">April</option>
<option value="5">May</option>
<option value="6">June</option>
<option value="7">July</option>
<option value="8">August</option>
<option value="9">September</option>
<option value="10">October</option>
<option value="11">November</option>
<option value="12">December</option>
</select>
</div>

</div>

<div class="col-4 columns">                  
<div class="input-group input-group-outline null is-filled">
<select @change="filter" class="form-control form-control-default" v-model="province">
<option :value="''" disabled selected>Filter By Board region</option>
<option v-for='board_region in computedBoardRegions' :key="board_region" :value=board_region > {{ board_region }}</option>
</select>
</div>
</div>
</div>
  <h5 class="text-center"> Temporary Licences</h5>
  <div class="table-responsive p-0">
    <table class="table align-items-center mb-0">
      <thead>
        <tr>
          <th class=" text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
           Event Name
          </th>
          <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
          Applicant
          </th>
          <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
            Event Date
            </th>
            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
              Licence Number
              </th>
          <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
          View
          </th>
          
        </tr>
      </thead>
      <tbody>
        <tr v-for="licence in temp_licences.data" :key="licence.id">
          <td class="text-sm" >
            <h6 class="mb-0 text-sm" style="margin-left: 1rem;">
          <Link :href="`/view-temp-licence/${licence.slug}`">
          {{ limit(licence.event_name) }}
          </Link>
        </h6>
            
          </td>
          <td>
   
                <h6  v-if="licence.people === null" class="mb-0 text-sm">               
                <Link :href="`/view-temp-licence/${licence.slug}`"
                  class="px-0 ">{{ limit(licence.company.name) }}
                </Link>
                 </h6>
                 <h6 v-if="licence.company === null" class="mb-0 text-sm">
                  <Link :href="`/view-temp-licence/${licence.slug}`">
                   {{ licence.people.full_name }}
                 </Link>
                  </h6>
          </td>
          <td class="">
            <h6 class="mb-0 text-sm">
              <Link :href="`/view-temp-licence/${licence.slug}`">
                {{ licence.start_date }}
             </Link>
           </h6>                      
          </td>
          <td class="">
            <h6 class="mb-0 text-sm">
              <Link :href="`/view-temp-licence/${licence.slug}`">
                {{ licence.liquor_licence_number }}
             </Link>
              </h6>
            
          </td>

          <td class="align-middle text-center">
            <Link :href="`/email-comms/get-mail-template/${licence.slug}/temporary-licences`" class="text-secondary text-center font-weight-bold text-xs"> 
            <i class="fa fa-envelope"></i> Send </Link>
    
            
            <Link :href="`/view-temp-licence/${licence.slug}`" class="text-secondary text-center font-weight-bold text-xs"> 
            <i class="fa fa-eye"></i> View </Link>
            </td>

         
          
        </tr>
        
       
      </tbody>
    </table>
</div>

  </div>

  <Paginate
  :modelName="temp_licences"
  :modelType="TempLicences"
  />

</div>


</div>


</div>

  </Layout>
</template>
