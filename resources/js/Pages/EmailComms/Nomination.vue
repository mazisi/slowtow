<script>
import { Link,Head } from "@inertiajs/inertia-vue3";
import Layout from "../../Shared/Layout.vue";
import Banner from '../components/Banner.vue';
import Paginate from '../../Shared/Paginate.vue';
import useToaster from '../../store/useToaster';
import Navigation from './Navigation.vue';
import useMonths from '../../store/useMonths';
import  common from '../common-js/common.js';
import useNomination from '../Nominations/useNomination'

export default {
  name: "email-comms-nominations",
  props: {
    nominations: Object,
    errors: Object,
    error: String,
    success: String

  // 1= > Client Quoted
  // 2 => Client Invoiced
  // 3 => Client Paid
  // 4 => Payment to the Liquor Board
  // 5 => Select nominees
  // 6 => Prepare Nomination Application 
  // 7  => Scanned Application
  // 8 => Nomination Lodged 
  // 9 => Nomination Issued
  // 10 => Nomination Delivered
  },
  data() {
    const { notifySuccess, notifyError } = useToaster();
    const { months } = useMonths();
    const { getBadgeStatus } = useNomination();

    const stagesArr = [
      { name: 'Client Quoted With Requirements', value: 100 },
      { name: 'Client Invoiced', value: 200 },
      { name: 'Payment To The Liquor Board', value: 400 },
      { name: 'Appointment of managers Lodged', value: 800 },
      { name: 'Appointment of managers Issued', value: 900 },

    ]
    return {
      getBadgeStatus,
      stagesArr,
      common,
      months,
      month: '',
      province: '',
      stage: '', 
      notifySuccess,
      notifyError
    }
  },

  computed: {
  computedProvinces(){
          return common.getProvinces();
        }
},
  components: {
    Layout,
    Link,
    Banner,
    Paginate,
    Navigation,
    Head
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
        this.$inertia.replace(route('get_nominations',{
             month: this.month,
             province: this.province,
             stage: this.stage,
          }))
        },

    getRenewalYear(date){
      let computed_date = new Date(date).getFullYear();
      return computed_date + 1;    
    },

      getStatus(stage_param){
        return this.getBadgeStatus(stage_param);
      }
  

    },

    mounted(){ 
          if(this.success){
            this.notifySuccess(this.success)
          }else if(this.error){
            this.notifyError(this.error)
          }
        }
};
</script>
<style>
#with-thrashed{
  margin-top: 3px;
  margin-left: 3px;
}</style>
<template>
<Layout>
  <Head title="EmailComms - Appointment of managers" />
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
<option v-for="stage in stagesArr" :value="stage.value" :key="stage.value">{{ stage.name }}</option>"

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
<option :value="''" disabled selected>Filter By Province</option>
<option v-for="province in computedProvinces" :key="province"  :value=province>{{ province }}</option>
</select>
</div>
</div>
</div>
  <h5 class="text-center"> Appointment of managers</h5>
  <div class="table-responsive p-0">
  <table class="table align-items-center mb-0">
    <thead>
      <tr>
        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"> Trading Name </th>
        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2"> Licence Number</th>
        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"> Licence Date </th>
        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"> Stage </th>
        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Email Action</th>
      </tr>
    </thead>
    <tbody>
      <tr v-if="nominations.data?.length > 0" v-for="nomination in nominations.data" :key="nomination.id">
        <td>
            <div class="d-flex flex-column justify-content-center">
              <h6 data-bs-placement="top" :title="nomination.licence.trading_name" class="mb-0 text-sm">
                <Link :href="`/view-nomination/${nomination.slug}`">{{ limit( nomination.licence.trading_name) }}</Link></h6>
          </div>
        </td>
        <td>
          <p class="text-xs font-weight-bold mb-0">{{ nomination.licence.licence_number ? nomination.licence.licence_number : '' }}</p>
        </td>
        <td class="align-middle font-weight-bold text-center text-sm">
          <span>{{ new Date(nomination.licence.licence_date).toISOString().split('T')[0] }}</span>
        </td>
         <td class="align-middle text-center">
          <span class="font-weight-bold text-sm" v-html="getStatus(nomination.status)"></span>
        </td>
        <td class="align-middle text-center">
        <Link :href="`/email-comms/get-mail-template/${nomination.slug}/nominations`" class="text-secondary text-center font-weight-bold text-xs"> 
        <i class="fa fa-envelope"></i> Send </Link>

        
        <Link :href="`/view-nomination/${nomination.slug}`" class="text-secondary text-center font-weight-bold text-xs"> 
        <i class="fa fa-eye"></i> View </Link>
        </td>
      </tr>
      <tr v-else>
        <td colspan="6" class="text-center text-danger">No Appointment of managers Found.</td>
    </tr>
   
    </tbody>
  </table>
</div>

  </div>

  <Paginate v-if="nominations.data?.length > 0"
  :modelName="nominations"
  :modelType="Nominations"
  />

</div>


</div>


</div>

  </Layout>
</template>
