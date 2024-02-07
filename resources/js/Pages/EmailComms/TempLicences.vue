<script>
import { Link,Head } from "@inertiajs/inertia-vue3";
import Layout from "../../Shared/Layout.vue";
import Banner from '../components/Banner.vue';
import Paginate from '../../Shared/Paginate.vue';
import common from '../common-js/common.js';
import useToaster from '../../store/useToaster';
import Navigation from './Navigation.vue';
import useMonths from '../../store/useMonths';

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
    const { months } = useMonths();

    const stagesArr = [
      { name: 'Client Quoted', value: 100 },
      { name: 'Client Invoiced', value: 200 },
      { name: 'Client Paid', value: 300 },
      { name: 'Collate Temporary Licence Documents', value: 400 },
      { name: 'Payment to the Liquor Board', value: 500 },
      { name: 'Scanned Application', value: 600 },
      { name: 'Temporary Licence Lodged', value: 700 },
      { name: 'Temporary Licence Issued', value: 800 },
      { name: 'Temporary Licence Delivered', value: 900 },
      ]

    return {
      months,
      stagesArr,
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
<option v-for="stage in stagesArr" :value="stage.value" :key="stage.value">{{ stage.name }}</option>
</select>
</div>

</div>
  <div class="col-4 columns">                  
<div class="input-group input-group-outline null is-filled">
<select v-model="month" @change="filter" class="form-control form-control-default" >
<option :value="''" disabled selected>Filter By Month</option>
<option v-for="month in months" :value="month.id" :key="month.id">{{ month.name }}</option>
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
        <tr v-if="temp_licences.data?.length > 0" v-for="licence in temp_licences.data" :key="licence.id">
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
        
        <tr v-else>
          <td colspan="6" class="text-center text-danger">No Temporary Licences Found.</td>
      </tr>

      </tbody>
    </table>
</div>

  </div>

  <Paginate  v-if="temp_licences.data?.length > 0"
  :modelName="temp_licences"
  :modelType="TempLicences"
  />

</div>


</div>


</div>

  </Layout>
</template>
